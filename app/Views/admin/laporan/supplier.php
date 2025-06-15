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
                <h5 class="mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form id="form-filter">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Cari berdasarkan nama, kode, no HP, atau email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="text" class="form-control datepicker" name="tgl_awal" id="tgl_awal" placeholder="Pilih Tanggal Awal">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="text" class="form-control datepicker" name="tgl_akhir" id="tgl_akhir" placeholder="Pilih Tanggal Akhir">
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary" id="btn-filter">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <button type="button" class="btn btn-success" id="btn-cetak">
                                <i class="bi bi-printer"></i> Cetak PDF
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn-reset">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
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
                <h5 class="mb-0">Data Supplier</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table-supplier">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Supplier</th>
                                <th>Nama Supplier</th>
                                <th>No. HP</th>
                                <th>Email</th>
                                <th>Alamat</th>
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

        // Fungsi untuk memuat data supplier
        function loadData() {
            const keyword = $('#keyword').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Tampilkan loading
            showLoading();

            // Kosongkan tabel
            $('#table-supplier tbody').empty();

            // Ambil data dari server
            $.ajax({
                url: '<?= site_url('admin/laporan/supplier/data') ?>',
                type: 'GET',
                data: {
                    keyword: keyword,
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir
                },
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (response.status === 'success') {
                        let no = 1;
                        if (response.data.length === 0) {
                            $('#table-supplier tbody').html('<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>');
                        } else {
                            $.each(response.data, function(i, item) {
                                const tanggal = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                }) : '-';

                                $('#table-supplier tbody').append(`
                                    <tr>
                                        <td>${no++}</td>
                                        <td>${item.kdspl}</td>
                                        <td>${item.namaspl}</td>
                                        <td>${item.nohp || '-'}</td>
                                        <td>${item.email || '-'}</td>
                                        <td>${item.alamat || '-'}</td>
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
            $('#keyword').val('');
            $('#tgl_awal').val('');
            $('#tgl_akhir').val('');
            loadData();
        });

        // Enter key pada input pencarian
        $('#keyword').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btn-filter').click();
            }
        });

        // Cetak PDF
        $('#btn-cetak').click(function() {
            const keyword = $('#keyword').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Buat URL untuk cetak PDF dengan parameter filter
            let url = '<?= site_url('admin/laporan/supplier/cetak') ?>';
            let params = [];

            if (keyword) params.push(`keyword=${keyword}`);
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