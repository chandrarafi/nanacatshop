<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Perawatan - <?= $perawatan['kdperawatan'] ?></title>
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
            <h3>FAKTUR PERAWATAN HEWAN</h3>
        </div>

        <table class="info-section">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 40%;"><strong>No. Faktur</strong></td>
                            <td style="width: 5%;">:</td>
                            <td><?= $perawatan['kdperawatan'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Perawatan</strong></td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($perawatan['tglperawatan'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>
                                <?php if ($perawatan['status'] == 0) : ?>
                                    Menunggu
                                <?php elseif ($perawatan['status'] == 1) : ?>
                                    Dalam Proses
                                <?php elseif ($perawatan['status'] == 2) : ?>
                                    Selesai
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 40%;"><strong>Pelanggan</strong></td>
                            <td style="width: 5%;">:</td>
                            <td><?= $perawatan['nama_pelanggan'] ?? 'Pelanggan Umum' ?></td>
                        </tr>
                        <?php if (!empty($perawatan['nohp'])) : ?>
                            <tr>
                                <td><strong>Telepon</strong></td>
                                <td>:</td>
                                <td><?= $perawatan['nohp'] ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($perawatan['alamat'])) : ?>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>:</td>
                                <td><?= $perawatan['alamat'] ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($perawatan['namahewan'])) : ?>
                            <tr>
                                <td><strong>Hewan</strong></td>
                                <td>:</td>
                                <td><?= $perawatan['namahewan'] ?> (<?= $perawatan['jenis'] ?>)</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table-detail">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Perawatan</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $totalLayanan = 0;
                foreach ($detail_perawatan as $detail) :
                    $totalLayanan += $detail['totalharga'];
                ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= $detail['namafasilitas'] ?></td>
                        <td class="text-center"><?= $detail['kategori'] ?></td>
                        <td class="text-right">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= $detail['jumlah'] ?> <?= $detail['satuan'] ?></td>
                        <td class="text-right">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($detail_perawatan)) : ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data layanan perawatan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right"><strong>Total Biaya:</strong></td>
                    <td class="text-right"><strong>Rp <?= number_format($perawatan['grandtotal'], 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <?php if (!empty($perawatan['keterangan'])) : ?>
            <div style="margin-bottom: 20px; border: 1px solid #000; padding: 10px;">
                <strong>Keterangan:</strong><br>
                <?= nl2br($perawatan['keterangan']) ?>
            </div>
        <?php endif; ?>

        <div class="signatures">
            <div class="signature-box">
                <p>Pelanggan</p>
                <br><br><br>
                <p><?= $perawatan['nama_pelanggan'] ?? '________________' ?></p>
            </div>
            <div class="signature-box">
                <p>Petugas</p>
                <br><br><br>
                <p>_________________</p>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda menggunakan layanan perawatan kami.</p>
            <p>Dokumen ini dicetak pada: <?= date('d-m-Y H:i:s') ?></p>
        </div>
    </div>
</body>

</html>