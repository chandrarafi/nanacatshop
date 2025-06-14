<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Perawatan</h5>
                <a href="<?= site_url('admin/perawatan/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Perawatan
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filter_pelanggan" class="form-label">Filter Pelanggan</label>
                        <select id="filter_pelanggan" class="form-select">
                            <option value="">Semua Pelanggan</option>
                            <?php foreach ($pelanggan as $p) : ?>
                                <option value="<?= $p['idpelanggan'] ?>"><?= $p['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="filter_tanggal_mulai">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_tanggal_akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="filter_tanggal_akhir">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_status" class="form-label">Status</label>
                        <select id="filter_status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="0">Pending</option>
                            <option value="1">Dalam Proses</option>
                            <option value="2">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-perawatan" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Hewan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data perawatan ini?</p>
                <p class="text-danger">Perhatian: Semua data detail perawatan juga akan dihapus.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let table = $('#table-perawatan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/perawatan/getPerawatan') ?>',
                type: 'GET',
                data: function(d) {
                    d.idpelanggan = $('#filter_pelanggan').val();
                    d.tanggal_mulai = $('#filter_tanggal_mulai').val();
                    d.tanggal_akhir = $('#filter_tanggal_akhir').val();
                    d.status = $('#filter_status').val();
                }
            },
            columns: [{
                    data: 'kdperawatan'
                },
                {
                    data: 'tglperawatan'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'namahewan'
                },
                {
                    data: 'grandtotal'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action',
                    orderable: false
                }
            ],
            order: [
                [0, 'desc']
            ]
        });

        // Apply filters
        $('#filter_pelanggan, #filter_status').change(function() {
            table.ajax.reload();
        });

        $('#filter_tanggal_mulai, #filter_tanggal_akhir').change(function() {
            if ($('#filter_tanggal_mulai').val() && $('#filter_tanggal_akhir').val()) {
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
        $('#confirmDelete').click(function() {
            if (deleteId) {
                $.ajax({
                    url: '<?= site_url('admin/perawatan/delete/') ?>' + deleteId,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                table.ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#deleteModal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>