<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Nana Cat Shop' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#F564A9', // Pink (sekarang sebagai warna utama)
                        secondary: '#FAA4BD', // Pink muda
                        accent: '#533B4D', // Ungu tua (sekarang sebagai aksen)
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
            /* Light pink background */
        }

        /* Style untuk hero section tidak lagi diperlukan karena menggunakan inline style */

        .section-title::after {
            content: '';
            position: absolute;
            display: block;
            width: 50px;
            height: 3px;
            background-color: #F564A9;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .btn-pink {
            background-color: #F564A9;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-pink:hover {
            background-color: #FAA4BD;
        }

        /* Animasi Kustom */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fadeIn {
            animation: fadeIn 1s ease-in-out;
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-light font-poppins">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-2">
            <div class="flex justify-between items-center">
                <a href="#" class="flex items-center">
                    <img src="<?= base_url('assets/img/catshoplogo.png') ?>" alt="Nana Cat Shop Logo" class="h-12 mr-2">
                    <span class="text-primary font-bold text-2xl">Nana Cat Shop</span>
                </a>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-primary focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Desktop menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-accent hover:text-primary font-medium">Beranda</a>
                    <a href="#products" class="text-accent hover:text-primary font-medium">Produk</a>
                    <a href="#services" class="text-accent hover:text-primary font-medium">Layanan</a>
                    <a href="#contact" class="text-accent hover:text-primary font-medium">Kontak</a>

                    <!-- Auth buttons -->
                    <?php if (session()->get('logged_in')): ?>
                        <?php if (session()->get('role') === 'pelanggan'): ?>
                            <a href="<?= site_url('pelanggan') ?>" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg font-medium transition duration-300">
                                <i class="fas fa-user mr-2"></i>Dashboard
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url('admin') ?>" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg font-medium transition duration-300">
                                <i class="fas fa-cog mr-2"></i>Admin
                            </a>
                        <?php endif; ?>
                        <a href="<?= site_url('auth/logout') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('auth') ?>" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg font-medium transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="<?= site_url('auth/register') ?>" class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded-lg font-medium transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden hidden pt-4 pb-2">
                <a href="#home" class="block py-2 text-accent hover:text-primary font-medium">Beranda</a>
                <a href="#products" class="block py-2 text-accent hover:text-primary font-medium">Produk</a>
                <a href="#services" class="block py-2 text-accent hover:text-primary font-medium">Layanan</a>
                <a href="#contact" class="block py-2 text-accent hover:text-primary font-medium">Kontak</a>

                <!-- Mobile Auth buttons -->
                <div class="border-t border-gray-200 mt-4 pt-4">
                    <?php if (session()->get('logged_in')): ?>
                        <?php if (session()->get('role') === 'pelanggan'): ?>
                            <a href="<?= site_url('pelanggan') ?>" class="block bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg font-medium transition duration-300 mb-2 text-center">
                                <i class="fas fa-user mr-2"></i>Dashboard
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url('admin') ?>" class="block bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg font-medium transition duration-300 mb-2 text-center">
                                <i class="fas fa-cog mr-2"></i>Admin
                            </a>
                        <?php endif; ?>
                        <a href="<?= site_url('auth/logout') ?>" class="block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-300 text-center">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('auth') ?>" class="block bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg font-medium transition duration-300 mb-2 text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="<?= site_url('auth/register') ?>" class="block bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded-lg font-medium transition duration-300 text-center">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-[#db5c95] py-20 md:py-32" id="home" style="background-image: url('https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1500&q=80'); background-size: cover; background-position: center; ">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <img src="<?= base_url('assets/img/catshoplogo.png') ?>" alt="Nana Cat Shop Logo" class="h-16 mx-auto mb-5 animate-float">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 text-white animate-fadeIn">Nana Cat Shop</h1>
                <p class="text-lg md:text-xl mb-10 text-white leading-relaxed animate-fadeIn" style="animation-delay: 0.3s">Toko dan layanan perawatan kucing terlengkap untuk<br>kebutuhan kucing kesayangan Anda</p>

                <?php if (session()->get('logged_in') && session()->get('role') === 'pelanggan'): ?>
                    <div class="mb-6 animate-fadeIn" style="animation-delay: 0.4s">
                        <p class="text-white text-lg mb-4">Selamat datang kembali, <strong><?= session()->get('name') ?></strong>!</p>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row justify-center gap-6 animate-fadeIn" style="animation-delay: 0.6s">
                    <a href="#products" class="bg-white hover:bg-light text-primary font-bold py-3 px-10 rounded-full transition duration-300 shadow-lg hover-scale">Lihat Produk</a>
                    <a href="#services" class="bg-accent/90 hover:bg-accent text-white font-bold py-3 px-10 rounded-full transition duration-300 shadow-lg hover-scale">Layanan Kami</a>

                    <?php if (!session()->get('logged_in')): ?>
                        <a href="<?= site_url('auth/register') ?>" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-10 rounded-full transition duration-300 shadow-lg hover-scale">
                            <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Benefits Section -->
    <?php if (!session()->get('logged_in')): ?>
        <section class="py-16 bg-gradient-to-r from-primary/10 to-secondary/10">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-primary mb-12 relative section-title" data-aos="fade-up">Mengapa Bergabung dengan Kami?</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-slow">
                            <i class="fas fa-shopping-cart text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-primary mb-4">Belanja Mudah</h3>
                        <p class="text-gray-600">Akses produk berkualitas dengan harga terbaik untuk kucing kesayangan Anda</p>
                    </div>

                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-20 h-20 bg-secondary rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-slow">
                            <i class="fas fa-calendar-check text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-primary mb-4">Booking Layanan</h3>
                        <p class="text-gray-600">Reservasi mudah untuk grooming, penitipan, dan perawatan kucing Anda</p>
                    </div>

                    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-slow">
                            <i class="fas fa-history text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-primary mb-4">Riwayat Lengkap</h3>
                        <p class="text-gray-600">Pantau semua transaksi dan layanan yang pernah Anda gunakan</p>
                    </div>
                </div>

                <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="400">
                    <a href="<?= site_url('auth/register') ?>" class="bg-primary hover:bg-secondary text-white font-bold py-4 px-12 rounded-full transition duration-300 shadow-lg hover-scale inline-flex items-center">
                        <i class="fas fa-user-plus mr-3"></i>
                        Daftar Sekarang - Gratis!
                    </a>
                    <p class="text-gray-600 mt-4">Sudah punya akun? <a href="<?= site_url('auth') ?>" class="text-primary hover:text-secondary font-medium">Login di sini</a></p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Products Section -->
    <section class="py-16 bg-white" id="products">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-primary mb-12 relative section-title" data-aos="fade-up">Produk Kami</h2>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-2 mb-8" data-aos="fade-up" data-aos-delay="100">
                <button class="px-4 py-2 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white transition duration-300 filter-btn active hover-scale" data-filter="all">Semua</button>
                <?php
                $delay = 150;
                foreach ($kategori_filter as $kat):
                    $delay += 50;
                ?>
                    <button class="px-4 py-2 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white transition duration-300 filter-btn hover-scale" data-filter="<?= $kat['kdkategori'] ?>" data-aos="fade-up" data-aos-delay="<?= $delay ?>"><?= $kat['namakategori'] ?></button>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php
                $delay = 100;
                foreach ($barang as $item):
                    $delay += 50;
                ?>
                    <div class="product-item" data-category="<?= $item['kdkategori'] ?>" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                        <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:-translate-y-2 transition duration-300 border-2 border-pink-100">
                            <?php if (!empty($item['foto']) && file_exists('uploads/barang/' . $item['foto'])): ?>
                                <img src="<?= base_url('uploads/barang/' . $item['foto']) ?>" class="w-full h-48 object-cover" alt="<?= $item['namabarang'] ?>">
                            <?php else: ?>
                                <div class="w-full h-48 bg-pink-50 flex items-center justify-center">
                                    <i class="fas fa-box text-4xl text-pink-300 animate-pulse-slow"></i>
                                </div>
                            <?php endif; ?>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-primary mb-2"><?= $item['namabarang'] ?></h3>
                                <p class="text-gray-600 text-sm mb-3 truncate"><?= $item['namabarang'] ?></p>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-accent">Rp <?= number_format($item['hargajual'], 0, ',', '.') ?></span>
                                    <span class="px-3 py-1 text-xs rounded-full <?= $item['jumlah'] > 0 ? 'bg-pink-100 text-primary' : 'bg-red-100 text-red-600' ?>">
                                        <?= $item['jumlah'] > 0 ? 'Stok: ' . $item['jumlah'] : 'Habis' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 bg-pink-50" id="services">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-primary mb-12 relative section-title" data-aos="fade-up">Layanan Kami</h2>

            <!-- Grooming & Healthcare Services -->
            <h3 class="text-2xl font-semibold text-center text-accent mb-8" data-aos="fade-up" data-aos-delay="100">Layanan Perawatan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                <?php
                $delay = 150;
                foreach ($layanan_perawatan as $layanan):
                    $delay += 100;
                ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:-translate-y-2 transition duration-300 border-2 border-pink-100" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                        <div class="p-8 text-center">
                            <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-slow">
                                <i class="fas fa-paw text-2xl text-white"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-primary mb-3"><?= $layanan['namafasilitas'] ?></h4>
                            <p class="text-gray-600 mb-5"><?= $layanan['keterangan'] ?? 'Layanan perawatan kucing profesional' ?></p>
                            <p class="text-xl font-bold text-accent">Rp <?= number_format($layanan['harga'], 0, ',', '.') ?> / <?= $layanan['satuan'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Boarding Services -->
            <h3 class="text-2xl font-semibold text-center text-accent mb-8" data-aos="fade-up">Layanan Penitipan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $delay = 150;
                foreach ($layanan_penitipan as $penitipan):
                    $delay += 100;
                ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:-translate-y-2 transition duration-300 border-2 border-pink-100" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                        <div class="p-8 text-center">
                            <div class="w-20 h-20 bg-secondary rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-slow">
                                <i class="fas fa-home text-2xl text-white"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-primary mb-3"><?= $penitipan['namafasilitas'] ?></h4>
                            <p class="text-gray-600 mb-5"><?= $penitipan['keterangan'] ?? 'Layanan penitipan kucing nyaman dan aman' ?></p>
                            <p class="text-xl font-bold text-accent">Rp <?= number_format($penitipan['harga'], 0, ',', '.') ?> / <?= $penitipan['satuan'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-white" id="contact">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-primary mb-12 relative section-title" data-aos="fade-up">Hubungi Kami</h2>
            <div class="flex justify-center">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl w-full">
                    <div class="bg-pink-50 rounded-xl shadow-lg p-8 border-2 border-pink-100 md:col-span-2 mx-auto max-w-2xl" data-aos="fade-right" data-aos-duration="800">
                        <h4 class="text-xl font-semibold text-primary mb-6 text-center">Informasi Kontak</h4>
                        <div class="space-y-5">
                            <div class="flex items-start" data-aos="fade-up" data-aos-delay="100">
                                <div class="bg-primary p-3 rounded-full mr-4">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <span class="text-gray-700 mt-1">Jl. Bandar Olo no.42, Padang Barat, Kota Padang</span>
                            </div>
                            <div class="flex items-start" data-aos="fade-up" data-aos-delay="200">
                                <div class="bg-primary p-3 rounded-full mr-4">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <span class="text-gray-700 mt-1">+6282285214024</span>
                            </div>
                            <div class="flex items-start" data-aos="fade-up" data-aos-delay="300">
                                <div class="bg-primary p-3 rounded-full mr-4">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <span class="text-gray-700 mt-1">info@nanacatshop.com</span>
                            </div>
                            <div class="flex items-start" data-aos="fade-up" data-aos-delay="400">
                                <div class="bg-primary p-3 rounded-full mr-4">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <span class="text-gray-700 mt-1">Senin - Minggu: 08.00 - 20.00 WIB</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary py-12 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="flex items-center mb-6 md:mb-0" data-aos="fade-right">
                    <img src="<?= base_url('assets/img/catshoplogo.png') ?>" alt="Nana Cat Shop Logo" class="h-16 mr-3 animate-float">
                    <div>
                        <h5 class="text-xl font-semibold text-white">Nana Cat Shop</h5>
                        <p class="text-pink-200 text-sm">Kebutuhan Kucing Kesayangan Anda</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:flex md:space-x-12 gap-8 md:gap-0">
                    <div data-aos="fade-up" data-aos-delay="100">
                        <h5 class="text-lg font-semibold mb-4 text-white">Link Cepat</h5>
                        <ul class="space-y-2">
                            <li><a href="#home" class="text-pink-200 hover:text-white transition duration-300">Beranda</a></li>
                            <li><a href="#products" class="text-pink-200 hover:text-white transition duration-300">Produk</a></li>
                            <li><a href="#services" class="text-pink-200 hover:text-white transition duration-300">Layanan</a></li>
                            <li><a href="#contact" class="text-pink-200 hover:text-white transition duration-300">Kontak</a></li>
                        </ul>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="200">
                        <h5 class="text-lg font-semibold mb-4 text-white">Akun</h5>
                        <ul class="space-y-2">
                            <?php if (session()->get('logged_in')): ?>
                                <?php if (session()->get('role') === 'pelanggan'): ?>
                                    <li><a href="<?= site_url('pelanggan') ?>" class="text-pink-200 hover:text-white transition duration-300">Dashboard</a></li>
                                <?php else: ?>
                                    <li><a href="<?= site_url('admin') ?>" class="text-pink-200 hover:text-white transition duration-300">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><a href="<?= site_url('auth/logout') ?>" class="text-pink-200 hover:text-white transition duration-300">Logout</a></li>
                            <?php else: ?>
                                <li><a href="<?= site_url('auth') ?>" class="text-pink-200 hover:text-white transition duration-300">Login</a></li>
                                <li><a href="<?= site_url('auth/register') ?>" class="text-pink-200 hover:text-white transition duration-300">Daftar</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-pink-400 mt-8 pt-6 text-center" data-aos="fade-up" data-aos-delay="300">
                <p class="text-pink-200">&copy; <?= date('Y') ?> Nana Cat Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JS -->
    <script>
        // Inisialisasi AOS (Animate On Scroll)
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: false,
            mirror: false
        });

        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Category filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const productItems = document.querySelectorAll('.product-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.classList.remove('bg-secondary');
                        btn.classList.remove('text-white');
                    });

                    // Add active class to clicked button
                    this.classList.add('active');
                    this.classList.add('bg-secondary');
                    this.classList.add('text-white');

                    const filterValue = this.getAttribute('data-filter');

                    // Show/hide products based on category
                    productItems.forEach(item => {
                        if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                            item.classList.remove('hidden');
                            // Refresh AOS untuk item yang ditampilkan
                            AOS.refresh();
                        } else {
                            item.classList.add('hidden');
                        }
                    });
                });
            });

            // Smooth scroll for navigation links
            const navLinks = document.querySelectorAll('nav a');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetSection = document.querySelector(targetId);

                    window.scrollTo({
                        top: targetSection.offsetTop - 80,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>

</html>