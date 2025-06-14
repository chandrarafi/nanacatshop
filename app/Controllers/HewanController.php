<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HewanModel;
use App\Models\PelangganModel;
use Hermawan\DataTables\DataTable;

class HewanController extends BaseController
{
    protected $hewanModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->hewanModel = new HewanModel();
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        $title = 'Manajemen Hewan';
        $pelanggan = $this->pelangganModel->findAll();

        return view('admin/hewan/index', compact('title', 'pelanggan'));
    }

    public function create()
    {
        $title = 'Tambah Data Hewan';
        $pelanggan = $this->pelangganModel->findAll();

        return view('admin/hewan/create', compact('title', 'pelanggan'));
    }

    public function edit($id = null)
    {
        $title = 'Edit Data Hewan';
        $hewan = $this->hewanModel->find($id);

        if (!$hewan) {
            return redirect()->to('admin/hewan')->with('error', 'Data hewan tidak ditemukan');
        }

        $pelanggan = $this->pelangganModel->findAll();

        return view('admin/hewan/edit', compact('title', 'hewan', 'pelanggan'));
    }

    public function getHewan()
    {
        $jenisFilter = $this->request->getGet('jenis') ?? '';
        $jenkelFilter = $this->request->getGet('jenkel') ?? '';
        $pelangganFilter = $this->request->getGet('idpelanggan') ?? '';

        $builder = $this->hewanModel->builder();
        $builder->select('hewan.idhewan, hewan.namahewan, hewan.jenis, hewan.jenkel, hewan.foto, hewan.idpelanggan, pelanggan.nama as nama_pelanggan');
        $builder->join('pelanggan', 'pelanggan.idpelanggan = hewan.idpelanggan', 'left');

        if (!empty($jenisFilter)) {
            $builder->where('hewan.jenis', $jenisFilter);
        }

        if (!empty($jenkelFilter)) {
            $builder->where('hewan.jenkel', $jenkelFilter);
        }

        if (!empty($pelangganFilter)) {
            $builder->where('hewan.idpelanggan', $pelangganFilter);
        }

        return DataTable::of($builder)
            ->addNumbering('nomor')

            ->format('jenkel', function ($value) {
                return ($value == 'L') ? 'Laki-laki' : 'Perempuan';
            })

            ->format('foto', function ($value) {
                if (empty($value) || !file_exists(FCPATH . 'uploads/hewan/' . $value)) {
                    return '<img src="' . base_url('assets/img/cat-default.webp') . '" class="img-thumbnail" width="50">';
                }
                return '<img src="' . base_url('uploads/hewan/' . $value) . '" class="img-thumbnail" width="50">';
            })
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <a href="' . site_url('admin/hewan/edit/' . $row->idhewan) . '" class="btn btn-sm btn-info">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->idhewan . '">
                        <i class="bi bi-trash"></i>
                    </button>
                    <a href="' . site_url('admin/hewan/detail/' . $row->idhewan) . '" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>';
            })
            ->toJson(true);
    }

    public function getNextIdHewan()
    {
        $nextId = $this->hewanModel->generateIdHewan();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['idhewan' => $nextId]
        ]);
    }

    public function getHewanById($id = null)
    {
        $data = $this->hewanModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data hewan tidak ditemukan'
        ]);
    }

    public function addHewan()
    {
        $data = $this->request->getPost();

        // Handle file upload jika ada
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/hewan', $newName);
            $data['foto'] = $newName;
        }

        // Generate ID
        $data['idhewan'] = $this->hewanModel->generateIdHewan();

        if ($this->hewanModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data hewan berhasil ditambahkan',
                'data' => ['idhewan' => $data['idhewan']]
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan data hewan',
            'errors' => $this->hewanModel->errors()
        ]);
    }

    public function updateHewan($id = null)
    {
        $data = $this->request->getPost();

        // Pastikan ID selalu diset dengan benar
        if (!empty($id)) {
            $data['idhewan'] = $id;
        }

        // Validasi ID
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID hewan tidak valid',
                'errors' => ['idhewan' => 'ID hewan tidak ditemukan']
            ]);
        }

        // Cek apakah data exists
        $existingHewan = $this->hewanModel->find($id);
        if (!$existingHewan) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data hewan tidak ditemukan',
                'errors' => ['idhewan' => 'Hewan dengan ID tersebut tidak ditemukan']
            ]);
        }

        // Handle file upload jika ada
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Hapus foto lama jika ada
            if (!empty($existingHewan['foto']) && file_exists(FCPATH . 'uploads/hewan/' . $existingHewan['foto'])) {
                unlink(FCPATH . 'uploads/hewan/' . $existingHewan['foto']);
            }

            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/hewan', $newName);
            $data['foto'] = $newName;
        }

        if ($this->hewanModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data hewan berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui data hewan',
            'errors' => $this->hewanModel->errors()
        ]);
    }

    public function deleteHewan($id = null)
    {
        // Cek apakah data exists
        $existingHewan = $this->hewanModel->find($id);
        if (!$existingHewan) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data hewan tidak ditemukan'
            ]);
        }

        // Hapus foto jika ada
        if (!empty($existingHewan['foto']) && file_exists(FCPATH . 'uploads/hewan/' . $existingHewan['foto'])) {
            unlink(FCPATH . 'uploads/hewan/' . $existingHewan['foto']);
        }

        if ($this->hewanModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data hewan berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus data hewan'
        ]);
    }

    public function detail($id = null)
    {
        $title = 'Detail Hewan';
        $hewan = $this->hewanModel->find($id);

        if (!$hewan) {
            return redirect()->to('admin/hewan')->with('error', 'Data hewan tidak ditemukan');
        }

        $pelanggan = $this->pelangganModel->find($hewan['idpelanggan']);

        return view('admin/hewan/detail', compact('title', 'hewan', 'pelanggan'));
    }
}
