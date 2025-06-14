<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/penitipan') ?>">Penitipan</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-alt me-1"></i>
            Detail Penitipan
            <div class="float-end">
                <button class="btn btn-sm btn-primary me-2" id="btn-print">
                    <i class="fas fa-print"></i> Cetak Faktur
                </button>
                <a href="<?= site_url('admin/penitipan') ?>" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="mb-3">Informasi Penitipan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%">Kode Penitipan</td>
                            <td width="5%">:</td>
                            <td><strong><?= $penitipan['kdpenitipan'] ?></strong></td>
                        </tr>
                        <tr>
                            <td>Tanggal Masuk</td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($penitipan['tglmasuk'])) ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Keluar</td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($penitipan['tglkeluar'])) ?></td>
                        </tr>
                        <tr>
                            <td>Durasi</td>
                            <td>:</td>
                            <td><?= $penitipan['durasi'] ?> Hari</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>
                                <?php if ($penitipan['status'] == 0) : ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif ($penitipan['status'] == 1) : ?>
                                    <span class="badge bg-primary">Dalam Penitipan</span>
                                <?php elseif ($penitipan['status'] == 2) : ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Informasi Pelanggan & Hewan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%">Nama Pelanggan</td>
                            <td width="5%">:</td>
                            <td><strong><?= $pelanggan['nama'] ?></strong></td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td><?= $pelanggan['telepon'] ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td><?= $pelanggan['alamat'] ?></td>
                        </tr>
                        <tr>
                            <td>Nama Hewan</td>
                            <td>:</td>
                            <td><strong><?= $hewan['nama'] ?></strong> (<?= $hewan['jenis'] ?>)</td>
                        </tr>
                        <tr>
                            <td>Catatan</td>
                            <td>:</td>
                            <td><?= $penitipan['catatan'] ?: '-' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5 class="mb-3">Fasilitas Penitipan</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
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
                        <?php $i = 1;
                        foreach ($detail_penitipan as $detail) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $detail['namafasilitas'] ?></td>
                                <td><?= $detail['kategori'] ?></td>
                                <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                <td><?= $detail['satuan'] ?></td>
                                <td><?= $detail['jumlah'] ?></td>
                                <td>Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Total Fasilitas:</strong></td>
                            <td>Rp <?= number_format($total_fasilitas, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Durasi:</strong></td>
                            <td><?= $penitipan['durasi'] ?> Hari</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Total Biaya:</strong></td>
                            <td><strong>Rp <?= number_format($penitipan['total'], 0, ',', '.') ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Riwayat Status</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-warning me-2">Pending</span>
                                        <strong>Pendaftaran Penitipan</strong>
                                    </div>
                                    <span><?= date('d-m-Y H:i', strtotime($penitipan['created_at'])) ?></span>
                                </li>

                                <?php if ($penitipan['status'] >= 1) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-primary me-2">Dalam Penitipan</span>
                                            <strong>Hewan Masuk Penitipan</strong>
                                        </div>
                                        <span><?= date('d-m-Y H:i', strtotime($penitipan['updated_at'])) ?></span>
                                    </li>
                                <?php endif; ?>

                                <?php if ($penitipan['status'] >= 2) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-success me-2">Selesai</span>
                                            <strong>Penitipan Selesai</strong>
                                        </div>
                                        <span><?= date('d-m-Y H:i', strtotime($penitipan['updated_at'])) ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Area cetak faktur (hidden) -->
<div id="print-area" style="display: none;">
    <div style="max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="margin-bottom: 5px;">NANA CAT SHOP</h2>
            <p style="margin: 0;">Jl. Kucing Manis No. 123, Kota Meong</p>
            <p style="margin: 0;">Telp: (021) 1234-5678 | Email: info@nanacatshop.com</p>
            <hr style="border-top: 2px solid #000; margin: 10px 0;">
            <h3>FAKTUR PENITIPAN HEWAN</h3>
        </div>

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 40%;"><strong>No. Faktur</strong></td>
                            <td style="width: 5%;">:</td>
                            <td><?= $penitipan['kdpenitipan'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Masuk</strong></td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($penitipan['tglmasuk'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Keluar</strong></td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($penitipan['tglkeluar'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Durasi</strong></td>
                            <td>:</td>
                            <td><?= $penitipan['durasi'] ?> Hari</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 40%;"><strong>Pelanggan</strong></td>
                            <td style="width: 5%;">:</td>
                            <td><?= $pelanggan['nama'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Telepon</strong></td>
                            <td>:</td>
                            <td><?= $pelanggan['telepon'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Hewan</strong></td>
                            <td>:</td>
                            <td><?= $hewan['nama'] ?> (<?= $hewan['jenis'] ?>)</td>
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

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">No</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: left;">Nama Fasilitas</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Kategori</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: right;">Harga</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Satuan</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Jumlah</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($detail_penitipan as $detail) : ?>
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $i++ ?></td>
                        <td style="border: 1px solid #000; padding: 8px;"><?= $detail['namafasilitas'] ?></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $detail['kategori'] ?></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $detail['satuan'] ?></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $detail['jumlah'] ?></td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="border: 1px solid #000; padding: 8px; text-align: right;"><strong>Total Fasilitas:</strong></td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($total_fasilitas, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="6" style="border: 1px solid #000; padding: 8px; text-align: right;"><strong>Durasi:</strong></td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: right;"><?= $penitipan['durasi'] ?> Hari</td>
                </tr>
                <tr>
                    <td colspan="6" style="border: 1px solid #000; padding: 8px; text-align: right;"><strong>Total Biaya:</strong></td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: right;"><strong>Rp <?= number_format($penitipan['total'], 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <div style="display: flex; justify-content: space-between; margin-top: 50px;">
            <div style="text-align: center; width: 200px;">
                <p>Pelanggan</p>
                <br><br><br>
                <p><?= $pelanggan['nama'] ?></p>
            </div>
            <div style="text-align: center; width: 200px;">
                <p>Petugas</p>
                <br><br><br>
                <p>_________________</p>
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center; font-style: italic;">
            <p>Terima kasih telah mempercayakan hewan kesayangan Anda kepada kami!</p>
            <p>Nana Cat Shop - Merawat dengan Sepenuh Hati</p>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Cetak faktur
        $('#btn-print').on('click', function() {
            let printContents = document.getElementById('print-area').innerHTML;
            let originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

            // Inisialisasi ulang event handler setelah print
            setTimeout(function() {
                $('#btn-print').on('click', function() {
                    let printContents = document.getElementById('print-area').innerHTML;
                    let originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;

                    window.print();

                    document.body.innerHTML = originalContents;
                });
            }, 1000);
        });
    });
</script>

<style>
    @media print {
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Pastikan konten tidak terpotong */
        @page {
            size: A4;
            margin: 1cm;
        }
    }
</style>
<?= $this->endSection(); ?>