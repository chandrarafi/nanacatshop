<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PelangganModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->pelangganModel = new PelangganModel();
        // Load helper cookie
        helper('cookie');
    }

    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to(session()->get('redirect_url') ?? 'admin');
        }

        return view('auth/login');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') == 'on';


        $user = $this->userModel->where('username', $username)
            ->orWhere('email', $username)
            ->first();


        if ($user) {
            // Debug log

            if ($user['status'] !== 'active') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ]);
            }


            if (password_verify($password, $user['password'])) {
                // Update last login
                $this->userModel->update($user['id'], [
                    'last_login' => date('Y-m-d H:i:s')
                ]);

                // Set session
                $sessionData = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'logged_in' => true
                ];
                session()->set($sessionData);

                // Set remember me cookie jika dipilih
                if ($remember) {
                    $this->setRememberMeCookie($user['id']);
                }

                // Redirect berdasarkan role
                $redirectUrl = 'admin';
                if ($user['role'] === 'pelanggan') {
                    $redirectUrl = 'pelanggan'; // Akan dibuat route untuk dashboard pelanggan
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'redirect' => site_url($redirectUrl)
                ]);
            }
        } else {
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Username/Email atau Password salah'
        ]);
    }

    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            $userRole = session()->get('role');
            $redirectUrl = ($userRole === 'pelanggan') ? 'pelanggan' : 'admin';
            return redirect()->to($redirectUrl);
        }

        return view('auth/register');
    }

    public function doRegister()
    {
        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'name' => $this->request->getPost('name'),
            'role' => 'pelanggan', // Default role untuk registrasi
            'status' => 'active'
        ];

        // Validasi data user
        if (!$this->userModel->insert($userData)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mendaftar',
                'errors' => $this->userModel->errors()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Registrasi berhasil! Silakan lengkapi profil Anda setelah login.',
            'redirect' => site_url('auth')
        ]);
    }

    public function logout()
    {
        // Hapus remember me cookie
        if (get_cookie('remember_token')) {
            delete_cookie('remember_token');
            delete_cookie('user_id');
        }

        // Destroy session
        session()->destroy();

        return redirect()->to('auth')->with('message', 'Anda telah berhasil logout');
    }

    protected function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));

        // Simpan token di database
        $this->userModel->update($userId, [
            'remember_token' => $token
        ]);

        // Set cookies yang akan expired dalam 30 hari
        $expires = 30 * 24 * 60 * 60; // 30 hari
        $secure = isset($_SERVER['HTTPS']); // Set secure hanya jika HTTPS

        // Set cookie untuk remember token
        set_cookie(
            'remember_token',
            $token,
            $expires,
            '',  // domain
            '/', // path
            '', // prefix
            $secure, // secure - hanya set true jika HTTPS
            true  // httponly
        );

        // Set cookie untuk user ID
        set_cookie(
            'user_id',
            $userId,
            $expires,
            '',
            '/',
            '',
            $secure,
            true
        );
    }
}
