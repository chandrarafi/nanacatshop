<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Nana Cat Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFF5F9;

        }
    </style>
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
</head>

<body class="min-h-screen">
    <header class="bg-white shadow-sm border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img src="<?= base_url('assets/img/catshoplogo.png') ?>" alt="Nana Cat Shop Logo" class="h-10 w-auto mr-3" onerror="this.style.display='none'">
                    <h1 class="text-2xl font-bold text-primary">Nana Cat Shop</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?= site_url('pelanggan') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="<?= site_url('pelanggan/bookings/new') ?>" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-plus mr-2"></i>Booking Baru
                    </a>
                    <span class="text-gray-700">Halo, <strong><?= $user['name'] ?></strong></span>
                    <a href="<?= site_url('auth/logout') ?>" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Booking Saya</h2>
        </div>

        <?php if (empty($bookings)): ?>
            <div class="text-center py-16">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum ada booking</h3>
                <p class="text-gray-400 mb-6">Buat booking perawatan pertama Anda</p>
                <a href="<?= site_url('pelanggan/bookings/new') ?>" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-lg transition duration-300">
                    <i class="fas fa-calendar-plus mr-2"></i>Booking Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($bookings as $b): ?>
                    <div class="bg-white rounded-lg shadow-md p-5 border border-pink-100">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-semibold text-primary truncate" title="<?= esc($b['service_name']) ?>"><?= esc($b['service_name']) ?></h4>
                            <span class="text-xs px-2 py-1 rounded-full <?= $b['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($b['status'] === 'confirmed' ? 'bg-blue-100 text-blue-700' : ($b['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                        </div>
                        <div class="text-sm text-gray-700 space-y-1 mb-3">
                            <div><i class="far fa-calendar mr-2"></i><?= date('d M Y', strtotime($b['booking_date'])) ?>, <?= substr($b['booking_time'], 0, 5) ?> WIB</div>
                            <div><i class="fas fa-money-bill mr-2"></i><?= rupiah($b['price']) ?></div>
                        </div>
                        <div class="text-xs text-gray-500 line-clamp-2 min-h-[2rem] mb-4"><?= esc($b['notes'] ?? '-') ?></div>
                        <div class="flex justify-end gap-2">
                            <a href="<?= site_url('pelanggan/bookings/invoice/' . $b['id']) ?>" class="bg-primary hover:bg-secondary text-white px-3 py-2 rounded text-sm"><i class="fas fa-file-invoice mr-1"></i> Invoice</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>

</html>