<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Detail Supplier</h1>
        <p class="mb-0 text-secondary">Informasi detail supplier</p>
    </div>
    <a href="<?= site_url('admin/supplier') ?>" class="btn btn-secondary d-flex align-items-center">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary bg-opacity-10 py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Supplier</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-detail">
                                    <tr>
                                        <td class="fw-bold" width="30%">Kode Supplier</td>
                                        <td width="5%">:</td>
                                        <td width="65%"><?= $supplier['kdspl'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nama Supplier</td>
                                        <td>:</td>
                                        <td><?= $supplier['namaspl'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">No. HP</td>
                                        <td>:</td>
                                        <td><?= $supplier['nohp'] ?: '-' ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-detail">
                                    <tr>
                                        <td class="fw-bold" width="30%">Email</td>
                                        <td width="5%">:</td>
                                        <td width="65%"><?= $supplier['email'] ?: '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Alamat</td>
                                        <td>:</td>
                                        <td><?= $supplier['alamat'] ?: '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Dibuat</td>
                                        <td>:</td>
                                        <td><?= date('d-m-Y H:i', strtotime($supplier['created_at'])) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-info btn-edit" data-id="<?= $supplier['kdspl'] ?>">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </button>
                    <button class="btn btn-danger btn-delete" data-id="<?= $supplier['kdspl'] ?>">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Supplier Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalTitle">Edit Supplier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formSupplier">
                <div class="modal-body">
                    <input type="hidden" id="isEdit" value="1">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kdspl" class="form-label fw-bold">Kode Supplier</label>
                                <input type="text" class="form-control" id="kdspl" name="kdspl" value="<?= $supplier['kdspl'] ?>" readonly>
                                <div class="invalid-feedback" id="kdspl-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="namaspl" class="form-label fw-bold">Nama Supplier <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namaspl" name="namaspl" value="<?= $supplier['namaspl'] ?>" required>
                                <div class="invalid-feedback" id="namaspl-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="nohp" class="form-label fw-bold">No. HP</label>
                                <input type="text" class="form-control" id="nohp" name="nohp" value="<?= $supplier['nohp'] ?>">
                                <div class="invalid-feedback" id="nohp-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $supplier['email'] ?>">
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="5"><?= $supplier['alamat'] ?></textarea>
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
                <input type="hidden" id="deleteSupplierId" value="<?= $supplier['kdspl'] ?>">
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

<?= $this->section('styles') ?>
<style>
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: none;
        border-radius: 0.5rem;
    }

    .table-detail td {
        padding: 0.5rem 0.5rem;
        border: none;
    }

    .table-detail tr:not(:last-child) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
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
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Edit Supplier
        $('.btn-edit').on('click', function() {
            $('#supplierModal').modal('show');
        });

        // Delete Supplier - Show confirmation modal
        $('.btn-delete').on('click', function() {
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
                        }).then(function() {
                            window.location.href = '<?= site_url('admin/supplier') ?>';
                        });
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

        // Form Submit - Update Supplier
        $('#formSupplier').submit(function(e) {
            e.preventDefault();

            // Reset form validation
            $('#formSupplier').find('.is-invalid').removeClass('is-invalid');

            // Get form data
            let formData = {
                kdspl: $('#kdspl').val(),
                namaspl: $('#namaspl').val(),
                nohp: $('#nohp').val(),
                email: $('#email').val(),
                alamat: $('#alamat').val()
            };

            // Disable submit button and show loading state
            $('#btnSimpan').attr('disabled', true);
            $('#btnSimpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            $.ajax({
                url: '<?= site_url('admin/supplier/updateSupplier') ?>/' + formData.kdspl,
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
                        }).then(function() {
                            window.location.reload();
                        });
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
<?= $this->endSection() ?>