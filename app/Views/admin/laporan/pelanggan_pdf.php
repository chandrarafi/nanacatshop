<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
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
    </style>
</head>

<body>
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
        <?php if (!empty($filter['tgl_awal']) && !empty($filter['tgl_akhir'])): ?>
            <div class="info-item">
                <strong>Periode:</strong> <?= date('d-m-Y', strtotime($filter['tgl_awal'])) ?> s/d <?= date('d-m-Y', strtotime($filter['tgl_akhir'])) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($filter['jenkel'])): ?>
            <div class="info-item">
                <strong>Jenis Kelamin:</strong> <?= $filter['jenkel'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
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
                <th>ID Pelanggan</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pelanggan)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php $no = 1;
                foreach ($pelanggan as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p->idpelanggan ?></td>
                        <td><?= $p->nama ?></td>
                        <td><?= $p->jenkel === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                        <td><?= $p->alamat ?: '-' ?></td>
                        <td><?= $p->nohp ?: '-' ?></td>
                        <td><?= date('d-m-Y', strtotime($p->created_at)) ?></td>
                    </tr>
                <?php endforeach; ?>
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