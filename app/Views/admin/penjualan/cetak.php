<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - <?= $penjualan['kdpenjualan'] ?></title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-family: monospace;
            font-size: 12px;
            line-height: 1.2;
            width: 70mm;
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
        }

        .text-center {
            text-align: center;
        }

        .dotted-line {
            border-top: 1px dotted #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table.items td {
            padding: 2px 0;
            vertical-align: top;
        }

        table.items td:nth-child(2) {
            text-align: center;
        }

        table.items td:nth-child(3),
        table.items td:nth-child(4) {
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
        }

        .print-instructions {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .print-instructions h4 {
            margin-top: 0;
        }

        .print-instructions ol {
            padding-left: 20px;
            margin-bottom: 0;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                width: 100%;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <div class="print-instructions">
            <h4>Petunjuk Cetak di Chrome:</h4>
            <ol>
                <li>Klik tombol "Cetak Struk" atau tekan Ctrl+P</li>
                <li>Di pengaturan cetak, pilih "Lainnya" pada bagian Ukuran Kertas</li>
                <li>Pilih "Ukuran Kustom" dan atur lebar 80mm dan tinggi sesuai kebutuhan</li>
                <li>Pada bagian Margin, pilih "Tidak Ada"</li>
                <li>Pada bagian Skala, pilih "Kustom" dan atur ke 100%</li>
                <li>Nonaktifkan opsi "Header dan Footer"</li>
                <li>Klik "Cetak"</li>
            </ol>
        </div>
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Cetak Struk</button>
        <button onclick="window.close()" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Tutup</button>
    </div>

    <div class="text-center">
        <b>NANA CAT SHOP</b><br>
        Toko Perlengkapan Kucing dan Anjing<br>
        Jl. Raya Contoh No. 123, Kota<br>
        Telp: +6282285214024
    </div>

    <div class="dotted-line"></div>

    <div>
        No: <?= $penjualan['kdpenjualan'] ?><br>
        Tgl: <?= date('d/m/Y H:i', strtotime($penjualan['tglpenjualan'])) ?><br>
        Kasir: Admin<br>
        Pelanggan: <?= !empty($penjualan['idpelanggan']) ? $penjualan['nama'] : 'Umum' ?>
    </div>

    <div class="dotted-line"></div>

    <table class="items">
        <tr>
            <td width="45%">Item</td>
            <td width="10%">Jml</td>
            <td width="20%">Harga</td>
            <td width="25%">Total</td>
        </tr>
        <?php foreach ($detailPenjualan as $detail): ?>
            <tr>
                <td><?= $detail['namabarang'] ?></td>
                <td><?= $detail['jumlah'] ?></td>
                <td><?= number_format($detail['harga'], 0, ',', '.') ?></td>
                <td><?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="dotted-line"></div>

    <div class="total-row">
        <div><b>TOTAL</b></div>
        <div><b>Rp <?= number_format($penjualan['grandtotal'], 0, ',', '.') ?></b></div>
    </div>

    <div class="dotted-line"></div>

    <div class="footer">
        Terima kasih telah berbelanja<br>
        di Nana Cat Shop<br>
        Barang yang sudah dibeli tidak dapat dikembalikan
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                // Uncomment baris di bawah ini jika ingin cetak otomatis saat halaman dibuka
                // window.print();
            }, 500);
        };
    </script>
</body>

</html>