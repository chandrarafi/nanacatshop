<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Edit Data Hewan</h1>
        <p class="mb-0 text-secondary">Edit data hewan <?= $hewan['namahewan'] ?></p>
    </div>
    <a href="<?= site_url('admin/hewan') ?>" class="btn btn-secondary d-flex align-items-center">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Alert untuk error umum -->
                <div id="generalError" class="alert alert-danger" style="display: none;">
                    <ul class="mb-0"></ul>
                </div>

                <form id="hewanForm" enctype="multipart/form-data">
                    <input type="hidden" name="idhewan" value="<?= $hewan['idhewan'] ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ID Hewan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="bi bi-upc"></i></span>
                                <input type="text" class="form-control" value="<?= $hewan['idhewan'] ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="idpelanggan" class="form-label">Pemilik <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="hidden" id="idpelanggan" name="idpelanggan" value="<?= $hewan['idpelanggan'] ?>">
                                <?php
                                $namaPemilik = '';
                                foreach ($pelanggan as $p) {
                                    if ($p['idpelanggan'] == $hewan['idpelanggan']) {
                                        $namaPemilik = $p['nama'];
                                        break;
                                    }
                                }
                                ?>
                                <input type="text" class="form-control" id="namaPelanggan" value="<?= $namaPemilik ?>" placeholder="Pilih pemilik hewan" readonly>
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#pilihPelangganModal">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="namahewan" class="form-label">Nama Hewan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="namahewan" name="namahewan" value="<?= $hewan['namahewan'] ?>" placeholder="Masukkan nama hewan">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="jenis" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis" name="jenis">
                                <option value="">Pilih Jenis Hewan</option>
                                <option value="1" <?= ($hewan['jenis'] == '1') ? 'selected' : '' ?>>Kucing</option>
                                <option value="2" <?= ($hewan['jenis'] == '2') ? 'selected' : '' ?>>Anjing</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="umur" class="form-label">Umur</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="umur" name="umur" value="<?= $hewan['umur'] ?>" placeholder="Masukkan umur hewan" min="0" max="30">
                                <select class="form-select" id="satuan_umur" name="satuan_umur" style="max-width: 120px;">
                                    <option value="tahun" <?= (isset($hewan['satuan_umur']) && $hewan['satuan_umur'] == 'tahun') ? 'selected' : '' ?>>Tahun</option>
                                    <option value="bulan" <?= (isset($hewan['satuan_umur']) && $hewan['satuan_umur'] == 'bulan') ? 'selected' : '' ?>>Bulan</option>
                                </select>
                            </div>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Untuk umur bulan lebih dari 12 akan dikonversi ke format Tahun + Bulan</small>
                        </div>
                        <div class="col-md-6">
                            <label for="jenkel" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenkel" name="jenkel">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= ($hewan['jenkel'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= ($hewan['jenkel'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="foto" class="form-label">Foto Hewan</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="hapusFoto" name="hapusFoto" value="1">
                                <label class="form-check-label" for="hapusFoto">
                                    Hapus foto saat ini
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-4">
                                <?php if (!empty($hewan['foto']) && file_exists(FCPATH . 'uploads/hewan/' . $hewan['foto'])) : ?>
                                    <img src="<?= base_url('uploads/hewan/' . $hewan['foto']) ?>" id="fotoPreview" class="img-thumbnail" style="max-height: 150px;">
                                <?php else : ?>
                                    <img src="<?= base_url('assets/img/cat-default.webp') ?>" id="fotoPreview" class="img-thumbnail" style="max-height: 150px;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2" id="btnSave">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="<?= site_url('admin/hewan') ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Styles for this page -->
