<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'kdbarang';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdbarang', 'namabarang', 'jumlah', 'foto', 'hargabeli', 'hargajual', 'satuan', 'kdkategori'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function __construct()
    {
        parent::__construct();
        $this->initValidationRules();
    }

    protected function initValidationRules()
    {
        $this->validationRules = [
            'kdbarang' => [
                'rules' => 'permit_empty|max_length[25]',
                'errors' => [
                    'max_length' => 'Kode barang maksimal 25 karakter'
                ]
            ],
            'namabarang' => [
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Nama barang harus diisi',
                    'min_length' => 'Nama barang minimal 3 karakter',
                    'max_length' => 'Nama barang maksimal 50 karakter'
                ]
            ],
            'jumlah' => [
                'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
                'errors' => [
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'foto' => [
                'rules' => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'Nama file foto maksimal 50 karakter'
                ]
            ],
            'hargabeli' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga beli harus diisi',
                    'numeric' => 'Harga beli harus berupa angka',
                    'greater_than' => 'Harga beli harus lebih dari 0'
                ]
            ],
            'hargajual' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga jual harus diisi',
                    'numeric' => 'Harga jual harus berupa angka',
                    'greater_than' => 'Harga jual harus lebih dari 0'
                ]
            ],
            'kdkategori' => [
                'rules' => 'required|max_length[7]',
                'errors' => [
                    'required' => 'Kategori harus dipilih',
                    'max_length' => 'Kode kategori maksimal 7 karakter'
                ]
            ],
            'satuan' => [
                'rules' => 'permit_empty|max_length[20]',
                'errors' => [
                    'max_length' => 'Satuan maksimal 20 karakter'
                ]
            ],
        ];
    }

    // Generate Kode Barang
    public function generateKdBarang()
    {
        $prefix = 'BRG';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('kdbarang', $prefix . $date)
            ->orderBy('kdbarang', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['kdbarang'], -4);
            $newSequence = str_pad((int)$lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '0001';
        }

        return $prefix . $date . $newSequence;
    }

    // Get kategori relation
    public function getKategori()
    {
        $kategoriModel = new KategoriModel();
        return $kategoriModel->findAll();
    }
}
