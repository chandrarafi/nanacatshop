<?= $this->extend("admin/layouts/main") ?>

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
                                        <label for="idpelanggan">Pelanggan</label>
                                        <select class="form-control" id="idpelanggan" name="idpelanggan">
                                            <option value="">Semua Pelanggan</option>
                                            <!-- Options will be loaded via AJAX -->
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">Semua Status</option>
                                            <option value="0">Menunggu</option>
                                            <option value="1">Dalam Proses</option>
                                            <option value="2">Selesai</option>
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
                            <th>Kode Perawatan</th>
                            <th>Tanggal Perawatan</th>
                            <th>Pelanggan</th>
                            <th>Hewan</th>
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
<div class="modal fade" id="detailModalPerawatan" tabindex="-1" role="dialog" aria-labelledby="
ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalPerawatanLabel">Detail Perawatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Kode Perawatan</th>
                                <td>: <span id="detail_kdperawatan"></span></td>
                            </tr>
                            <tr>
                                <th>Tanggal Perawatan</th>
                                <td>: <span id="detail_tglperawatan"></span></td>
                            </tr>
                            <tr>
                                <th>Pelanggan</th>
                                <td>: <span id="detail_pelanggan"></span></td>
                            </tr>
                            <tr>
                                <th>Hewan</th>
                                <td>: <span id="detail_hewan"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Grand Total</th>
                                <td>: <span id="detail_grandtotal"></span></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: <span id="detail_status"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <h6 class="font-weight-bold">Detail Layanan</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="detailTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Fasilitas</th>
                                <th>Nama Fasilitas</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="detail_items">
                            <!-- Detail items will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCloseModal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load pelanggan data
        loadPelangganData();

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

        // Inisialisasi jQuery untuk modal
        $(document).ready(function() {
            // Tangani tombol tutup modal
            $('#btnCloseModal').on('click', function() {
                $('#detailModalPerawatan').modal('hide');
            });
        });
    });

    function loadPelangganData() {
        fetch('<?= base_url('admin/laporan/pelanggan-list') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const select = document.getElementById('idpelanggan');
                    data.data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.idpelanggan;
                        option.textContent = item.nama;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error loading pelanggan data:', error));
    }

    function toggleFilterFields(filterType) {
        document.getElementById('tanggalFilter').style.display = filterType === 'tanggal' ? 'flex' : 'none';
        document.getElementById('bulanFilter').style.display = filterType === 'bulan' ? 'flex' : 'none';
        document.getElementById('tahunFilter').style.display = filterType === 'tahun' ? 'flex' : 'none';
    }

    function loadData() {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);

        fetch('<?= base_url('admin/laporan/perawatan-data') ?>?' + params.toString())
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    renderTable(data.data);
                    document.getElementById('btnCetak').disabled = data.data.length === 0;
                }
            })
            .catch(error => console.error('Error loading data:', error));
    }

    function renderTable(data) {
        const tbody = document.querySelector('#dataTable tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="8" class="text-center">Tidak ada data yang ditemukan</td>';
            tbody.appendChild(tr);
            return;
        }

        data.forEach((item, index) => {
            const tr = document.createElement('tr');

            // Format tanggal
            const tglPerawatan = new Date(item.tglperawatan);
            const formattedDate = tglPerawatan.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            // Format grand total
            const formattedGrandTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(item.grandtotal);

            // Status
            let status = '';
            if (item.status == 0) {
                status = '<span class="badge badge-warning">Menunggu</span>';
            } else if (item.status == 1) {
                status = '<span class="badge badge-primary">Dalam Proses</span>';
            } else {
                status = '<span class="badge badge-success">Selesai</span>';
            }

            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.kdperawatan}</td>
                <td>${formattedDate}</td>
                <td>${item.namapelanggan}</td>
                <td>${item.namahewan || '-'}</td>
                <td class="text-right">${formattedGrandTotal}</td>
                <td class="text-center">${status}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info" onclick="showDetail('${item.kdperawatan}')">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    <a href="<?= base_url('admin/laporan/perawatan/cetak') ?>?kdperawatan=${item.kdperawatan}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function showDetail(kdperawatan) {
        // Cari data perawatan dari data yang sudah dimuat
        fetch('<?= base_url('admin/laporan/perawatan-data') ?>?kdperawatan=' + kdperawatan)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data.length > 0) {
                    const item = data.data[0];

                    // Format tanggal
                    const tglPerawatan = new Date(item.tglperawatan);
                    const formattedDatePerawatan = tglPerawatan.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });

                    // Format grand total
                    const formattedGrandTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(item.grandtotal);

                    // Status
                    let status = '';
                    if (item.status == 0) {
                        status = '<span class="badge badge-warning">Menunggu</span>';
                    } else if (item.status == 1) {
                        status = '<span class="badge badge-primary">Dalam Proses</span>';
                    } else {
                        status = '<span class="badge badge-success">Selesai</span>';
                    }

                    // Set data ke modal
                    document.getElementById('detail_kdperawatan').textContent = item.kdperawatan;
                    document.getElementById('detail_tglperawatan').textContent = formattedDatePerawatan;
                    document.getElementById('detail_pelanggan').textContent = item.namapelanggan;
                    document.getElementById('detail_hewan').textContent = item.namahewan || '-';
                    document.getElementById('detail_grandtotal').textContent = formattedGrandTotal;
                    document.getElementById('detail_status').innerHTML = status;

                    // Render detail items
                    renderDetailItems(item.detail);

                    // Show modal
                    $('#detailModalPerawatan').modal('show');
                }
            })
            .catch(error => console.error('Error loading detail:', error));
    }

    function renderDetailItems(details) {
        const tbody = document.getElementById('detail_items');
        tbody.innerHTML = '';

        if (!details || details.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td colspan="7" class="text-center">Tidak ada detail layanan</td>';
            tbody.appendChild(tr);
            return;
        }

        details.forEach((item, index) => {
            const tr = document.createElement('tr');

            // Format harga dan total
            const formattedHarga = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(item.harga);

            const formattedTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(item.totalharga);

            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.kdfasilitas}</td>
                <td>${item.namafasilitas}</td>
                <td>${item.kategori}</td>
                <td class="text-center">${item.jumlah}</td>
                <td class="text-right">${formattedHarga}</td>
                <td class="text-right">${formattedTotal}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    function cetakPDF() {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);

        window.open('<?= base_url('admin/laporan/perawatan/cetak') ?>?' + params.toString(), '_blank');
    }
</script>
<?= $this->endSection() ?>