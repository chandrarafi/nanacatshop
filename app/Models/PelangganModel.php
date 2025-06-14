<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'idpelanggan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idpelanggan', 'nama', 'jenkel', 'nohp', 'alamat'];

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
            'idpelanggan' => [
                'rules' => 'permit_empty|max_length[30]',
                'errors' => [
                    'max_length' => 'ID pelanggan maksimal 30 karakter'
                ]
            ],
            'nama' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Nama pelanggan harus diisi',
                    'max_length' => 'Nama pelanggan maksimal 100 karakter'
                ]
            ],
            'nohp' => [
                'rules' => 'permit_empty|max_length[15]',
                'errors' => [
                    'max_length' => 'Nomor HP maksimal 15 karakter'
                ]
            ],
            'alamat' => [
                'rules' => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Alamat maksimal 255 karakter'
                ]
            ],
        ];
    }

    // Generate ID Pelanggan
    public function generateIdPelanggan()
    {
        $prefix = 'PL';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('idpelanggan', $prefix . $date)
            ->orderBy('idpelanggan', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['idpelanggan'], -4);
            $newSequence = str_pad((int)$lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '0001';
        }

        return $prefix . $date . $newSequence;
    }
}
