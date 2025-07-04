<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e83e8c;
            background-color: #fdf5f8;
            padding: 15px;
            border-radius: 5px;
        }

        .header-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .logo-cell {
            width: 100px;
            text-align: left;
        }

        .text-cell {
            text-align: center;
        }

        .logo-img {
            width: 80px;
            height: auto;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #e83e8c;
            text-align: center;
        }

        .header p {
            margin: 5px 0;
            color: #333;
            text-align: center;
        }

        .header h2 {
            margin: 15px 0 5px 0;
            color: #e83e8c;
            font-size: 16px;
            text-align: center;
        }

        .info {
            margin-bottom: 15px;
        }

        .info-item {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table,
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #fdf5f8;
            color: #e83e8c;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .footer p {
            margin: 5px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .stok-habis {
            color: #dc3545;
            font-weight: bold;
        }

        .stok-menipis {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php
    // Fungsi untuk menangani encoding dan escape karakter khusus
    function safeOutput($str)
    {
        if (empty($str)) return '-';
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    // Fungsi untuk format harga
    function formatHarga($harga)
    {
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }
    ?>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <?php if (!empty($logo)): ?>
                        <img src="<?= $logo ?>" alt="Logo" class="logo-img">
                    <?php endif; ?>
                </td>
                <td class="text-cell">
                    <h1>NANA CATSHOP</h1>
                    <p>Jl. Bandar Olo no.42, Padang Barat, Kota Padang</p>
                    <p>Telp: (021) 1234567 | Email: info@nanacatshop.com</p>
                </td>
                <td class="logo-cell">
                    <!-- Sel kosong untuk menyeimbangkan tata letak -->
                </td>
            </tr>
        </table>
        <h2><?= $title ?></h2>
    </div>

    <div class="info">
        <?php if (!empty($filter['nama_kategori'])): ?>
            <div class="info-item">
                <strong>Kategori:</strong> <?= safeOutput($filter['nama_kategori']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($filter['status_stok'])): ?>
            <div class="info-item">
                <strong>Status Stok:</strong> <?= safeOutput($filter['status_stok']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($filter['tgl_awal']) && !empty($filter['tgl_akhir'])): ?>
            <div class="info-item">
                <strong>Periode:</strong> <?= date('d-m-Y', strtotime($filter['tgl_awal'])) ?> s/d <?= date('d-m-Y', strtotime($filter['tgl_akhir'])) ?>
            </div>
        <?php endif; ?>

        <div class="info-item">
            <strong>Tanggal Cetak:</strong> <?= $tanggal_cetak ?>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($barang)): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php
                $no = 1;
                $totalStok = 0;
                $totalNilai = 0;
                foreach ($barang as $b):
                    $stokClass = '';
                    if ($b->jumlah == 0) {
                        $stokClass = 'stok-habis';
                    } else if ($b->jumlah <= 5) {
                        $stokClass = 'stok-menipis';
                    }
                    $totalStok += $b->jumlah;
                    $totalNilai += ($b->jumlah * $b->hargabeli);
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= safeOutput($b->kdbarang) ?></td>
                        <td><?= safeOutput($b->namabarang) ?></td>
                        <td><?= safeOutput($b->namakategori) ?></td>
                        <td class="<?= $stokClass ?>"><?= $b->jumlah ?></td>
                        <td class="text-right"><?= formatHarga($b->hargabeli) ?></td>
                        <td class="text-right"><?= formatHarga($b->hargajual) ?></td>
                        <td><?= date('d-m-Y', strtotime($b->created_at)) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="4" class="text-right">Total</th>
                    <th><?= $totalStok ?></th>
                    <th class="text-right"><?= formatHarga($totalNilai) ?></th>
                    <th colspan="2"></th>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Kota Padang, <?= date('d F Y') ?></p>
        <br><br><br>
        <p>Admin Nana Cat Shop</p>
    </div>
</body>

</html>