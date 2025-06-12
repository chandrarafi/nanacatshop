<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Supplier</h1>
        <p class="mb-0 text-secondary">Kelola data supplier</p>
    </div>
    <button class="btn btn-primary d-flex align-items-center" id="btnTambahSupplier">
        <i class="bi bi-plus-circle me-2"></i> Tambah Supplier
    </button>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="supplierTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Kode Supplier</th>
                                <th width="20%">Nama Supplier</th>
                                <th width="15%">No. HP</th>
                                <th width="20%">Email</th>
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

<!-- Supplier Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title" id="modalTitle">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSupplier">
                <div class="modal-body">
                    <input type="hidden" id="isEdit" value="0">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kdspl" class="form-label fw-bold">Kode Supplier</label>
                                <input type="text" class="form-control" id="kdspl" name="kdspl" placeholder="Otomatis (diisi sistem)" readonly>
                                <div class="invalid-feedback" id="kdspl-error"></div>
                                <small class="text-muted">Kode supplier akan digenerate otomatis oleh sistem</small>
                            </div>
                            <div class="mb-3">
                                <label for="namaspl" class="form-label fw-bold">Nama Supplier <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namaspl" name="namaspl" required>
                                <div class="invalid-feedback" id="namaspl-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="nohp" class="form-label fw-bold">No. HP</label>
                                <input type="text" class="form-control" id="nohp" name="nohp">
                                <div class="invalid-feedback" id="nohp-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="5"></textarea>
                                <div class="invalid-feedback" id="alamat-error"></div>
                            </div>
                        </div>
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
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus supplier ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deleteSupplierId">
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
        var supplierTable = $('#supplierTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/supplier/getSupplier') ?>',
                type: 'GET'
            },
            columns: [{
                    data: 'nomor'
                },
                {
                    data: 'kdspl'
                },
                {
                    data: 'namaspl'
                },
                {
                    data: 'nohp'
                },
                {
                    data: 'email'
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
                emptyTable: "Tidak ada data supplier",
                zeroRecords: "Tidak ada data supplier yang cocok",
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

        // Open Add Supplier Modal
        $('#btnTambahSupplier').on('click', function() {
            resetForm();
            $('#modalTitle').text('Tambah Supplier');
            $('#modalHeader').removeClass('bg-info text-white').addClass('bg-primary text-white');
            $('#isEdit').val('0');
            $('#kdspl').val('');

            // Get kode supplier baru dari server
            $.ajax({
                url: '<?= site_url('admin/supplier/addSupplier') ?>',
                type: 'POST',
                data: {
                    getKodeOnly: 'true',
                    namaspl: 'temp'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#kdspl').val(response.data.kdspl);
                    }
                }
            });

            $('#supplierModal').modal('show');
        });

        // Edit Supplier - Show form with data
        $(document).on('click', '.btn-edit', function() {
            let supplierId = $(this).data('id');

            $.ajax({
                url: '<?= site_url('admin/supplier/getSupplierById') ?>/' + supplierId,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    resetForm();
                },
                success: function(response) {
                    if (response.status === 'success') {
                        let data = response.data;
                        $('#kdspl').val(data.kdspl);
                        $('#namaspl').val(data.namaspl);
                        $('#nohp').val(data.nohp);
                        $('#email').val(data.email);
                        $('#alamat').val(data.alamat);

                        $('#modalTitle').text('Edit Supplier');
                        $('#modalHeader').removeClass('bg-primary text-white').addClass('bg-info text-white');
                        $('#isEdit').val('1');
                        $('#supplierModal').modal('show');
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
                        text: 'Gagal memuat data supplier',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Delete Supplier - Show confirmation modal
        $(document).on('click', '.btn-delete', function() {
            let supplierId = $(this).data('id');
            $('#deleteSupplierId').val(supplierId);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete Supplier
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            let supplierId = $('#deleteSupplierId').val();

            $.ajax({
                url: '<?= site_url('admin/supplier/deleteSupplier') ?>/' + supplierId,
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
                        supplierTable.ajax.reload();
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

        // Form Submit - Add or Update Supplier
        $('#formSupplier').submit(function(e) {
            e.preventDefault();

            // Reset form validation
            $('#formSupplier').find('.is-invalid').removeClass('is-invalid');

            // Get form data
            let formData = {
                namaspl: $('#namaspl').val(),
                nohp: $('#nohp').val(),
                email: $('#email').val(),
                alamat: $('#alamat').val()
            };

            // Untuk edit, tambahkan kdspl
            let isEdit = $('#isEdit').val() === '1';
            if (isEdit) {
                formData.kdspl = $('#kdspl').val();
            }

            // Determine if add or edit
            let url = isEdit ?
                '<?= site_url('admin/supplier/updateSupplier') ?>/' + formData.kdspl :
                '<?= site_url('admin/supplier/addSupplier') ?>';

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
                        $('#supplierModal').modal('hide');
                        Swal.fire({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        supplierTable.ajax.reload();
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
            $('#formSupplier').trigger('reset');
            $('#formSupplier').find('.is-invalid').removeClass('is-invalid');
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