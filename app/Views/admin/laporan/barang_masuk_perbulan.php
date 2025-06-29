<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
                        </div>
                        <div class="card-body">
                            <form id="filterForm" action="javascript:void(0)">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="bulan">Bulan</label>
                                        <select class="form-control" id="bulan" name="bulan" required>
                                            <option value="">Pilih Bulan</option>
                                            <option value="01">Januari</option>
                                            <option value="02">Februari</option>
                                            <option value="03">Maret</option>
                                            <option value="04">April</option>
                                            <option value="05">Mei</option>
                                            <option value="06">Juni</option>
                                            <option value="07">Juli</option>
                                            <option value="08">Agustus</option>
                                            <option value="09">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tahun">Tahun</label>
                                        <select class="form-control" id="tahun" name="tahun" required>
                                            <option value="">Pilih Tahun</option>
                                            <?php
                                            $currentYear = date('Y');
                                            for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                                                echo "<option value=\"$i\">$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Tampilkan
                                        </button>
                                        <button type="button" id="btnCetak" class="btn btn-success" disabled>
                                            <i class="fas fa-print"></i> Cetak PDF
                                        </button>
                                        <button type="button" id="btnReset" class="btn btn-secondary">
                                            <i class="fas fa-sync"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter form submit
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            loadData();
        });

        // Reset button
        document.getElementById('btnReset').addEventListener('click', function() {
            document.getElementById('filterForm').reset();
            document.getElementById('btnCetak').disabled = true;
            document.querySelector('#dataTable tbody').innerHTML = '';
        });

        // Cetak button
        document.getElementById('btnCetak').addEventListener('click', function() {
            cetakPDF();
        });
    });

    function loadData() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;

        if (!bulan || !tahun) {
            alert('Silakan pilih bulan dan tahun terlebih dahulu');
            return;
        }

        const queryParams = new URLSearchParams({
            bulan: bulan,
            tahun: tahun
        });

        fetch('<?= site_url('admin/laporan/barang-masuk-perbulan/data') ?>?' + queryParams.toString())
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    renderTable(data.data);
                    document.getElementById('btnCetak').disabled = data.data.length === 0;
                } else {
                    alert('Gagal memuat data: ' + data.message);
                    document.getElementById('btnCetak').disabled = true;
                }
            })
            .catch(error => {
                console.error('Error loading data:', error);
                alert('Terjadi kesalahan saat memuat data');
                document.getElementById('btnCetak').disabled = true;
            });
    }

    function renderTable(data) {
        const tbody = document.querySelector('#dataTable tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="8" class="text-center">Tidak ada data</td>';
            tbody.appendChild(tr);
            return;
        }

        data.forEach((item, index) => {
            const tr = document.createElement('tr');

            // Format tanggal
            const tglMasuk = new Date(item.tglmasuk);
            const formattedDate = tglMasuk.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            // Format currency
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${formattedDate}</td>
                <td>${item.detailkdbarang}</td>
                <td>${item.namabarang}</td>
                <td>${item.satuan || '-'}</td>
                <td>${formatter.format(item.harga)}</td>
                <td>${item.jumlah}</td>
                <td>${formatter.format(item.totalharga)}</td>
            `;

            tbody.appendChild(tr);
        });
    }

    function cetakPDF() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;

        if (!bulan || !tahun) {
            alert('Silakan pilih bulan dan tahun terlebih dahulu');
            return;
        }

        const queryParams = new URLSearchParams({
            bulan: bulan,
            tahun: tahun
        });

        const url = '<?= site_url('admin/laporan/barang-masuk-perbulan/cetak') ?>?' + queryParams.toString();
        window.open(url, '_blank');
    }
</script>
<?= $this->endSection() ?>