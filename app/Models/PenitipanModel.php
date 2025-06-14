<?php

namespace App\Models;

use CodeIgniter\Model;

class PenitipanModel extends Model
{
    protected $table            = 'penitipan';
    protected $primaryKey       = 'kdpenitipan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kdpenitipan', 'tglpenitipan', 'tglselesai', 'tglpenjemputan', 'idpelanggan', 'durasi', 'grandtotal', 'total_biaya_dengan_denda', 'status', 'is_terlambat', 'jumlah_hari_terlambat', 'biaya_denda', 'keterangan'];

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
            'tglpenitipan' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal penitipan harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'tglselesai' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal selesai harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'durasi' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Durasi harus diisi',
                    'integer' => 'Durasi harus berupa angka',
                    'greater_than' => 'Durasi harus lebih dari 0'
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
                'rules' => 'permit_empty|in_list[0,1,2]',
                'errors' => [
                    'in_list' => 'Status tidak valid'
                ]
            ],
        ];
    }

    // Generate Kode Penitipan
    public function generateKdPenitipan()
    {
        $prefix = 'PT';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->like('kdpenitipan', $prefix . $date)
            ->orderBy('kdpenitipan', 'DESC')
            ->first();

        if ($lastId) {
            // Extract the sequence number and increment
            $lastSequence = substr($lastId['kdpenitipan'], -4);
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
        $builder = $this->db->table('penitipan p');
        $builder->select('p.*');
        $builder->select('IFNULL(pl.nama, "Pelanggan Umum") as nama', false);
        $builder->select('IFNULL(pl.nohp, "-") as nohp', false);
        $builder->select('IFNULL(h.namahewan, "-") as namahewan', false);
        $builder->select('IFNULL(h.jenis, "-") as jenishewan', false);
        $builder->join('pelanggan pl', 'pl.idpelanggan = p.idpelanggan', 'left');
        $builder->join('hewan h', 'h.idpelanggan = p.idpelanggan', 'left');

        if ($id) {
            $builder->where('p.kdpenitipan', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    // Get status label
    public function getStatusLabel($status)
    {
        switch ($status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Dalam Penitipan';
            case 2:
                return 'Selesai';
            default:
                return 'Unknown';
        }
    }

    // Hitung denda keterlambatan
    public function hitungDenda($kdpenitipan, $tglPenjemputan = null)
    {
        // Ambil data penitipan
        $penitipan = $this->find($kdpenitipan);
        if (!$penitipan) {
            return [
                'status' => false,
                'message' => 'Data penitipan tidak ditemukan'
            ];
        }

        // Jika tidak ada tanggal penjemputan yang diberikan, gunakan tanggal hari ini
        if (!$tglPenjemputan) {
            $tglPenjemputan = date('Y-m-d');
        }

        // Konversi tanggal ke format datetime
        $tglSelesai = new \DateTime($penitipan['tglselesai']);
        $tglJemput = new \DateTime($tglPenjemputan);

        // Hitung selisih hari
        $selisih = $tglSelesai->diff($tglJemput);
        $hariTerlambat = $selisih->invert ? 0 : $selisih->days;

        // Jika tidak terlambat
        if ($hariTerlambat <= 0) {
            return [
                'status' => true,
                'is_terlambat' => 0,
                'jumlah_hari_terlambat' => 0,
                'biaya_denda' => 0,
                'total_biaya_dengan_denda' => $penitipan['grandtotal']
            ];
        }

        // Jika terlambat, hitung denda (50% dari total biaya per hari)
        $dendaPerHari = $penitipan['grandtotal'] * 0.5;
        $totalDenda = $dendaPerHari * $hariTerlambat;
        $totalBiayaDenganDenda = $penitipan['grandtotal'] + $totalDenda;

        return [
            'status' => true,
            'is_terlambat' => 1,
            'jumlah_hari_terlambat' => $hariTerlambat,
            'biaya_denda' => $totalDenda,
            'total_biaya_dengan_denda' => $totalBiayaDenganDenda
        ];
    }

    // Simpan data denda
    public function simpanDenda($kdpenitipan, $tglPenjemputan)
    {
        $hasil = $this->hitungDenda($kdpenitipan, $tglPenjemputan);

        if (!$hasil['status']) {
            return $hasil;
        }

        // Update data penitipan dengan informasi denda
        $data = [
            'tglpenjemputan' => $tglPenjemputan,
            'is_terlambat' => $hasil['is_terlambat'],
            'jumlah_hari_terlambat' => $hasil['jumlah_hari_terlambat'],
            'biaya_denda' => $hasil['biaya_denda'],
            'total_biaya_dengan_denda' => $hasil['total_biaya_dengan_denda'],
            'status' => 2 // Ubah status menjadi Selesai
        ];

        $update = $this->update($kdpenitipan, $data);

        if ($update) {
            return [
                'status' => true,
                'message' => 'Data denda berhasil disimpan',
                'data' => $hasil
            ];
        }

        return [
            'status' => false,
            'message' => 'Gagal menyimpan data denda'
        ];
    }
}
