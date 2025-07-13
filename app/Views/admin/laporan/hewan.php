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
                            <label class="form-label">Jenis Hewan</label>
                            <select class="form-select" name="jenis" id="jenis">
                                <option value="">Semua</option>
                                <option value="Domestic">Domestic</option>
                                <option value="Campuran">Campuran</option>
                                <option value="Persian">Persian</option>
                                <option value="Maine Coon">Maine Coon</option>
                                <option value="Siamese">Siamese</option>
                                <option value="British Shorthair">British Shorthair</option>
                                <option value="Ragdoll">Ragdoll</option>
                                <option value="Bengal">Bengal</option>
                                <option value="Sphynx">Sphynx</option>
                                <option value="Scottish Fold">Scottish Fold</option>
                                <option value="Angora">Angora</option>
                                <option value="Himalayan">Himalayan</option>
                            </select>
                        </div> -->
                        <!-- <div class="col-md-6">
                            <label class="form-label">Pemilik</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nama-pemilik" placeholder="Pilih Pemilik" readonly>
                                <input type="hidden" name="idpelanggan" id="idpelanggan">
                                <button class="btn btn-outline-primary" type="button" id="btn-pilih-pemilik">
                                    <i class="bi bi-search"></i> Pilih
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="btn-reset-pemilik">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
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
                                <!-- </button>
                            <button type="button" class="btn btn-secondary" id="btn-reset">
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
                <h5 class="mb-0">Data Hewan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table-hewan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Hewan</th>
                                <th>Nama Hewan</th>
                                <th>Jenis</th>
                                <th>Umur</th>
                                <th>Pemilik</th>
                                <th>Tanggal Daftar</th>
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

<!-- Modal Pilih Pemilik -->
<div class="modal fade" id="modalPilihPemilik" tabindex="-1" aria-labelledby="modalPilihPemilikLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihPemilikLabel">Pilih Pemilik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-pelanggan" placeholder="Cari pemilik...">
                        <button class="btn btn-outline-primary" type="button" id="btn-search-pelanggan">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table-pelanggan">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
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

        // Variabel untuk menyimpan data pelanggan
        let dataPelanggan = [];

        // Fungsi untuk memuat data pelanggan
        function loadPelanggan(keyword = '') {
            $.ajax({
                url: '<?= site_url('admin/laporan/hewan/pelanggan') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        dataPelanggan = response.data;
                        renderPelangganTable(keyword);
                    }
                }
            });
        }

        // Fungsi untuk menampilkan data pelanggan di tabel modal
        function renderPelangganTable(keyword = '') {
            let filteredData = dataPelanggan;

            // Filter data jika ada keyword
            if (keyword) {
                keyword = keyword.toLowerCase();
                filteredData = dataPelanggan.filter(item =>
                    item.nama.toLowerCase().includes(keyword) ||
                    item.alamat.toLowerCase().includes(keyword) ||
                    item.nohp.toLowerCase().includes(keyword)
                );
            }

            // Kosongkan tabel
            $('#table-pelanggan tbody').empty();

            // Tampilkan data
            if (filteredData.length === 0) {
                $('#table-pelanggan tbody').html('<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>');
            } else {
                $.each(filteredData, function(i, item) {
                    const row = `
                        <tr>
                            <td>${item.idpelanggan}</td>
                            <td>${item.nama}</td>
                            <td>${item.alamat || '-'}</td>
                            <td>${item.nohp || '-'}</td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-pilih-pelanggan" data-id="${item.idpelanggan}" data-nama="${item.nama}">
                                     Pilih
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#table-pelanggan tbody').append(row);
                });
            }
        }

        // Buka modal pilih pemilik
        $('#btn-pilih-pemilik').click(function() {
            loadPelanggan();
            $('#modalPilihPemilik').modal('show');
        });

        // Pencarian pelanggan
        $('#btn-search-pelanggan').click(function() {
            const keyword = $('#search-pelanggan').val();
            renderPelangganTable(keyword);
        });

        // Enter key pada input pencarian
        $('#search-pelanggan').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btn-search-pelanggan').click();
            }
        });

        // Tombol pilih pelanggan pada setiap baris
        $(document).on('click', '.btn-pilih-pelanggan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#idpelanggan').val(id);
            $('#nama-pemilik').val(nama);
            $('#modalPilihPemilik').modal('hide');
        });

        // Reset pilihan pemilik
        $('#btn-reset-pemilik').click(function() {
            $('#idpelanggan').val('');
            $('#nama-pemilik').val('');
        });

        // Fungsi untuk memuat data hewan
        function loadData() {
            const jenis = $('#jenis').val();
            const idpelanggan = $('#idpelanggan').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Tampilkan loading
            showLoading();

            // Kosongkan tabel
            $('#table-hewan tbody').empty();

            // Ambil data dari server
            $.ajax({
                url: '<?= site_url('admin/laporan/hewan/data') ?>',
                type: 'GET',
                data: {
                    jenis: jenis,
                    idpelanggan: idpelanggan,
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir
                },
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (response.status === 'success') {
                        let no = 1;
                        if (response.data.length === 0) {
                            $('#table-hewan tbody').html('<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>');
                        } else {
                            $.each(response.data, function(i, item) {
                                const tanggal = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                }) : '-';

                                // Format umur dengan satuan
                                const umur = item.umur ? `${item.umur} ${item.satuan_umur || 'tahun'}` : '-';

                                $('#table-hewan tbody').append(`
                                    <tr>
                                        <td>${no++}</td>
                                        <td>${item.idhewan}</td>
                                        <td>${item.namahewan}</td>
                                        <td>${item.jenis}</td>
                                        <td>${umur}</td>
                                        <td>${item.nama_pelanggan}</td>
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
            $('#jenis').val('');
            $('#idpelanggan').val('');
            $('#nama-pemilik').val('');
            $('#tgl_awal').val('');
            $('#tgl_akhir').val('');
            loadData();
        });

        // Cetak PDF
        $('#btn-cetak').click(function() {
            const jenis = $('#jenis').val();
            const idpelanggan = $('#idpelanggan').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Buat URL untuk cetak PDF dengan parameter filter
            let url = '<?= site_url('admin/laporan/hewan/cetak') ?>';
            let params = [];

            if (jenis) params.push(`jenis=${jenis}`);
            if (idpelanggan) params.push(`idpelanggan=${idpelanggan}`);
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