<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Daftar Penjualan
            <a href="<?= site_url('admin/penjualan/create') ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Tambah Penjualan
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filter-pelanggan" class="form-label">Filter Pelanggan</label>
                        <select name="idpelanggan" id="filter-pelanggan" class="form-control">
                            <option value="">Semua Pelanggan</option>
                            <?php foreach ($pelanggan as $p) : ?>
                                <option value="<?= $p['idpelanggan'] ?>"><?= $p['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filter-tanggal-mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="filter-tanggal-mulai" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filter-tanggal-akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="filter-tanggal-akhir" class="form-control">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-penjualan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Penjualan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data diisi melalui AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data penjualan ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        let table = $('#table-penjualan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/penjualan/getPenjualan') ?>',
                type: 'GET',
                data: function(d) {
                    d.idpelanggan = $('#filter-pelanggan').val();
                    d.tanggal_mulai = $('#filter-tanggal-mulai').val();
                    d.tanggal_akhir = $('#filter-tanggal-akhir').val();
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'kdpenjualan'
                },
                {
                    data: 'tglpenjualan'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'grandtotal'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Filter berdasarkan pelanggan
        $('#filter-pelanggan').change(function() {
            table.ajax.reload();
        });

        // Filter berdasarkan tanggal
        $('#filter-tanggal-mulai, #filter-tanggal-akhir').change(function() {
            if ($('#filter-tanggal-mulai').val() && $('#filter-tanggal-akhir').val()) {
                table.ajax.reload();
            }
        });

        // Handle delete button
        let deleteId = null;
        $(document).on('click', '.btn-delete', function() {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#btn-confirm-delete').click(function() {
            if (deleteId) {
                $.ajax({
                    url: '<?= site_url('admin/penjualan/delete/') ?>' + deleteId,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#deleteModal').modal('hide');
                            table.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection(); ?>