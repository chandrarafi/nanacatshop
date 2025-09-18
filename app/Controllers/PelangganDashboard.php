<?php

namespace App\Controllers;

use App\Models\PelangganModel;

class PelangganDashboard extends BaseController
{
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil data pelanggan berdasarkan nama (karena tidak ada relasi langsung)
        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();

        $data = [
            'title' => 'Dashboard Pelanggan',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ],
            'pelanggan' => $pelangganData
        ];

        return view('pelanggan/dashboard', $data);
    }

    public function completeProfile()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Cek apakah profil sudah lengkap
        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();
        if ($pelangganData) {
            return redirect()->to('pelanggan')->with('message', 'Profil Anda sudah lengkap');
        }

        $data = [
            'title' => 'Lengkapi Profil',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ]
        ];

        return view('pelanggan/complete_profile', $data);
    }

    public function doCompleteProfile()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke halaman ini'
            ]);
        }

        // Cek apakah profil sudah lengkap
        $existingPelanggan = $this->pelangganModel->where('nama', session()->get('name'))->first();
        if ($existingPelanggan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Profil Anda sudah lengkap'
            ]);
        }

        $data = [
            'idpelanggan' => $this->pelangganModel->generateIdPelanggan(),
            'nama' => $this->request->getPost('nama'),
            'jenkel' => $this->request->getPost('jenkel'),
            'nohp' => $this->request->getPost('nohp'),
            'alamat' => $this->request->getPost('alamat')
        ];

        // Validasi data
        if (!$this->pelangganModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan profil',
                'errors' => $this->pelangganModel->errors()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Profil berhasil dilengkapi!',
            'redirect' => site_url('pelanggan')
        ]);
    }
}
