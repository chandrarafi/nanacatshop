<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Nana Cat Shop</title>
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
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary/90 to-accent py-12 px-4">
    <div class="max-w-md w-full" data-aos="fade-up" data-aos-duration="800">
        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-pink-100 animate-fadeIn">
            <!-- Header -->
            <div class="bg-primary p-6 text-center">
                <img src="<?= base_url('assets/img/catshoplogo.png') ?>" alt="Nana Cat Shop Logo" class="h-16 mx-auto mb-3 animate-float" onerror="this.style.display='none'">
                <h2 class="text-2xl font-bold text-white">Daftar Akun</h2>
                <p class="text-pink-100 mt-1">Bergabung dengan Nana Cat Shop</p>
            </div>

            <!-- Body -->
            <div class="p-8">
                <!-- Alert untuk pesan error/success -->
                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700"><?= session()->getFlashdata('message') ?></p>
                            </div>
                            <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-blue-500 rounded-md focus:outline-none" data-dismiss-target="alert">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Alert untuk error -->
                <div id="registerError" class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md hidden" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700" id="errorMessage"></p>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-red-500 rounded-md focus:outline-none" onclick="document.getElementById('registerError').classList.add('hidden')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Alert untuk success -->
                <div id="registerSuccess" class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md hidden" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700" id="successMessage"></p>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-green-500 rounded-md focus:outline-none" onclick="document.getElementById('registerSuccess').classList.add('hidden')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <form id="registerForm" method="post">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-2 font-medium">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="name" name="name" required
                                class="w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 mb-2 font-medium">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-at text-gray-400"></i>
                            </div>
                            <input type="text" id="username" name="username" required
                                class="w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Masukkan username">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2 font-medium">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Masukkan email">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-2 font-medium">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-10 pr-12 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Masukkan password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="confirmPassword" class="block text-gray-700 mb-2 font-medium">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="confirmPassword" name="confirmPassword" required
                                class="w-full pl-10 pr-12 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Konfirmasi password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="toggleConfirmPassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btnRegister" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar
                    </button>
                </form>

                <!-- Link ke Login -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Sudah punya akun?
                        <a href="<?= site_url('auth') ?>" class="text-primary hover:text-secondary font-medium transition duration-300">
                            Login di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-5 rounded-lg flex items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mr-3"></div>
                <p class="text-primary font-medium">Memproses...</p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AOS JS -->
    <script>
        // Inisialisasi AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        });

        $(document).ready(function() {
            // Toggle Password Visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Toggle Confirm Password Visibility
            $('#toggleConfirmPassword').on('click', function() {
                const passwordInput = $('#confirmPassword');
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Handle form submission
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                // Validasi password match
                const password = $('#password').val();
                const confirmPassword = $('#confirmPassword').val();

                if (password !== confirmPassword) {
                    showError('Password dan konfirmasi password tidak sama');
                    return;
                }

                // Show loading overlay
                $('#loadingOverlay').removeClass('hidden');

                // Get form data
                const formData = {
                    name: $('#name').val(),
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: password
                };

                $.ajax({
                    url: '<?= site_url('auth/register') ?>',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $('#loadingOverlay').addClass('hidden');

                        if (response.status === 'success') {
                            showSuccess(response.message);
                            // Redirect setelah 2 detik
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loadingOverlay').addClass('hidden');
                        showError('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            function showError(message) {
                $('#errorMessage').text(message);
                $('#registerError').removeClass('hidden');
                $('#registerSuccess').addClass('hidden');
            }

            function showSuccess(message) {
                $('#successMessage').text(message);
                $('#registerSuccess').removeClass('hidden');
                $('#registerError').addClass('hidden');
            }
        });
    </script>
</body>

</html>