<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        line-height: 38px;
        padding: 0.375rem 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Aksi Laporan</h5>
            </div>
            <div class="card-body">
                <form id="form-filter">
                    <div class="row g-3">
                        <!-- <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nama-kategori" placeholder="Pilih Kategori" readonly>
                                <input type="hidden" name="kdkategori" id="kdkategori">
                                <button class="btn btn-outline-primary" type="button" id="btn-pilih-kategori">
                                    <i class="bi bi-search"></i> Pilih
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="btn-reset-kategori">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Stok</label>
                            <select class="form-select" name="stok" id="stok">
                                <option value="">Semua</option>
                                <option value="tersedia">Tersedia</option>
                                <option value="habis">Habis</option>
                                <option value="menipis">Menipis (≤ 5)</option>
                            </select>
                        </div> -->
                        <!-- <div class="col-md-6">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="text" class="form-control datepicker" name="tgl_awal" id="tgl_awal" placeholder="Pilih Tanggal Awal">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="text" class="form-control datepicker" name="tgl_akhir" id="tgl_akhir" placeholder="Pilih Tanggal Akhir">
                        </div> -->
                        <div class="col-12 text-end">
                            <!-- <button type="button" class="btn btn-primary" id="btn-filter">
                                <i class="bi bi-search"></i> Filter
                            </button> -->
                            <button type="button" class="btn btn-success" id="btn-cetak">
                                <i class="bi bi-printer"></i> Cetak PDF
                            </button>
                            <!-- <button type="button" class="btn btn-secondary" id="btn-reset">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Barang</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table-barang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Tanggal Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Kategori -->
<div class="modal fade" id="modalPilihKategori" tabindex="-1" aria-labelledby="modalPilihKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihKategoriLabel">Pilih Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-kategori" placeholder="Cari kategori...">
                        <button class="btn btn-outline-primary" type="button" id="btn-search-kategori">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table-kategori">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi date picker
        $(".datepicker").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Variabel untuk menyimpan data kategori
        let dataKategori = [];

        // Fungsi untuk memuat data kategori
        function loadKategori(keyword = '') {
            $.ajax({
                url: '<?= site_url('admin/laporan/barang/kategori') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        dataKategori = response.data;
                        renderKategoriTable(keyword);
                    }
                }
            });
        }

        // Fungsi untuk menampilkan data kategori di tabel modal
        function renderKategoriTable(keyword = '') {
            let filteredData = dataKategori;

            // Filter data jika ada keyword
            if (keyword) {
                keyword = keyword.toLowerCase();
                filteredData = dataKategori.filter(item =>
                    item.namakategori.toLowerCase().includes(keyword) ||
                    item.kdkategori.toLowerCase().includes(keyword)
                );
            }

            // Kosongkan tabel
            $('#table-kategori tbody').empty();

            // Tampilkan data
            if (filteredData.length === 0) {
                $('#table-kategori tbody').html('<tr><td colspan="3" class="text-center">Tidak ada data</td></tr>');
            } else {
                $.each(filteredData, function(i, item) {
                    const row = `
                        <tr>
                            <td>${item.kdkategori}</td>
                            <td>${item.namakategori}</td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-pilih-kategori" data-id="${item.kdkategori}" data-nama="${item.namakategori}">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#table-kategori tbody').append(row);
                });
            }
        }

        // Buka modal pilih kategori
        $('#btn-pilih-kategori').click(function() {
            loadKategori();
            $('#modalPilihKategori').modal('show');
        });

        // Pencarian kategori
        $('#btn-search-kategori').click(function() {
            const keyword = $('#search-kategori').val();
            renderKategoriTable(keyword);
        });

        // Enter key pada input pencarian
        $('#search-kategori').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btn-search-kategori').click();
            }
        });

        // Tombol pilih kategori pada setiap baris
        $(document).on('click', '.btn-pilih-kategori', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#kdkategori').val(id);
            $('#nama-kategori').val(nama);
            $('#modalPilihKategori').modal('hide');
        });

        // Reset pilihan kategori
        $('#btn-reset-kategori').click(function() {
            $('#kdkategori').val('');
            $('#nama-kategori').val('');
        });

        // Fungsi untuk memuat data barang
        function loadData() {
            const kdkategori = $('#kdkategori').val();
            const stok = $('#stok').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Tampilkan loading
            showLoading();

            // Kosongkan tabel
            $('#table-barang tbody').empty();

            // Ambil data dari server
            $.ajax({
                url: '<?= site_url('admin/laporan/barang/data') ?>',
                type: 'GET',
                data: {
                    kdkategori: kdkategori,
                    stok: stok,
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir
                },
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (response.status === 'success') {
                        let no = 1;
                        if (response.data.length === 0) {
                            $('#table-barang tbody').html('<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>');
                        } else {
                            $.each(response.data, function(i, item) {
                                const tanggal = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                }) : '-';

                                // Format harga
                                const hargaBeli = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).format(item.hargabeli);
                                const hargaJual = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).format(item.hargajual);

                                // Status stok
                                let stokClass = '';
                                if (item.jumlah == 0) {
                                    stokClass = 'text-danger fw-bold';
                                } else if (item.jumlah <= 5) {
                                    stokClass = 'text-warning fw-bold';
                                }

                                $('#table-barang tbody').append(`
                                    <tr>
                                        <td>${no++}</td>
                                        <td>${item.kdbarang}</td>
                                        <td>${item.namabarang}</td>
                                        <td>${item.namakategori}</td>
                                        <td class="${stokClass}">${item.jumlah}</td>
                                        <td>${hargaBeli}</td>
                                        <td>${hargaJual}</td>
                                        <td>${tanggal}</td>
                                    </tr>
                                `);
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memuat data!'
                        });
                    }
                },
                error: function() {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat memuat data!'
                    });
                }
            });
        }

        // Filter data
        $('#btn-filter').click(function() {
            loadData();
        });

        // Reset filter
        $('#btn-reset').click(function() {
            $('#kdkategori').val('');
            $('#nama-kategori').val('');
            $('#stok').val('');
            $('#tgl_awal').val('');
            $('#tgl_akhir').val('');
            loadData();
        });

        // Cetak PDF
        $('#btn-cetak').click(function() {
            const kdkategori = $('#kdkategori').val();
            const stok = $('#stok').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Buat URL untuk cetak PDF dengan parameter filter
            let url = '<?= site_url('admin/laporan/barang/cetak') ?>';
            let params = [];

            if (kdkategori) params.push(`kdkategori=${kdkategori}`);
            if (stok) params.push(`stok=${stok}`);
            if (tglAwal) params.push(`tgl_awal=${tglAwal}`);
            if (tglAkhir) params.push(`tgl_akhir=${tglAkhir}`);

            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            // Buka di tab baru
            window.open(url, '_blank');
        });

        // Load data saat halaman dimuat
        loadData();
    });
</script>
<?= $this->endSection() ?>