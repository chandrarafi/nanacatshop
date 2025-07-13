<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                        <!-- <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenkel" id="jenkel">
                                <option value="">Semua</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="text" class="form-control datepicker" name="tgl_awal" id="tgl_awal" placeholder="Pilih Tanggal Awal">
                        </div>
                        <div class="col-md-4">
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
                <h5 class="mb-0">Data Pelanggan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="table-pelanggan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pelanggan</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Tanggal Terdaftar</th>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi date picker
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            allowInput: true,
            locale: "id"
        });

        // Fungsi untuk memuat data
        function loadData() {
            const jenkel = $('#jenkel').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Tampilkan loading
            showLoading();

            // Kosongkan tabel
            $('#table-pelanggan tbody').empty();

            // Ambil data dari server
            $.ajax({
                url: '<?= site_url('admin/laporan/pelanggan/data') ?>',
                type: 'GET',
                data: {
                    jenkel: jenkel,
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir
                },
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    if (response.status === 'success') {
                        let no = 1;
                        if (response.data.length === 0) {
                            $('#table-pelanggan tbody').html('<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>');
                        } else {
                            $.each(response.data, function(i, item) {
                                const jenkel = item.jenkel === 'L' ? 'Laki-laki' : 'Perempuan';
                                const tanggal = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                }) : '-';

                                $('#table-pelanggan tbody').append(`
                                    <tr>
                                        <td>${no++}</td>
                                        <td>${item.idpelanggan}</td>
                                        <td>${item.nama}</td>
                                        <td>${jenkel}</td>
                                        <td>${item.alamat || '-'}</td>
                                        <td>${item.nohp || '-'}</td>
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
            $('#jenkel').val('');
            $('#tgl_awal').val('');
            $('#tgl_akhir').val('');
            loadData();
        });

        // Cetak PDF
        $('#btn-cetak').click(function() {
            const jenkel = $('#jenkel').val();
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();

            // Buat URL untuk cetak PDF dengan parameter filter
            let url = '<?= site_url('admin/laporan/pelanggan/cetak') ?>';
            let params = [];

            if (jenkel) params.push(`jenkel=${jenkel}`);
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