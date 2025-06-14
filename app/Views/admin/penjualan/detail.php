<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/penjualan') ?>">Penjualan</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Detail Penjualan
            <div class="float-end">
                <a href="<?= site_url('admin/penjualan') ?>" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="<?= site_url('admin/penjualan/cetak/' . $penjualan['kdpenjualan']) ?>" class="btn btn-sm btn-primary" target="_blank">
                    <i class="fas fa-print"></i> Cetak Faktur
                </a>
                <a href="<?= site_url('admin/penjualan/edit/' . $penjualan['kdpenjualan']) ?>" class="btn btn-sm btn-info">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button type="button" class="btn btn-sm <?= $penjualan['status'] == 1 ? 'btn-warning' : 'btn-success' ?>" id="btn-change-status">
                    <i class="fas fa-exchange-alt"></i> Ubah Status ke <?= $penjualan['status'] == 1 ? 'Pending' : 'Selesai' ?>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informasi Penjualan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="35%">Kode Penjualan</td>
                            <td width="5%">:</td>
                            <td><?= $penjualan['kdpenjualan'] ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Penjualan</td>
                            <td>:</td>
                            <td><?= date('d/m/Y', strtotime($penjualan['tglpenjualan'])) ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>
                                <?php if ($penjualan['status'] == 1) : ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php else : ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Informasi Pelanggan</h5>
                    <table class="table table-borderless">
                        <?php if (!empty($penjualan['idpelanggan'])) : ?>
                            <tr>
                                <td width="35%">ID Pelanggan</td>
                                <td width="5%">:</td>
                                <td><?= $penjualan['idpelanggan'] ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td width="35%">Nama Pelanggan</td>
                            <td width="5%">:</td>
                            <td><?= !empty($penjualan['idpelanggan']) ? $penjualan['nama'] : 'Pelanggan Umum' ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5>Detail Barang</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($detailPenjualan as $detail) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $detail['detailkdbarang'] ?></td>
                                <td><?= $detail['namabarang'] ?></td>
                                <td><?= $detail['namakategori'] ?></td>
                                <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                <td><?= $detail['jumlah'] ?></td>
                                <td>Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">Grand Total</th>
                            <th>Rp <?= number_format($penjualan['grandtotal'], 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#btn-change-status').click(function() {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin mengubah status penjualan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah Status',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url('admin/penjualan/changeStatus/' . $penjualan['kdpenjualan']) ?>',
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan saat mengubah status'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>