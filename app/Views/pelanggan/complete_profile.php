<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - Nana Cat Shop</title>
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
    <div class="max-w-2xl w-full" data-aos="fade-up" data-aos-duration="800">
        <!-- Complete Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-pink-100 animate-fadeIn">
            <!-- Header -->
            <div class="bg-primary p-6 text-center">
                <img src="<?= base_url('assets/img/catshoplogo.png') ?>" alt="Nana Cat Shop Logo" class="h-16 mx-auto mb-3 animate-float" onerror="this.style.display='none'">
                <h2 class="text-2xl font-bold text-white">Lengkapi Profil</h2>
                <p class="text-pink-100 mt-1">Lengkapi informasi pelanggan Anda</p>
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
                <div id="profileError" class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md hidden" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700" id="errorMessage"></p>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-red-500 rounded-md focus:outline-none" onclick="document.getElementById('profileError').classList.add('hidden')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Alert untuk success -->
                <div id="profileSuccess" class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md hidden" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700" id="successMessage"></p>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-green-500 rounded-md focus:outline-none" onclick="document.getElementById('profileSuccess').classList.add('hidden')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <form id="completeProfileForm" method="post">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="nama" class="block text-gray-700 mb-2 font-medium">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="nama" name="nama" required
                                    class="w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                    placeholder="Masukkan nama lengkap" value="<?= $user['name'] ?>">
                            </div>
                        </div>

                        <div>
                            <label for="jenkel" class="block text-gray-700 mb-2 font-medium">Jenis Kelamin</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-venus-mars text-gray-400"></i>
                                </div>
                                <select id="jenkel" name="jenkel" required
                                    class="w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="nohp" class="block text-gray-700 mb-2 font-medium">Nomor HP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input type="tel" id="nohp" name="nohp" required
                                class="w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300"
                                placeholder="Masukkan nomor HP">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="alamat" class="block text-gray-700 mb-2 font-medium">Alamat</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <textarea id="alamat" name="alamat" rows="4" required
                                class="w-full pl-10 pr-3 py-3 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 resize-none"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" id="btnCompleteProfile" class="flex-1 bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Profil
                        </button>
                        <a href="<?= site_url('pelanggan') ?>" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Lewati
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-5 rounded-lg flex items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mr-3"></div>
                <p class="text-primary font-medium">Menyimpan...</p>
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
            // Handle form submission
            $('#completeProfileForm').on('submit', function(e) {
                e.preventDefault();

                // Show loading overlay
                $('#loadingOverlay').removeClass('hidden');

                // Get form data
                const formData = {
                    nama: $('#nama').val(),
                    jenkel: $('#jenkel').val(),
                    nohp: $('#nohp').val(),
                    alamat: $('#alamat').val()
                };

                $.ajax({
                    url: '<?= site_url('pelanggan/complete-profile') ?>',
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
                $('#profileError').removeClass('hidden');
                $('#profileSuccess').addClass('hidden');
            }

            function showSuccess(message) {
                $('#successMessage').text(message);
                $('#profileSuccess').removeClass('hidden');
                $('#profileError').addClass('hidden');
            }
        });
    </script>
</body>

</html>