<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Hermawan\DataTables\DataTable;

class BarangController extends BaseController
{
    protected $barangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $title = 'Manajemen Barang';
        $kategori = $this->kategoriModel->findAll();
        return view('admin/barang/index', compact('title', 'kategori'));
    }

    public function create()
    {
        $title = 'Tambah Barang';
        $kategori = $this->kategoriModel->findAll();
        $satuan = [
            'Pcs',
            'Box',
            'Kg',
            'Gram',
            'Botol',
            'Sachet',
            'Pack',
            'Lusin',
            'Kodi',
            'Rim',
            'Roll',
            'Meter'
        ];
        return view('admin/barang/create', compact('title', 'kategori', 'satuan'));
    }

    public function edit($id = null)
    {
        $title = 'Edit Barang';
        $barang = $this->barangModel->find($id);

        if (!$barang) {
            return redirect()->to('admin/barang')->with('error', 'Data barang tidak ditemukan');
        }

        $kategori = $this->kategoriModel->findAll();
        $satuan = [
            'Pcs',
            'Box',
            'Kg',
            'Gram',
            'Botol',
            'Sachet',
            'Pack',
            'Lusin',
            'Kodi',
            'Rim',
            'Roll',
            'Meter'
        ];
        return view('admin/barang/edit', compact('title', 'barang', 'kategori', 'satuan'));
    }

    public function getBarang()
    {
        $kategoriFilter = $this->request->getGet('kdkategori') ?? '';

        $builder = $this->barangModel->builder();
        $builder->select('barang.kdbarang, barang.namabarang, barang.jumlah, barang.hargabeli, barang.hargajual, barang.foto, barang.satuan, kategori.namakategori');
        $builder->join('kategori', 'kategori.kdkategori = barang.kdkategori', 'left');

        if (!empty($kategoriFilter)) {
            $builder->where('barang.kdkategori', $kategoriFilter);
        }

        return DataTable::of($builder)
            ->addNumbering('nomor')
            ->format('hargabeli', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->format('hargajual', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->format('foto', function ($value) {
                if (empty($value) || !file_exists(FCPATH . 'uploads/barang/' . $value)) {
                    return '<img src="' . base_url('assets/img/product-default.jpg') . '" class="img-thumbnail" width="50">';
                }
                return '<img src="' . base_url('uploads/barang/' . $value) . '" class="img-thumbnail" width="50">';
            })
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <a href="' . site_url('admin/barang/edit/' . $row->kdbarang) . '" class="btn btn-sm btn-info">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdbarang . '">
                        <i class="bi bi-trash"></i>
                    </button>
                    <a href="' . site_url('admin/barang/detail/' . $row->kdbarang) . '" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>';
            })
            ->toJson(true);
    }

    public function getNextKdBarang()
    {
        $nextId = $this->barangModel->generateKdBarang();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['kdbarang' => $nextId]
        ]);
    }

    public function getBarangById($id = null)
    {
        $data = $this->barangModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data barang tidak ditemukan'
        ]);
    }

    public function addBarang()
    {
        $data = $this->request->getPost();

        // Handle file upload jika ada
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/barang', $newName);
            $data['foto'] = $newName;
        }

        // Generate ID
        $data['kdbarang'] = $this->barangModel->generateKdBarang();

        if ($this->barangModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data barang berhasil ditambahkan',
                'data' => ['kdbarang' => $data['kdbarang']]
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan data barang',
            'errors' => $this->barangModel->errors()
        ]);
    }

    public function updateBarang($id = null)
    {
        $data = $this->request->getPost();

        // Pastikan ID selalu diset dengan benar
        if (!empty($id)) {
            $data['kdbarang'] = $id;
        }

        // Validasi ID
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID barang tidak valid',
                'errors' => ['kdbarang' => 'ID barang tidak ditemukan']
            ]);
        }

        // Cek apakah data exists
        $existingBarang = $this->barangModel->find($id);
        if (!$existingBarang) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data barang tidak ditemukan',
                'errors' => ['kdbarang' => 'Barang dengan ID tersebut tidak ditemukan']
            ]);
        }

        // Handle file upload jika ada
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Hapus foto lama jika ada
            if (!empty($existingBarang['foto']) && file_exists(FCPATH . 'uploads/barang/' . $existingBarang['foto'])) {
                unlink(FCPATH . 'uploads/barang/' . $existingBarang['foto']);
            }

            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/barang', $newName);
            $data['foto'] = $newName;
        }

        // Handle hapus foto
        if ($this->request->getPost('hapusFoto') == '1') {
            // Hapus foto dari storage
            if (!empty($existingBarang['foto']) && file_exists(FCPATH . 'uploads/barang/' . $existingBarang['foto'])) {
                unlink(FCPATH . 'uploads/barang/' . $existingBarang['foto']);
            }
            $data['foto'] = '';
        }

        if ($this->barangModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data barang berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui data barang',
            'errors' => $this->barangModel->errors()
        ]);
    }

    public function deleteBarang($id = null)
    {
        // Cek apakah data exists
        $existingBarang = $this->barangModel->find($id);
        if (!$existingBarang) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data barang tidak ditemukan'
            ]);
        }

        // Hapus foto jika ada
        if (!empty($existingBarang['foto']) && file_exists(FCPATH . 'uploads/barang/' . $existingBarang['foto'])) {
            unlink(FCPATH . 'uploads/barang/' . $existingBarang['foto']);
        }

        if ($this->barangModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data barang berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus data barang'
        ]);
    }

    public function detail($id = null)
    {
        $title = 'Detail Barang';
        $barang = $this->barangModel->find($id);

        if (!$barang) {
            return redirect()->to('admin/barang')->with('error', 'Data barang tidak ditemukan');
        }

        $kategori = $this->kategoriModel->find($barang['kdkategori']);
        return view('admin/barang/detail', compact('title', 'barang', 'kategori'));
    }
}
