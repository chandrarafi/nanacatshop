<?php

namespace App\Models;

use CodeIgniter\Model;

class PerawatanModel extends Model
{
    protected $table            = 'perawatan';
    protected $primaryKey       = 'kdperawatan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdperawatan', 'tglperawatan', 'idpelanggan', 'idhewan', 'grandtotal', 'status', 'keterangan', 'created_at', 'updated_at'];

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
            'kdperawatan' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Kode perawatan harus diisi',
                    'max_length' => 'Kode perawatan maksimal 30 karakter'
                ]
            ],
            'tglperawatan' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal perawatan harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'idpelanggan' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'ID pelanggan harus berupa angka'
                ]
            ],
            'idhewan' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'ID hewan harus berupa angka'
                ]
            ],
            'grandtotal' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Total biaya harus diisi',
                    'numeric' => 'Total biaya harus berupa angka',
                    'greater_than_equal_to' => 'Total biaya tidak boleh negatif'
                ]
            ],
            'status' => [
                'rules' => 'permit_empty|in_list[0,1,2]',
                'errors' => [
                    'in_list' => 'Status tidak valid'
                ]
            ],
        ];
    }

    // Generate kode perawatan baru
    public function generateKdPerawatan()
    {
        $prefix = 'PRW' . date('Ymd');
        $lastOrder = $this->db->table($this->table)
            ->select('MAX(RIGHT(kdperawatan, 4)) as max_code')
            ->like('kdperawatan', $prefix, 'after')
            ->get()
            ->getRow();

        if ($lastOrder) {
            $lastNumber = (int) $lastOrder->max_code;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Get perawatan with pelanggan info
    public function getWithPelanggan($kdperawatan)
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('p.*, pl.nama as nama_pelanggan, pl.nohp, pl.alamat, h.namahewan, h.jenis, h.umur,h.idhewan');
        $builder->join('pelanggan pl', 'pl.idpelanggan = p.idpelanggan', 'left');
        $builder->join('hewan h', 'h.idhewan = p.idhewan', 'left');
        $builder->where('p.kdperawatan', $kdperawatan);

        return $builder->get()->getRowArray();
    }

    // Get status label
    public function getStatusLabel($status)
    {
        switch ($status) {
            case 0:
                return 'Menunggu';
            case 1:
                return 'Dalam Proses';
            case 2:
                return 'Selesai';
            default:
                return 'Unknown';
        }
    }

    // Get status badge class
    public function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 0:
                return 'bg-warning';
            case 1:
                return 'bg-info';
            case 2:
                return 'bg-success';
            default:
                return 'bg-secondary';
        }
    }
}
