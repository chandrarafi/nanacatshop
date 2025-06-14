<?php

namespace App\Models;

use CodeIgniter\Model;

class HewanModel extends Model
{
    protected $table            = 'hewan';
    protected $primaryKey       = 'idhewan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idhewan', 'namahewan', 'jenis', 'umur', 'jenkel', 'foto', 'idpelanggan', 'satuan_umur'];

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
            'idhewan' => 'permit_empty',
            'namahewan' => [
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Nama hewan harus diisi',
                    'min_length' => 'Nama hewan minimal 3 karakter',
                    'max_length' => 'Nama hewan maksimal 50 karakter'
                ]
            ],
            'jenis' => [
                'rules' => 'required|',
                'errors' => [
                    'required' => 'Jenis hewan harus dipilih',
                    'in_list' => 'Jenis hewan tidak valid'
                ]
            ],
            'umur' => [
                'rules' => 'permit_empty|integer|greater_than[0]|less_than[30]',
                'errors' => [
                    'integer' => 'Umur harus berupa angka',
                    'greater_than' => 'Umur harus lebih dari 0',
                    'less_than' => 'Umur harus kurang dari 30'
                ]
            ],
            'jenkel' => [
                'rules' => 'required|in_list[L,P]',
                'errors' => [
                    'required' => 'Jenis kelamin harus dipilih',
                    'in_list' => 'Jenis kelamin harus L atau P'
                ]
            ],
            'foto' => [
                'rules' => 'permit_empty|max_length[50]',
                'errors' => [
                    'max_length' => 'Nama file foto maksimal 50 karakter'
                ]
            ],
            'idpelanggan' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'ID Pelanggan harus diisi',
                    'max_length' => 'ID Pelanggan maksimal 30 karakter'
                ]
            ],
        ];
    }

    // Generate ID Hewan
    public function generateIdHewan()
    {
        $prefix = 'HWN';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('idhewan', $prefix)
            ->orderBy('idhewan', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['idhewan'], -4);
            $newSequence = str_pad((int)$lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '0001';
        }

        return $prefix . $date . $newSequence;
    }

    // Get pelanggan relation
    public function getPelanggan()
    {
        $pelangganModel = new PelangganModel();
        return $pelangganModel->findAll();
    }
}
