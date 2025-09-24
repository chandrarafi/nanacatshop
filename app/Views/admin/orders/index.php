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
            <i class="bi bi-bag-check text-primary"></i>
            <h5 class="mb-0">Kelola Pesanan</h5>
        </div>
        <a href="<?= site_url('admin') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Dashboard</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="ordersTable" class="table table-striped table-hover align-middle" style="width:100%">
                <thead class="table-light">
                    <tr class="text-uppercase small text-muted">
                        <th>No. Order</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan</td>
                        </tr>
                        <?php else: foreach ($orders as $order): ?>
                            <tr>
                                <td class="fw-semibold">#<?= esc($order['order_number']) ?></td>
                                <td><?= esc($order['pelanggan_id']) ?></td>
                                <td class="fw-semibold"><?= rupiah($order['total_amount']) ?></td>
                                <td>
                                    <div class="small text-muted">Transfer</div>
                                    <span class="badge bg-info text-dark"><?= esc($order['payment_bank'] ?? '-') ?></span>
                                </td>
                                <td>
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
                                </td>
                                <td class="text-end">
                                    <a href="<?= site_url('admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-pink"><i class="bi bi-eye me-1"></i>Detail</a>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        $('#ordersTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            lengthChange: true,
            pageLength: 10,
            autoWidth: false,
            ordering: true,
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                targets: -1,
                orderable: false,
                searchable: false,
                className: 'text-end'
            }],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
            },
            dom: '<"row align-items-center mb-3"<"col-sm-6"l><"col-sm-6 text-sm-end"f>>rt<"row align-items-center mt-3"<"col-sm-6"i><"col-sm-6"p>>'
        });
    });
</script>
<?= $this->endSection() ?>