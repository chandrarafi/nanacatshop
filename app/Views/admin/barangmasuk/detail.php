<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Barang Masuk</h3>
                <p class="text-subtitle text-muted">Detail data barang masuk</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/barangmasuk') ?>">Barang Masuk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Informasi Barang Masuk</h4>
                            <div>
                                <a href="<?= site_url('admin/barangmasuk') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <a href="<?= site_url('admin/barangmasuk/edit/' . $barangMasuk['kdmasuk']) ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="btn <?= $barangMasuk['status'] == 1 ? 'btn-warning' : 'btn-success' ?>" id="btn-change-status" data-id="<?= $barangMasuk['kdmasuk'] ?>" data-status="<?= $barangMasuk['status'] ?>">
                                    <i class="bi bi-check-circle"></i> <?= $barangMasuk['status'] == 1 ? 'Set Pending' : 'Set Selesai' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%">Kode Barang Masuk</td>
                                        <td width="5%">:</td>
                                        <td><?= $barangMasuk['kdmasuk'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Masuk</td>
                                        <td>:</td>
                                        <td><?= date('d/m/Y', strtotime($barangMasuk['tglmasuk'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td>
                                            <?php if ($barangMasuk['status'] == 1) : ?>
                                                <span class="badge bg-success">Selesai</span>
                                            <?php else : ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%">Supplier</td>
                                        <td width="5%">:</td>
                                        <td><?= $barangMasuk['namaspl'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>:</td>
                                        <td>Rp <?= number_format($barangMasuk['grandtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td>:</td>
                                        <td><?= $barangMasuk['keterangan'] ?? '-' ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Detail Barang</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Kode Barang</th>
                                        <th width="25%">Nama Barang</th>
                                        <th width="15%">Kategori</th>
                                        <th width="10%">Jumlah</th>
                                        <th width="15%">Harga</th>
                                        <th width="15%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($detailBarangMasuk)) : ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data detail barang</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($detailBarangMasuk as $index => $detail) : ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $detail['detailkdbarang'] ?></td>
                                                <td><?= $detail['namabarang'] ?></td>
                                                <td><?= $detail['namakategori'] ?? '-' ?></td>
                                                <td><?= $detail['jumlah'] ?></td>
                                                <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-end">Grand Total</th>
                                        <th>Rp <?= number_format($barangMasuk['grandtotal'], 0, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Konfirmasi Ubah Status -->
<div class="modal fade" id="modalChangeStatus" tabindex="-1" role="dialog" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChangeStatusLabel">Konfirmasi Ubah Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengubah status barang masuk ini?</p>
                <p id="status-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-confirm-change-status">Ubah Status</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#btn-change-status').click(function() {
            const id = $(this).data('id');
            const status = $(this).data('status');
            const statusText = status == 1 ? 'Pending' : 'Selesai';
            const statusDesc = status == 1 ?
                'Mengubah status menjadi Pending akan mengurangi stok barang yang telah ditambahkan.' :
                'Mengubah status menjadi Selesai akan menambahkan stok barang sesuai dengan jumlah yang diinput.';

            $('#status-message').text(statusDesc);

            $('#modalChangeStatus').modal('show');

            $('#btn-confirm-change-status').off('click').on('click', function() {
                $.ajax({
                    url: '<?= site_url('admin/barangmasuk/changeStatus/') ?>' + id,
                    method: 'POST',
                    success: function(response) {
                        $('#modalChangeStatus').modal('hide');
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#modalChangeStatus').modal('hide');
                        let response = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response?.message || 'Terjadi kesalahan saat mengubah status'
                        });
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>