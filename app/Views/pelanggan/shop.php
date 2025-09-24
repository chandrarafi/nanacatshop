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
                    <a href="<?= site_url('pelanggan/cart') ?>" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-300 relative">
                        <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </a>
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
            <h2 class="text-3xl font-bold text-gray-900">Belanja Online</h2>
            <a href="<?= site_url('pelanggan') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
            </a>
        </div>

        <!-- Category Filter -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Kategori</h3>
            <div class="flex flex-wrap gap-2">
                <button class="px-4 py-2 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white transition duration-300 filter-btn active" data-filter="all">Semua</button>
                <?php foreach ($kategori as $kat): ?>
                    <button class="px-4 py-2 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white transition duration-300 filter-btn" data-filter="<?= $kat['kdkategori'] ?>"><?= $kat['namakategori'] ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($barang as $item): ?>
                <div class="product-item" data-category="<?= $item['kdkategori'] ?>">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:-translate-y-2 transition duration-300 border-2 border-pink-100">
                        <?php if (!empty($item['foto']) && file_exists('uploads/barang/' . $item['foto'])): ?>
                            <img src="<?= base_url('uploads/barang/' . $item['foto']) ?>" class="w-full h-48 object-cover" alt="<?= $item['namabarang'] ?>">
                        <?php else: ?>
                            <div class="w-full h-48 bg-pink-50 flex items-center justify-center">
                                <i class="fas fa-box text-4xl text-pink-300"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-primary mb-2"><?= $item['namabarang'] ?></h3>
                            <p class="text-gray-600 text-sm mb-3">Stok: <?= $item['jumlah'] ?> <?= $item['satuan'] ?></p>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl font-bold text-accent">Rp <?= number_format($item['hargajual'], 0, ',', '.') ?></span>
                                <span class="px-3 py-1 text-xs rounded-full <?= $item['jumlah'] > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                                    <?= $item['jumlah'] > 0 ? 'Tersedia' : 'Habis' ?>
                                </span>
                            </div>

                            <?php if ($item['jumlah'] > 0): ?>
                                <div class="flex items-center space-x-2">
                                    <input type="number" id="quantity-<?= $item['kdbarang'] ?>" min="1" max="<?= $item['jumlah'] ?>" value="1" class="w-16 px-2 py-1 border border-pink-200 rounded text-center">
                                    <button onclick="addToCart('<?= $item['kdbarang'] ?>')" class="flex-1 bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-300">
                                        <i class="fas fa-cart-plus mr-2"></i>Tambah
                                    </button>
                                </div>
                            <?php else: ?>
                                <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-times mr-2"></i>Stok Habis
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($barang)): ?>
            <div class="text-center py-12">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Tidak ada produk tersedia</h3>
                <p class="text-gray-400">Semua produk sedang habis stok</p>
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
        $(document).ready(function() {
            // Update cart count on page load
            updateCartCount();

            // Category filter functionality
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active bg-primary text-white').addClass('text-primary');
                $(this).addClass('active bg-primary text-white').removeClass('text-primary');

                const filterValue = $(this).data('filter');

                $('.product-item').each(function() {
                    if (filterValue === 'all' || $(this).data('category') === filterValue) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });

        function addToCart(kdbarang) {
            const quantity = parseInt($('#quantity-' + kdbarang).val());

            if (quantity <= 0) {
                alert('Jumlah harus lebih dari 0');
                return;
            }

            $('#loadingOverlay').removeClass('hidden');

            $.ajax({
                url: '<?= site_url('pelanggan/add-to-cart') ?>',
                type: 'POST',
                data: {
                    kdbarang: kdbarang,
                    quantity: quantity
                },
                dataType: 'json',
                success: function(response) {
                    $('#loadingOverlay').addClass('hidden');

                    if (response.status === 'success') {
                        alert(response.message);
                        updateCartCount();
                        $('#quantity-' + kdbarang).val(1); // Reset quantity
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

        function updateCartCount() {
            $.ajax({
                url: '<?= site_url('pelanggan/cart') ?>',
                type: 'GET',
                success: function(data) {
                    // Extract cart count from the page (simple approach)
                    const cartCount = $(data).find('.cart-item').length;
                    $('#cart-count').text(cartCount);
                }
            });
        }
    </script>
</body>

</html>


