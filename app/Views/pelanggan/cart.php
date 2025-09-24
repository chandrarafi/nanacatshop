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
            <h2 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h2>
            <div class="flex space-x-4">
                <a href="<?= site_url('pelanggan/shop') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Lanjut Belanja
                </a>
                <a href="<?= site_url('pelanggan') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
            </div>
        </div>

        <?php if (empty($cart)): ?>
            <!-- Empty Cart -->
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-400 mb-6">Belum ada barang di keranjang Anda</p>
                <a href="<?= site_url('pelanggan/shop') ?>" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg transition duration-300">
                    <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <!-- Cart Items -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Item di Keranjang</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($cart as $item): ?>
                                <div class="cart-item p-6">
                                    <div class="flex items-center space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            <?php if (!empty($item['foto']) && file_exists('uploads/barang/' . $item['foto'])): ?>
                                                <img src="<?= base_url('uploads/barang/' . $item['foto']) ?>" class="h-20 w-20 object-cover rounded-lg" alt="<?= $item['namabarang'] ?>">
                                            <?php else: ?>
                                                <div class="h-20 w-20 bg-pink-50 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-box text-2xl text-pink-300"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-semibold text-gray-900"><?= $item['namabarang'] ?></h4>
                                            <p class="text-sm text-gray-500"><?= rupiah($item['price']) ?> per <?= $item['satuan'] ?></p>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-2">
                                            <button onclick="updateQuantity('<?= $item['kdbarang'] ?>', <?= $item['quantity'] - 1 ?>)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span id="quantity-<?= $item['kdbarang'] ?>" class="px-3 py-1 border border-gray-300 rounded min-w-[3rem] text-center"><?= $item['quantity'] ?></span>
                                            <button onclick="updateQuantity('<?= $item['kdbarang'] ?>', <?= $item['quantity'] + 1 ?>)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>

                                        <!-- Price -->
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-accent"><?= rupiah($item['price'] * $item['quantity']) ?></p>
                                        </div>

                                        <!-- Remove Button -->
                                        <button onclick="removeFromCart('<?= $item['kdbarang'] ?>')" class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>

                        <div class="space-y-3 mb-6">
                            <?php
                            $subtotal = 0;
                            foreach ($cart as $item):
                                $subtotal += $item['price'] * $item['quantity'];
                            ?>
                                <div class="flex justify-between text-sm">
                                    <span><?= $item['namabarang'] ?> (<?= $item['quantity'] ?>)</span>
                                    <span><?= rupiah($item['price'] * $item['quantity']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span class="text-accent"><?= rupiah($subtotal) ?></span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <a href="<?= site_url('pelanggan/checkout') ?>" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fas fa-credit-card mr-2"></i>Checkout
                            </a>
                            <a href="<?= site_url('pelanggan/shop') ?>" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fas fa-shopping-bag mr-2"></i>Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
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

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-5 rounded-lg flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mr-3"></div>
            <p class="text-primary font-medium">Memproses...</p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateQuantity(kdbarang, newQuantity) {
            if (newQuantity <= 0) {
                removeFromCart(kdbarang);
                return;
            }

            $('#loadingOverlay').removeClass('hidden');

            $.ajax({
                url: '<?= site_url('pelanggan/update-cart') ?>',
                type: 'POST',
                data: {
                    kdbarang: kdbarang,
                    quantity: newQuantity
                },
                dataType: 'json',
                success: function(response) {
                    $('#loadingOverlay').addClass('hidden');

                    if (response.status === 'success') {
                        $('#quantity-' + kdbarang).text(newQuantity);
                        // Reload page to update totals
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    $('#loadingOverlay').addClass('hidden');
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }

        function removeFromCart(kdbarang) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                return;
            }

            $('#loadingOverlay').removeClass('hidden');

            $.ajax({
                url: '<?= site_url('pelanggan/remove-from-cart') ?>',
                type: 'POST',
                data: {
                    kdbarang: kdbarang
                },
                dataType: 'json',
                success: function(response) {
                    $('#loadingOverlay').addClass('hidden');

                    if (response.status === 'success') {
                        // Reload page to update cart
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    $('#loadingOverlay').addClass('hidden');
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }
    </script>
</body>

</html>


