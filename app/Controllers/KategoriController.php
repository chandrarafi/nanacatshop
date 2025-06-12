<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use Hermawan\DataTables\DataTable;

class KategoriController extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $title = 'Manajemen Kategori';
        return view('admin/kategori/index', compact('title'));
    }

    public function getKategori()
    {
        $builder = $this->kategoriModel->builder();

        return DataTable::of($builder)
            ->addNumbering('nomor')
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <button class="btn btn-sm btn-info btn-edit" data-id="' . $row->kdkategori . '">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdkategori . '">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>';
            })
            ->toJson(true);
    }

    public function getKategoriById($id = null)
    {
        $data = $this->kategoriModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data kategori tidak ditemukan'
        ]);
    }

    public function addKategori()
    {
        $data = $this->request->getPost();

        // Jika hanya ingin mendapatkan kode baru tanpa menyimpan
        if (isset($data['getKodeOnly']) && $data['getKodeOnly'] == 'true') {
            $newKode = $this->kategoriModel->generateKdKategori();
            return $this->response->setJSON([
                'status' => 'success',
                'data' => ['kdkategori' => $newKode]
            ]);
        }

        // Selalu generate ID baru tanpa menerima dari client
        $data['kdkategori'] = $this->kategoriModel->generateKdKategori();

        if ($this->kategoriModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Kategori berhasil ditambahkan',
                'data' => ['kdkategori' => $data['kdkategori']]
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan kategori',
            'errors' => $this->kategoriModel->errors()
        ]);
    }

    public function updateKategori($id = null)
    {
        $data = $this->request->getPost();

        // Pastikan ID selalu diset dengan benar
        if (!empty($id)) {
            $data['kdkategori'] = $id;
        }

        // Validasi ID
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID kategori tidak valid',
                'errors' => ['kdkategori' => 'ID kategori tidak ditemukan']
            ]);
        }

        // Cek apakah data exists
        $existingKategori = $this->kategoriModel->find($id);
        if (!$existingKategori) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data kategori tidak ditemukan',
                'errors' => ['kdkategori' => 'Kategori dengan ID tersebut tidak ditemukan']
            ]);
        }

        if ($this->kategoriModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Kategori berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui kategori',
            'errors' => $this->kategoriModel->errors()
        ]);
    }

    public function deleteKategori($id = null)
    {
        // Cek apakah data exists
        $existingKategori = $this->kategoriModel->find($id);
        if (!$existingKategori) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data kategori tidak ditemukan'
            ]);
        }

        if ($this->kategoriModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Kategori berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus kategori'
        ]);
    }
}
