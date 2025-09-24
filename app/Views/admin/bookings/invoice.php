<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>

<style>
    @media print {

        /* Hide admin chrome when printing */
        .sidebar,
        .topbar,
        .navbar,
        .breadcrumb,
        .btn,
        .d-print-none {
            display: none !important;
        }

        .container-fluid {
            margin: 0 !important;
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Invoice Booking #<?= $booking['id'] ?></h1>
        <a href="<?= site_url('admin/bookings/' . $booking['id']) ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="mb-1">Nana Cat Shop</h5>
                    <div class="text-muted small">Jl. Bandar Olo no.42, Padang Barat, Kota Padang</div>
                    <div class="text-muted small">+6282285214024</div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div><strong>Tanggal:</strong> <?= date('d M Y', strtotime($booking['created_at'])) ?></div>
                    <div><strong>Status:</strong> <?= ucfirst($booking['status']) ?></div>
                    <div><strong>Pembayaran:</strong> <?= !empty($booking['payment_type']) ? strtoupper($booking['payment_type']) : '-' ?><?= !empty($booking['payment_bank']) ? ' • Bank: ' . esc($booking['payment_bank']) : '' ?></div>
                    <div><strong>Pelanggan:</strong> <?= esc($pelanggan['nama'] ?? ('User #' . $booking['user_id'])) ?></div>
                    <div class="text-muted small">HP: <?= esc($pelanggan['nohp'] ?? '-') ?> • Alamat: <?= esc($pelanggan['alamat'] ?? '-') ?></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Layanan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $it): ?>
                                <tr>
                                    <td><?= esc($it['name']) ?></td>
                                    <td class="text-end"><?= $it['price'] !== null ? rupiah($it['price']) : '' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td><?= esc($booking['service_name']) ?></td>
                                <td class="text-end"><?= rupiah($booking['price']) ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-end">Total</th>
                            <th class="text-end"><?= rupiah($booking['price']) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <?php if (!empty($booking['payment_proof'])): ?>
                <div class="mt-3">
                    <a href="<?= base_url($booking['payment_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-receipt"></i> Lihat Bukti Pembayaran</a>
                </div>
            <?php endif; ?>

            <div class="mt-4 d-print-none">
                <button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>