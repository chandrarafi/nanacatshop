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
                <a href="<?= site_url('admin/penitipan/cetak/' . $penitipan['kdpenitipan']) ?>" class="btn btn-sm btn-primary me-2" target="_blank">
                    <i class="fas fa-print"></i> Cetak Faktur
                </a>
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
                            <td><?= date('d-m-Y', strtotime($penitipan['tglpenitipan'])) ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Keluar</td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($penitipan['tglselesai'])) ?></td>
                        </tr>
                        <?php if (!empty($penitipan['tglpenjemputan'])) : ?>
                            <tr>
                                <td>Tanggal Penjemputan</td>
                                <td>:</td>
                                <td><?= date('d-m-Y', strtotime($penitipan['tglpenjemputan'])) ?></td>
                            </tr>
                        <?php endif; ?>
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
                            <td><strong><?= $pelanggan['nama'] ?? 'Data tidak tersedia' ?></strong></td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>:</td>
                            <td><?= $pelanggan['telepon'] ?? 'Data tidak tersedia' ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td><?= $pelanggan['alamat'] ?? 'Data tidak tersedia' ?></td>
                        </tr>
                        <tr>
                            <td>Nama Hewan</td>
                            <td>:</td>
                            <td>
                                <?php if (!empty($detailPenitipan) && !empty($detailPenitipan[0]['nama_hewan'])): ?>
                                    <strong><?= $detailPenitipan[0]['nama_hewan'] ?></strong>
                                    (<?= $detailPenitipan[0]['jenis_hewan'] ?>)
                                <?php else: ?>
                                    Data tidak tersedia
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Catatan</td>
                            <td>:</td>
                            <td><?= $penitipan['keterangan'] ?: '-' ?></td>
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
                        <?php
                        $i = 1;
                        $totalFasilitas = 0;
                        foreach ($detailPenitipan as $detail):
                            $totalFasilitas += $detail['totalharga'];
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $detail['namafasilitas'] ?? 'Data tidak tersedia' ?></td>
                                <td><?= $detail['kategori'] ?? 'Data tidak tersedia' ?></td>
                                <td class="text-end">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                <td><?= $detail['satuan'] ?? 'Data tidak tersedia' ?></td>
                                <td class="text-center"><?= $detail['jumlah'] ?></td>
                                <td class="text-end">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($detailPenitipan)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data fasilitas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Total Fasilitas:</strong></td>
                            <td class="text-end">Rp <?= number_format($totalFasilitas, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Durasi:</strong></td>
                            <td class="text-end"><?= $penitipan['durasi'] ?> Hari</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>Total Biaya:</strong></td>
                            <td class="text-end"><strong>Rp <?= number_format($penitipan['grandtotal'], 0, ',', '.') ?></strong></td>
                        </tr>
                        <?php if (!empty($penitipan['tglpenjemputan']) && $penitipan['is_terlambat'] == 1) : ?>
                            <tr class="table-warning">
                                <td colspan="6" class="text-end"><strong>Keterlambatan:</strong></td>
                                <td class="text-end"><?= $penitipan['jumlah_hari_terlambat'] ?> Hari</td>
                            </tr>
                            <tr class="table-warning">
                                <td colspan="6" class="text-end"><strong>Biaya Denda (50% per hari):</strong></td>
                                <td class="text-end">Rp <?= number_format($penitipan['biaya_denda'], 0, ',', '.') ?></td>
                            </tr>
                            <tr class="table-warning">
                                <td colspan="6" class="text-end"><strong>Total Biaya dengan Denda:</strong></td>
                                <td class="text-end"><strong>Rp <?= number_format($penitipan['total_biaya_dengan_denda'], 0, ',', '.') ?></strong></td>
                            </tr>
                        <?php endif; ?>
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

            <div class="mt-4">
                <?php if ($penitipan['status'] == 1 && empty($penitipan['tglpenjemputan'])) : ?>
                    <button class="btn btn-success" id="btn-penjemputan" data-bs-toggle="modal" data-bs-target="#modalPenjemputan">
                        <i class="fas fa-home"></i> Proses Penjemputan
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Penjemputan -->
    <div class="modal fade" id="modalPenjemputan" tabindex="-1" aria-labelledby="modalPenjemputanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenjemputanLabel">Form Penjemputan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-penjemputan">
                        <input type="hidden" name="kdpenitipan" value="<?= $penitipan['kdpenitipan'] ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tglpenjemputan" class="form-label">Tanggal Penjemputan</label>
                                    <input type="date" class="form-control" id="tglpenjemputan" name="tglpenjemputan" value="<?= date('Y-m-d') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-info text-white" id="btn-hitung-denda">
                                        <i class="fas fa-calculator"></i> Hitung Denda
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="alert alert-info mb-3">
                                    <h5 class="alert-heading">Informasi Denda</h5>
                                    <p class="mb-0">Keterlambatan penjemputan akan dikenakan denda sebesar 50% dari total biaya penitipan per hari.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Perhitungan Denda -->
                        <div id="hasil-denda" style="display: none;">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Hasil Perhitungan Denda</h5>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>Tanggal Seharusnya</td>
                                                    <td>:</td>
                                                    <td><span id="tgl-selesai"><?= date('d-m-Y', strtotime($penitipan['tglselesai'])) ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Penjemputan</td>
                                                    <td>:</td>
                                                    <td><span id="tgl-jemput"></span></td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>:</td>
                                                    <td><span id="status-keterlambatan"></span></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>Keterlambatan</td>
                                                    <td>:</td>
                                                    <td><span id="hari-terlambat">0</span> Hari</td>
                                                </tr>
                                                <tr>
                                                    <td>Biaya Denda</td>
                                                    <td>:</td>
                                                    <td>Rp <span id="biaya-denda">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Biaya</td>
                                                    <td>:</td>
                                                    <td>Rp <span id="total-biaya-denda">0</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btn-simpan-penjemputan">Simpan Penjemputan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Hitung denda keterlambatan
        $('#btn-hitung-denda').click(function() {
            let kdpenitipan = $('input[name="kdpenitipan"]').val();
            let tglpenjemputan = $('#tglpenjemputan').val();

            if (!tglpenjemputan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Tanggal penjemputan harus diisi',
                });
                return;
            }

            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Hitung denda
            $.ajax({
                url: '<?= site_url('admin/penitipan/hitungDenda') ?>',
                type: 'POST',
                data: {
                    kdpenitipan: kdpenitipan,
                    tglpenjemputan: tglpenjemputan
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        let data = response.data;

                        // Format tanggal penjemputan
                        let tglJemput = new Date(tglpenjemputan);
                        let formattedDate = tglJemput.getDate().toString().padStart(2, '0') + '-' +
                            (tglJemput.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            tglJemput.getFullYear();

                        // Tampilkan hasil perhitungan
                        $('#tgl-jemput').text(formattedDate);
                        $('#hari-terlambat').text(data.jumlah_hari_terlambat);
                        $('#biaya-denda').text(data.biaya_denda.toLocaleString('id-ID'));
                        $('#total-biaya-denda').text(data.total_biaya_dengan_denda.toLocaleString('id-ID'));

                        if (data.is_terlambat) {
                            $('#status-keterlambatan').html('<span class="badge bg-danger">Terlambat</span>');
                        } else {
                            $('#status-keterlambatan').html('<span class="badge bg-success">Tepat Waktu</span>');
                        }

                        // Tampilkan hasil
                        $('#hasil-denda').slideDown();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + error,
                    });
                }
            });
        });

        // Submit form penjemputan
        $('#btn-simpan-penjemputan').click(function() {
            let kdpenitipan = $('input[name="kdpenitipan"]').val();
            let tglpenjemputan = $('#tglpenjemputan').val();

            if (!tglpenjemputan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Tanggal penjemputan harus diisi',
                });
                return;
            }

            // Cek apakah denda sudah dihitung
            if ($('#hasil-denda').is(':hidden')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan hitung denda terlebih dahulu sebelum menyimpan penjemputan',
                });
                return;
            }

            // Konfirmasi
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan data penjemputan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Simpan data penjemputan
                    $.ajax({
                        url: '<?= site_url('admin/penitipan/penjemputan') ?>',
                        type: 'POST',
                        data: {
                            kdpenitipan: kdpenitipan,
                            tglpenjemputan: tglpenjemputan
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan: ' + error,
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>