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
                return 'Menunggu';
            case 1:
                return 'Dalam Proses';
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
            <strong>Filter:</strong> <?= $filter['text'] ?>
        </div>
        <div class="info-item">
            <strong>Pelanggan:</strong> <?= $filter['pelanggan'] ?>
        </div>
        <div class="info-item">
            <strong>Status:</strong> <?= $filter['status'] ?>
        </div>
        <div class="info-item">
            <strong>Tanggal Cetak:</strong> <?= $tanggal_cetak ?>
        </div>
    </div>

    <?php if (empty($perawatan)) : ?>
        <p class="text-center">Tidak ada data yang ditemukan.</p>
    <?php else : ?>
        <?php if (isset($filter['is_detail']) && $filter['is_detail']) : ?>
            <?php
            // Tampilan untuk cetak detail per kode perawatan
            $item = $perawatan[0]; // Hanya ada 1 item
            $details = $item->detail;
            ?>
            <div class="detail-header">Data Perawatan</div>
            <table class="data-table">
                <tr>
                    <td width="20%"><strong>Kode Perawatan</strong></td>
                    <td width="30%"><?= safeOutput($item->kdperawatan) ?></td>
                    <td width="20%"><strong>Tanggal Perawatan</strong></td>
                    <td width="30%"><?= date('d-m-Y', strtotime($item->tglperawatan)) ?></td>
                </tr>
                <tr>
                    <td><strong>Pelanggan</strong></td>
                    <td><?= safeOutput($item->namapelanggan) ?></td>
                    <td><strong>Status</strong></td>
                    <td><?= getStatusLabel($item->status) ?></td>
                </tr>
                <tr>
                    <td><strong>Hewan</strong></td>
                    <td><?= safeOutput($item->namahewan) ?></td>
                    <td><strong>Grand Total</strong></td>
                    <td>Rp <?= number_format($item->grandtotal, 0, ',', '.') ?></td>
                </tr>
            </table>

            <div class="detail-header">Detail Layanan</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode Fasilitas</th>
                        <th width="30%">Nama Fasilitas</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Jumlah</th>
                        <th width="12%">Harga</th>
                        <th width="13%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $totalSeluruh = 0;
                    foreach ($details as $detail) :
                        $totalSeluruh += $detail['totalharga'];
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= safeOutput($detail['kdfasilitas']) ?></td>
                            <td><?= safeOutput($detail['namafasilitas']) ?></td>
                            <td><?= safeOutput($detail['kategori']) ?></td>
                            <td class="text-center"><?= $detail['jumlah'] ?></td>
                            <td class="text-right">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                            <td class="text-right">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <th colspan="6" class="text-right">TOTAL</th>
                        <th class="text-right">Rp <?= number_format($totalSeluruh, 0, ',', '.') ?></th>
                    </tr>
                </tbody>
            </table>
        <?php else : ?>
            <!-- Tampilan untuk cetak laporan keseluruhan -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th width="10%">Tanggal</th>
                        <th width="20%">Pelanggan</th>
                        <th width="20%">Hewan</th>
                        <th width="10%">Status</th>
                        <th width="15%">Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $totalSeluruh = 0;
                    foreach ($perawatan as $item) :
                        $totalSeluruh += $item->grandtotal;
                    ?>
                        <tr class="main-row">
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= safeOutput($item->kdperawatan) ?></td>
                            <td><?= date('d-m-Y', strtotime($item->tglperawatan)) ?></td>
                            <td><?= safeOutput($item->namapelanggan) ?></td>
                            <td><?= safeOutput($item->namahewan) ?></td>
                            <td class="text-center">
                                <?php if ($item->status == 0) : ?>
                                    <span class="status-pending">Menunggu</span>
                                <?php elseif ($item->status == 1) : ?>
                                    <span class="status-proses">Dalam Proses</span>
                                <?php else : ?>
                                    <span class="status-selesai">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">Rp <?= number_format($item->grandtotal, 0, ',', '.') ?></td>
                        </tr>

                        <?php if (isset($filter['compact_view']) && $filter['compact_view'] && !empty($item->detail)) : ?>
                            <tr>
                                <td colspan="7" style="padding: 0;">
                                    <table class="nested-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Kode Fasilitas</th>
                                                <th width="30%">Nama Fasilitas</th>
                                                <th width="15%">Kategori</th>
                                                <th width="10%">Jumlah</th>
                                                <th width="12%">Harga</th>
                                                <th width="13%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $noDetail = 1;
                                            $subtotal = 0;
                                            foreach ($item->detail as $detail) :
                                                $subtotal += $detail['totalharga'];
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?= $noDetail++ ?></td>
                                                    <td><?= safeOutput($detail['detailkdfasilitas']) ?></td>
                                                    <td><?= safeOutput($detail['namafasilitas']) ?></td>
                                                    <td><?= safeOutput($detail['kategori']) ?></td>
                                                    <td class="text-center"><?= $detail['jumlah'] ?></td>
                                                    <td class="text-right">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                                    <td class="text-right">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="nested-row">
                                                <td colspan="6" class="text-right">Subtotal</td>
                                                <td class="text-right">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <th colspan="6" class="text-right">TOTAL</th>
                        <th class="text-right">Rp <?= number_format($totalSeluruh, 0, ',', '.') ?></th>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <div class="footer">
        <p><?= $kota ?>, <?= $tanggal_ttd ?></p>
        <br><br><br>
        <p><strong><?= $admin ?></strong></p>
    </div>
</body>

</html>