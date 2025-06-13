<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailBarangMasukModel extends Model
{
    protected $table            = 'detailbarangmasuk';
    protected $primaryKey       = 'iddetail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['iddetail', 'detailkdmasuk', 'detailkdbarang', 'jumlah', 'harga', 'totalharga', 'namabarang'];

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
            'detailkdmasuk' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Kode barang masuk harus diisi',
                    'max_length' => 'Kode barang masuk maksimal 30 karakter'
                ]
            ],
            'detailkdbarang' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Kode barang harus diisi',
                    'max_length' => 'Kode barang maksimal 30 karakter'
                ]
            ],
            'jumlah' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Jumlah harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than' => 'Jumlah harus lebih dari 0'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga harus diisi',
                    'numeric' => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih dari 0'
                ]
            ],
            'totalharga' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Total harga harus diisi',
                    'numeric' => 'Total harga harus berupa angka',
                    'greater_than_equal_to' => 'Total harga tidak boleh negatif'
                ]
            ],
            'namabarang' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'Nama barang harus diisi',
                    'max_length' => 'Nama barang maksimal 50 karakter'
                ]
            ],
        ];
    }

    // Get detail barang masuk by ID masuk
    public function getDetailByKdMasuk($kdmasuk)
    {
        return $this->where('detailkdmasuk', $kdmasuk)->findAll();
    }

    // Get detail with barang info
    public function getDetailWithBarang($kdmasuk)
    {
        $builder = $this->db->table('detailbarangmasuk d');
        $builder->select('d.*, b.namabarang, b.kdkategori, k.namakategori');
        $builder->join('barang b', 'b.kdbarang = d.detailkdbarang', 'left');
        $builder->join('kategori k', 'k.kdkategori = b.kdkategori', 'left');
        $builder->where('d.detailkdmasuk', $kdmasuk);

        return $builder->get()->getResultArray();
    }
}
