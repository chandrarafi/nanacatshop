<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\PelangganModel;

class AdminOrders extends BaseController
{
    protected $orderModel;
    protected $orderDetailModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        // Only authenticated users (assumed admin/staff via existing filter/group)
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();

        // Enrich with pelanggan data
        $pelangganIds = array_values(array_unique(array_filter(array_map(fn($o) => $o['pelanggan_id'] ?? null, $orders))));
        $pelangganMap = [];
        if (!empty($pelangganIds)) {
            $pelangganRows = $this->pelangganModel
                ->whereIn('idpelanggan', $pelangganIds)
                ->findAll();
            foreach ($pelangganRows as $p) {
                $pelangganMap[$p['idpelanggan']] = $p;
            }
        }
        foreach ($orders as &$o) {
            $o['_pelanggan'] = $pelangganMap[$o['pelanggan_id']] ?? null;
        }

        $data = [
            'title' => 'Kelola Pesanan',
            'orders' => $orders,
        ];

        return view('admin/orders/index', $data);
    }

    public function show($id)
    {
        $order = $this->orderModel->getOrderWithDetails($id);
        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Pesanan tidak ditemukan');
        }

        // Load pelanggan detail
        $pelanggan = null;
        if (!empty($order['pelanggan_id'])) {
            $pelanggan = $this->pelangganModel->where('idpelanggan', $order['pelanggan_id'])->first();
        }

        $data = [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'pelanggan' => $pelanggan,
        ];

        return view('admin/orders/show', $data);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $courier = $this->request->getPost('courier');
        $tracking = $this->request->getPost('tracking_number');
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $validStatuses, true)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Status tidak valid'
            ]);
        }

        $payload = ['status' => $status];

        // When moving to shipped, courier and tracking should be provided and shipped_at set
        if ($status === 'shipped') {
            if (empty($courier) || empty($tracking)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Kurir dan nomor resi wajib diisi untuk status Dikirim'
                ]);
            }
            $payload['courier'] = $courier;
            $payload['tracking_number'] = $tracking;
            $payload['shipped_at'] = date('Y-m-d H:i:s');
        }

        if (!$this->orderModel->update($id, $payload)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui status'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Status pesanan berhasil diperbarui'
        ]);
    }
}
