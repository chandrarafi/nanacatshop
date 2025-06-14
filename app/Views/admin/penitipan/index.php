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
            Daftar Penitipan
            <a href="<?= site_url('admin/penitipan/create') ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Tambah Penitipan
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
                        <label for="filter-status" class="form-label">Status</label>
                        <select name="status" id="filter-status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="0">Pending</option>
                            <option value="1">Dalam Penitipan</option>
                            <option value="2">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="table-penitipan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Penitipan</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Pelanggan</th>
                            <th>Hewan</th>
                            <th>Total Biaya</th>
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
                <p>Apakah Anda yakin ingin menghapus data penitipan ini?</p>
                <p class="text-danger"><strong>Perhatian:</strong> Menghapus data penitipan akan menghapus semua data terkait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status Penitipan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-status">
                    <input type="hidden" id="idpenitipan" name="idpenitipan">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="0">Pending</option>
                            <option value="1">Dalam Penitipan</option>
                            <option value="2">Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save-status">Simpan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        let table = $('#table-penitipan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/penitipan/getPenitipan') ?>',
                type: 'GET',
                data: function(d) {
                    d.idpelanggan = $('#filter-pelanggan').val();
                    d.tanggal_mulai = $('#filter-tanggal-mulai').val();
                    d.status = $('#filter-status').val();
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'kdpenitipan'
                },
                {
                    data: 'tglpenitipan'
                },
                {
                    data: 'tglselesai'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'namahewan'
                },
                {
                    data: 'grandtotal',

                },
                {
                    data: 'status',

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
        $('#filter-tanggal-mulai').change(function() {
            table.ajax.reload();
        });

        // Filter berdasarkan status
        $('#filter-status').change(function() {
            table.ajax.reload();
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
                    url: '<?= site_url('admin/penitipan/delete/') ?>' + deleteId,
                    type: 'POST',
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

        // Handle status update button
        $(document).on('click', '.btn-status', function() {
            let id = $(this).data('id');
            let currentStatus = $(this).data('status');

            $('#idpenitipan').val(id);
            $('#status').val(currentStatus);
            $('#statusModal').modal('show');
        });

        // Save status update
        $('#btn-save-status').click(function() {
            let formData = $('#form-status').serialize();

            $.ajax({
                url: '<?= site_url('admin/penitipan/updateStatus') ?>',
                type: 'POST',
                data: formData,
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
                        $('#statusModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate status'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>