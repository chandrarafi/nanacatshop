<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px) !important;
    }

    .select2-container--bootstrap4 {
        width: 100% !important;
    }

    .select2-container--bootstrap4 .select2-selection {
        border-color: #d1d3e2;
        border-radius: 0.35rem;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + 0.75rem);
        padding-left: 0.75rem;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem);
    }
</style>
<?= $this->endSection() ?>

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
                                        <!-- <label for="idpelanggan">Pelanggan</label> -->
                                        <div class="input-group" hidden>
                                            <input type="hidden" id="idpelanggan" name="idpelanggan">
                                            <input type="text" class="form-control" id="nama_pelanggan" placeholder="Semua Pelanggan" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="btnPilihPelanggan">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" id="resetPelanggan">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </div>
                                        </div>
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
                            <th>Kode Penjualan</th>
                            <th>Tanggal Penjualan</th>
                            <th>Pelanggan</th>
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
                <h5 class="modal-title" id="detailModalLabel">Detail Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Kode Penjualan</th>
                                <td>: <span id="detail_kdpenjualan"></span></td>
                            </tr>
                            <tr>
                                <th>Tanggal Penjualan</th>
                                <td>: <span id="detail_tglpenjualan"></span></td>
                            </tr>
                            <tr>
                                <th>Pelanggan</th>
                                <td>: <span id="detail_pelanggan"></span></td>
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
                <h6 class="font-weight-bold">Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="detailTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Pelanggan Modal -->
