<?= $this->extend('admin/layouts/main') ?>
<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2"><i class="bi bi-calendar2-week text-primary"></i>
            <h5 class="mb-0">Laporan Booking Pertahun</h5>
        </div>
        <a href="<?= site_url('admin') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Dashboard</a>
    </div>
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end mb-3">
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Pelanggan</label>
                <input type="text" name="idpelanggan" class="form-control" placeholder="ID Pelanggan (opsional)">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-12 d-flex gap-2">
                <button type="button" id="btnFilter" class="btn btn-primary"><i class="bi bi-search me-1"></i>Terapkan</button>
                <a id="btnCetak" class="btn btn-outline-danger" target="_blank"><i class="bi bi-filetype-pdf me-1"></i>Cetak PDF</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="bookingTable">
                <thead class="table-light">
                    <tr class="text-uppercase small text-muted">
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    function buildQuery() {
        return new URLSearchParams(new FormData(document.getElementById('filterForm'))).toString();
    }

    function loadData() {
        const tbody = document.querySelector('#bookingTable tbody');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Memuat...</td></tr>';
        fetch('<?= site_url('admin/laporan/booking-pertahun/data') ?>?' + buildQuery())
            .then(r => r.json()).then(res => {
                if (res.status !== 'success') {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Gagal memuat</td></tr>';
                    return;
                }
                if (!res.data || res.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                res.data.forEach(b => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>#${b.id}</td>
          <td>${b.booking_date??'-'}</td>
          <td>${b.booking_time??'-'}</td>
          <td><div class="fw-semibold">${b.namapelanggan??'-'}</div><div class="text-muted small">${b.pelanggan_id??''} • ${b.nohp??'-'}</div></td>
          <td>${(b.service_name||'').split(',').map(s=>s.trim()).filter(Boolean).join('<br>')}</td>
          <td><div class="small">${(b.payment_type||'-').toUpperCase()}</div><span class="badge bg-info text-dark">${b.payment_bank||'-'}</span></td>
          <td><span class="badge ${b.status==='completed'?'bg-success':b.status==='confirmed'?'bg-primary':b.status==='cancelled'?'bg-danger':'bg-secondary'}">${(b.status||'-').charAt(0).toUpperCase()+(b.status||'-').slice(1)}</span></td>`;
                    tbody.appendChild(tr);
                })
            });
    }
    document.getElementById('btnFilter').addEventListener('click', function() {
        loadData();
        document.getElementById('btnCetak').setAttribute('href', '<?= site_url('admin/laporan/booking-pertahun/cetak') ?>?' + buildQuery());
    });
    document.getElementById('btnFilter').click();
</script>
<?= $this->endSection() ?>
