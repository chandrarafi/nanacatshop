<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Booking #<?= $booking['id'] ?></h1>
            <div class="mt-2">
                <?php
                $statusClass = 'bg-secondary';
                if (($booking['status'] ?? '') === 'confirmed') $statusClass = 'bg-primary';
                if (($booking['status'] ?? '') === 'completed') $statusClass = 'bg-success';
                if (($booking['status'] ?? '') === 'cancelled') $statusClass = 'bg-danger';
                ?>
                <span class="badge <?= $statusClass ?> me-2">Status: <?= ucfirst($booking['status']) ?></span>
                <span class="badge bg-light text-dark border me-2">Pembayaran: <?= !empty($booking['payment_type']) ? strtoupper($booking['payment_type']) : '-' ?></span>
                <span class="badge bg-info text-dark">Bank: <?= !empty($booking['payment_bank']) ? esc($booking['payment_bank']) : '-' ?></span>
                <span class="badge bg-light text-dark border">User #<?= $booking['user_id'] ?></span>
            </div>
        </div>
        <a href="<?= site_url('admin/bookings') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header"><strong>Informasi Booking</strong></div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Pelanggan</div>
                        <div class="col-8">
                            <div><strong><?= esc($pelanggan['nama'] ?? ('User #' . $booking['user_id'])) ?></strong></div>
                            <div class="text-muted small">HP: <?= esc($pelanggan['nohp'] ?? '-') ?> • Alamat: <?= esc($pelanggan['alamat'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Layanan</div>
                        <div class="col-8">
                            <?php if (!empty($items)): ?>
                                <ul class="mb-0">
                                    <?php foreach ($items as $it): ?>
                                        <li class="d-flex justify-content-between"><span><?= esc($it['name']) ?></span><span><?= $it['price'] !== null ? rupiah($it['price']) : '' ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <?= esc($booking['service_name']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Harga</div>
                        <div class="col-8"><?= rupiah($booking['price']) ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Pembayaran</div>
                        <div class="col-8">
                            <div><?= !empty($booking['payment_type']) ? strtoupper($booking['payment_type']) : '-' ?></div>
                            <div class="text-muted small">Bank: <?= !empty($booking['payment_bank']) ? esc($booking['payment_bank']) : '-' ?></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Bukti Pembayaran</div>
                        <div class="col-8">
                            <?php if (!empty($booking['payment_proof'])): ?>
                                <a href="<?= base_url($booking['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-success">Lihat Bukti</a>
                            <?php else: ?>
                                <span class="text-muted">Tidak ada</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Tanggal</div>
                        <div class="col-8"><?= date('d/m/Y', strtotime($booking['booking_date'])) ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 text-muted">Waktu</div>
                        <div class="col-8"><?= substr($booking['booking_time'], 0, 5) ?> WIB</div>
                    </div>
                    <div class="row">
                        <div class="col-4 text-muted">Catatan</div>
                        <div class="col-8"><?= esc($booking['notes'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center"><strong>Bukti Pembayaran</strong>
                    <?php if (!empty($booking['payment_proof'])): ?>
                        <a href="<?= base_url($booking['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-up-right"></i></a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($booking['payment_proof'])): ?>
                        <?php $ext = strtolower(pathinfo($booking['payment_proof'], PATHINFO_EXTENSION)); ?>
                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                            <img src="<?= base_url($booking['payment_proof']) ?>" alt="Payment Proof" class="img-fluid rounded border">
                        <?php elseif ($ext === 'pdf'): ?>
                            <embed src="<?= base_url($booking['payment_proof']) ?>" type="application/pdf" width="100%" height="420px" />
                        <?php else: ?>
                            <a href="<?= base_url($booking['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Buka Bukti</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-muted">Tidak ada bukti pembayaran diunggah.</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header"><strong>Ubah Status</strong></div>
                <div class="card-body">
                    <form method="post" action="<?= site_url('admin/bookings/' . $booking['id'] . '/status') ?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="completed" <?= $booking['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <?php if (($booking['payment_type'] ?? 'dp') === 'dp'): ?>
                            <div class="alert alert-warning py-2">
                                Status pembayaran saat ini DP. Untuk menyelesaikan booking, unggah bukti pelunasan atau centang sudah lunas.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bukti Pelunasan (opsional)</label>
                                <input type="file" name="payment_proof" accept="image/jpeg,image/png,application/pdf" class="form-control" />
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="mark_lunas" name="mark_lunas" value="1">
                                <label class="form-check-label" for="mark_lunas">Tandai sudah lunas tanpa bukti</label>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>