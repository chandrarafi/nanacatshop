<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
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

        .text-right {
            text-align: right;
        }

        .small {
            font-size: 11px;
            color: #555
        }

        .footer {
            margin-top: 15px;
            text-align: right;
        }

        .footer p {
            margin: 3px 0;
        }
    </style>
    <?php function escHtml($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    } ?>
    <?php function fmtDate($d)
    {
        return $d ? date('d-m-Y', strtotime($d)) : '-';
    } ?>
    <?php function badge($txt)
    {
        return escHtml($txt);
    } ?>
</head>

<body>
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
                <td class="logo-cell"></td>
            </tr>
        </table>
        <h2><?= esc($title) ?></h2>
    </div>

    <div class="info">
        <div class="info-item"><strong>Tanggal: <?= date('d-m-Y') ?></strong></div>
        <?php if (!empty($filter['text'])): ?>
            <div class="info-item"><strong>Filter: <?= esc($filter['text']) ?></strong></div>
        <?php endif; ?>
        <?php if (!empty($filter['pelanggan']) && $filter['pelanggan'] !== 'Semua'): ?>
            <div class="info-item"><strong>Pelanggan: <?= esc($filter['pelanggan']) ?></strong></div>
        <?php endif; ?>
        <?php if (!empty($filter['status']) && $filter['status'] !== 'Semua'): ?>
            <div class="info-item"><strong>Status: <?= esc($filter['status']) ?></strong></div>
        <?php endif; ?>
    </div>

    <!-- Ringkasan -->
    <?php
    $fmt = function ($n) {
        return function_exists('rupiah') ? rupiah($n) : number_format((float)$n, 0, ',', '.');
    };
    ?>
    <div class="mb-3">
        <table style="width:100%;border-collapse:collapse;margin-bottom:10px">
            <tr>
                <th style="text-align:left;border:1px solid #333;padding:6px;background:#f9f9f9">Total Booking</th>
                <td style="border:1px solid #333;padding:6px"><?= (int)($summary['total_count'] ?? 0) ?></td>
                <th style="text-align:left;border:1px solid #333;padding:6px;background:#f9f9f9">Total Nilai</th>
                <td style="border:1px solid #333;padding:6px"><?= $fmt($summary['total_price'] ?? 0) ?></td>
            </tr>
            <tr>
                <th style="text-align:left;border:1px solid #333;padding:6px;background:#f9f9f9">Status</th>
                <td style="border:1px solid #333;padding:6px">
                    Pending: <?= (int)($summary['status']['pending'] ?? 0) ?>,
                    Confirmed: <?= (int)($summary['status']['confirmed'] ?? 0) ?>,
                    Completed: <?= (int)($summary['status']['completed'] ?? 0) ?>,
                    Cancelled: <?= (int)($summary['status']['cancelled'] ?? 0) ?>
                </td>
                <th style="text-align:left;border:1px solid #333;padding:6px;background:#f9f9f9">Pembayaran</th>
                <td style="border:1px solid #333;padding:6px">
                    DP: <?= (int)($summary['payment_type']['dp'] ?? 0) ?>,
                    Lunas: <?= (int)($summary['payment_type']['lunas'] ?? 0) ?>
                </td>
            </tr>
            <tr>
                <th style="text-align:left;border:1px solid #333;padding:6px;background:#f9f9f9">Bank</th>
                <td colspan="3" style="border:1px solid #333;padding:6px">
                    <?php if (!empty($summary['bank'])): ?>
                        <?php foreach ($summary['bank'] as $bk => $cnt): ?>
                            <span style="margin-right:10px"><?= esc($bk) ?>: <?= (int)$cnt ?></span>
                        <?php endforeach; ?>
                        <?php else: ?>-
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <?php if (!empty($grouped)) : ?>
        <table class="data-table" style="margin-bottom:14px">
            <thead>
                <tr>
                    <th width="20%"><?= esc($groupLabel ?: 'Periode') ?></th>
                    <th width="20%">Jumlah Booking</th>
                    <th>Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php $gt = 0;
                foreach ($grouped as $g): $gt += (float)($g['total'] ?? 0); ?>
                    <tr>
                        <td><?= esc($g['periode']) ?></td>
                        <td><?= (int)($g['jumlah'] ?? 0) ?></td>
                        <td class="text-right">Rp <?= number_format((float)($g['total'] ?? 0), 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <th colspan="2" class="text-right">TOTAL</th>
                    <th class="text-right">Rp <?= number_format($gt, 0, ',', '.') ?></th>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">ID Booking</th>
                <th width="18%">Nama Pelanggan</th>
                <th width="10%">Tgl Booking</th>
                <th width="10%">Jam</th>
                <th>Layanan</th>
                <th width="12%">Pembayaran</th>
                <th width="10%">Total</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
                <?php else: $no = 1;
                $grand = 0;
                foreach ($bookings as $b): $grand += (float)($b->price ?? 0); ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>#<?= esc($b->id) ?></td>
                        <td>
                            <div><strong><?= esc($b->namapelanggan ?? '-') ?></strong></div>
                            <div class="small">ID: <?= esc($b->pelanggan_id ?? '-') ?> • <?= esc($b->nohp ?? '-') ?></div>
                        </td>
                        <td><?= fmtDate($b->booking_date ?? null) ?></td>
                        <td><?= esc($b->booking_time ?? '-') ?></td>
                        <td>
                            <?php $names = array_filter(array_map('trim', explode(',', (string)($b->service_name ?? '')))); ?>
                            <?= empty($names) ? '-' : esc(implode(', ', $names)) ?>
                        </td>
                        <td>
                            <div class="small"><?= strtoupper(esc($b->payment_type ?? '-')) ?></div>
                            <div class="small">Bank: <?= esc($b->payment_bank ?? '-') ?></div>
                        </td>
                        <td class="text-right">Rp <?= number_format((float)($b->price ?? 0), 0, ',', '.') ?></td>
                        <td><?= esc(ucfirst($b->status ?? '-')) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <th colspan="7" class="text-right">TOTAL</th>
                    <th colspan="2" class="text-right">Rp <?= number_format($grand, 0, ',', '.') ?></th>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Padang, <?= date('Y-m-d') ?></p>
        <br><br><br>
        <p><strong>Admin Nana Cat Shop</strong></p>
    </div>
</body>

</html>
