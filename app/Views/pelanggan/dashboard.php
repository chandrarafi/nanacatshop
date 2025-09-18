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
                        primary: '#F564A9', // Pink (warna utama)
                        secondary: '#FAA4BD', // Pink muda
                        accent: '#533B4D', // Ungu tua (aksen)
                        light: '#FAE3C6', // Cream
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
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Pelanggan</h2>
            <p class="text-gray-600">Selamat datang di dashboard Nana Cat Shop</p>
        </div>

        <!-- Profile Completion Alert -->
        <?php if (!$pelanggan): ?>
            <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Profil belum lengkap!</strong> Silakan lengkapi informasi pelanggan Anda untuk mengakses semua fitur.
                        </p>
                    </div>
                    <div class="ml-auto">
                        <a href="<?= site_url('pelanggan/complete-profile') ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300">
                            Lengkapi Profil
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border border-pink-100">
                <div class="flex items-center">
                    <div class="p-3 bg-primary/10 rounded-full">
                        <i class="fas fa-user text-primary text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Profil Saya</h3>
                        <p class="text-gray-600">Kelola informasi pribadi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border border-pink-100">
                <div class="flex items-center">
                    <div class="p-3 bg-secondary/10 rounded-full">
                        <i class="fas fa-shopping-cart text-secondary text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan</h3>
                        <p class="text-gray-600">Lihat riwayat pesanan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border border-pink-100">
                <div class="flex items-center">
                    <div class="p-3 bg-accent/10 rounded-full">
                        <i class="fas fa-paw text-accent text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Hewan Peliharaan</h3>
                        <p class="text-gray-600">Kelola data hewan peliharaan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-pink-100">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Akun</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <p class="mt-1 text-sm text-gray-900"><?= $user['name'] ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <p class="mt-1 text-sm text-gray-900"><?= $user['username'] ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900"><?= $user['email'] ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <p class="mt-1 text-sm text-gray-900">Pelanggan</p>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <?php if ($pelanggan): ?>
            <div class="bg-white rounded-lg shadow-md p-6 border border-pink-100 mt-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Pelanggan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Pelanggan</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $pelanggan['idpelanggan'] ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $pelanggan['jenkel'] ? ($pelanggan['jenkel'] == 'L' ? 'Laki-laki' : 'Perempuan') : '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $pelanggan['nohp'] ?: '-' ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $pelanggan['alamat'] ?: '-' ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6 border border-pink-100">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-4">
                <a href="<?= site_url('/') ?>" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg transition duration-300 flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
                <?php if ($pelanggan): ?>
                    <button class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-300 flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Profil
                    </button>
                <?php else: ?>
                    <a href="<?= site_url('pelanggan/complete-profile') ?>" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-300 flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Lengkapi Profil
                    </a>
                <?php endif; ?>
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
</body>

</html>