<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pengguna</h1>
        <p class="mb-0 text-secondary">Kelola semua pengguna dalam sistem</p>
    </div>
    <button type="button" class="btn btn-primary d-flex align-items-center" id="btnAddUser">
        <i class="bi bi-person-plus me-2"></i> Tambah Pengguna
    </button>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="row mb-4 align-items-end">
                    <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                        <label for="roleFilter" class="form-label fw-bold">Filter berdasarkan Role</label>
                        <select class="form-select" id="roleFilter">
                            <option value="">Semua Role</option>
                            <!-- Role options will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                        <label for="statusFilter" class="form-label fw-bold">Filter berdasarkan Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <button class="btn btn-outline-secondary" id="resetFilter">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Login Terakhir</th>
                                <th>Aksi</th>
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



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Load roles for filter and form
        $.ajax({
            url: '<?= site_url('admin/getRoles') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let roles = response.data;
                    let roleOptions = '';

                    $.each(roles, function(index, role) {
                        roleOptions += '<option value="' + role + '">' + role.charAt(0).toUpperCase() + role.slice(1) + '</option>';
                    });

                    $('#roleFilter').append(roleOptions);
                    $('#role').append(roleOptions);
                }
            }
        });

        // Initialize DataTable
        let usersTable = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/getUsers') ?>',
                type: 'GET',
                data: function(d) {
                    d.role = $('#roleFilter').val(); // Add role filter
                    d.status = $('#statusFilter').val(); // Add status filter
                    return d;
                }
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'username'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role'
                },
                {
                    data: 'status'
                },
                {
                    data: 'last_login'
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
                emptyTable: "Tidak ada data pengguna",
                zeroRecords: "Tidak ada data pengguna yang cocok",
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
        $('#roleFilter, #statusFilter').change(function() {
            usersTable.ajax.reload();
        });

        // Reset Filters
        $('#resetFilter').click(function() {
            $('#roleFilter, #statusFilter').val('');
            usersTable.ajax.reload();
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

                $.each(errors, function(field, message) {
                    const $field = $('#' + field);
                    if ($field.length) {
                        $field.addClass('is-invalid');
                        $('#' + field + 'Error').text(message);
                        hasFieldErrors = true;
                    } else {
                        generalErrors.push(message);
                    }
                });

                if (generalErrors.length > 0) {
                    const $generalError = $('#generalError');
                    const $errorList = $generalError.find('ul');
                    generalErrors.forEach(function(error) {
                        $errorList.append('<li>' + error + '</li>');
                    });
                    $generalError.show();
                }
            } else if (typeof errors === 'string') {
                $('#generalError').show().find('ul').append('<li>' + errors + '</li>');
            }
        }

        // Add User
        $('#btnAddUser').on('click', function() {
            resetFormErrors();
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#userModalLabel').text('Tambah Pengguna');
            $('#passwordHelp').hide();
            $('#userModal').modal('show');
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
        });

        // Ensure modals are properly handled on page load
        $(window).on('load', function() {
            // Check if any modals are open and make sure backdrop is fixed
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open');
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop show"></div>');
                }
            }
        });

        // Improve DataTable responsiveness
        $(window).on('resize', function() {
            if ($(window).width() < 768) {
                $('.dataTables_length select').addClass('form-select-sm');
            } else {
                $('.dataTables_length select').removeClass('form-select-sm');
            }
        }).trigger('resize');

        // Edit user
        $(document).on('click', '.btn-edit', function() {
            resetFormErrors();
            $('#userForm')[0].reset();
            let userId = $(this).data('id');

            // Get user data
            $.ajax({
                url: '<?= site_url('admin/getUser') ?>/' + userId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let user = response.data;

                        $('#userId').val(user.id);
                        $('#username').val(user.username);
                        $('#name').val(user.name);
                        $('#email').val(user.email);
                        $('#role').val(user.role);
                        $('#status').val(user.status);

                        $('#password').val(''); // Clear password field
                        $('#passwordHelp').show(); // Show password help text

                        $('#userModalLabel').text('Edit Pengguna');
                        $('#userModal').modal('show');
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
                        text: 'Gagal mengambil data pengguna',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Save User (Add or Edit)
        $('#btnSaveUser').on('click', function() {
            resetFormErrors();

            // Disable button and show loading state
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            const userId = $('#userId').val();
            const url = userId ? `<?= site_url('admin/updateUser') ?>/${userId}` : '<?= site_url('admin/addUser') ?>';
            const formData = {
                id: userId,
                username: $('#username').val(),
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                role: $('#role').val(),
                status: $('#status').val(),
                additionalInfo: $('#additionalInfo').val()
            };

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#userModal').modal('hide');
                        Swal.fire({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        usersTable.ajax.reload();
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
                    $('#btnSaveUser').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });

        // Delete User - Show confirmation modal
        $(document).on('click', '.btn-delete', function() {
            let userId = $(this).data('id');
            $('#deleteUserId').val(userId);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete User
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            let userId = $('#deleteUserId').val();

            $.ajax({
                url: '<?= site_url('admin/deleteUser') ?>/' + userId,
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
                        usersTable.ajax.reload();
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

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="userModalLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <!-- Alert untuk error umum -->
                    <div id="generalError" class="alert alert-danger" style="display: none;">
                        <ul class="mb-0"></ul>
                    </div>

                    <input type="hidden" id="userId">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="username">
                                <div class="invalid-feedback" id="usernameError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email">
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input type="text" class="form-control" id="name" name="name">
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="invalid-feedback" id="passwordError"></div>
                            </div>
                            <div class="form-text" id="passwordHelp" style="display: none;">Biarkan kosong jika tidak ingin mengubah password</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="role" class="form-label">Role</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-shield"></i></span>
                                <select class="form-select" id="role" name="role">
                                    <!-- Role options will be loaded dynamically -->
                                </select>
                                <div class="invalid-feedback" id="roleError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
                                <select class="form-select" id="status" name="status">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                </select>
                                <div class="invalid-feedback" id="statusError"></div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Informasi Tambahan</label>
                        <textarea class="form-control" id="additionalInfo" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveUser">
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
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deleteUserId">
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