<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'supplier';
    protected $primaryKey       = 'kdspl';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdspl', 'namaspl', 'nohp', 'alamat', 'email'];

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
            'kdspl' => [
                'rules' => 'permit_empty|max_length[30]',
                'errors' => [
                    'max_length' => 'Kode supplier maksimal 30 karakter'
                ]
            ],
            'namaspl' => [
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Nama supplier harus diisi',
                    'min_length' => 'Nama supplier minimal 3 karakter',
                    'max_length' => 'Nama supplier maksimal 50 karakter'
                ]
            ],
            'nohp' => [
                'rules' => 'permit_empty|max_length[20]|numeric',
                'errors' => [
                    'max_length' => 'Nomor HP maksimal 20 karakter',
                    'numeric' => 'Nomor HP harus berupa angka'
                ]
            ],
            'email' => [
                'rules' => 'permit_empty|max_length[30]|valid_email',
                'errors' => [
                    'max_length' => 'Email maksimal 30 karakter',
                    'valid_email' => 'Format email tidak valid'
                ]
            ],
        ];
    }

    // Generate Kode Supplier
    public function generateKdSpl()
    {
        $prefix = 'SPL';
        $date = date('ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('kdspl', $prefix . $date)
            ->orderBy('kdspl', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['kdspl'], -3);
            $newSequence = str_pad((int)$lastSequence + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '001';
        }

        return $prefix . $date . $newSequence;
    }
}
