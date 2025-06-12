<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Kategori</h1>
        <p class="mb-0 text-secondary">Kelola data kategori barang</p>
    </div>
    <button class="btn btn-primary d-flex align-items-center" id="btnTambahKategori">
        <i class="bi bi-plus-circle me-2"></i> Tambah Kategori
    </button>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="kategoriTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Kode Kategori</th>
                                <th width="65%">Nama Kategori</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded by DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kategori Modal -->
<div class="modal fade" id="kategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKategori">
                <div class="modal-body">
                    <input type="hidden" id="isEdit" value="0">
                    <div class="mb-3">
                        <label for="kdkategori" class="form-label fw-bold">Kode Kategori</label>
                        <input type="text" class="form-control" id="kdkategori" name="kdkategori" placeholder="Otomatis (diisi sistem)" readonly>
                        <div class="invalid-feedback" id="kdkategori-error"></div>
                        <small class="text-muted">Kode kategori akan digenerate otomatis oleh sistem</small>
                    </div>
                    <div class="mb-3">
                        <label for="namakategori" class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="namakategori" name="namakategori" required>
                        <div class="invalid-feedback" id="namakategori-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus kategori ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deleteKategoriId">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Styles for this page -->
<?= $this->section('styles') ?>
<style>
    .table th {
        background-color: #f8f9fc;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: none;
        border-radius: 0.5rem;
    }

    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .btn-primary {
        background-color: #FF69B4;
        border-color: #FF69B4;
    }

    .btn-primary:hover {
        background-color: #FF1493;
        border-color: #FF1493;
    }

    .btn-secondary {
        background-color: #999;
        border-color: #999;
    }

    .btn-secondary:hover {
        background-color: #777;
        border-color: #777;
    }

    .btn-info {
        background-color: #5bc0de;
        border-color: #5bc0de;
        color: white;
    }

    .btn-danger {
        background-color: #d9534f;
        border-color: #d9534f;
    }

    .modal-header.bg-primary {
        background-color: #FF69B4 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var kategoriTable = $('#kategoriTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/kategori/getKategori') ?>',
                type: 'GET'
            },
            columns: [{
                    data: 'nomor'
                },
                {
                    data: 'kdkategori'
                },
                {
                    data: 'namakategori'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ],
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            language: {
                emptyTable: "Tidak ada data kategori",
                zeroRecords: "Tidak ada data kategori yang cocok",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                lengthMenu: "Tampilkan _MENU_ data"
            }
        });

        // Open Add Kategori Modal
        $('#btnTambahKategori').on('click', function() {
            resetForm();
            $('#modalTitle').text('Tambah Kategori');
            $('#modalHeader').removeClass('bg-info text-white').addClass('bg-primary text-white');
            $('#isEdit').val('0');
            $('#kdkategori').val('');

            // Get kode kategori baru dari server
            $.ajax({
                url: '<?= site_url('admin/kategori/addKategori') ?>',
                type: 'POST',
                data: {
                    getKodeOnly: 'true',
                    namakategori: 'temp'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#kdkategori').val(response.data.kdkategori);
                    }
                }
            });

            $('#kategoriModal').modal('show');
        });

        // Edit Kategori - Show form with data
        $(document).on('click', '.btn-edit', function() {
            let kategoriId = $(this).data('id');

            $.ajax({
                url: '<?= site_url('admin/kategori/getKategoriById') ?>/' + kategoriId,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    resetForm();
                },
                success: function(response) {
                    if (response.status === 'success') {
                        let data = response.data;
                        $('#kdkategori').val(data.kdkategori);
                        $('#namakategori').val(data.namakategori);

                        $('#modalTitle').text('Edit Kategori');
                        $('#modalHeader').removeClass('bg-primary text-white').addClass('bg-info text-white');
                        $('#isEdit').val('1');
                        $('#kategoriModal').modal('show');
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal memuat data kategori',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Delete Kategori - Show confirmation modal
        $(document).on('click', '.btn-delete', function() {
            let kategoriId = $(this).data('id');
            $('#deleteKategoriId').val(kategoriId);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete Kategori
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            let kategoriId = $('#deleteKategoriId').val();

            $.ajax({
                url: '<?= site_url('admin/kategori/deleteKategori') ?>/' + kategoriId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#deleteModal').modal('hide');
                        Swal.fire({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        kategoriTable.ajax.reload();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan pada server',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    // Re-enable button and restore label
                    $('#btnConfirmDelete').attr('disabled', false);
                    $('#btnConfirmDelete').html('<i class="bi bi-trash me-1"></i> Hapus');
                }
            });
        });

        // Form Submit - Add or Update Kategori
        $('#formKategori').submit(function(e) {
            e.preventDefault();

            // Reset form validation
            $('#formKategori').find('.is-invalid').removeClass('is-invalid');

            // Get form data
            let formData = {
                namakategori: $('#namakategori').val()
            };

            // Untuk edit, tambahkan kdkategori
            let isEdit = $('#isEdit').val() === '1';
            if (isEdit) {
                formData.kdkategori = $('#kdkategori').val();
            }

            // Determine if add or edit
            let url = isEdit ?
                '<?= site_url('admin/kategori/updateKategori') ?>/' + formData.kdkategori :
                '<?= site_url('admin/kategori/addKategori') ?>';

            // Disable submit button and show loading state
            $('#btnSimpan').attr('disabled', true);
            $('#btnSimpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#kategoriModal').modal('hide');
                        Swal.fire({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        kategoriTable.ajax.reload();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });

                        // Display validation errors
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value);
                            });
                        }
                    }
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;
                    Swal.fire({
                        title: 'Error',
                        text: response && response.message ? response.message : 'Terjadi kesalahan pada server',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });

                    // Display validation errors from server if any
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key + '-error').text(value);
                        });
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    $('#btnSimpan').attr('disabled', false);
                    $('#btnSimpan').html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });

        // Helper function to reset form
        function resetForm() {
            $('#formKategori').trigger('reset');
            $('#formKategori').find('.is-invalid').removeClass('is-invalid');
        }

        // Fix modal backdrop issue
        $('.modal').on('shown.bs.modal', function() {
            $('body').addClass('modal-open');
            if ($('.modal-backdrop').length === 0) {
                $('body').append('<div class="modal-backdrop show"></div>');
            }
        });

        $('.modal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            // Fix untuk ukuran halaman setelah modal ditutup
            $('body').css({
                'overflow': '',
                'padding-right': ''
            });
        });
    });
</script>
<?= $this->endSection() ?>