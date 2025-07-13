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
                                    <div class="col-md-4 mb-3">
                                        <label for="filter_type">Tipe Filter</label>
                                        <select class="form-control" id="filter_type" name="filter_type">
                                            <option value="tanggal">Berdasarkan Tanggal</option>
                                            <option value="bulan">Berdasarkan Bulan</option>
                                            <option value="tahun">Berdasarkan Tahun</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <!-- <label for="kdspl">Supplier</label> -->
                                        <select class="form-control" id="kdspl" name="kdspl" hidden>
                                            <option value="">Semua Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <!-- <label for="status">Status</label> -->
                                        <select class="form-control" id="status" name="status" hidden>
                                            <option value="">Semua Status</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Selesai</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Filter by Date (default) -->
                                <div class="row" id="tanggalFilter">
                                    <div class="col-md-6 mb-3">
                                        <label for="tgl_awal">Tanggal Awal</label>
                                        <input type="date" class="form-control" id="tgl_awal" name="tgl_awal">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tgl_akhir">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir">
                                    </div>
                                </div>

                                <!-- Filter by Month (hidden by default) -->
                                <div class="row" id="bulanFilter" style="display: none;">
                                    <div class="col-md-6 mb-3">
                                        <label for="bulan">Bulan</label>
                                        <select class="form-control" id="bulan" name="bulan">
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
                                        <label for="tahun_bulan">Tahun</label>
                                        <select class="form-control" id="tahun_bulan" name="tahun">
                                            <?php
                                            $currentYear = date('Y');
                                            for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                                                echo "<option value=\"$i\">$i</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Filter by Year (hidden by default) -->
                                <div class="row" id="tahunFilter" style="display: none;">
                                    <div class="col-md-12 mb-3">
                                        <label for="tahun">Tahun</label>
                                        <select class="form-control" id="tahun" name="tahun">
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
                            <th>Kode Masuk</th>
                            <th>Tanggal Masuk</th>
                            <th>Supplier</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
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

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="font-weight-bold">Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="detailTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Supplier</th>
                            </tr>
                        </thead>
                        <tbody id="detail_items">
                            <!-- Detail items will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnTutupModal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load supplier data
        loadSupplierData();

        // Filter form submit
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            loadData();
        });

        // Reset button
        document.getElementById('btnReset').addEventListener('click', function() {
            document.getElementById('filterForm').reset();
            document.getElementById('filter_type').value = 'tanggal';
            toggleFilterFields('tanggal');
            document.getElementById('btnCetak').disabled = true;
            document.querySelector('#dataTable tbody').innerHTML = '';
        });

        // Filter type change
        document.getElementById('filter_type').addEventListener('change', function() {
            toggleFilterFields(this.value);
        });

        // Cetak button
        document.getElementById('btnCetak').addEventListener('click', function() {
            cetakPDF();
        });

        // Modal close buttons - menggunakan jQuery untuk memastikan berfungsi
        $(document).on('click', '#detailModal .modal-footer .btn-secondary', function() {
            $('#detailModal').modal('hide');
        });

        $(document).on('click', '#detailModal .close', function() {
            $('#detailModal').modal('hide');
        });
    });

    function toggleFilterFields(filterType) {
        // Hide all filter divs
        document.getElementById('tanggalFilter').style.display = 'none';
        document.getElementById('bulanFilter').style.display = 'none';
        document.getElementById('tahunFilter').style.display = 'none';

        // Show selected filter div
        document.getElementById(filterType + 'Filter').style.display = 'flex';
    }

    function loadSupplierData() {
        fetch('<?= site_url('admin/laporan/barang-masuk/supplier') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const select = document.getElementById('kdspl');
                    data.data.forEach(supplier => {
                        const option = document.createElement('option');
                        option.value = supplier.kdspl;
                        option.textContent = supplier.namaspl;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error loading supplier data:', error));
    }

    function loadData() {
        const formData = new FormData(document.getElementById('filterForm'));
        const queryParams = new URLSearchParams();

        for (const pair of formData.entries()) {
            if (pair[1]) {
                queryParams.append(pair[0], pair[1]);
            }
        }

        fetch('<?= site_url('admin/laporan/barang-masuk/data') ?>?' + queryParams.toString())
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    renderTable(data.data);
                    document.getElementById('btnCetak').disabled = data.data.length === 0;
                } else {
                    alert('Gagal memuat data');
                }
            })
            .catch(error => {
                console.error('Error loading data:', error);
                alert('Terjadi kesalahan saat memuat data');
            });
    }

    function renderTable(data) {
        const tbody = document.querySelector('#dataTable tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="7" class="text-center">Tidak ada data</td>';
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

            // Format status
            let statusText = '';
            let statusBadge = '';
            if (item.status == 0) {
                statusText = 'Pending';
                statusBadge = 'warning';
            } else if (item.status == 1) {
                statusText = 'Selesai';
                statusBadge = 'success';
            }

            // Format currency
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.kdmasuk}</td>
                <td>${formattedDate}</td>
                <td>${item.namaspl}</td>
                <td>${formatter.format(item.grandtotal)}</td>
                <td>${statusText}</td>
                <td>
                    <button class="btn btn-sm btn-info" style="color: white;" onclick="showDetail('${item.kdmasuk}', ${JSON.stringify(item).replace(/"/g, '&quot;')})">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    function showDetail(kdmasuk, item) {
        // Detail items
        const detailItems = document.getElementById('detail_items');
        detailItems.innerHTML = '';

        // Format currency
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        if (item.detail && item.detail.length > 0) {
            item.detail.forEach((detail, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${detail.detailkdbarang}</td>
                    <td>${detail.namabarang}</td>
                    <td>${detail.satuan || '-'}</td>
                    <td>${detail.jumlah}</td>
                    <td>${formatter.format(detail.harga)}</td>
                    <td>${item.namaspl}</td>
                `;
                detailItems.appendChild(tr);
            });
        } else {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="7" class="text-center">Tidak ada detail</td>';
            detailItems.appendChild(tr);
        }

        // Show modal
        $('#detailModal').modal('show');
    }

    function cetakPDF() {
        const formData = new FormData(document.getElementById('filterForm'));
        const queryParams = new URLSearchParams();
        const filterType = document.getElementById('filter_type').value;

        for (const pair of formData.entries()) {
            if (pair[1]) {
                queryParams.append(pair[0], pair[1]);
            }
        }

        let url;
        if (filterType === 'bulan') {
            // Jika filter berdasarkan bulan, gunakan endpoint laporan perbulan
            url = '<?= site_url('admin/laporan/barang-masuk-perbulan/cetak') ?>?' + queryParams.toString();
        } else if (filterType === 'tahun') {
            // Jika filter berdasarkan tahun, gunakan endpoint laporan pertahun
            url = '<?= site_url('admin/laporan/barang-masuk-pertahun/cetak') ?>?' + queryParams.toString();
        } else {
            // Untuk filter lainnya, gunakan endpoint laporan biasa
            url = '<?= site_url('admin/laporan/barang-masuk/cetak') ?>?' + queryParams.toString();
        }

        window.open(url, '_blank');
    }

    // Script untuk memastikan tombol tutup pada modal berfungsi
    $(document).ready(function() {
        // Tombol tutup pada modal
        $(document).on('click', '#detailModal .btn-secondary', function() {
            $('#detailModal').modal('hide');
        });

        // Tombol silang (x) pada modal
        $(document).on('click', '#detailModal .close', function() {
            $('#detailModal').modal('hide');
        });

        // Tutup modal saat klik di luar modal
        $(document).on('click', function(e) {
            if ($(e.target).hasClass('modal')) {
                $('#detailModal').modal('hide');
            }
        });
    });
</script>
<?= $this->endSection() ?>