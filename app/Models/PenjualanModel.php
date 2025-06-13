<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'kdpenjualan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdpenjualan', 'tglpenjualan', 'idpelanggan', 'grandtotal', 'status'];

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
            'kdpenjualan' => [
                'rules' => 'permit_empty|max_length[30]',
                'errors' => [
                    'max_length' => 'Kode penjualan maksimal 30 karakter'
                ]
            ],
            'tglpenjualan' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal penjualan harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'idpelanggan' => [
                'rules' => 'permit_empty|max_length[30]',
                'errors' => [
                    'max_length' => 'ID pelanggan maksimal 30 karakter'
                ]
            ],
            'grandtotal' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Grand total harus diisi',
                    'numeric' => 'Grand total harus berupa angka',
                    'greater_than_equal_to' => 'Grand total tidak boleh negatif'
                ]
            ],
            'status' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'errors' => [
                    'in_list' => 'Status tidak valid'
                ]
            ],
        ];
    }

    // Generate Kode Penjualan
    public function generateKdPenjualan()
    {
        $prefix = 'PJ';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('kdpenjualan', $prefix . $date)
            ->orderBy('kdpenjualan', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['kdpenjualan'], -4);
            $newSequence = str_pad((int)$lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '0001';
        }

        return $prefix . $date . $newSequence;
    }

    // Get data with pelanggan info
    public function getWithPelanggan($id = null)
    {
        $builder = $this->db->table('penjualan p');
        $builder->select('p.*');
        $builder->select('IFNULL(pl.nama, "Pelanggan Umum") as nama', false);
        $builder->join('pelanggan pl', 'pl.idpelanggan = p.idpelanggan', 'left');

        if ($id) {
            $builder->where('p.kdpenjualan', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
}
