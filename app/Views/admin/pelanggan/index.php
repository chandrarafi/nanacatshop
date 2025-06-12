<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pelanggan</h1>
        <p class="mb-0 text-secondary">Kelola data pelanggan Nana Cat Shop</p>
    </div>
    <button type="button" class="btn btn-primary d-flex align-items-center" id="btnAddPelanggan">
        <i class="bi bi-person-plus me-2"></i> Tambah Pelanggan
    </button>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="pelangganTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Pelanggan</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var pelangganTable = $('#pelangganTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/pelanggan/getPelanggan') ?>',
                type: 'GET',
                data: function(d) {
                    d.jenkel = $('#jenkelFilter').val();
                    return d;
                }
            },
            columns: [{
                    data: 'idpelanggan'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'jenkel'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'nohp'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, 'desc']
            ],
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            language: {
                emptyTable: "Tidak ada data pelanggan",
                zeroRecords: "Tidak ada data pelanggan yang cocok",
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
        $('#jenkelFilter').change(function() {
            pelangganTable.ajax.reload();
        });

        // Reset Filters
        $('#resetFilter').click(function() {
            $('#jenkelFilter').val('');
            pelangganTable.ajax.reload();
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
                        $('<div class="invalid-feedback"></div>')
                            .text(errors[field])
                            .insertAfter(element);
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

        // Show Add Pelanggan Modal
        $('#btnAddPelanggan').on('click', function() {
            resetFormErrors();
            $('#pelangganForm')[0].reset();
            $('#pelangganId').val('');
            $('#pelangganModalLabel').text('Tambah Pelanggan');

            // Tampilkan field ID Pelanggan dan ambil ID otomatis
            $('#idPelangganDisplay').show();
            getNextIdPelanggan();

            $('#pelangganModal').modal('show');
        });

        // Fungsi untuk mendapatkan ID pelanggan otomatis
        function getNextIdPelanggan() {
            $.ajax({
                url: '<?= site_url('admin/pelanggan/getNextIdPelanggan') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#idPelangganText').val(response.data.idpelanggan);
                    }
                },
                error: function() {
                    $('#idPelangganText').val('Gagal mendapatkan ID');
                }
            });
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

        // Ensure modals are properly handled on page load
        $(window).on('load', function() {
            // Check if any modals are open and make sure backdrop is fixed
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open');
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop show"></div>');
                }
            } else {
                // Pastikan tidak ada sisa backdrop jika tidak ada modal terbuka
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            }
        });

        // Show Edit Pelanggan Modal
        $(document).on('click', '.btn-edit', function() {
            resetFormErrors();
            const id = $(this).data('id');

            // Show loading state
            Swal.fire({
                title: 'Memuat...',
                html: 'Mengambil data pelanggan',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '<?= site_url('admin/pelanggan/getPelangganById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        const data = response.data;
                        $('#pelangganId').val(data.idpelanggan);
                        $('#nama').val(data.nama);
                        $('#jenkel').val(data.jenkel);
                        $('#alamat').val(data.alamat);
                        $('#nohp').val(data.nohp);

                        // Sembunyikan field ID pelanggan pada mode edit
                        $('#idPelangganDisplay').hide();

                        $('#pelangganModalLabel').text('Edit Pelanggan');
                        $('#pelangganModal').modal('show');
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
                        text: 'Gagal mengambil data pelanggan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Save Pelanggan (Add or Edit)
        $('#btnSavePelanggan').on('click', function() {
            resetFormErrors();

            // Disable button and show loading state
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            const pelangganId = $('#pelangganId').val();
            const url = pelangganId ? `<?= site_url('admin/pelanggan/updatePelanggan') ?>/${pelangganId}` : '<?= site_url('admin/pelanggan/addPelanggan') ?>';

            // Gunakan ID dari field tersembunyi atau dari field tampilan
            const formData = {
                idpelanggan: pelangganId || $('#idPelangganText').val(),
                nama: $('#nama').val(),
                jenkel: $('#jenkel').val(),
                alamat: $('#alamat').val(),
                nohp: $('#nohp').val()
            };

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#pelangganModal').modal('hide');
                        Swal.fire({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        pelangganTable.ajax.reload();
                    } else {
                        if (response.errors) {
                            displayErrors(response.errors);
                        } else {
                            displayErrors(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    if (response.errors) {
                        displayErrors(response.errors);
                    } else {
                        displayErrors(response.message || 'Terjadi kesalahan pada server');
                    }
                },
                complete: function() {
                    // Re-enable button and restore label
                    $('#btnSavePelanggan').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });

        // Delete Pelanggan - Show confirmation modal
        $(document).on('click', '.btn-delete', function() {
            let pelangganId = $(this).data('id');
            $('#deletePelangganId').val(pelangganId);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete Pelanggan
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            let pelangganId = $('#deletePelangganId').val();

            $.ajax({
                url: '<?= site_url('admin/pelanggan/deletePelanggan') ?>/' + pelangganId,
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
                        pelangganTable.ajax.reload();
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
    });
</script>

<!-- Add/Edit Pelanggan Modal -->
<div class="modal fade" id="pelangganModal" tabindex="-1" aria-labelledby="pelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="pelangganModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pelangganForm">
                    <!-- Alert untuk error umum -->
                    <div id="generalError" class="alert alert-danger" style="display: none;">
                        <ul class="mb-0"></ul>
                    </div>

                    <input type="hidden" id="pelangganId">

                    <div class="mb-3" id="idPelangganDisplay">
                        <label class="form-label">ID Pelanggan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i class="bi bi-upc"></i></span>
                            <input type="text" class="form-control" id="idPelangganText" readonly>
                        </div>
                        <small class="text-muted">ID pelanggan akan digenerate otomatis oleh sistem</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama pelanggan">
                            <div class="invalid-feedback"></div>
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

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="nohp" class="form-label">No HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Contoh: 081234567890">
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSavePelanggan">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
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
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus pelanggan ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deletePelangganId">
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