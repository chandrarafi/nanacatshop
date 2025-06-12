<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use Hermawan\DataTables\DataTable;

class PelangganController extends BaseController
{
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        $title = 'Manajemen Pelanggan';
        return view('admin/pelanggan/index', compact('title'));
    }

    public function getPelanggan()
    {
        $jenkelFilter = $this->request->getGet('jenkel') ?? '';

        $builder = $this->pelangganModel->builder();

        // Tambahkan filter jenis kelamin jika ada
        if (!empty($jenkelFilter)) {
            $builder->where('jenkel', $jenkelFilter);
        }

        return DataTable::of($builder)
            ->addNumbering() // Menambahkan kolom nomor
            ->format('jenkel', function ($value) {
                return $value === 'L' ? 'Laki-laki' : 'Perempuan';
            })
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <button class="btn btn-sm btn-info btn-edit" data-id="' . $row->idpelanggan . '">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->idpelanggan . '">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>';
            })
            ->toJson(true);
    }

    public function generateIdPelanggan()
    {
        $prefix = 'PLG';
        $date = date('Ymd');

        // Get the last ID with the same prefix and date
        $lastId = $this->pelangganModel->like('idpelanggan', $prefix)
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

    public function getNextIdPelanggan()
    {
        $nextId = $this->generateIdPelanggan();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['idpelanggan' => $nextId]
        ]);
    }

    protected function handlePelangganSave($data, $isNew = true)
    {
        // Generate ID for new records
        if ($isNew) {
            $data['idpelanggan'] = $this->generateIdPelanggan();
        }

        if ($this->pelangganModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $isNew ? 'Pelanggan berhasil ditambahkan' : 'Pelanggan berhasil diperbarui',
                'data' => ['idpelanggan' => $isNew ? $data['idpelanggan'] : $data['idpelanggan']]
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => $isNew ? 'Gagal menambahkan pelanggan' : 'Gagal memperbarui pelanggan',
            'errors' => $this->pelangganModel->errors()
        ]);
    }

    public function addPelanggan()
    {
        return $this->handlePelangganSave($this->request->getPost(), true);
    }

    public function getPelangganById($id = null)
    {
        $data = $this->pelangganModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Pelanggan tidak ditemukan'
        ]);
    }

    public function updatePelanggan($id = null)
    {
        $data = $this->request->getPost();

        // Pastikan ID selalu diset dengan benar
        if (!empty($id)) {
            $data['idpelanggan'] = $id;
        }

        // Validasi ID
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID pelanggan tidak valid',
                'errors' => ['idpelanggan' => 'ID pelanggan tidak ditemukan']
            ]);
        }

        // Cek apakah pelanggan exists
        $existingPelanggan = $this->pelangganModel->find($id);
        if (!$existingPelanggan) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Pelanggan tidak ditemukan',
                'errors' => ['idpelanggan' => 'Pelanggan dengan ID tersebut tidak ditemukan']
            ]);
        }

        return $this->handlePelangganSave($data, false);
    }

    public function deletePelanggan($id = null)
    {
        if ($this->pelangganModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pelanggan berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus pelanggan'
        ]);
    }
}
