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
            margin-bottom: 15px;
            border-bottom: 2px solid #e83e8c;
            background-color: #fdf5f8;
            padding: 10px;
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
            margin: 3px 0;
            color: #333;
            text-align: center;
        }

        .header h2 {
            margin: 10px 0 3px 0;
            color: #e83e8c;
            font-size: 16px;
            text-align: center;
        }

        .info {
            margin-bottom: 8px;
        }

        .info-item {
            margin-bottom: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.data-table,
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #fdf5f8;
            color: #e83e8c;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
        }

        .footer p {
            margin: 3px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .detail-header {
            background-color: #fdf5f8;
            color: #e83e8c;
            padding: 5px;
            margin-top: 8px;
            margin-bottom: 5px;
            font-weight: bold;
            border-radius: 3px;
        }

        .detail-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .detail-info-item {
            width: 32%;
        }

        .nested-table {
            margin: 0;
            width: 100%;
        }

        .nested-table th,
        .nested-table td {
            padding: 3px;
            font-size: 11px;
        }

        .nested-table th {
            background-color: #fef2f7;
        }

        .nested-row td {
            border-top: 2px solid #e83e8c;
            border-bottom: 2px solid #e83e8c;
            background-color: #fef9fb;
            font-weight: bold;
        }

        .main-row {
            background-color: #e83e8c;
        }

        .main-row td {
            font-weight: bold;
            border-top: 2px solid #fdf5f8;
            color: white;
        }

        .status-selesai {
            color: white;
            font-weight: bold;
        }

        .status-pending {
            color: white;
            font-weight: bold;
        }

        .status-proses {
            color: white;
            font-weight: bold;
        }

        .total-row {
            background-color: #e83e8c;
            color: white;
        }

        .total-row th {
            background-color: #e83e8c;
            color: white;
            font-weight: bold;
            font-size: 13px;
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

    // Fungsi untuk mendapatkan label status
    function getStatusLabel($status)
    {
        switch ($status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Dalam Penitipan';
            case 2:
                return 'Selesai';
            default:
                return 'Unknown';
        }
    }
    ?>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <?php if (!empty($logo)) : ?>
                        <img src="<?= $logo ?>" alt="Logo" class="logo-img">
                    <?php endif; ?>
                </td>
                <td class="text-cell">
                    <h1>NANA CAT SHOP</h1>
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
        <div class="info-item">
            <strong>Tanggal: <?= date('d-m-Y') ?></strong>
        </div>
        <?php if (!empty($filter['text'])) : ?>
            <div class="info-item">
                <strong>Filter: <?= $filter['text'] ?></strong>
            </div>
        <?php endif; ?>
        <?php if (!empty($filter['pelanggan']) && $filter['pelanggan'] !== 'Semua') : ?>
            <div class="info-item">
                <strong>Pelanggan: <?= $filter['pelanggan'] ?></strong>
            </div>
        <?php endif; ?>
        <?php if (!empty($filter['status']) && $filter['status'] !== 'Semua') : ?>
            <div class="info-item">
                <strong>Status: <?= $filter['status'] ?></strong>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($penitipan)) : ?>
        <p class="text-center">Tidak ada data yang ditemukan.</p>
    <?php else : ?>
        <!-- Tampilan untuk cetak laporan keseluruhan -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Kode Penitipan</th>
                    <th width="15%">Nama Pelanggan</th>
                    <th width="10%">Kode Hewan</th>
                    <th width="15%">Nama Hewan</th>
                    <th width="10%">Tgl Penitipan</th>
                    <th width="10%">Jam penitipan</th>
                    <th width="10%">Harga</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalSeluruh = 0;
                foreach ($penitipan as $item) :
                    $totalSeluruh += ($item->total_biaya_dengan_denda > 0) ? $item->total_biaya_dengan_denda : $item->grandtotal;
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= safeOutput($item->kdpenitipan) ?></td>
                        <td><?= safeOutput($item->namapelanggan) ?></td>
                        <td><?= safeOutput($item->kdhewan) ?></td>
                        <td><?= safeOutput($item->namahewan) ?></td>
                        <td><?= date('Y-m-d', strtotime($item->tglpenitipan)) ?></td>
                        <td><?= date('H:i', strtotime($item->tglpenitipan)) ?></td>
                        <td class="text-right">Rp <?= number_format($item->grandtotal / $item->durasi, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($item->grandtotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <th colspan="7" class="text-right">TOTAL</th>
                    <th colspan="2" class="text-right">Rp <?= number_format($totalSeluruh, 0, ',', '.') ?></th>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        <p>Padang, <?= date('Y-m-d') ?></p>
        <br><br><br>
        <p><strong><?= $admin ?></strong></p>
    </div>
</body>

</html>