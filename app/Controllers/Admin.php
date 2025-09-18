<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Hermawan\DataTables\DataTable;

class Admin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $title = 'Dashboard';
        return view('admin/dashboard', compact('title'));
    }

    // User Management
    public function users()
    {
        $title = 'User Management';
        return view('admin/users/index', compact('title'));
    }

    public function getUsers()
    {
        $roleFilter = $this->request->getGet('role') ?? '';
        $statusFilter = $this->request->getGet('status') ?? '';

        $builder = $this->userModel->builder();

        // Role filter
        if (!empty($roleFilter)) {
            $builder->where('role', $roleFilter);
        }

        // Status filter
        if (!empty($statusFilter)) {
            $builder->where('status', $statusFilter);
        }

        return DataTable::of($builder)
            ->format('role', function ($value) {
                $badgeClass = 'bg-secondary';

                if ($value === 'admin') {
                    $badgeClass = 'bg-primary';
                } else if ($value === 'pimpinan') {
                    $badgeClass = 'bg-info';
                } else if ($value === 'user') {
                    $badgeClass = 'bg-dark';
                }

                return '<span class="badge ' . $badgeClass . '">' . ucfirst($value) . '</span>';
            })
            ->format('status', function ($value) {
                if ($value === 'active') {
                    return '<span class="badge bg-success">Aktif</span>';
                } else {
                    return '<span class="badge bg-danger">Tidak Aktif</span>';
                }
            })
            ->format('last_login', function ($value) {
                return $value ? $value : '<span class="text-muted small">Belum pernah</span>';
            })
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <button class="btn btn-sm btn-info btn-action btn-edit" data-id="' . $row->id . '">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-action btn-delete" data-id="' . $row->id . '">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>';
            })
            ->toJson(true);
    }

    protected function handleUserSave($data, $isNew = true)
    {
        if ($this->userModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $isNew ? 'User berhasil ditambahkan' : 'User berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => $isNew ? 'Gagal menambahkan user' : 'Gagal memperbarui user',
            'errors' => $this->userModel->errors()
        ]);
    }

    public function addUser()
    {
        return $this->handleUserSave($this->request->getPost(), true);
    }

    public function createUser()
    {
        return $this->handleUserSave($this->request->getJSON(true), true);
    }

    public function getUser($id = null)
    {
        $data = $this->userModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'User tidak ditemukan'
        ]);
    }

    public function updateUser($id = null)
    {
        $data = $this->request->getPost();

        // Pastikan ID selalu diset dengan benar
        if (!empty($id)) {
            $data['id'] = $id;
        } elseif (!empty($data['id'])) {
            $id = $data['id'];
        }

        // Validasi ID
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID user tidak valid',
                'errors' => ['id' => 'ID user tidak ditemukan']
            ]);
        }

        // Cek apakah user exists
        $existingUser = $this->userModel->find($id);
        if (!$existingUser) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'User tidak ditemukan',
                'errors' => ['id' => 'User dengan ID tersebut tidak ditemukan']
            ]);
        }

        return $this->handleUserSave($data, false);
    }

    public function deleteUser($id = null)
    {
        if ($this->userModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus user'
        ]);
    }

    public function getRoles()
    {
        // Daftar role yang tersedia
        $roles = ['admin', 'pimpinan', 'user', 'pelanggan'];

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $roles
        ]);
    }
}
