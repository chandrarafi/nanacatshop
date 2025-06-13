<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Barang Masuk</h3>
                <p class="text-subtitle text-muted">Kelola data barang masuk pada sistem</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Barang Masuk</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Barang Masuk</h5>
                    <a href="<?= site_url('admin/barangmasuk/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Barang Masuk
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter-supplier">Filter Supplier</label>
                            <select class="form-select" id="filter-supplier">
                                <option value="">Semua Supplier</option>
                                <?php foreach ($supplier as $s) : ?>
                                    <option value="<?= $s['kdspl'] ?>"><?= $s['namaspl'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal-mulai">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal-mulai">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal-akhir">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal-akhir">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group pt-4">
                            <button class="btn btn-primary btn-block mt-2" id="btn-filter">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="table-barangmasuk">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Masuk</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi dari AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let table = $('#table-barangmasuk').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/barangmasuk/getBarangMasuk') ?>',
                method: 'GET',
                data: function(d) {
                    d.kdspl = $('#filter-supplier').val();
                    d.tanggal_mulai = $('#tanggal-mulai').val();
                    d.tanggal_akhir = $('#tanggal-akhir').val();
                }
            },
            columns: [{
                    // Kolom nomor urut yang digenerate di klien
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kdmasuk'
                },
                {
                    data: 'tglmasuk'
                },
                {
                    data: 'namaspl'
                },
                {
                    data: 'grandtotal'
                },
                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'desc']
            ] // Urutkan berdasarkan kolom kedua (kdmasuk) secara descending secara default
        });

        $('#btn-filter').click(function() {
            table.ajax.reload();
        });

        // Hapus data
        let idToDelete;

        $('#table-barangmasuk').on('click', '.btn-delete', function() {
            idToDelete = $(this).data('id');
            $('#modalDelete').modal('show');
        });

        $('#btn-confirm-delete').click(function() {
            $.ajax({
                url: '<?= site_url('admin/barangmasuk/deleteBarangMasuk/') ?>' + idToDelete,
                method: 'POST',
                success: function(response) {
                    $('#modalDelete').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        table.ajax.reload();
                    });
                },
                error: function(xhr, status, error) {
                    $('#modalDelete').modal('hide');
                    let response = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response?.message || 'Terjadi kesalahan saat menghapus data',
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>