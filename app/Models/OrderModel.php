<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['order_number', 'pelanggan_id', 'total_amount', 'status', 'payment_method', 'payment_bank', 'shipping_address', 'notes', 'payment_proof', 'courier', 'tracking_number', 'shipped_at', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'order_number' => 'required|max_length[20]',
        'pelanggan_id' => 'required|max_length[30]',
        'total_amount' => 'required|decimal',
        'status' => 'required|in_list[pending,processing,shipped,delivered,cancelled]',
        'payment_method' => 'required|in_list[transfer]',
        'payment_bank' => 'permit_empty|max_length[50]',
        'shipping_address' => 'required|max_length[500]',
        'notes' => 'permit_empty|max_length[500]',
        'payment_proof' => 'permit_empty|max_length[255]',
        'courier' => 'permit_empty|max_length[50]',
        'tracking_number' => 'permit_empty|max_length[100]',
        'shipped_at' => 'permit_empty|valid_date'
    ];

    protected $validationMessages = [
        'order_number' => [
            'required' => 'Nomor order harus diisi',
            'max_length' => 'Nomor order maksimal 20 karakter'
        ],
        'pelanggan_id' => [
            'required' => 'ID pelanggan harus diisi',
            'max_length' => 'ID pelanggan maksimal 30 karakter'
        ],
        'total_amount' => [
            'required' => 'Total amount harus diisi',
            'decimal' => 'Total amount harus berupa angka'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status tidak valid'
        ],
        'payment_method' => [
            'required' => 'Metode pembayaran harus dipilih',
            'in_list' => 'Metode pembayaran tidak valid'
        ],
        'shipping_address' => [
            'required' => 'Alamat pengiriman harus diisi',
            'max_length' => 'Alamat pengiriman maksimal 500 karakter'
        ],
        'notes' => [
            'max_length' => 'Catatan maksimal 500 karakter'
        ],
        'payment_proof' => [
            'max_length' => 'Nama file bukti pembayaran terlalu panjang'
        ],
        'courier' => [
            'max_length' => 'Nama kurir maksimal 50 karakter'
        ],
        'tracking_number' => [
            'max_length' => 'Nomor resi maksimal 100 karakter'
        ],
        'shipped_at' => [
            'valid_date' => 'Tanggal pengiriman tidak valid'
        ]
    ];

    // Generate Order Number
    public function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');

        // Get the last order number with the same prefix and date
        $lastOrder = $this->like('order_number', $prefix . $date)
            ->orderBy('order_number', 'DESC')
            ->first();

        if ($lastOrder) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastOrder['order_number'], -4);
            $newSequence = str_pad((int)$lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First order
            $newSequence = '0001';
        }

        return $prefix . $date . $newSequence;
    }

    // Get orders by pelanggan
    public function getOrdersByPelanggan($pelangganId)
    {
        return $this->where('pelanggan_id', $pelangganId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    // Get order with details
    public function getOrderWithDetails($orderId)
    {
        $order = $this->find($orderId);
        if ($order) {
            $orderDetailModel = new OrderDetailModel();
            $order['details'] = $orderDetailModel->getOrderDetails($orderId);
        }
        return $order;
    }
}
