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
            Daftar Fasilitas
            <button class="btn btn-primary btn-sm float-end" id="btn-add-fasilitas">
                <i class="fas fa-plus"></i> Tambah Fasilitas
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-fasilitas" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Fasilitas</th>
                            <th>Nama Fasilitas</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Satuan</th>
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

<!-- Modal Fasilitas -->
<div class="modal fade" id="fasilitasModal" tabindex="-1" aria-labelledby="fasilitasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fasilitasModalLabel">Tambah Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-fasilitas">
                    <input type="hidden" id="kdfasilitas" name="kdfasilitas">
                    <div class="mb-3">
                        <label for="namafasilitas" class="form-label">Nama Fasilitas</label>
                        <input type="text" class="form-control" id="namafasilitas" name="namafasilitas" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Kandang">Kandang</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Grooming">Grooming</option>
                            <option value="Medis">Medis</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <select class="form-select" id="satuan" name="satuan" required>
                            <option value="Hari">Hari</option>
                            <option value="Unit">Unit</option>
                            <option value="Paket">Paket</option>
                            <option value="Kali">Kali</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save-fasilitas">Simpan</button>
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
                <p>Apakah Anda yakin ingin menghapus fasilitas ini?</p>
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
        let table = $('#table-fasilitas').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/fasilitas/getFasilitas') ?>',
                type: 'GET'
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'kdfasilitas'
                },
                {
                    data: 'namafasilitas'
                },
                {
                    data: 'kategori'
                },
                {
                    data: 'harga'
                },
                {
                    data: 'satuan'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Tambah Fasilitas
        $('#btn-add-fasilitas').click(function() {
            $('#fasilitasModalLabel').text('Tambah Fasilitas');
            $('#form-fasilitas')[0].reset();
            $('#kdfasilitas').val('');
            $('#fasilitasModal').modal('show');
        });

        // Edit Fasilitas
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $('#fasilitasModalLabel').text('Edit Fasilitas');

            $.ajax({
                url: '<?= site_url('admin/fasilitas/getFasilitasById/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let data = response.data;
                        $('#kdfasilitas').val(data.kdfasilitas);
                        $('#namafasilitas').val(data.namafasilitas);
                        $('#kategori').val(data.kategori);
                        $('#harga').val(data.harga);
                        $('#satuan').val(data.satuan);
                        $('#keterangan').val(data.keterangan);

                        $('#fasilitasModal').modal('show');
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
                        text: 'Terjadi kesalahan saat mengambil data'
                    });
                }
            });
        });

        // Simpan Fasilitas
        $('#btn-save-fasilitas').click(function() {
            let formData = $('#form-fasilitas').serialize();
            let id = $('#kdfasilitas').val();
            let url = id ?
                '<?= site_url('admin/fasilitas/updateFasilitas/') ?>' + id :
                '<?= site_url('admin/fasilitas/addFasilitas') ?>';

            $.ajax({
                url: url,
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
                        $('#fasilitasModal').modal('hide');
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
                    let response = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response?.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
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
                    url: '<?= site_url('admin/fasilitas/deleteFasilitas/') ?>' + deleteId,
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
    });
</script>
<?= $this->endSection(); ?>