<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table            = 'order_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['order_id', 'kdbarang', 'quantity', 'price', 'subtotal'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'kdbarang' => 'required|max_length[25]',
        'quantity' => 'required|integer|greater_than[0]',
        'price' => 'required|decimal',
        'subtotal' => 'required|decimal'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'ID order harus diisi',
            'integer' => 'ID order harus berupa angka'
        ],
        'kdbarang' => [
            'required' => 'Kode barang harus diisi',
            'max_length' => 'Kode barang maksimal 25 karakter'
        ],
        'quantity' => [
            'required' => 'Jumlah harus diisi',
            'integer' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih dari 0'
        ],
        'price' => [
            'required' => 'Harga harus diisi',
            'decimal' => 'Harga harus berupa angka'
        ],
        'subtotal' => [
            'required' => 'Subtotal harus diisi',
            'decimal' => 'Subtotal harus berupa angka'
        ]
    ];

    // Get order details by order ID
    public function getOrderDetails($orderId)
    {
        return $this->select('order_details.*, barang.namabarang, barang.foto, barang.satuan')
            ->join('barang', 'barang.kdbarang = order_details.kdbarang')
            ->where('order_details.order_id', $orderId)
            ->findAll();
    }

    // Get order details with product info
    public function getOrderDetailsWithProduct($orderId)
    {
        return $this->select('order_details.*, barang.namabarang, barang.foto, barang.satuan')
            ->join('barang', 'barang.kdbarang = order_details.kdbarang')
            ->where('order_details.order_id', $orderId)
            ->findAll();
    }
}



