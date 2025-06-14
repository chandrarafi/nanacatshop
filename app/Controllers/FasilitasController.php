<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FasilitasModel;
use Hermawan\DataTables\DataTable;

class FasilitasController extends BaseController
{
    protected $fasilitasModel;

    public function __construct()
    {
        $this->fasilitasModel = new FasilitasModel();
    }

    public function index()
    {
        $title = 'Manajemen Fasilitas';
        return view('admin/fasilitas/index', compact('title'));
    }

    public function getFasilitas()
    {
        $builder = $this->fasilitasModel->builder();
        $builder->select('kdfasilitas, namafasilitas, kategori, harga, satuan');

        return DataTable::of($builder)
            ->format('harga', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <button class="btn btn-sm btn-info btn-edit" data-id="' . $row->kdfasilitas . '">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdfasilitas . '">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>';
            })
            ->toJson(true);
    }

    public function getNextKdFasilitas()
    {
        $nextId = $this->fasilitasModel->generateKdFasilitas();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['kdfasilitas' => $nextId]
        ]);
    }

    public function getFasilitasById($id = null)
    {
        $data = $this->fasilitasModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data fasilitas tidak ditemukan'
        ]);
    }

    public function addFasilitas()
    {
        $data = [
            'kdfasilitas' => $this->request->getPost('kdfasilitas') ?: $this->fasilitasModel->generateKdFasilitas(),
            'namafasilitas' => $this->request->getPost('namafasilitas'),
            'kategori' => $this->request->getPost('kategori'),
            'harga' => $this->request->getPost('harga'),
            'satuan' => $this->request->getPost('satuan'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if ($this->fasilitasModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data fasilitas berhasil ditambahkan'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan data fasilitas',
            'errors' => $this->fasilitasModel->errors()
        ]);
    }

    public function updateFasilitas($id = null)
    {
        $data = [
            'kdfasilitas' => $id,
            'namafasilitas' => $this->request->getPost('namafasilitas'),
            'kategori' => $this->request->getPost('kategori'),
            'harga' => $this->request->getPost('harga'),
            'satuan' => $this->request->getPost('satuan'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if ($this->fasilitasModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data fasilitas berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui data fasilitas',
            'errors' => $this->fasilitasModel->errors()
        ]);
    }

    public function deleteFasilitas($id = null)
    {
        if ($this->fasilitasModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data fasilitas berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus data fasilitas'
        ]);
    }
}
