<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Hewan</h1>
        <p class="mb-0 text-secondary">Kelola data hewan pelanggan</p>
    </div>
    <a href="<?= site_url('admin/hewan/create') ?>" class="btn btn-primary d-flex align-items-center">
        <i class="bi bi-plus-circle me-2"></i> Tambah Hewan
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="row mb-4 align-items-end">
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <label for="jenisFilter" class="form-label fw-bold">Jenis Hewan</label>
                        <select class="form-select" id="jenisFilter">
                            <option value="">Semua Jenis</option>
                            <option value="1">Kucing</option>
                            <option value="2">Anjing</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <label for="jenkelFilter" class="form-label fw-bold">Jenis Kelamin</label>
                        <select class="form-select" id="jenkelFilter">
                            <option value="">Semua</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                        <label for="pelangganFilter" class="form-label fw-bold">Pelanggan</label>
                        <select class="form-select" id="pelangganFilter">
                            <option value="">Semua Pelanggan</option>
                            <?php foreach ($pelanggan as $p) : ?>
                                <option value="<?= $p['idpelanggan'] ?>"><?= $p['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 text-md-end d-flex align-items-end">
                        <button id="resetFilter" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-repeat me-1"></i> Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="hewanTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">ID Hewan</th>
                                <th width="15%">Nama</th>
                                <th width="10%">Jenis</th>
                                <th width="10%">Jenis Kelamin</th>
                                <th width="10%">Foto</th>
                                <th width="15%">Pemilik</th>
                                <th width="10%">Aksi</th>
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
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus data hewan ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deleteHewanId">
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
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var hewanTable = $('#hewanTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/hewan/getHewan') ?>',
                type: 'GET',
                data: function(d) {
                    d.jenis = $('#jenisFilter').val();
                    d.jenkel = $('#jenkelFilter').val();
                    d.idpelanggan = $('#pelangganFilter').val();
                    return d;
                }
            },
            columns: [{
                    data: 'nomor'
                },
                {
                    data: 'idhewan'
                },
                {
                    data: 'namahewan'
                },
                {
                    data: 'jenis'
                },
                {
                    data: 'jenkel'
                },
                {
                    data: 'foto'
                },
                {
                    data: 'nama_pelanggan'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'desc']
            ],
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            language: {
                emptyTable: "Tidak ada data hewan",
                zeroRecords: "Tidak ada data hewan yang cocok",
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

        // Apply Filters
        $('#jenisFilter, #jenkelFilter, #pelangganFilter').change(function() {
            hewanTable.ajax.reload();
        });

        // Reset Filters
        $('#resetFilter').click(function() {
            $('#jenisFilter, #jenkelFilter, #pelangganFilter').val('');
            hewanTable.ajax.reload();
        });

        // Delete Hewan - Show confirmation modal
        $(document).on('click', '.btn-delete', function() {
            let hewanId = $(this).data('id');
            $('#deleteHewanId').val(hewanId);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete Hewan
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            let hewanId = $('#deleteHewanId').val();

            $.ajax({
                url: '<?= site_url('admin/hewan/deleteHewan') ?>/' + hewanId,
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
                        hewanTable.ajax.reload();
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