<?= $this->section('styles') ?>
<style>
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: none;
        border-radius: 0.5rem;
    }

    .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
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

    /* Fix untuk modal backdrop */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
        width: 100% !important;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(44, 62, 80, 0.6) !important;
        backdrop-filter: blur(4px) !important;
        -webkit-backdrop-filter: blur(4px) !important;
        z-index: 1040 !important;
    }

    .modal-backdrop.show {
        opacity: 0.5 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 1050 !important;
    }

    .modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1060 !important;
        width: 100% !important;
        height: 100% !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        outline: 0 !important;
    }

    .modal-dialog {
        margin: 1.75rem auto !important;
        max-width: 500px !important;
    }

    .modal-lg {
        max-width: 800px !important;
    }

    .modal-content {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.3) !important;
        border: none !important;
        border-radius: 0.5rem !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Preview foto saat dipilih
        $('#foto').change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#fotoPreview').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Checkbox hapus foto
        $('#hapusFoto').change(function() {
            if ($(this).is(':checked')) {
                $('#fotoPreview').attr('src', '<?= base_url('assets/img/default_pet.jpg') ?>');
            } else {
                <?php if (!empty($hewan['foto']) && file_exists(FCPATH . 'uploads/hewan/' . $hewan['foto'])) : ?>
                    $('#fotoPreview').attr('src', '<?= base_url('uploads/hewan/' . $hewan['foto']) ?>');
                <?php else : ?>
                    $('#fotoPreview').attr('src', '<?= base_url('assets/img/default_pet.jpg') ?>');
                <?php endif; ?>
            }
        });

        // Pilih Pelanggan dari Modal
        $('#tablePelanggan').on('click', '.btn-pilih-pelanggan', function() {
            var idPelanggan = $(this).data('id');
            var namaPelanggan = $(this).data('nama');

            $('#idpelanggan').val(idPelanggan);
            $('#namaPelanggan').val(namaPelanggan);
            $('#pilihPelangganModal').modal('hide');

            // Trigger change event untuk validasi
            $('#idpelanggan').trigger('change');
        });

        // Filter Table Pelanggan
        $('#searchPelanggan').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tablePelangganBody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Fix modal backdrop issue
        $('#pilihPelangganModal').on('shown.bs.modal', function() {
            $('body').addClass('modal-open');
            if ($('.modal-backdrop').length === 0) {
                $('body').append('<div class="modal-backdrop show"></div>');
            }
        });

        $('#pilihPelangganModal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $('body').css({
                'overflow': '',
                'padding-right': ''
            });
        });

        // Reset form errors
        function resetFormErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            $('#generalError').hide().find('ul').empty();
        }

        // Display validation errors
        function displayErrors(errors) {
            resetFormErrors();

            if (typeof errors === 'object' && errors !== null) {
                let hasFieldErrors = false;
                let generalErrors = [];

                for (const field in errors) {
                    const element = $('#' + field);
                    if (element.length) {
                        hasFieldErrors = true;
                        element.addClass('is-invalid');
                        element.siblings('.invalid-feedback').text(errors[field]);
                    } else {
                        generalErrors.push(errors[field]);
                    }
                }

                if (generalErrors.length > 0) {
                    $('#generalError').show();
                    const errorList = $('#generalError').find('ul');
                    generalErrors.forEach(error => {
                        errorList.append(`<li>${error}</li>`);
                    });
                }

                if (!hasFieldErrors && typeof errors === 'string') {
                    $('#generalError').show();
                    $('#generalError').find('ul').append(`<li>${errors}</li>`);
                }
            } else if (typeof errors === 'string') {
                $('#generalError').show();
                $('#generalError').find('ul').append(`<li>${errors}</li>`);
            }
        }

        // Submit form
        $('#hewanForm').submit(function(e) {
            e.preventDefault();
            resetFormErrors();

            // Validasi pemilik
            if (!$('#idpelanggan').val()) {
                $('#namaPelanggan').addClass('is-invalid');
                $('#namaPelanggan').siblings('.invalid-feedback').text('Pemilik hewan harus dipilih');
                return false;
            }

            // Disable button and show loading state
            $('#btnSave').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            // Gunakan FormData untuk mengirim file
            var formData = new FormData(this);

            $.ajax({
                url: '<?= site_url('admin/hewan/updateHewan/' . $hewan['idhewan']) ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = '<?= site_url('admin/hewan') ?>';
                        });
                    } else {
                        if (response.errors) {
                            displayErrors(response.errors);
                        } else {
                            displayErrors(response.message);
                        }
                        $('#btnSave').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Perubahan');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan pada server';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                        if (response.errors) {
                            displayErrors(response.errors);
                        } else {
                            displayErrors(errorMessage);
                        }
                    } catch (e) {
                        displayErrors(errorMessage);
                    }
                    $('#btnSave').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Perubahan');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

<!-- Modal Pilih Pelanggan -->
<div class="modal fade" id="pilihPelangganModal" tabindex="-1" aria-labelledby="pilihPelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="pilihPelangganModalLabel">Pilih Pemilik Hewan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="searchPelanggan" class="form-control" placeholder="Cari nama pemilik...">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablePelanggan">
                        <thead>
                            <tr>
                                <th>ID Pelanggan</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tablePelangganBody">
                            <?php foreach ($pelanggan as $p) : ?>
                                <tr>
                                    <td><?= $p['idpelanggan'] ?></td>
                                    <td><?= $p['nama'] ?></td>
                                    <td><?= $p['alamat'] ?></td>
                                    <td><?= $p['nohp'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-pelanggan"
                                            data-id="<?= $p['idpelanggan'] ?>"
                                            data-nama="<?= $p['nama'] ?>">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>