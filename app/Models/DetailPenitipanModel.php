<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenitipanModel extends Model
{
    protected $table            = 'detailpenitipan';
    protected $primaryKey       = 'iddetail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdpenitipan', 'idhewan', 'kdfasilitas', 'jumlah', 'harga', 'totalharga'];

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
            'kdpenitipan' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Kode penitipan harus diisi',
                    'max_length' => 'Kode penitipan maksimal 30 karakter'
                ]
            ],
            'idhewan' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'ID hewan harus diisi',
                    'max_length' => 'ID hewan maksimal 30 karakter'
                ]
            ],
            'kdfasilitas' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Kode fasilitas harus diisi',
                    'max_length' => 'Kode fasilitas maksimal 30 karakter'
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
        ];
    }

    // Get detail penitipan by ID penitipan
    public function getDetailByKdPenitipan($kdpenitipan)
    {
        return $this->where('kdpenitipan', $kdpenitipan)->findAll();
    }

    // Get detail with hewan and fasilitas info
    public function getDetailWithInfo($kdpenitipan)
    {
        $builder = $this->db->table('detailpenitipan d');
        $builder->select('d.*, h.namahewan, h.jenis, f.namafasilitas, f.kategori, f.satuan');
        $builder->join('hewan h', 'h.idhewan = d.idhewan', 'left');
        $builder->join('fasilitas f', 'f.kdfasilitas = d.kdfasilitas', 'left');
        $builder->where('d.kdpenitipan', $kdpenitipan);

        return $builder->get()->getResultArray();
    }
}
