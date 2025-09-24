<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Nana Cat Shop</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#F564A9',
                        secondary: '#FAA4BD',
                        accent: '#533B4D',
                        light: '#FAE3C6',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFF5F9;
        }
    </style>
</head>

<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img src="<?= base_url('assets/img/catshoplogo.png') ?>" alt="Nana Cat Shop Logo" class="h-10 w-auto mr-3" onerror="this.style.display='none'">
                    <h1 class="text-2xl font-bold text-primary">Nana Cat Shop</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Selamat datang, <strong><?= $user['name'] ?></strong></span>
                    <a href="<?= site_url('auth/logout') ?>" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Detail Pesanan #<?= $order['order_number'] ?></h2>
            <div class="flex space-x-4">
                <a href="<?= site_url('pelanggan/orders') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Pesanan
                </a>
                <a href="<?= site_url('pelanggan') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pesanan</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                <?php
                                switch ($order['status']) {
                                    case 'pending':
                                        echo 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'processing':
                                        echo 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'shipped':
                                        echo 'bg-purple-100 text-purple-800';
                                        break;
                                    case 'delivered':
                                        echo 'bg-green-100 text-green-800';
                                        break;
                                    case 'cancelled':
                                        echo 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?php
                                switch ($order['status']) {
                                    case 'pending':
                                        echo 'Menunggu Konfirmasi';
                                        break;
                                    case 'processing':
                                        echo 'Sedang Diproses';
                                        break;
                                    case 'shipped':
                                        echo 'Sedang Dikirim';
                                        break;
                                    case 'delivered':
                                        echo 'Selesai';
                                        break;
                                    case 'cancelled':
                                        echo 'Dibatalkan';
                                        break;
                                    default:
                                        echo ucfirst($order['status']);
                                }
                                ?>
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            Dibuat: <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Pesanan</h3>
                    <div class="space-y-4">
                        <?php foreach ($order['details'] as $detail): ?>
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                <?php if (!empty($detail['foto']) && file_exists('uploads/barang/' . $detail['foto'])): ?>
                                    <img src="<?= base_url('uploads/barang/' . $detail['foto']) ?>" class="h-16 w-16 object-cover rounded" alt="<?= $detail['namabarang'] ?>">
                                <?php else: ?>
                                    <div class="h-16 w-16 bg-pink-50 rounded flex items-center justify-center">
                                        <i class="fas fa-box text-2xl text-pink-300"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900"><?= $detail['namabarang'] ?></h4>
                                    <p class="text-sm text-gray-500"><?= $detail['quantity'] ?> x <?= rupiah($detail['price']) ?> per <?= $detail['satuan'] ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-accent"><?= rupiah($detail['subtotal']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengiriman</h3>
                    <div class="space-y-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Alamat Pengiriman</h4>
                            <p class="text-gray-900"><?= $order['shipping_address'] ?></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Metode Pembayaran</h4>
                            <p class="text-gray-900">
                                <?php
                                switch ($order['payment_method']) {
                                    case 'cash':
                                        echo 'Tunai (Bayar di tempat)';
                                        break;
                                    case 'transfer':
                                        echo 'Transfer Bank';
                                        break;
                                    case 'bank':
                                        echo 'Bank Transfer';
                                        break;
                                    default:
                                        echo ucfirst($order['payment_method']);
                                }
                                ?>
                            </p>
                        </div>
                        <?php if ($order['notes']): ?>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Catatan</h4>
                                <p class="text-gray-900"><?= $order['notes'] ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Pesanan</h3>
                    <?php
                    $steps = [
                        ['label' => 'Pesanan Dibuat', 'time' => $order['created_at'] ?? null, 'icon' => 'fa-receipt'],
                        ['label' => 'Diproses', 'time' => ($order['status'] === 'processing' || $order['status'] === 'shipped' || $order['status'] === 'delivered') ? ($order['updated_at'] ?? null) : null, 'icon' => 'fa-cogs'],
                        ['label' => 'Dikirim', 'time' => $order['shipped_at'] ?? null, 'icon' => 'fa-truck'],
                        ['label' => 'Selesai', 'time' => ($order['status'] === 'delivered') ? ($order['updated_at'] ?? null) : null, 'icon' => 'fa-check-circle'],
                    ];
                    if ($order['status'] === 'cancelled') {
                        $steps[] = ['label' => 'Dibatalkan', 'time' => $order['updated_at'] ?? null, 'icon' => 'fa-times-circle'];
                    }
                    $hasAny = false;
                    ?>
                    <ul class="space-y-3">
                        <?php foreach ($steps as $s): if (!$s['time']) continue;
                            $hasAny = true; ?>
                            <li class="flex items-center justify-between bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas <?= $s['icon'] ?> text-primary mr-3"></i>
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900"><?= $s['label'] ?></p>
                                        <p class="text-gray-500 text-xs"><?= date('d M Y H:i', strtotime($s['time'])) ?></p>
                                    </div>
                                </div>
                                <?php if ($s['label'] === 'Dikirim' && !empty($order['courier'])): ?>
                                    <div class="text-xs text-gray-600">Kurir: <span class="font-medium"><?= $order['courier'] ?></span> • Resi: <span class="font-medium"><?= $order['tracking_number'] ?? '-' ?></span></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        <?php if (!$hasAny): ?>
                            <li class="text-sm text-gray-500">Belum ada aktivitas</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>

                    <div class="space-y-3 mb-6">
                        <?php foreach ($order['details'] as $detail): ?>
                            <div class="flex justify-between text-sm">
                                <span><?= $detail['namabarang'] ?> (<?= $detail['quantity'] ?>)</span>
                                <span><?= rupiah($detail['subtotal']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total Pesanan</span>
                            <span class="text-accent"><?= rupiah($order['total_amount']) ?></span>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Butuh Bantuan?</h4>
                        <p class="text-xs text-gray-600 mb-2">Hubungi kami untuk informasi lebih lanjut:</p>
                        <div class="space-y-1 text-xs text-gray-600">
                            <p><i class="fas fa-phone mr-2"></i>+6282285214024</p>
                            <p><i class="fas fa-envelope mr-2"></i>info@nanacatshop.com</p>
                        </div>
                    </div>

                    <?php if ($order['status'] === 'shipped'): ?>
                        <div class="mt-6">
                            <button id="btnTerimaPesanan" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                                <i class="fas fa-check-circle mr-2"></i>Pesanan Diterima
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-pink-100 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-gray-600">
                <p>&copy; <?= date('Y') ?> Nana Cat Shop. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            $('#btnTerimaPesanan').on('click', function() {
                if (!confirm('Konfirmasi bahwa Anda sudah menerima pesanan?')) return;
                $.ajax({
                    url: '<?= site_url('pelanggan/order-receive/' . $order['id']) ?>',
                    type: 'POST',
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.status === 'success') {
                            alert(resp.message);
                            window.location.href = resp.redirect;
                        } else {
                            alert(resp.message);
                        }
                    },
                    error: function() {
                        alert('Gagal memproses. Coba lagi.');
                    }
                });
            });
        });
    </script>
</body>

</html>


