<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Edit Barang</h1>
        <p class="mb-0 text-secondary">Formulir edit data barang</p>
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
                                <input type="text" class="form-control" id="kdbarang" name="kdbarang" value="<?= $barang['kdbarang'] ?>" readonly>
                                <div class="invalid-feedback" id="kdbarang-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="namabarang" class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="namabarang" name="namabarang" value="<?= $barang['namabarang'] ?>" required>
                                <div class="invalid-feedback" id="namabarang-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label fw-bold">Jumlah Stok</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="0" value="<?= $barang['jumlah'] ?>">
                                <div class="invalid-feedback" id="jumlah-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hargabeli" class="form-label fw-bold">Harga Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="hargabeli" name="hargabeli" min="0" value="<?= $barang['hargabeli'] ?>" required>
                                </div>
                                <div class="invalid-feedback" id="hargabeli-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="hargajual" class="form-label fw-bold">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="hargajual" name="hargajual" min="0" value="<?= $barang['hargajual'] ?>" required>
                                </div>
                                <div class="invalid-feedback" id="hargajual-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="kdkategori" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kdkategori" name="kdkategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($kategori as $k) : ?>
                                        <option value="<?= $k['kdkategori'] ?>" <?= ($k['kdkategori'] == $barang['kdkategori']) ? 'selected' : '' ?>><?= $k['namakategori'] ?></option>
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

                            <?php if (!empty($barang['foto']) && file_exists(FCPATH . 'uploads/barang/' . $barang['foto'])) : ?>
                                <div class="mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="<?= base_url('uploads/barang/' . $barang['foto']) ?>" alt="Foto Barang" class="img-thumbnail" style="max-height: 150px;">
                                        </div>

                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1">Foto saat ini: <span class="fw-bold"><?= $barang['foto'] ?></span></p>

                                </div>
                            <?php endif; ?>

                            <div class="mt-3">
                                <div id="previewContainer" class="d-none mt-3">
                                    <p class="mb-1">Preview foto baru:</p>
                                    <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="<?= site_url('admin/barang') ?>" class="btn btn-secondary me-2">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnUpdate">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
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

        // Handle hapusFoto checkbox interaction with file input
        $('#hapusFoto').change(function() {
            if ($(this).is(':checked')) {
                $('#foto').val('');
                $('#previewContainer').addClass('d-none');
            }
        });

        $('#foto').change(function() {
            if ($(this).val()) {
                $('#hapusFoto').prop('checked', false);
            }
        });

        // Form Submit
        $('#formBarang').submit(function(e) {
            e.preventDefault();

            // Reset form validation
            $('#formBarang').find('.is-invalid').removeClass('is-invalid');

            // Create FormData object to handle file upload
            let formData = new FormData(this);

            // Disable submit button and show loading state
            $('#btnUpdate').attr('disabled', true);
            $('#btnUpdate').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            $.ajax({
                url: '<?= site_url('admin/barang/updateBarang/' . $barang['kdbarang']) ?>',
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
                    $('#btnUpdate').attr('disabled', false);
                    $('#btnUpdate').html('<i class="bi bi-save me-1"></i> Simpan Perubahan');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>