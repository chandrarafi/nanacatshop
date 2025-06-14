<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/perawatan') ?>">Perawatan</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-info-circle me-1"></i> Informasi Perawatan</h5>
            <div>
                <a href="<?= site_url('admin/perawatan/edit/' . $perawatan['kdperawatan']) ?>" class="btn btn-sm btn-primary me-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <?php if ($perawatan['status'] == 2) : ?>
                    <a href="<?= site_url('admin/perawatan/cetak/' . $perawatan['kdperawatan']) ?>" class="btn btn-sm btn-success" target="_blank">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                <?php else : ?>
                    <span class="text-muted ms-2" style="font-size: 0.875rem;">
                        <i class="fas fa-info-circle"></i> Cetak faktur tersedia setelah status perawatan selesai
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2">Data Perawatan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="35%">Kode Perawatan</td>
                            <td width="5%">:</td>
                            <td><strong><?= $perawatan['kdperawatan'] ?></strong></td>
                        </tr>
                        <tr>
                            <td>Tanggal Perawatan</td>
                            <td>:</td>
                            <td><?= date('d/m/Y', strtotime($perawatan['tglperawatan'])) ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span id="status-badge" class="badge <?= (new \App\Models\PerawatanModel())->getStatusBadgeClass($perawatan['status']) ?> me-2">
                                        <?= (new \App\Models\PerawatanModel())->getStatusLabel($perawatan['status']) ?>
                                    </span>
                                    <?php if ($perawatan['status'] != 2) : ?>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUbahStatus">
                                            Ubah Status
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Biaya</td>
                            <td>:</td>
                            <td><strong>Rp <?= number_format($perawatan['grandtotal'], 0, ',', '.') ?></strong></td>
                        </tr>
                        <?php if (!empty($perawatan['keterangan'])) : ?>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td><?= $perawatan['keterangan'] ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2">Data Pelanggan & Hewan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="35%">Nama Pelanggan</td>
                            <td width="5%">:</td>
                            <td><strong><?= $perawatan['nama_pelanggan'] ?? 'Pelanggan Umum' ?></strong></td>
                        </tr>
                        <?php if (!empty($perawatan['nohp'])) : ?>
                            <tr>
                                <td>No. HP</td>
                                <td>:</td>
                                <td><?= $perawatan['nohp'] ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($perawatan['alamat'])) : ?>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td><?= $perawatan['alamat'] ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($perawatan['namahewan'])) : ?>
                            <tr>
                                <td>Nama Hewan</td>
                                <td>:</td>
                                <td><strong><?= $perawatan['namahewan'] ?></strong></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($perawatan['jenis'])) : ?>
                            <tr>
                                <td>Jenis Hewan</td>
                                <td>:</td>
                                <td><?= $perawatan['jenis'] ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-list me-1"></i> Detail Fasilitas Perawatan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Fasilitas</th>
                            <th width="15%">Harga</th>
                            <th width="10%">Satuan</th>
                            <th width="10%">Jumlah</th>
                            <th width="20%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($detail_perawatan as $detail) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= $detail['namafasilitas'] ?></strong>
                                    <?php if (!empty($detail['kategori'])) : ?>
                                        <br><small class="text-muted">Kategori: <?= $detail['kategori'] ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                <td><?= $detail['satuan'] ?></td>
                                <td class="text-center"><?= $detail['jumlah'] ?></td>
                                <td class="text-end">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end"><strong>Rp <?= number_format($perawatan['grandtotal'], 0, ',', '.') ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <a href="<?= site_url('admin/perawatan') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Modal Ubah Status Perawatan -->
<div class="modal fade" id="modalUbahStatus" tabindex="-1" aria-labelledby="modalUbahStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUbahStatusLabel">Ubah Status Perawatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUbahStatus">
                    <input type="hidden" id="kdperawatan" value="<?= $perawatan['kdperawatan'] ?>">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status">
                            <option value="0" <?= $perawatan['status'] == 0 ? 'selected' : '' ?>>Menunggu</option>
                            <option value="1" <?= $perawatan['status'] == 1 ? 'selected' : '' ?>>Dalam Proses</option>
                            <option value="2" <?= $perawatan['status'] == 2 ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanStatus">Simpan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Ubah status perawatan
        $('#btnSimpanStatus').click(function() {
            const kdperawatan = $('#kdperawatan').val();
            const status = $('#status').val();

            $.ajax({
                url: '<?= site_url('admin/perawatan/updateStatus') ?>',
                type: 'POST',
                data: {
                    kdperawatan: kdperawatan,
                    status: status
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Update badge
                        const badgeClasses = {
                            '0': 'bg-warning',
                            '1': 'bg-primary',
                            '2': 'bg-success'
                        };

                        const statusLabels = {
                            '0': 'Menunggu',
                            '1': 'Dalam Proses',
                            '2': 'Selesai'
                        };

                        $('#status-badge').removeClass('bg-warning bg-primary bg-success')
                            .addClass(badgeClasses[status])
                            .text(statusLabels[status]);

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Jika status diubah menjadi selesai (2), buka halaman cetak di tab baru
                            if (response.print_url) {
                                window.open(response.print_url, '_blank');
                            }

                            // Reload halaman untuk memperbarui tampilan tombol
                            location.reload();
                        });

                        $('#modalUbahStatus').modal('hide');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memperbarui status'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>