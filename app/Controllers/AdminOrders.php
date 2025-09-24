<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderDetailModel;

class AdminOrders extends BaseController
{
    protected $orderModel;
    protected $orderDetailModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
    }

    public function index()
    {
        // Only authenticated users (assumed admin/staff via existing filter/group)
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();

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

        $data = [
            'title' => 'Detail Pesanan',
            'order' => $order,
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
