<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'idpelanggan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idpelanggan', 'nama', 'jenkel', 'alamat', 'nohp'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    public function __construct()
    {
        parent::__construct();
        $this->initValidationRules();
    }

    protected function initValidationRules()
    {
        $this->validationRules = [
            'idpelanggan' => 'permit_empty',
            'nama' => [
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Nama pelanggan harus diisi',
                    'min_length' => 'Nama pelanggan minimal 3 karakter',
                    'max_length' => 'Nama pelanggan maksimal 50 karakter'
                ]
            ],
            'jenkel' => [
                'rules' => 'required|in_list[L,P]',
                'errors' => [
                    'required' => 'Jenis kelamin harus dipilih',
                    'in_list' => 'Jenis kelamin harus L atau P'
                ]
            ],
            'alamat' => [
                'rules' => 'required|min_length[5]|max_length[100]',
                'errors' => [
                    'required' => 'Alamat harus diisi',
                    'min_length' => 'Alamat minimal 5 karakter',
                    'max_length' => 'Alamat maksimal 100 karakter'
                ]
            ],
            'nohp' => [
                'rules' => 'required|min_length[10]|max_length[15]|numeric',
                'errors' => [
                    'required' => 'Nomor HP harus diisi',
                    'min_length' => 'Nomor HP minimal 10 karakter',
                    'max_length' => 'Nomor HP maksimal 15 karakter',
                    'numeric' => 'Nomor HP harus berupa angka'
                ]
            ]
        ];
    }
}
