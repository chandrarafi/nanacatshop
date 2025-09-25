<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\BarangModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;

class PelangganDashboard extends BaseController
{
    protected $pelangganModel;
    protected $barangModel;
    protected $orderModel;
    protected $orderDetailModel;
    protected $db;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
        $this->barangModel = new BarangModel();
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {

        // Ambil data pelanggan berdasarkan user session
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

    public function editProfile()
    {
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin');
        }
        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();
        $data = [
            'title' => 'Edit Profil',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ],
            'pelanggan' => $pelangganData
        ];
        return view('pelanggan/complete_profile', $data);
    }

    public function updateProfile()
    {
        if (session()->get('role') !== 'pelanggan') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak diizinkan']);
        }
        $existing = $this->pelangganModel->where('nama', session()->get('name'))->first();
        if (!$existing) {
            return $this->doCompleteProfile();
        }
        $data = [
            'jenkel' => $this->request->getPost('jenkel'),
            'nohp' => $this->request->getPost('nohp'),
            'alamat' => $this->request->getPost('alamat')
        ];
        if (!$this->pelangganModel->update($existing['idpelanggan'], $data)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui profil', 'errors' => $this->pelangganModel->errors()]);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Profil diperbarui', 'redirect' => site_url('pelanggan')]);
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

    public function shop()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Cek apakah profil sudah lengkap
        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();
        if (!$pelangganData) {
            return redirect()->to('pelanggan/complete-profile')->with('error', 'Silakan lengkapi profil terlebih dahulu');
        }

        // Ambil data barang yang tersedia
        $barang = $this->barangModel->where('jumlah >', 0)->findAll();
        $kategoriModel = new \App\Models\KategoriModel();
        $kategori = $kategoriModel->findAll();

        $data = [
            'title' => 'Belanja Online',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ],
            'pelanggan' => $pelangganData,
            'barang' => $barang,
            'kategori' => $kategori
        ];

        return view('pelanggan/shop', $data);
    }

    public function addToCart()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses'
            ]);
        }

        $kdbarang = $this->request->getPost('kdbarang');
        $quantity = (int)$this->request->getPost('quantity', FILTER_SANITIZE_NUMBER_INT);

        if (!$kdbarang || $quantity <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak valid'
            ]);
        }

        // Cek stok barang
        $barang = $this->barangModel->find($kdbarang);
        if (!$barang || $barang['jumlah'] < $quantity) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi'
            ]);
        }

        // Simpan ke session cart
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$kdbarang])) {
            $cart[$kdbarang]['quantity'] += $quantity;
        } else {
            $cart[$kdbarang] = [
                'kdbarang' => $kdbarang,
                'namabarang' => $barang['namabarang'],
                'price' => $barang['hargajual'],
                'quantity' => $quantity,
                'foto' => $barang['foto'],
                'satuan' => $barang['satuan']
            ];
        }

        session()->set('cart', $cart);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Barang berhasil ditambahkan ke keranjang',
            'cart_count' => count($cart)
        ]);
    }

    public function cart()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $cart = session()->get('cart') ?? [];
        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();

        $data = [
            'title' => 'Keranjang Belanja',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ],
            'pelanggan' => $pelangganData,
            'cart' => $cart
        ];

        return view('pelanggan/cart', $data);
    }

    public function updateCart()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses'
            ]);
        }

        $kdbarang = $this->request->getPost('kdbarang');
        $quantity = (int)$this->request->getPost('quantity', FILTER_SANITIZE_NUMBER_INT);

        $cart = session()->get('cart') ?? [];

        if ($quantity <= 0) {
            unset($cart[$kdbarang]);
        } else {
            // Cek stok barang
            $barang = $this->barangModel->find($kdbarang);
            if (!$barang || $barang['jumlah'] < $quantity) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi'
                ]);
            }

            if (isset($cart[$kdbarang])) {
                $cart[$kdbarang]['quantity'] = $quantity;
            }
        }

        session()->set('cart', $cart);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Keranjang berhasil diperbarui',
            'cart_count' => count($cart)
        ]);
    }

    public function removeFromCart()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses'
            ]);
        }

        $kdbarang = $this->request->getPost('kdbarang');
        $cart = session()->get('cart') ?? [];

        unset($cart[$kdbarang]);
        session()->set('cart', $cart);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Barang berhasil dihapus dari keranjang',
            'cart_count' => count($cart)
        ]);
    }

    public function checkout()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('pelanggan/cart')->with('error', 'Keranjang kosong');
        }

        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();

        $data = [
            'title' => 'Checkout',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ],
            'pelanggan' => $pelangganData,
            'cart' => $cart
        ];

        return view('pelanggan/checkout', $data);
    }

    public function processOrder()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses'
            ]);
        }

        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Keranjang kosong'
            ]);
        }

        // Ambil data pelanggan
        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();
        if (!$pelangganData) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Profil pelanggan tidak ditemukan'
            ]);
        }

        // Validasi form
        $paymentMethod = $this->request->getPost('payment_method');
        $paymentBank = $this->request->getPost('payment_bank');
        $shippingAddress = $this->request->getPost('shipping_address');
        $notes = $this->request->getPost('notes');
        $paymentProofFile = $this->request->getFile('payment_proof');

        if (!$paymentMethod || !$shippingAddress) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Metode pembayaran dan alamat pengiriman harus diisi'
            ]);
        }

        // Validasi panjang alamat dan nomor HP pelanggan
        $shippingAddress = trim($shippingAddress);
        if (mb_strlen($shippingAddress) < 10 || mb_strlen($shippingAddress) > 300) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Alamat pengiriman minimal 10 dan maksimal 300 karakter'
            ]);
        }

        $pelangganPhone = $pelangganData['nohp'] ?? '';
        if (!preg_match('/^(\+?62|0)\d{9,13}$/', preg_replace('/\s+/', '', (string)$pelangganPhone))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nomor HP pada profil tidak valid. Perbarui profil Anda.'
            ]);
        }

        // Revalidasi stok terbaru dan hitung total
        $totalAmount = 0;
        foreach ($cart as $item) {
            $barangCurrent = $this->barangModel->find($item['kdbarang']);
            if (!$barangCurrent || $barangCurrent['jumlah'] < $item['quantity']) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi untuk ' . $item['namabarang']
                ]);
            }
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Siapkan upload bukti pembayaran (opsional)
        $paymentProofFilename = null;
        if ($paymentProofFile && $paymentProofFile->isValid() && !$paymentProofFile->hasMoved()) {
            // Validasi mime dan ukuran dasar
            $validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            if (!in_array($paymentProofFile->getMimeType(), $validTypes)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Format bukti pembayaran harus gambar (JPG/PNG) atau PDF'
                ]);
            }
            // Batas ukuran 2MB
            if ($paymentProofFile->getSize() > 2 * 1024 * 1024) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Ukuran bukti pembayaran maksimal 2MB'
                ]);
            }

            // Pastikan folder upload ada (public/uploads/payment_proofs)
            $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'payment_proofs';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $newName = $paymentProofFile->getRandomName();
            if (!$paymentProofFile->move($uploadDir, $newName)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal mengunggah bukti pembayaran'
                ]);
            }
            // Simpan path relatif dari public root untuk ditampilkan
            $paymentProofFilename = 'uploads/payment_proofs/' . $newName;
        }

        // Mulai transaksi database
        $this->db->transStart();

        try {
            // Buat order
            $orderData = [
                'order_number' => $this->orderModel->generateOrderNumber(),
                'pelanggan_id' => $pelangganData['idpelanggan'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_bank' => $paymentBank,
                'shipping_address' => $shippingAddress,
                'notes' => $notes,
                'payment_proof' => $paymentProofFilename
            ];

            $orderId = $this->orderModel->insert($orderData);

            if (!$orderId) {
                throw new \Exception('Gagal membuat order');
            }

            // Buat order details dan update stok
            foreach ($cart as $item) {
                // Insert order detail
                $orderDetailData = [
                    'order_id' => $orderId,
                    'kdbarang' => $item['kdbarang'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ];

                if (!$this->orderDetailModel->insert($orderDetailData)) {
                    throw new \Exception('Gagal membuat detail order');
                }

                // Update stok barang
                $barang = $this->barangModel->find($item['kdbarang']);
                $newStock = $barang['jumlah'] - $item['quantity'];

                if (!$this->barangModel->update($item['kdbarang'], ['jumlah' => $newStock])) {
                    throw new \Exception('Gagal update stok barang');
                }
            }

            // Log initial status timeline
            $historyModel = new \App\Models\OrderStatusHistoryModel();
            $historyModel->logStatus((int)$orderId, 'pending', 'Pesanan dibuat');

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi gagal');
            }

            // Hapus cart dari session
            session()->remove('cart');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Order berhasil dibuat!',
                'order_number' => $orderData['order_number'],
                'redirect' => site_url('pelanggan/orders')
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            // Hapus file bukti bayar jika sudah terunggah
            if ($paymentProofFilename) {
                $full = FCPATH . $paymentProofFilename;
                if (is_file($full)) @unlink($full);
            }
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function orders()
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();
        $orders = $this->orderModel->getOrdersByPelanggan($pelangganData['idpelanggan']);

        $data = [
            'title' => 'Riwayat Pesanan',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ],
            'pelanggan' => $pelangganData,
            'orders' => $orders
        ];

        return view('pelanggan/orders', $data);
    }

    public function orderDetail($orderId)
    {
        // Pastikan hanya pelanggan yang bisa akses
        if (session()->get('role') !== 'pelanggan') {
            return redirect()->to('admin')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();
        $order = $this->orderModel->getOrderWithDetails($orderId);

        // Pastikan order milik pelanggan ini
        if (!$order || $order['pelanggan_id'] !== $pelangganData['idpelanggan']) {
            return redirect()->to('pelanggan/orders')->with('error', 'Order tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pesanan',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'username' => session()->get('username')
            ],
            'pelanggan' => $pelangganData,
            'order' => $order
        ];

        return view('pelanggan/order_detail', $data);
    }

    public function receiveOrder($orderId)
    {
        // Hanya pelanggan
        if (session()->get('role') !== 'pelanggan') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses'
            ]);
        }

        // Ambil data pelanggan dan order
        $pelangganData = $this->pelangganModel->where('nama', session()->get('name'))->first();
        if (!$pelangganData) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Profil pelanggan tidak ditemukan'
            ]);
        }

        $order = $this->orderModel->find($orderId);
        if (!$order || $order['pelanggan_id'] !== $pelangganData['idpelanggan']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan'
            ]);
        }

        // Hanya bisa diterima jika sudah dikirim
        if ($order['status'] !== 'shipped') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pesanan belum dalam status Dikirim'
            ]);
        }

        if (!$this->orderModel->update($orderId, ['status' => 'delivered'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengubah status pesanan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Terima kasih! Pesanan telah ditandai selesai.',
            'redirect' => site_url('pelanggan/order-detail/' . $orderId)
        ]);
    }
}
