<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Booking</h1>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Jadwal</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $b): ?>
                            <?php
                            $statusClass = 'bg-secondary';
                            if (($b['status'] ?? '') === 'confirmed') $statusClass = 'bg-primary';
                            if (($b['status'] ?? '') === 'completed') $statusClass = 'bg-success';
                            if (($b['status'] ?? '') === 'cancelled') $statusClass = 'bg-danger';
                            $payClass = !empty($b['payment_proof']) ? 'bg-success' : 'bg-warning text-dark';
                            ?>
                            <tr>
                                <td class="text-muted">#<?= $b['id'] ?></td>
                                <td><span class="fw-semibold">User #<?= $b['user_id'] ?></span></td>
                                <td>
                                    <div class="fw-semibold text-dark mb-1" style="max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($b['service_name']) ?>"><?= esc($b['service_name']) ?></div>
                                </td>
                                <td>
                                    <div><?= date('d M Y', strtotime($b['booking_date'])) ?></div>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i><?= substr($b['booking_time'], 0, 5) ?> WIB</small>
                                </td>
                                <td class="fw-semibold text-dark"><?= rupiah($b['price']) ?></td>
                                <td>
                                    <div class="mb-1"><span class="badge bg-light text-dark border"><?= $b['payment_type'] ? strtoupper($b['payment_type']) : '-' ?></span></div>
                                    <?php if (!empty($b['payment_proof'])): ?>
                                        <a href="<?= base_url($b['payment_proof']) ?>" target="_blank" class="badge <?= $payClass ?> text-decoration-none"><i class="bi bi-receipt"></i> Lihat Bukti</a>
                                    <?php else: ?>
                                        <span class="badge <?= $payClass ?>"><i class="bi bi-exclamation-circle"></i> Belum Ada</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge <?= $statusClass ?>"><?= ucfirst($b['status']) ?></span></td>
                                <td class="text-center">
                                    <a href="<?= site_url('admin/bookings/' . $b['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    <a href="<?= site_url('admin/bookings/' . $b['id'] . '/invoice') ?>" class="btn btn-sm btn-outline-success ms-1"><i class="bi bi-file-earmark-text"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            order: [
                [0, 'desc']
            ],
            pageLength: 10,
            columnDefs: [{
                targets: [7],
                orderable: false
            }]
        });
    });
</script>
<?= $this->endSection() ?>