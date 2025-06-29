<?php

namespace App\Models;

use CodeIgniter\Model;

class FasilitasModel extends Model
{
    protected $table            = 'fasilitas';
    protected $primaryKey       = 'kdfasilitas';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdfasilitas', 'namafasilitas', 'kategori', 'harga', 'satuan', 'keterangan'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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
            'kdfasilitas' => [
                'rules' => 'permit_empty',
                'errors' => [
                    'required' => 'Kode fasilitas harus diisi',
                    'max_length' => 'Kode fasilitas maksimal 30 karakter',
                    'is_unique' => 'Kode fasilitas sudah digunakan'
                ]
            ],
            'namafasilitas' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Nama fasilitas harus diisi',
                    'max_length' => 'Nama fasilitas maksimal 100 karakter'
                ]
            ],
            'kategori' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'Kategori harus diisi',
                    'max_length' => 'Kategori maksimal 50 karakter'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Harga harus diisi',
                    'numeric' => 'Harga harus berupa angka',
                    'greater_than_equal_to' => 'Harga tidak boleh negatif'
                ]
            ],
            'satuan' => [
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'Satuan harus diisi',
                    'max_length' => 'Satuan maksimal 20 karakter'
                ]
            ],
        ];
    }

    // Generate Kode Fasilitas
    public function generateKdFasilitas()
    {
        $prefix = 'FS';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('kdfasilitas', $prefix . $date)
            ->orderBy('kdfasilitas', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['kdfasilitas'], -4);
            $newSequence = str_pad((int)$lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '0001';
        }

        return $prefix . $date . $newSequence;
    }

    public function getWithKategori()
    {
        $builder = $this->db->table('fasilitas');
        $builder->select('*');
        return $builder->get()->getResultArray();
    }
    public function getWithKategoriPerawatan()
    {
        $builder = $this->db->table('fasilitas');
        $builder->select('*')->whereNotIn('kategori', ['kandang', 'makanan']);
        return $builder->get()->getResultArray();
    }
}
