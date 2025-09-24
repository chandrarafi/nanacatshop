<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Nana Cat Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFF5F9;
        }
    </style>
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
            <h2 class="text-3xl font-bold text-gray-900">Booking Perawatan</h2>
            <a href="<?= site_url('pelanggan/bookings') ?>" class="bg-secondary hover:bg-secondary/80 text-white px-4 py-2 rounded-lg transition duration-300">
                <i class="fas fa-list mr-2"></i>Booking Saya
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Pilih Layanan</h3>
                <form id="bookingForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Layanan (boleh lebih dari satu) *</label>
                        <div id="servicesList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-auto p-2 border border-gray-200 rounded-lg">
                            <?php foreach ($services as $svc): ?>
                                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded hover:border-primary cursor-pointer">
                                    <input type="checkbox" class="svc-checkbox mt-1" value="<?= $svc['kdfasilitas'] ?>" data-name="<?= $svc['namafasilitas'] ?>" data-price="<?= $svc['harga'] ?>">
                                    <div>
                                        <div class="font-medium text-gray-900"><?= $svc['namafasilitas'] ?></div>
                                        <div class="text-sm text-gray-600"><?= rupiah($svc['harga']) ?> / <?= $svc['satuan'] ?></div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                        <input type="date" id="booking_date" name="booking_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jam *</label>
                        <div id="timeSlots" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2"></div>
                        <input type="hidden" id="booking_time" name="booking_time" required>
                        <p class="text-xs text-gray-500 mt-2">Pilih salah satu jam yang tersedia.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pembayaran *</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="payment_type" value="dp" class="mr-3" checked>
                                <span class="text-gray-700">DP (Uang Muka)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_type" value="lunas" class="mr-3">
                                <span class="text-gray-700">Lunas</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bank Transfer *</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="payment_bank" value="BCA" class="mr-3" checked>
                                <span class="text-gray-700">BCA • 1234567890 a/n Nana Cat Shop</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_bank" value="BRI" class="mr-3">
                                <span class="text-gray-700">BRI • 5555555555 a/n Nana Cat Shop</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran (JPG/PNG/PDF)</label>
                        <input type="file" id="payment_proof" accept="image/jpeg,image/png,application/pdf" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Keterangan tambahan"></textarea>
                    </div>

                    <input type="hidden" id="service_id" name="service_id">
                    <input type="hidden" id="service_name" name="service_name">
                    <input type="hidden" id="price" name="price">

                    <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition duration-300 mt-4 inline-flex items-center justify-center">
                        <i class="fas fa-arrow-right mr-2"></i>Lanjutkan
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6"></div>
        </div>
    </main>

    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-5 rounded-lg flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mr-3"></div>
            <p class="text-primary font-medium">Memproses...</p>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Booking</h4>
            <div class="space-y-2 text-sm text-gray-700 mb-4">
                <div><span class="text-gray-500">Tanggal:</span> <span id="cfDate" class="font-medium"></span></div>
                <div><span class="text-gray-500">Jam:</span> <span id="cfTime" class="font-medium"></span></div>
                <div class="mt-3">
                    <div class="text-gray-500 mb-1">Detail Layanan:</div>
                    <div id="cfItems" class="border border-gray-200 rounded p-2 max-h-40 overflow-auto"></div>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 mt-2">
                    <span class="text-gray-700 font-medium">Total</span>
                    <span id="cfTotal" class="text-accent font-semibold"></span>
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button id="btnCancelConfirm" class="px-4 py-2 rounded border border-gray-300 text-gray-700">Batal</button>
                <button id="btnDoConfirm" class="px-4 py-2 rounded bg-primary text-white hover:bg-secondary"><i class="fas fa-calendar-check mr-2"></i>Konfirmasi Booking</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            function recalcServices() {
                const names = [];
                let total = 0;
                const ids = [];
                $('.svc-checkbox:checked').each(function() {
                    ids.push($(this).val());
                    names.push($(this).data('name'));
                    total += parseInt($(this).data('price') || 0);
                });
                $('#service_id').val(ids.join(','));
                $('#service_name').val(names.join(', '));
                $('#price').val(total);
            }

            $(document).on('change', '.svc-checkbox', recalcServices);

            function renderSlots(date) {
                if (!date) {
                    $('#timeSlots').html('');
                    return;
                }
                $('#timeSlots').html('<div class="col-span-full text-gray-500 text-sm">Memuat ketersediaan...</div>');
                $.get('<?= site_url('pelanggan/bookings/availability') ?>', {
                    date: date
                }, function(resp) {
                    if (resp.status !== 'success') {
                        $('#timeSlots').html('<div class="col-span-full text-red-600 text-sm">Gagal memuat</div>');
                        return;
                    }
                    const slots = resp.slots || {};
                    const wrap = [];
                    Object.keys(slots).forEach(function(time) {
                        const s = slots[time];
                        const disabled = s.full ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-primary';
                        const badge = s.full ? '<span class="ml-2 text-xs text-red-600">Penuh</span>' : `<span class="ml-2 text-xs text-green-600">Sisa ${s.remaining}</span>`;
                        wrap.push(`<div class="slot-item border border-gray-300 rounded px-3 py-2 text-center ${disabled}" data-time="${time}">${time} WIB ${badge}</div>`);
                    });
                    $('#timeSlots').html(wrap.join(''));
                    $('.slot-item').on('click', function() {
                        if ($(this).hasClass('cursor-not-allowed')) return;
                        $('.slot-item').removeClass('ring-2 ring-primary');
                        $(this).addClass('ring-2 ring-primary');
                        $('#booking_time').val($(this).data('time'));
                    });
                }).fail(function() {
                    $('#timeSlots').html('<div class="col-span-full text-red-600 text-sm">Gagal memuat</div>');
                });
            }

            $('#booking_date').on('change', function() {
                $('#booking_time').val('');
                renderSlots(this.value);
            });

            $('#bookingForm').on('submit', function(e) {
                e.preventDefault();
                // Recalculate selected services to ensure hidden fields are up to date
                recalcServices();
                const serviceId = $('#service_id').val();
                const serviceName = $('#service_name').val();
                const price = $('#price').val();
                const date = $('#booking_date').val();
                const time = $('#booking_time').val();
                const notes = $('#notes').val();

                if (!serviceId) {
                    alert('Pilih minimal satu layanan.');
                    return;
                }
                if (!date || !time) {
                    alert('Lengkapi data booking');
                    return;
                }

                // Fill confirmation modal
                $('#cfDate').text(date);
                $('#cfTime').text(time + ' WIB');
                // Render itemized services
                const items = [];
                $('.svc-checkbox:checked').each(function() {
                    const nm = $(this).data('name');
                    const pr = parseInt($(this).data('price') || 0);
                    items.push(`<div class="flex justify-between"><span>${nm}</span><span>${new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(pr)}</span></div>`);
                });
                if (items.length === 0) {
                    items.push('<div class="text-gray-500">Tidak ada layanan dipilih</div>');
                }
                $('#cfItems').html(items.join(''));
                $('#cfTotal').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(parseInt(price || 0)));
                $('#confirmModal').removeClass('hidden');

                // Confirm button handler
                $('#btnDoConfirm').off('click').on('click', function() {
                    $('#confirmModal').addClass('hidden');
                    $('#loadingOverlay').removeClass('hidden');
                    const formData = new FormData();
                    formData.append('service_id', serviceId);
                    formData.append('service_name', serviceName);
                    formData.append('price', price);
                    formData.append('booking_date', date);
                    formData.append('booking_time', time);
                    formData.append('notes', notes);
                    formData.append('payment_type', $('input[name="payment_type"]:checked').val());
                    formData.append('payment_bank', $('input[name="payment_bank"]:checked').val());
                    const proof = document.getElementById('payment_proof');
                    if (proof && proof.files && proof.files[0]) {
                        formData.append('payment_proof', proof.files[0]);
                    }
                    $.ajax({
                        url: '<?= site_url('pelanggan/bookings') ?>',
                        type: 'POST',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(resp) {
                            $('#loadingOverlay').addClass('hidden');
                            if (resp.status === 'success') {
                                window.location.href = resp.redirect;
                            } else {
                                alert(resp.message || 'Gagal membuat booking');
                            }
                        },
                        error: function() {
                            $('#loadingOverlay').addClass('hidden');
                            alert('Terjadi kesalahan. Coba lagi.');
                        }
                    });
                });

                // Cancel handler
                $('#btnCancelConfirm').off('click').on('click', function() {
                    $('#confirmModal').addClass('hidden');
                });
            });
        });
    </script>
</body>

</html>