<div class="modal fade" id="pelangganModal" tabindex="-1" role="dialog" aria-labelledby="pelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pelangganModalLabel">Pilih Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="searchPelanggan" placeholder="Cari pelanggan...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="pelangganTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Script -->
<script>
    $(document).ready(function() {
        // Event handler untuk tombol pilih pelanggan
        $('#btnPilihPelanggan').click(function() {
            $('#pelangganModal').modal('show');
        });

        // Load data pelanggan saat modal dibuka
        $('#pelangganModal').on('shown.bs.modal', function() {
            loadPelangganData();
            $('#searchPelanggan').focus();
        });

        // Pencarian pelanggan
        $('#searchPelanggan').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#pelangganTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
            });
        });

        // Reset pelanggan
        $('#resetPelanggan').click(function() {
            $('#idpelanggan').val('');
            $('#nama_pelanggan').val('');
        });

        // Tampilkan filter yang sesuai berdasarkan tipe filter
        $('#filter_type').change(function() {
            const filterType = $(this).val();
            if (filterType === 'tanggal') {
                $('#tanggalFilter').show();
                $('#bulanFilter').hide();
                $('#tahunFilter').hide();
            } else if (filterType === 'bulan') {
                $('#tanggalFilter').hide();
                $('#bulanFilter').show();
                $('#tahunFilter').hide();
            } else if (filterType === 'tahun') {
                $('#tanggalFilter').hide();
                $('#bulanFilter').hide();
                $('#tahunFilter').show();
            }
        });

        // Reset form
        $('#btnReset').click(function() {
            $('#filterForm')[0].reset();
            $('#filter_type').val('tanggal').trigger('change');
            $('#idpelanggan').val('');
            $('#nama_pelanggan').val('');
            $('#dataTable tbody').empty();
            $('#btnCetak').prop('disabled', true);
        });

        // Submit form
        $('#filterForm').submit(function(e) {
            e.preventDefault();
            const filterType = $('#filter_type').val();
            let endpoint = '<?= base_url('admin/laporan/penjualan/data') ?>';
            let formData = $(this).serialize();

            // Pilih endpoint berdasarkan tipe filter
            if (filterType === 'bulan') {
                endpoint = '<?= base_url('admin/laporan/penjualan/perbulan/data') ?>';
            } else if (filterType === 'tahun') {
                endpoint = '<?= base_url('admin/laporan/penjualan/pertahun/data') ?>';
            }

            // Validasi form
            if (filterType === 'tanggal') {
                if (!$('#tgl_awal').val() || !$('#tgl_akhir').val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Tanggal awal dan tanggal akhir harus diisi!'
                    });
                    return;
                }
            } else if (filterType === 'bulan') {
                if (!$('#bulan').val() || !$('#tahun_bulan').val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Bulan dan tahun harus diisi!'
                    });
                    return;
                }
            } else if (filterType === 'tahun') {
                if (!$('#tahun').val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Tahun harus diisi!'
                    });
                    return;
                }
            }

            // Tampilkan loading
            Swal.fire({
                title: 'Memuat Data',
                html: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Ambil data
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        renderTable(response.data);
                        $('#btnCetak').prop('disabled', false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengambil data!'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat mengambil data!'
                    });
                }
            });
        });

        // Cetak PDF
        $('#btnCetak').click(function() {
            const filterType = $('#filter_type').val();
            let url = '<?= base_url('admin/laporan/penjualan/cetak') ?>?';

            // Pilih URL berdasarkan tipe filter
            if (filterType === 'bulan') {
                url = '<?= base_url('admin/laporan/penjualan/perbulan/cetak') ?>?';
                url += 'bulan=' + $('#bulan').val();
                url += '&tahun=' + $('#tahun_bulan').val();
            } else if (filterType === 'tahun') {
                url = '<?= base_url('admin/laporan/penjualan/pertahun/cetak') ?>?';
                url += 'tahun=' + $('#tahun').val();
            } else {
                url += 'filter_type=' + filterType;
                url += '&tgl_awal=' + $('#tgl_awal').val();
                url += '&tgl_akhir=' + $('#tgl_akhir').val();
            }

            // Tambahkan parameter lainnya
            if ($('#idpelanggan').val()) {
                url += '&idpelanggan=' + $('#idpelanggan').val();
            }
            if ($('#status').val() !== '') {
                url += '&status=' + $('#status').val();
            }

            // Buka di tab baru
            window.open(url, '_blank');
        });

        // Fungsi untuk memuat data pelanggan
        function loadPelangganData() {
            $.ajax({
                url: '<?= base_url('admin/laporan/penjualan/pelanggan-modal') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        renderPelangganTable(response.data);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengambil data pelanggan!'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat mengambil data pelanggan!'
                    });
                }
            });
        }

        // Fungsi untuk menampilkan data pelanggan di tabel
        function renderPelangganTable(data) {
            const tbody = $('#pelangganTable tbody');
            tbody.empty();

            if (data.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center">Tidak ada data pelanggan</td></tr>');
                return;
            }

            data.forEach(function(item) {
                const row = `
                    <tr>
                        <td>${item.idpelanggan}</td>
                        <td>${item.nama}</td>
                        <td>${item.alamat || '-'}</td>
                        <td>${item.notelp || '-'}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary btn-pilih-pelanggan" 
                                data-id="${item.idpelanggan}" 
                                data-nama="${item.nama}">
                                <i class="fas fa-check"></i> Pilih
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Tambahkan opsi "Semua Pelanggan"
            const allRow = `
                <tr class="table-primary">
                    <td colspan="4"><strong>Semua Pelanggan</strong></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-success btn-pilih-pelanggan" 
                            data-id="" 
                            data-nama="Semua Pelanggan">
                            <i class="fas fa-check"></i> Pilih
                        </button>
                    </td>
                </tr>
            `;
            tbody.prepend(allRow);

            // Event untuk tombol pilih pelanggan
            $(document).on('click', '.btn-pilih-pelanggan', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');

                $('#idpelanggan').val(id);
                $('#nama_pelanggan').val(nama);

                $('#pelangganModal').modal('hide');
            });
        }

        // Fungsi untuk menampilkan data di tabel
        function renderTable(data) {
            const tbody = $('#dataTable tbody');
            tbody.empty();

            if (data.length === 0) {
                tbody.append('<tr><td colspan="7" class="text-center">Tidak ada data yang ditemukan</td></tr>');
                return;
            }

            let no = 1;
            data.forEach(function(item) {
                const status = item.status == 1 ?
                    '<span class="badge badge-success">Selesai</span>' :
                    '<span class="badge badge-warning">Pending</span>';

                const row = `
                    <tr>
                        <td class="text-center">${no++}</td>
                <td>${item.kdpenjualan}</td>
                        <td>${formatDate(item.tglpenjualan)}</td>
                <td>${item.namapelanggan}</td>
                        <td class="text-right">Rp ${formatNumber(item.grandtotal)}</td>
                <td class="text-center">${status}</td>
                <td class="text-center">
                            <button class="btn btn-sm btn-info btn-detail" data-id="${item.kdpenjualan}">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                            <a href="<?= base_url('admin/laporan/penjualan/detail/') ?>${item.kdpenjualan}" target="_blank" class="btn btn-sm btn-success">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Event untuk tombol detail
            $('.btn-detail').click(function() {
                const id = $(this).data('id');
                showDetailModal(id, data);
            });
        }

        // Fungsi untuk menampilkan modal detail
        function showDetailModal(id, data) {
            const item = data.find(item => item.kdpenjualan === id);
            if (!item) return;

            // Isi data header
            $('#detail_kdpenjualan').text(item.kdpenjualan);
            $('#detail_tglpenjualan').text(formatDate(item.tglpenjualan));
            $('#detail_pelanggan').text(item.namapelanggan);
            $('#detail_grandtotal').text('Rp ' + formatNumber(item.grandtotal));
            $('#detail_status').text(item.status == 1 ? 'Selesai' : 'Pending');
            $('#detail_status').removeClass('text-success text-warning')
                .addClass(item.status == 1 ? 'text-success' : 'text-warning');

            // Isi tabel detail
            const tbody = $('#detailTable tbody');
            tbody.empty();

            if (!item.detail || item.detail.length === 0) {
                tbody.append('<tr><td colspan="7" class="text-center">Tidak ada detail barang</td></tr>');
            } else {
                let no = 1;
                item.detail.forEach(function(detail) {
                    const row = `
                        <tr>
                            <td class="text-center">${no++}</td>
                            <td>${detail.detailkdbarang}</td>
                            <td>${detail.namabarang}</td>
                            <td>${detail.namakategori}</td>
                            <td class="text-center">${detail.jumlah}</td>
                            <td class="text-right">Rp ${formatNumber(detail.harga)}</td>
                            <td class="text-right">Rp ${formatNumber(detail.totalharga)}</td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }

            // Tampilkan modal
            $('#detailModal').modal('show');
        }

        // Helper function untuk format tanggal
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        // Helper function untuk format angka
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Trigger change event untuk menampilkan filter yang sesuai saat halaman dimuat
        $('#filter_type').trigger('change');
    });
</script>
<?= $this->endSection() ?>