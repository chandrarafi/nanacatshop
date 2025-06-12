<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Detail Hewan</h1>
        <p class="mb-0 text-secondary">Informasi lengkap data hewan <?= $hewan['namahewan'] ?></p>
    </div>
    <div>
        <a href="<?= site_url('admin/hewan/edit/' . $hewan['idhewan']) ?>" class="btn btn-info me-2">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <a href="<?= site_url('admin/hewan') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center p-5">
                <?php if (!empty($hewan['foto']) && file_exists(FCPATH . 'uploads/hewan/' . $hewan['foto'])) : ?>
                    <img src="<?= base_url('uploads/hewan/' . $hewan['foto']) ?>" class="img-fluid rounded mb-3" style="max-height: 200px;">
                <?php else : ?>
                    <img src="<?= base_url('assets/img/cat-default.webp') ?>" class="img-fluid rounded mb-3" style="max-height: 200px;">
                <?php endif; ?>
                <h4 class="card-title"><?= $hewan['namahewan'] ?></h4>
                <p class="text-muted mb-1">ID: <?= $hewan['idhewan'] ?></p>
                <div class="mt-3">
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <?= ($hewan['jenis'] == '1') ? 'Kucing' : 'Anjing' ?>
                    </span>
                    <span class="badge bg-info rounded-pill px-3 py-2">
                        <?= ($hewan['jenkel'] == 'L') ? 'Laki-laki' : 'Perempuan' ?>
                    </span>
                    <?php if (!empty($hewan['umur'])) : ?>
                        <span class="badge bg-secondary rounded-pill px-3 py-2">
                            <?php
                            $umur = $hewan['umur'];
                            $satuan = $hewan['satuan_umur'] ?? 'tahun';

                            if ($satuan == 'bulan' && $umur >= 12) {
                                $tahun = floor($umur / 12);
                                $bulan = $umur % 12;

                                if ($bulan > 0) {
                                    echo $tahun . ' Tahun ' . $bulan . ' Bulan';
                                } else {
                                    echo $tahun . ' Tahun';
                                }
                            } else {
                                echo $umur . ' ' . ucfirst($satuan);
                            }
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informasi Hewan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%" class="bg-light">ID Hewan</th>
                            <td><?= $hewan['idhewan'] ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama Hewan</th>
                            <td><?= $hewan['namahewan'] ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jenis Hewan</th>
                            <td><?= ($hewan['jenis'] == '1') ? 'Kucing' : 'Anjing' ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jenis Kelamin</th>
                            <td><?= ($hewan['jenkel'] == 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Umur</th>
                            <td>
                                <?php
                                if (!empty($hewan['umur'])) {
                                    $umur = $hewan['umur'];
                                    $satuan = $hewan['satuan_umur'] ?? 'tahun';

                                    if ($satuan == 'bulan' && $umur >= 12) {
                                        $tahun = floor($umur / 12);
                                        $bulan = $umur % 12;

                                        if ($bulan > 0) {
                                            echo $tahun . ' Tahun ' . $bulan . ' Bulan';
                                        } else {
                                            echo $tahun . ' Tahun';
                                        }
                                    } else {
                                        echo $umur . ' ' . ucfirst($satuan);
                                    }
                                } else {
                                    echo 'Tidak diketahui';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Tanggal Dibuat</th>
                            <td><?= date('d-m-Y H:i', strtotime($hewan['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Terakhir Diperbarui</th>
                            <td><?= date('d-m-Y H:i', strtotime($hewan['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Informasi Pemilik</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%" class="bg-light">ID Pelanggan</th>
                            <td><?= $pelanggan['idpelanggan'] ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama Pelanggan</th>
                            <td><?= $pelanggan['nama'] ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jenis Kelamin</th>
                            <td><?= ($pelanggan['jenkel'] == 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Alamat</th>
                            <td><?= $pelanggan['alamat'] ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">No. HP</th>
                            <td><?= $pelanggan['nohp'] ?></td>
                        </tr>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="<?= site_url('admin/pelanggan') ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-people me-1"></i> Lihat Semua Pelanggan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles for this page -->
<?= $this->section('styles') ?>
<style>
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: none;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .card-header {
        border-radius: calc(0.5rem - 1px) calc(0.5rem - 1px) 0 0;
        padding: 1rem 1.25rem;
    }

    .bg-primary {
        background-color: #FF69B4 !important;
    }

    .bg-info {
        background-color: #5bc0de !important;
    }

    .btn-info {
        background-color: #5bc0de;
        border-color: #5bc0de;
        color: white;
    }

    .btn-primary {
        background-color: #FF69B4;
        border-color: #FF69B4;
    }

    .btn-secondary {
        background-color: #999;
        border-color: #999;
    }

    .table th {
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        font-weight: 500;
        margin-right: 0.25rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->endSection() ?>