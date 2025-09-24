<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Nana Cat Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-[#FFF5F9] font-sans">
    <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-[#F564A9]">Invoice Booking</h1>
                    <p class="text-sm text-gray-500">#<?= $booking['id'] ?> • <?= date('d M Y', strtotime($booking['created_at'])) ?></p>
                </div>
                <div class="text-right">
                    <p class="font-semibold">Nana Cat Shop</p>
                    <p class="text-sm text-gray-600">Jl. Bandar Olo no.42, Padang</p>
                    <p class="text-sm text-gray-600">+6282285214024</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-pink-50 rounded p-4">
                    <p class="text-sm text-gray-500">Pelanggan</p>
                    <p class="font-semibold"><?= $user['name'] ?></p>
                    <p class="text-sm text-gray-600"><?= $user['email'] ?></p>
                </div>
                <div class="bg-pink-50 rounded p-4">
                    <p class="text-sm text-gray-500">Jadwal</p>
                    <p class="font-semibold"><?= date('d M Y', strtotime($booking['booking_date'])) ?> • <?= substr($booking['booking_time'], 0, 5) ?> WIB</p>
                    <p class="text-sm text-gray-600">Status: <?= ucfirst($booking['status']) ?> • Pembayaran: <?= !empty($booking['payment_type']) ? strtoupper($booking['payment_type']) : '-' ?></p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-pink-100 text-pink-800">
                            <th class="text-left p-3">Layanan</th>
                            <th class="text-right p-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $it): ?>
                                <tr class="border-b">
                                    <td class="p-3 align-top"><?= esc($it['name']) ?></td>
                                    <td class="p-3 text-right font-semibold"><?= $it['price'] !== null ? rupiah($it['price']) : '' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="border-b">
                                <td class="p-3 align-top"><?= esc($booking['service_name']) ?></td>
                                <td class="p-3 text-right font-semibold"><?= rupiah($booking['price']) ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="p-3 text-right">Total</th>
                            <th class="p-3 text-right text-[#533B4D] text-lg"><?= rupiah($booking['price']) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6 flex justify-between print:hidden">
                <a href="<?= site_url('pelanggan/bookings') ?>" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Kembali</a>
                <button onclick="window.print()" class="px-4 py-2 rounded bg-[#F564A9] text-white hover:bg-[#FAA4BD]"><i class="fa fa-print mr-2"></i>Cetak</button>
            </div>
        </div>
    </div>
</body>

</html>