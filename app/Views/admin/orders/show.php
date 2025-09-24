<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .btn-pink {
        background-color: #F564A9;
        color: #fff;
    }

    .btn-pink:hover {
        background-color: #FAA4BD;
        color: #fff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-receipt text-primary"></i>
            <h5 class="mb-0">Detail Pesanan #<?= esc($order['order_number']) ?></h5>
        </div>
        <a href="<?= site_url('admin/orders') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>
    <div class="card-body">

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header"><strong>Informasi Pesanan</strong></div>
                    <div class="card-body small">
                        <div class="row g-2">
                            <div class="col-md-6"><span class="text-muted">Pelanggan:</span> <?= esc($order['pelanggan_id']) ?></div>
                            <div class="col-md-6"><span class="text-muted">Metode Pembayaran:</span> <?= esc(ucfirst($order['payment_method'])) ?><?= !empty($order['payment_bank']) ? ' • Bank: ' . esc($order['payment_bank']) : '' ?></div>
                            <div class="col-12"><span class="text-muted">Alamat:</span> <?= esc($order['shipping_address']) ?></div>
                            <?php if (!empty($order['notes'])): ?>
                                <div class="col-12"><span class="text-muted">Catatan:</span> <?= esc($order['notes']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><strong>Item Pesanan</strong></div>
                    <div class="card-body">
                        <?php foreach ($order['details'] as $detail): ?>
                            <div class="d-flex justify-content-between border-bottom py-2 small">
                                <div>
                                    <div class="fw-semibold"><?= esc($detail['namabarang']) ?></div>
                                    <div class="text-muted"><?= esc($detail['quantity']) ?> x <?= rupiah($detail['price']) ?></div>
                                </div>
                                <div class="fw-semibold"><?= rupiah($detail['subtotal']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (!empty($order['payment_proof'])): ?>
                    <div class="card mb-4">
                        <div class="card-header"><strong>Bukti Pembayaran</strong></div>
                        <div class="card-body">
                            <?php $isImage = preg_match('/\.(jpg|jpeg|png)$/i', $order['payment_proof']); ?>
                            <?php if ($isImage): ?>
                                <img src="<?= base_url($order['payment_proof']) ?>" alt="Bukti Pembayaran" class="img-fluid rounded border">
                            <?php else: ?>
                                <a href="<?= base_url($order['payment_proof']) ?>" target="_blank" class="link-primary">Lihat Bukti (PDF)</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header"><strong>Timeline Pesanan</strong></div>
                    <div class="card-body">
                        <?php
                        $steps = [
                            ['key' => 'created', 'label' => 'Pesanan Dibuat', 'time' => $order['created_at'] ?? null],
                            ['key' => 'processing', 'label' => 'Diproses', 'time' => ($order['status'] === 'processing' || $order['status'] === 'shipped' || $order['status'] === 'delivered') ? ($order['updated_at'] ?? null) : null],
                            ['key' => 'shipped', 'label' => 'Dikirim', 'time' => $order['shipped_at'] ?? null],
                            ['key' => 'delivered', 'label' => 'Selesai', 'time' => ($order['status'] === 'delivered') ? ($order['updated_at'] ?? null) : null],
                        ];
                        if ($order['status'] === 'cancelled') {
                            $steps[] = ['key' => 'cancelled', 'label' => 'Dibatalkan', 'time' => $order['updated_at'] ?? null];
                        }
                        ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($steps as $s): if (!$s['time']) continue; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center small">
                                    <div>
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong><?= $s['label'] ?></strong>
                                        <?php if ($s['key'] === 'shipped' && !empty($order['courier'])): ?>
                                            <span class="ms-2 text-muted">(<?= esc($order['courier']) ?> • <?= esc($order['tracking_number'] ?? '-') ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-muted"><?= date('d M Y H:i', strtotime($s['time'])) ?></span>
                                </li>
                            <?php endforeach; ?>
                            <?php if (array_filter(array_map(fn($x) => $x['time'] ?? null, $steps)) === []): ?>
                                <li class="list-group-item text-muted small">Belum ada aktivitas</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card sticky-top" style="top:1rem">
                    <div class="card-header"><strong>Ringkasan</strong></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Total</span><span class="fw-semibold"><?= rupiah($order['total_amount']) ?></span></div>
                        <div class="d-flex justify-content-between mb-3 small">
                            <span class="text-muted">Status</span>
                            <span class="badge <?php
                                                switch ($order['status']) {
                                                    case 'pending':
                                                        echo 'bg-warning text-dark';
                                                        break;
                                                    case 'processing':
                                                        echo 'bg-info text-dark';
                                                        break;
                                                    case 'shipped':
                                                        echo 'bg-primary';
                                                        break;
                                                    case 'delivered':
                                                        echo 'bg-success';
                                                        break;
                                                    case 'cancelled':
                                                        echo 'bg-danger';
                                                        break;
                                                    default:
                                                        echo 'bg-secondary';
                                                }
                                                ?>"><?= esc(ucfirst($order['status'])) ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ubah Status</label>
                            <select id="status" class="form-select">
                                <?php $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                                foreach ($statuses as $st): ?>
                                    <option value="<?= $st ?>" <?= $order['status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="shippingFields" class="mb-3" style="display:none;">
                            <div class="mb-2">
                                <label class="form-label">Kurir</label>
                                <input type="text" id="courier" class="form-control" placeholder="Contoh: JNE / J&T / SiCepat" value="<?= esc($order['courier'] ?? '') ?>">
                            </div>
                            <div>
                                <label class="form-label">Nomor Resi</label>
                                <input type="text" id="tracking_number" class="form-control" placeholder="Masukkan nomor resi" value="<?= esc($order['tracking_number'] ?? '') ?>">
                            </div>
                            <?php if (!empty($order['shipped_at'])): ?>
                                <small class="text-muted">Dikirim pada: <?= esc($order['shipped_at']) ?></small>
                            <?php endif; ?>
                        </div>

                        <button id="btnUpdateStatus" class="btn btn-pink w-100"><i class="bi bi-save me-1"></i>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleShippingFields() {
        if ($('#status').val() === 'shipped') {
            $('#shippingFields').slideDown(150);
        } else {
            $('#shippingFields').slideUp(150);
        }
    }
    toggleShippingFields();
    $('#status').on('change', toggleShippingFields);

    $('#btnUpdateStatus').on('click', function() {
        const status = $('#status').val();
        const courier = $('#courier').val();
        const tracking_number = $('#tracking_number').val();
        $.ajax({
            url: '<?= site_url('admin/orders/' . $order['id'] . '/status') ?>',
            type: 'POST',
            data: {
                status,
                courier,
                tracking_number
            },
            dataType: 'json',
            success: function(resp) {
                Swal.fire({
                        icon: resp.status === 'success' ? 'success' : 'error',
                        text: resp.message || 'Berhasil'
                    })
                    .then(() => {
                        if (resp.status === 'success') {
                            location.reload();
                        }
                    });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Gagal memperbarui status'
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>