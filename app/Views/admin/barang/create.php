<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Tambah Barang</h1>
        <p class="mb-0 text-secondary">Formulir tambah data barang</p>
    </div>
    <a href="<?= site_url('admin/barang') ?>" class="btn btn-secondary d-flex align-items-center">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="formBarang" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kdbarang" class="form-label fw-bold">Kode Barang</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="kdbarang" name="kdbarang" placeholder="Otomatis" readonly>
                                    <button type="button" class="btn btn-info" id="btnGenerateKdBarang">Generate</button>
                                </div>
                                <div class="invalid-feedback" id="kdbarang-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="namabarang" class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namabarang" name="namabarang" required>
                                <div class="invalid-feedback" id="namabarang-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label fw-bold">Jumlah Stok</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="0" value="0">
                                <div class="invalid-feedback" id="jumlah-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="satuan" class="form-label fw-bold">Satuan</label>
                                <select class="form-select" id="satuan" name="satuan">
                                    <option value="">Pilih Satuan</option>
                                    <?php foreach ($satuan as $s) : ?>
                                        <option value="<?= $s ?>"><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="satuan-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hargabeli" class="form-label fw-bold">Harga Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="hargabeli" name="hargabeli" min="0" required>
                                </div>
                                <div class="invalid-feedback" id="hargabeli-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="hargajual" class="form-label fw-bold">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="hargajual" name="hargajual" min="0" required>
                                </div>
                                <div class="invalid-feedback" id="hargajual-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="kdkategori" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kdkategori" name="kdkategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($kategori as $k) : ?>
                                        <option value="<?= $k['kdkategori'] ?>"><?= $k['namakategori'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="kdkategori-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="foto" class="form-label fw-bold">Foto Barang</label>
                            <div class="custom-file-upload mb-2">
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <div class="invalid-feedback" id="foto-error"></div>
                            </div>
                            <small class="text-secondary">Format: JPG, JPEG, PNG. Maks. 2MB</small>
                            <div class="mt-3">
                                <div id="previewContainer" class="d-none mt-3">
                                    <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="bi bi-x-circle me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSimpan">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
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

    .custom-file-upload {
        position: relative;
        overflow: hidden;
    }

    .custom-file-upload input[type=file] {
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Generate Kode Barang
        $('#btnGenerateKdBarang').on('click', function() {
            $.ajax({
                url: '<?= site_url('admin/barang/getNextKdBarang') ?>',
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#btnGenerateKdBarang').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#kdbarang').val(response.data.kdbarang);
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal generate kode barang',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    $('#btnGenerateKdBarang').html('Generate');
                }
            });
        });

        // Generate Kode Barang otomatis saat halaman dimuat
        $('#btnGenerateKdBarang').trigger('click');

        // Preview image before upload
        $('#foto').change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#imagePreview').attr('src', event.target.result);
                    $('#previewContainer').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                $('#previewContainer').addClass('d-none');
            }
        });

        // Reset Form
        $('button[type="reset"]').click(function() {
            $('#previewContainer').addClass('d-none');
            $('#formBarang').find('.is-invalid').removeClass('is-invalid');
            $('#btnGenerateKdBarang').trigger('click');
        });

        // Form Submit
        $('#formBarang').submit(function(e) {
            e.preventDefault();

            // Reset form validation
            $('#formBarang').find('.is-invalid').removeClass('is-invalid');

            // Create FormData object to handle file upload
            let formData = new FormData(this);

            // Disable submit button and show loading state
            $('#btnSimpan').attr('disabled', true);
            $('#btnSimpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            $.ajax({
                url: '<?= site_url('admin/barang/addBarang') ?>',
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
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.href = '<?= site_url('admin/barang') ?>';
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
    });
</script>
<?= $this->endSection() ?>