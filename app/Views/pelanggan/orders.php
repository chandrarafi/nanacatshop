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
            <h2 class="text-3xl font-bold text-gray-900">Riwayat Pesanan</h2>
            <div class="flex space-x-4">
                <a href="<?= site_url('pelanggan/shop') ?>" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-shopping-bag mr-2"></i>Belanja Lagi
                </a>
                <a href="<?= site_url('pelanggan') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
            </div>
        </div>

        <?php if (empty($orders)): ?>
            <!-- No Orders -->
            <div class="text-center py-12">
                <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-400 mb-6">Anda belum pernah melakukan pembelian</p>
                <a href="<?= site_url('pelanggan/shop') ?>" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg transition duration-300">
                    <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <div class="space-y-6">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Pesanan #<?= $order['order_number'] ?></h3>
                                    <p class="text-sm text-gray-500"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-accent"><?= rupiah($order['total_amount']) ?></p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
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
                                                echo 'Menunggu';
                                                break;
                                            case 'processing':
                                                echo 'Diproses';
                                                break;
                                            case 'shipped':
                                                echo 'Dikirim';
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
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Metode Pembayaran</h4>
                                    <p class="text-sm text-gray-900">
                                        <?php
                                        switch ($order['payment_method']) {
                                            case 'cash':
                                                echo 'Tunai';
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
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Alamat Pengiriman</h4>
                                    <p class="text-sm text-gray-900"><?= $order['shipping_address'] ?></p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Catatan</h4>
                                    <p class="text-sm text-gray-900"><?= $order['notes'] ?: '-' ?></p>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <a href="<?= site_url('pelanggan/order-detail/' . $order['id']) ?>" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-300">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-pink-100 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-gray-600">
                <p>&copy; <?= date('Y') ?> Nana Cat Shop. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</body>

</html>


