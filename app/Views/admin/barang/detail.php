<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Detail Barang</h1>
        <p class="mb-0 text-secondary">Informasi detail barang</p>
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
                <div class="row">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <?php if (!empty($barang['foto']) && file_exists(FCPATH . 'uploads/barang/' . $barang['foto'])) : ?>
                            <img src="<?= base_url('uploads/barang/' . $barang['foto']) ?>" alt="Foto Barang" class="img-fluid rounded shadow" style="max-height: 300px; width: auto;">
                        <?php else : ?>
                            <img src="<?= base_url('assets/img/product-default.jpg') ?>" alt="Default" class="img-fluid rounded shadow" style="max-height: 300px; width: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary bg-opacity-10 py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Informasi Barang</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-detail">
                                            <tr>
                                                <td class="fw-bold">Kode Barang</td>
                                                <td>:</td>
                                                <td><?= $barang['kdbarang'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Nama Barang</td>
                                                <td>:</td>
                                                <td><?= $barang['namabarang'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Stok</td>
                                                <td>:</td>
                                                <td><?= $barang['jumlah'] ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-detail">
                                            <tr>
                                                <td class="fw-bold">Kategori</td>
                                                <td>:</td>
                                                <td><?= $kategori ? $kategori['namakategori'] : 'Tidak ada' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Harga Beli</td>
                                                <td>:</td>
                                                <td>Rp <?= number_format($barang['hargabeli'], 0, ',', '.') ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Harga Jual</td>
                                                <td>:</td>
                                                <td>Rp <?= number_format($barang['hargajual'], 0, ',', '.') ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="<?= site_url('admin/barang/edit/' . $barang['kdbarang']) ?>" class="btn btn-info">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-delete" data-id="<?= $barang['kdbarang'] ?>">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus data barang ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deleteBarangId">
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
        // Delete Barang - Show confirmation modal
        $('.btn-delete').on('click', function() {
            let barangId = $(this).data('id');
            $('#deleteBarangId').val(barangId);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete Barang
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            let barangId = $('#deleteBarangId').val();

            $.ajax({
                url: '<?= site_url('admin/barang/deleteBarang') ?>/' + barangId,
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
                            window.location.href = '<?= site_url('admin/barang') ?>';
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