<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'kdkategori';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdkategori', 'namakategori'];

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
            'kdkategori' => [
                'rules' => 'permit_empty|max_length[7]',
                'errors' => [
                    'required' => 'Kode kategori harus diisi',
                    'max_length' => 'Kode kategori maksimal 7 karakter',
                    'is_unique' => 'Kode kategori sudah ada'
                ]
            ],
            'namakategori' => [
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Nama kategori harus diisi',
                    'min_length' => 'Nama kategori minimal 3 karakter',
                    'max_length' => 'Nama kategori maksimal 50 karakter'
                ]
            ],
        ];
    }

    // Generate Kode Kategori
    public function generateKdKategori()
    {
        $prefix = 'KTG';
        $date = date('y');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('kdkategori', $prefix . $date)
            ->orderBy('kdkategori', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['kdkategori'], -2);
            $newSequence = str_pad((int)$lastSequence + 1, 2, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '01';
        }

        return $prefix . $date . $newSequence;
    }

    public function getKategoriFilter()
    {
        return $this->db->table('kategori')->whereNotIn('kdkategori', ['KTG2503', 'KTG2504', 'KTG2506'])->get()->getResultArray();
    }
}
