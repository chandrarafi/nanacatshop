<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Hewan</h1>
        <p class="mb-0 text-secondary">Tambahkan data hewan baru</p>
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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ID Hewan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="bi bi-upc"></i></span>
                                <input type="text" class="form-control" id="idHewan" name="idhewan" readonly>
                            </div>
                            <small class="text-muted">ID hewan akan digenerate otomatis oleh sistem</small>
                        </div>
                        <div class="col-md-6">
                            <label for="idpelanggan" class="form-label">Pemilik <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="hidden" id="idpelanggan" name="idpelanggan">
                                <input type="text" class="form-control" id="namaPelanggan" placeholder="Pilih pemilik hewan" readonly>
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
                            <input type="text" class="form-control" id="namahewan" name="namahewan" placeholder="Masukkan nama hewan">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="jenis" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis" name="jenis">
                                <option value="">Pilih Jenis Hewan</option>
                                <option value="1">Kucing</option>
                                <option value="2">Anjing</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="umur" class="form-label">Umur</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="umur" name="umur" placeholder="Masukkan umur hewan" min="0" max="30">
                                <select class="form-select" id="satuan_umur" name="satuan_umur" style="max-width: 120px;">
                                    <option value="tahun">Tahun</option>
                                    <option value="bulan">Bulan</option>
                                </select>
                            </div>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Untuk umur bulan lebih dari 12 akan dikonversi ke format Tahun + Bulan</small>
                        </div>
                        <div class="col-md-6">
                            <label for="jenkel" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenkel" name="jenkel">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
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
                        </div>
                        <div class="col-md-6">
                            <div class="mt-4">
                                <img src="<?= base_url('assets/img/cat-default.webp') ?>" id="fotoPreview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2" id="btnSave">
                                <i class="bi bi-save me-1"></i> Simpan
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
</style>



<?= $this->endSection() ?>



<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Get ID Hewan otomatis
        $.ajax({
            url: '<?= site_url('admin/hewan/getNextIdHewan') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#idHewan').val(response.data.idhewan);
                }
            },
            error: function() {
                $('#idHewan').val('Gagal mendapatkan ID');
            }
        });

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
                url: '<?= site_url('admin/hewan/addHewan') ?>',
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
                        $('#btnSave').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
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
                    $('#btnSave').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });
    });
</script>



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