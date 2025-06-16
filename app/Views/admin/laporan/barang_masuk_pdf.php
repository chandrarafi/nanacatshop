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
            <strong>Supplier:</strong> <?= $filter['supplier'] ?>
        </div>
        <div class="info-item">
            <strong>Status:</strong> <?= $filter['status'] ?>
        </div>
        <div class="info-item">
            <strong>Tanggal Cetak:</strong> <?= $tanggal_cetak ?>
        </div>
    </div>

    <?php if (empty($barangMasuk)) : ?>
        <p class="text-center">Tidak ada data yang ditemukan.</p>
    <?php else : ?>
        <?php if (isset($filter['is_detail']) && $filter['is_detail']) : ?>
            <?php
            // Tampilan untuk cetak detail per kode masuk
            $item = $barangMasuk[0]; // Hanya ada 1 item
            ?>
            <div class="detail-header">
                Detail Barang Masuk: <?= $item->kdmasuk ?> (<?= date('d-m-Y', strtotime($item->tglmasuk)) ?>)
            </div>
            <div class="detail-info">
                <div class="detail-info-item">
                    <strong>Supplier:</strong> <?= $item->namaspl ?>
                </div>
                <div class="detail-info-item">
                    <strong>Status:</strong> <?= $item->status == 0 ? 'Pending' : 'Selesai' ?>
                </div>
                <div class="detail-info-item">
                    <strong>Keterangan:</strong> <?= $item->keterangan ?: '-' ?>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $noDetail = 1;
                    $subtotal = 0;
                    if (!empty($item->detail)) :
                        foreach ($item->detail as $detail) :
                            $subtotal += $detail['totalharga'];
                    ?>
                            <tr>
                                <td><?= $noDetail++ ?></td>
                                <td><?= safeOutput($detail['detailkdbarang']) ?></td>
                                <td><?= safeOutput($detail['namabarang']) ?></td>
                                <td><?= safeOutput($detail['namakategori']) ?></td>
                                <td class="text-center"><?= $detail['jumlah'] ?></td>
                                <td class="text-right">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                <td class="text-right">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php
                        endforeach;
                    else :
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada detail barang</td>
                        </tr>
                    <?php endif; ?>
                    <tr class="total-row">
                        <th colspan="6" class="text-right"><strong>Grand Total</strong></th>
                        <th class="text-right"><strong>Rp <?= number_format($item->grandtotal, 0, ',', '.') ?></strong></th>
                    </tr>
                </tbody>
            </table>
        <?php else : ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Masuk</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $totalNilai = 0;
                    foreach ($barangMasuk as $item) :
                        $totalNilai += $item->grandtotal;
                        $statusClass = $item->status == 0 ? 'status-pending' : 'status-selesai';
                    ?>
                        <tr class="main-row">
                            <td><?= $no++ ?></td>
                            <td><?= $item->kdmasuk ?></td>
                            <td><?= date('d-m-Y', strtotime($item->tglmasuk)) ?></td>
                            <td><?= $item->namaspl ?></td>
                            <td class="<?= $statusClass ?>"><?= $item->status == 0 ? 'Pending' : 'Selesai' ?></td>
                            <td class="text-right">Rp <?= number_format($item->grandtotal, 0, ',', '.') ?></td>
                        </tr>
                        <?php if (!empty($item->detail)) : ?>
                            <tr>
                                <td colspan="6" style="padding: 0;">
                                    <table class="nested-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Kategori</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $noDetail = 1;
                                            foreach ($item->detail as $detail) :
                                            ?>
                                                <tr>
                                                    <td><?= $noDetail++ ?></td>
                                                    <td><?= safeOutput($detail['detailkdbarang']) ?></td>
                                                    <td><?= safeOutput($detail['namabarang']) ?></td>
                                                    <td><?= safeOutput($detail['namakategori']) ?></td>
                                                    <td class="text-center"><?= $detail['jumlah'] ?></td>
                                                    <td class="text-right">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                                    <td class="text-right">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="nested-row">
                                                <td colspan="6" class="text-right"><strong>Subtotal</strong></td>
                                                <td class="text-right"><strong>Rp <?= number_format($item->grandtotal, 0, ',', '.') ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <th colspan="5" class="text-right"><strong>Total Nilai</strong></th>
                        <th class="text-right"><strong>Rp <?= number_format($totalNilai, 0, ',', '.') ?></strong></th>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <div class="footer">
        <p>Kota Padang, <?= $tanggal_ttd ?></p>
        <br>
        <p>Admin Nana Cat Shop</p>
    </div>
</body>

</html>