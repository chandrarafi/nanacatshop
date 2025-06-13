<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table            = 'barangmasuk';
    protected $primaryKey       = 'kdmasuk';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdmasuk', 'tglmasuk', 'kdspl', 'grandtotal', 'keterangan', 'status'];

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
            'kdmasuk' => [
                'rules' => 'permit_empty|max_length[30]',
                'errors' => [
                    'max_length' => 'Kode barang masuk maksimal 30 karakter'
                ]
            ],
            'tglmasuk' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal masuk harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'kdspl' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Supplier harus dipilih',
                    'max_length' => 'Kode supplier maksimal 30 karakter'
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
            'keterangan' => [
                'rules' => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Keterangan maksimal 255 karakter'
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

    // Generate Kode Barang Masuk
    public function generateKdMasuk()
    {
        $prefix = 'BM';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('kdmasuk', $prefix . $date)
            ->orderBy('kdmasuk', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['kdmasuk'], -4);
            $newSequence = str_pad((int)$lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First record
            $newSequence = '0001';
        }

        return $prefix . $date . $newSequence;
    }

    // Get data with supplier info
    public function getWithSupplier($id = null)
    {
        $builder = $this->db->table('barangmasuk bm');
        $builder->select('bm.*, s.namaspl');
        $builder->join('supplier s', 's.kdspl = bm.kdspl', 'left');

        if ($id) {
            $builder->where('bm.kdmasuk', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
}
