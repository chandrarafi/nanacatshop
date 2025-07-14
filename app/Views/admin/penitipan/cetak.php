<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penitipan - <?= $penitipan['kdpenitipan'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12pt;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin-bottom: 5px;
        }

        .header p {
            margin: 0;
        }

        .header hr {
            border-top: 2px solid #000;
            margin: 10px 0;
        }

        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-section td {
            vertical-align: top;
        }

        .table-detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-detail th,
        .table-detail td {
            border: 1px solid #000;
            padding: 8px;
        }

        .table-detail th {
            text-align: center;
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-style: italic;
        }

        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">
        <div class="header">
            <h2>NANA CAT SHOP</h2>
            <p>Jl. Bandar Olo No.42, Padang Barat, Kota Padang</p>
            <p>Telp: (021) 1234-5678 | Email: info@nanacatshop.com</p>
            <hr>
            <h3>FAKTUR PENITIPAN HEWAN</h3>
        </div>

        <table class="info-section">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 40%;"><strong>No. Faktur</strong></td>
                            <td style="width: 5%;">:</td>
                            <td><?= $penitipan['kdpenitipan'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Masuk</strong></td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($penitipan['tglpenitipan'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Keluar</strong></td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($penitipan['tglselesai'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Durasi</strong></td>
                            <td>:</td>
                            <td><?= $penitipan['durasi'] ?> Hari</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 40%;"><strong>Pelanggan</strong></td>
                            <td style="width: 5%;">:</td>
                            <td><?= $penitipan['nama'] ?? 'Pelanggan Umum' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Telepon</strong></td>
                            <td>:</td>
                            <td><?= $penitipan['nohp'] ?? '-' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Hewan</strong></td>
                            <td>:</td>
                            <td><?= $penitipan['namahewan'] ?? '-' ?> (<?= $penitipan['jenishewan'] ?? '-' ?>)</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>
                                <?php if ($penitipan['status'] == 0) : ?>
                                    Pending
                                <?php elseif ($penitipan['status'] == 1) : ?>
                                    Dalam Penitipan
                                <?php elseif ($penitipan['status'] == 2) : ?>
                                    Selesai
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table-detail">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Fasilitas</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $totalFasilitas = 0;
                foreach ($detailPenitipan as $detail) :
                    $totalFasilitas += $detail['totalharga'];
                ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= $detail['namafasilitas'] ?></td>
                        <td class="text-center"><?= $detail['kategori'] ?></td>
                        <td class="text-right">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= $detail['satuan'] ?></td>
                        <td class="text-center"><?= $detail['jumlah'] ?></td>
                        <td class="text-right">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right"><strong>Total Fasilitas:</strong></td>
                    <td class="text-right">Rp <?= number_format($totalFasilitas, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right"><strong>Durasi:</strong></td>
                    <td class="text-right"><?= $penitipan['durasi'] ?> Hari</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right"><strong>Total Biaya:</strong></td>
                    <td class="text-right"><strong>Rp <?= number_format($penitipan['grandtotal'], 0, ',', '.') ?></strong></td>
                </tr>
                <?php if (!empty($penitipan['tglpenjemputan']) && $penitipan['is_terlambat'] == 1) : ?>
                    <tr style="background-color: #ffe6e6;">
                        <td colspan="6" class="text-right"><strong>Tanggal Penjemputan:</strong></td>
                        <td class="text-right"><?= date('d-m-Y', strtotime($penitipan['tglpenjemputan'])) ?></td>
                    </tr>
                    <tr style="background-color: #ffe6e6;">
                        <td colspan="6" class="text-right"><strong>Keterlambatan:</strong></td>
                        <td class="text-right"><?= $penitipan['jumlah_hari_terlambat'] ?> Hari</td>
                    </tr>
                    <tr style="background-color: #ffe6e6;">
                        <td colspan="6" class="text-right"><strong>Denda (50% per hari):</strong></td>
                        <td class="text-right">Rp <?= number_format($penitipan['biaya_denda'], 0, ',', '.') ?></td>
                    </tr>
                    <tr style="background-color: #ffe6e6;">
                        <td colspan="6" class="text-right"><strong>Total Biaya dengan Denda:</strong></td>
                        <td class="text-right"><strong>Rp <?= number_format($penitipan['total_biaya_dengan_denda'], 0, ',', '.') ?></strong></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <p>Pelanggan</p>
                <br><br><br>
                <p><?= $penitipan['nama_pelanggan'] ?? '________________' ?></p>
            </div>
            <div class="signature-box">
                <p>Petugas</p>
                <br><br><br>
                <p>_________________</p>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan hewan kesayangan Anda kepada kami!</p>
            <p>Nana Cat Shop - Merawat dengan Sepenuh Hati</p>
        </div>
    </div>
</body>

</html>