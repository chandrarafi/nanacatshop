<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use Hermawan\DataTables\DataTable;

class SupplierController extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        $title = 'Manajemen Supplier';
        return view('admin/supplier/index', compact('title'));
    }

    public function getSupplier()
    {
        $builder = $this->supplierModel->builder();

        return DataTable::of($builder)
            ->addNumbering('nomor')
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <button class="btn btn-sm btn-info btn-edit" data-id="' . $row->kdspl . '">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdspl . '">
                        <i class="bi bi-trash"></i>
                    </button>
                    <a href="' . site_url('admin/supplier/detail/' . $row->kdspl) . '" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>';
            })
            ->toJson(true);
    }

    public function getSupplierById($id = null)
    {
        $data = $this->supplierModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data supplier tidak ditemukan'
        ]);
    }

    public function addSupplier()
    {
        $data = $this->request->getPost();

        // Jika hanya ingin mendapatkan kode baru tanpa menyimpan
        if (isset($data['getKodeOnly']) && $data['getKodeOnly'] == 'true') {
            $newKode = $this->supplierModel->generateKdSpl();
            return $this->response->setJSON([
                'status' => 'success',
                'data' => ['kdspl' => $newKode]
            ]);
        }

        // Generate ID
        $data['kdspl'] = $this->supplierModel->generateKdSpl();

        if ($this->supplierModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data supplier berhasil ditambahkan',
                'data' => ['kdspl' => $data['kdspl']]
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan data supplier',
            'errors' => $this->supplierModel->errors()
        ]);
    }

    public function updateSupplier($id = null)
    {
        $data = $this->request->getPost();

        // Pastikan ID selalu diset dengan benar
        if (!empty($id)) {
            $data['kdspl'] = $id;
        }

        // Validasi ID
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID supplier tidak valid',
                'errors' => ['kdspl' => 'ID supplier tidak ditemukan']
            ]);
        }

        // Cek apakah data exists
        $existingSupplier = $this->supplierModel->find($id);
        if (!$existingSupplier) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data supplier tidak ditemukan',
                'errors' => ['kdspl' => 'Supplier dengan ID tersebut tidak ditemukan']
            ]);
        }

        if ($this->supplierModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data supplier berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui data supplier',
            'errors' => $this->supplierModel->errors()
        ]);
    }

    public function deleteSupplier($id = null)
    {
        // Cek apakah data exists
        $existingSupplier = $this->supplierModel->find($id);
        if (!$existingSupplier) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data supplier tidak ditemukan'
            ]);
        }

        if ($this->supplierModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data supplier berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus data supplier'
        ]);
    }

    public function detail($id = null)
    {
        $title = 'Detail Supplier';
        $supplier = $this->supplierModel->find($id);

        if (!$supplier) {
            return redirect()->to('admin/supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        return view('admin/supplier/detail', compact('title', 'supplier'));
    }
}
