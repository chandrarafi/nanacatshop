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
            <h2 class="text-3xl font-bold text-gray-900">Checkout</h2>
            <a href="<?= site_url('pelanggan/cart') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Keranjang
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Checkout Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Pengiriman</h3>

                <form id="checkoutForm" enctype="multipart/form-data">
                    <!-- Customer Info -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-700 mb-4">Data Pelanggan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" value="<?= $pelanggan['nama'] ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                                <input type="text" value="<?= $pelanggan['nohp'] ?: '-' ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="mb-6">
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman *</label>
                        <textarea id="shipping_address" name="shipping_address" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                            placeholder="Masukkan alamat lengkap pengiriman"><?= $pelanggan['alamat'] ?: '' ?></textarea>
                    </div>

                    <!-- Payment Method (Transfer only) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <div class="p-4 rounded-lg border border-gray-200 bg-gray-50 space-y-3">
                            <p class="text-gray-700 font-medium">Transfer Bank</p>
                            <div class="space-y-2">
                                <label class="flex items-start gap-3">
                                    <input type="radio" name="payment_bank" value="BCA" class="mt-1" checked>
                                    <div class="text-sm text-gray-700">
                                        <div class="font-semibold">BCA</div>
                                        <div>No. Rekening: 1234567890</div>
                                        <div>Atas Nama: Nana Cat Shop</div>
                                    </div>
                                </label>
                                <label class="flex items-start gap-3">
                                    <input type="radio" name="payment_bank" value="BRI" class="mt-1">
                                    <div class="text-sm text-gray-700">
                                        <div class="font-semibold">BRI</div>
                                        <div>No. Rekening: 5555555555</div>
                                        <div>Atas Nama: Nana Cat Shop</div>
                                    </div>
                                </label>
                            </div>
                            <input type="hidden" name="payment_method" value="transfer">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                            placeholder="Catatan tambahan untuk pesanan"></textarea>
                    </div>

                    <!-- Payment Proof Upload -->
                    <div class="mb-6">
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran (JPG/PNG/PDF)</label>
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/jpeg,image/png,application/pdf"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <p class="text-xs text-gray-500 mt-2">Unggah bukti transfer untuk mempercepat verifikasi pesanan.</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-credit-card mr-2"></i>Proses Pesanan
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan Pesanan</h3>

                <div class="space-y-4 mb-6">
                    <?php
                    $subtotal = 0;
                    foreach ($cart as $item):
                        $subtotal += $item['price'] * $item['quantity'];
                    ?>
                        <div class="flex items-center space-x-3">
                            <?php if (!empty($item['foto']) && file_exists('uploads/barang/' . $item['foto'])): ?>
                                <img src="<?= base_url('uploads/barang/' . $item['foto']) ?>" class="h-12 w-12 object-cover rounded" alt="<?= $item['namabarang'] ?>">
                            <?php else: ?>
                                <div class="h-12 w-12 bg-pink-50 rounded flex items-center justify-center">
                                    <i class="fas fa-box text-pink-300"></i>
                                </div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900"><?= $item['namabarang'] ?></h4>
                                <p class="text-xs text-gray-500"><?= $item['quantity'] ?> x <?= rupiah($item['price']) ?></p>
                            </div>
                            <span class="text-sm font-semibold text-accent"><?= rupiah($item['price'] * $item['quantity']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total Pesanan</span>
                        <span class="text-accent"><?= rupiah($subtotal) ?></span>
                    </div>
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

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-5 rounded-lg flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mr-3"></div>
            <p class="text-primary font-medium">Memproses pesanan...</p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#checkoutForm').on('submit', function(e) {
                e.preventDefault();

                // Validate form
                const shippingAddress = $('#shipping_address').val().trim();
                const paymentMethod = 'transfer';
                const paymentBank = $('input[name="payment_bank"]:checked').val();

                if (!shippingAddress) {
                    alert('Alamat pengiriman harus diisi');
                    return;
                }

                if (!paymentMethod) {
                    alert('Metode pembayaran harus dipilih');
                    return;
                }

                // Show loading
                $('#loadingOverlay').removeClass('hidden');

                // Prepare form data with file
                const formData = new FormData();
                formData.append('shipping_address', shippingAddress);
                formData.append('payment_method', paymentMethod);
                formData.append('payment_bank', paymentBank);
                formData.append('notes', $('#notes').val().trim());
                const fileInput = document.getElementById('payment_proof');
                if (fileInput && fileInput.files && fileInput.files[0]) {
                    formData.append('payment_proof', fileInput.files[0]);
                }

                $.ajax({
                    url: '<?= site_url('pelanggan/process-order') ?>',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#loadingOverlay').addClass('hidden');

                        if (response.status === 'success') {
                            alert('Pesanan berhasil dibuat!\nNomor Pesanan: ' + response.order_number);
                            window.location.href = response.redirect;
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        $('#loadingOverlay').addClass('hidden');
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
</body>

</html>