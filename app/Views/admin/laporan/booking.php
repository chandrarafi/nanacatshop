<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar-check text-primary"></i>
            <h5 class="mb-0">Laporan Booking Online</h5>
        </div>
        <a href="<?= site_url('admin') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-house-door me-2"></i>Dashboard</a>
    </div>
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end mb-3">
            <div class="col-md-2">
                <label class="form-label">Tipe Filter</label>
                <select name="filter_type" id="filter_type" class="form-select">
                    <option value="tanggal">Tanggal</option>
                    <option value="bulan">Bulan</option>
                    <option value="tahun">Tahun</option>
                </select>
            </div>
            <div class="col-md-4 filter-by-tanggal">
                <label class="form-label">Tanggal</label>
                <div class="input-group">
                    <input type="date" name="tgl_awal" class="form-control">
                    <span class="input-group-text">s/d</span>
                    <input type="date" name="tgl_akhir" class="form-control">
                </div>
            </div>
            <div class="col-md-2 filter-by-bulan" style="display:none;">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= sprintf('%02d', $m) ?>"><?= sprintf('%02d', $m) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2 filter-by-bulan" style="display:none;">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>">
            </div>
            <div class="col-md-2 filter-by-tahun" style="display:none;">
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

        <div id="summaryBox" class="mb-3"></div>
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
    function toggleFilterInputs() {
        const type = document.getElementById('filter_type').value;
        document.querySelectorAll('.filter-by-tanggal').forEach(el => el.style.display = (type === 'tanggal' ? '' : 'none'));
        document.querySelectorAll('.filter-by-bulan').forEach(el => el.style.display = (type === 'bulan' ? '' : 'none'));
        document.querySelectorAll('.filter-by-tahun').forEach(el => el.style.display = (type === 'tahun' ? '' : 'none'));
    }
    document.getElementById('filter_type').addEventListener('change', toggleFilterInputs);
    toggleFilterInputs();

    function buildQuery() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form));
        return params.toString();
    }

    async function loadSummary() {
        const type = document.getElementById('filter_type').value;
        const box = document.getElementById('summaryBox');
        box.innerHTML = '';
        const q = buildQuery();
        if (type === 'bulan') {
            const res = await fetch('<?= site_url('admin/laporan/booking-perbulan/data') ?>?' + q).then(r => r.json()).catch(() => null);
            if (!res || res.status !== 'success') return;
            const rows = res.data || [];
            if (rows.length === 0) return;
            let html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>Tanggal</th><th>Jumlah</th></tr></thead><tbody>';
            const counts = {};
            rows.forEach(b => {
                const d = (b.booking_date || '').slice(0, 10);
                counts[d] = (counts[d] || 0) + 1;
            });
            Object.keys(counts).sort().forEach(d => {
                html += `<tr><td>${d}</td><td>${counts[d]}</td></tr>`;
            });
            html += '</tbody></table></div>';
            box.innerHTML = html;
        } else if (type === 'tahun') {
            const res = await fetch('<?= site_url('admin/laporan/booking-pertahun/data') ?>?' + q).then(r => r.json()).catch(() => null);
            if (!res || res.status !== 'success') return;
            const rows = res.data || [];
            let html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>Bulan</th><th>Jumlah</th></tr></thead><tbody>';
            const bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const counts = new Array(13).fill(0);
            rows.forEach(b => {
                const m = (b.booking_date ? new Date(b.booking_date).getMonth() + 1 : 0);
                if (m >= 1 && m <= 12) counts[m]++;
            });
            for (let m = 1; m <= 12; m++) {
                html += `<tr><td>${bulanIndo[m]}</td><td>${counts[m]}</td></tr>`;
            }
            html += '</tbody></table></div>';
            box.innerHTML = html;
        }
    }

    function loadData() {
        const tbody = document.querySelector('#bookingTable tbody');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Memuat...</td></tr>';
        fetch('<?= site_url('admin/laporan/booking/data') ?>?' + buildQuery())
            .then(r => r.json())
            .then(res => {
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
                    tr.innerHTML = `
					<td>#${b.id}</td>
					<td>${b.booking_date ?? '-'}</td>
					<td>${b.booking_time ?? '-'}</td>
					<td><div class="fw-semibold">${b.namapelanggan ?? '-'}</div><div class="text-muted small">${b.pelanggan_id ?? ''} • ${b.nohp ?? '-'}</div></td>
					<td>${(b.service_name ?? '').split(',').map(s=>s.trim()).filter(Boolean).join('<br>')}</td>
					<td><div class="small text-muted">${(b.payment_type||'').toUpperCase()||'-'}</div><span class="badge bg-info text-dark">${b.payment_bank||'-'}</span></td>
					<td><span class="badge ${b.status==='completed'?'bg-success':b.status==='confirmed'?'bg-primary':b.status==='cancelled'?'bg-danger':'bg-secondary'}">${(b.status||'-').charAt(0).toUpperCase()+ (b.status||'-').slice(1)}</span></td>
				`;
                    tbody.appendChild(tr);
                });
            });
        loadSummary();
    }

    document.getElementById('btnFilter').addEventListener('click', function() {
        loadData();
        const url = '<?= site_url('admin/laporan/booking/cetak') ?>?' + buildQuery();
        document.getElementById('btnCetak').setAttribute('href', url);
    });

    // initial
    document.getElementById('btnFilter').click();
</script>
<?= $this->endSection() ?>
