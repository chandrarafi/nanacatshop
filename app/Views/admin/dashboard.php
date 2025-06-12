<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Welcome Message -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card glassmorphism welcome-card">
            <div class="card-body p-4">
                <div class="d-md-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Selamat datang kembali, Administrator!</h4>
                        <p class="text-secondary mb-md-0">Berikut adalah ringkasan statistik sistem Anda hari ini</p>
                    </div>
                    <div class="d-flex mt-3 mt-md-0">
                        <button class="btn btn-sm btn-primary px-3">
                            <i class="bi bi-calendar-check me-1"></i> Hari Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-primary fw-bold">Total Pengguna</div>
                        <div class="d-flex align-items-center">
                            <div class="h3 mb-0 fw-bold" id="totalUsers">0</div>
                            <div class="ms-2 badge bg-success-soft text-success px-2 rounded-pill">
                                <i class="bi bi-arrow-up me-1"></i>12%
                            </div>
                        </div>
                        <div class="mt-2 text-secondary small">
                            <span class="fw-bold">12%</span> meningkat dibanding bulan lalu
                        </div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-success fw-bold">Pengguna Aktif</div>
                        <div class="d-flex align-items-center">
                            <div class="h3 mb-0 fw-bold" id="activeUsers">0</div>
                            <div class="ms-2 badge bg-success-soft text-success px-2 rounded-pill">
                                <i class="bi bi-arrow-up me-1"></i>8%
                            </div>
                        </div>
                        <div class="mt-2 text-secondary small">
                            <span class="fw-bold">8%</span> meningkat dibanding bulan lalu
                        </div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-warning fw-bold">Admin</div>
                        <div class="d-flex align-items-center">
                            <div class="h3 mb-0 fw-bold" id="adminUsers">0</div>
                            <div class="ms-2 badge bg-secondary-soft text-secondary px-2 rounded-pill">
                                <i class="bi bi-dash me-1"></i>0%
                            </div>
                        </div>
                        <div class="mt-2 text-secondary small">
                            Tetap stabil dibanding bulan lalu
                        </div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-danger fw-bold">Pengguna Tidak Aktif</div>
                        <div class="d-flex align-items-center">
                            <div class="h3 mb-0 fw-bold" id="inactiveUsers">0</div>
                            <div class="ms-2 badge bg-danger-soft text-danger px-2 rounded-pill">
                                <i class="bi bi-arrow-down me-1"></i>5%
                            </div>
                        </div>
                        <div class="mt-2 text-secondary small">
                            <span class="fw-bold">5%</span> menurun dari bulan lalu
                        </div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart & Table Row -->
<div class="row">
    <!-- Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Statistik Pengguna</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-3" type="button" id="chartRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        30 Hari Terakhir
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="chartRangeDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-calendar2-week me-2"></i>7 Hari Terakhir</a></li>
                        <li><a class="dropdown-item active" href="#"><i class="bi bi-calendar2-month me-2"></i>30 Hari Terakhir</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-calendar3 me-2"></i>90 Hari Terakhir</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-calendar-check me-2"></i>Tahun ini</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="userStatsChart" style="height: 320px;"></div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Pengguna Terbaru</h6>
                <a href="<?= site_url('admin/users') ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-people me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="recentUsers">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded by AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activity & Tasks Row -->
<div class="row">
    <!-- Recent Activity -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Aktivitas Terbaru</h6>
                <span class="badge bg-primary-soft text-primary rounded-pill px-3">Hari ini</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-gradient-primary rounded-circle text-white p-2 me-3">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold">Pengguna Baru</div>
                                    <small class="text-muted">30 menit lalu</small>
                                </div>
                                <div class="small text-secondary">Admin baru ditambahkan - admin2</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-gradient-success rounded-circle text-white p-2 me-3">
                                <i class="bi bi-pencil"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold">Pembaruan Data</div>
                                    <small class="text-muted">2 jam lalu</small>
                                </div>
                                <div class="small text-secondary">Profil user123 diperbarui</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-gradient-danger rounded-circle text-white p-2 me-3">
                                <i class="bi bi-trash"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold">Penghapusan</div>
                                    <small class="text-muted">1 hari lalu</small>
                                </div>
                                <div class="small text-secondary">Pengguna test123 dihapus</div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-gradient-info rounded-circle text-white p-2 me-3">
                                <i class="bi bi-key"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold">Akses</div>
                                    <small class="text-muted">2 hari lalu</small>
                                </div>
                                <div class="small text-secondary">manager123 mendapatkan akses baru</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-center p-3">
                <a href="#" class="btn btn-sm btn-outline-primary px-4">Lihat Semua Aktivitas</a>
            </div>
        </div>
    </div>

    <!-- Quick Tasks -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Tugas Cepat</h6>
                <button class="btn btn-sm btn-success px-3" data-bs-toggle="modal" data-bs-target="#taskModal">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
                </button>
            </div>
            <div class="card-body">
                <div class="task-list">
                    <div class="task-item d-flex align-items-center p-3 border-start border-3 border-success rounded mb-3 bg-light-hover">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="" id="task1">
                        </div>
                        <div class="flex-grow-1">
                            <label class="w-100 mb-0" for="task1">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Verifikasi pengguna baru</span>
                                    <span class="badge bg-success rounded-pill">Tinggi</span>
                                </div>
                                <div class="text-secondary small my-1">Pastikan semua data valid</div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Tugas untuk: Admin</small>
                                    <small class="text-muted">Deadline: Hari ini</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="task-item d-flex align-items-center p-3 border-start border-3 border-warning rounded mb-3 bg-light-hover">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="" id="task2">
                        </div>
                        <div class="flex-grow-1">
                            <label class="w-100 mb-0" for="task2">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Update kebijakan privasi</span>
                                    <span class="badge bg-warning rounded-pill">Sedang</span>
                                </div>
                                <div class="text-secondary small my-1">Pembaruan regulasi baru</div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Tugas untuk: Admin</small>
                                    <small class="text-muted">Deadline: 20 Des 2023</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="task-item d-flex align-items-center p-3 border-start border-3 border-danger rounded mb-3 bg-light-hover">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="" id="task3">
                        </div>
                        <div class="flex-grow-1">
                            <label class="w-100 mb-0" for="task3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Backup database</span>
                                    <span class="badge bg-danger rounded-pill">Penting</span>
                                </div>
                                <div class="text-secondary small my-1">Backup mingguan database utama</div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Tugas untuk: Admin</small>
                                    <small class="text-muted">Deadline: 18 Des 2023</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-center p-3">
                <a href="#" class="btn btn-sm btn-outline-primary px-4">Kelola Semua Tugas</a>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="taskModalLabel">Tambah Tugas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Judul Tugas</label>
                        <input type="text" class="form-control" id="taskTitle" placeholder="Masukkan judul tugas" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="taskDescription" rows="3" placeholder="Masukkan deskripsi tugas"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="taskAssignee" class="form-label">Ditugaskan Kepada</label>
                            <select class="form-select" id="taskAssignee">
                                <option selected>Admin</option>
                                <option>Manager</option>
                                <option>User</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="taskPriority" class="form-label">Prioritas</label>
                            <select class="form-select" id="taskPriority">
                                <option value="high">Tinggi</option>
                                <option value="medium" selected>Sedang</option>
                                <option value="low">Rendah</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="taskDueDate" class="form-label">Tanggal Deadline</label>
                            <input type="date" class="form-control" id="taskDueDate">
                        </div>
                        <div class="col-md-6">
                            <label for="taskStatus" class="form-label">Status</label>
                            <select class="form-select" id="taskStatus">
                                <option value="pending" selected>Pending</option>
                                <option value="in-progress">Dalam Proses</option>
                                <option value="completed">Selesai</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveTask">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for this page -->
<style>
    .bg-primary-soft {
        background-color: rgba(44, 62, 80, 0.1);
    }

    .bg-success-soft {
        background-color: rgba(39, 174, 96, 0.1);
    }

    .bg-warning-soft {
        background-color: rgba(243, 156, 18, 0.1);
    }

    .bg-danger-soft {
        background-color: rgba(231, 76, 60, 0.1);
    }

    .bg-secondary-soft {
        background-color: rgba(127, 140, 141, 0.1);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-success {
        color: var(--success-color) !important;
    }

    .text-warning {
        color: var(--warning-color) !important;
    }

    .text-danger {
        color: var(--danger-color) !important;
    }

    .text-secondary {
        color: var(--secondary-color) !important;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .bg-light-hover {
        transition: all 0.3s;
    }

    .bg-light-hover:hover {
        background-color: #f8f9fc !important;
        transform: translateX(5px);
    }

    .task-item {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s;
    }

    .task-item:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-3px);
    }

    /* Modal backdrop overlay fix */
    .modal-backdrop.show {
        opacity: 0.7;
    }

    /* Mobile responsive adjustments */
    @media (max-width: 767.98px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            white-space: nowrap;
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        /* Card adjustments */
        .card-footer {
            text-align: center;
            padding: 0.75rem;
        }

        /* Welcome card adjustments */
        .welcome-card h4 {
            font-size: 1.2rem;
        }

        .welcome-card p {
            font-size: 0.85rem;
        }

        /* Task items adjustments */
        .task-item {
            padding: 0.75rem;
        }

        .task-item .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start;
        }

        .task-item .badge {
            margin-top: 5px;
        }

        .task-item .small {
            font-size: 0.75rem;
        }

        /* Stats card adjustments */
        .stat-card .d-flex {
            flex-direction: column;
        }

        .stat-card .icon {
            margin-top: 0.5rem;
            text-align: start;
        }

        .stat-card .h3 {
            font-size: 1.3rem;
        }

        .stat-card .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }

        .stat-card .text-xs {
            font-size: 0.7rem;
        }

        /* Chart adjustments */
        #userStatsChart {
            height: 250px !important;
        }

        /* Activity list adjustments */
        .list-group-item {
            padding: 0.75rem 0.5rem;
        }

        .list-group-item .small {
            font-size: 0.75rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Load dashboard data
        $.ajax({
            url: '<?= site_url('admin/getUsers') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.data) {
                    // Count total users
                    $('#totalUsers').text(response.recordsTotal);

                    // Count active users
                    let activeUsers = 0;
                    // Count admin users
                    let adminUsers = 0;
                    // Count inactive users
                    let inactiveUsers = 0;

                    $.each(response.data, function(i, item) {
                        if (item.status === 'active') {
                            activeUsers++;
                        } else {
                            inactiveUsers++;
                        }
                        if (item.role === 'admin') {
                            adminUsers++;
                        }
                    });

                    $('#activeUsers').text(activeUsers);
                    $('#adminUsers').text(adminUsers);
                    $('#inactiveUsers').text(inactiveUsers);
                }
            }
        });

        // Initialize DataTable for recent users
        $('#recentUsers').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/getUsers') ?>',
                type: 'GET'
            },
            columns: [{
                    data: 'username'
                },
                {
                    data: 'name'
                },
                {
                    data: 'role'
                },
                {
                    data: 'status'
                },
                {
                    data: 'last_login'
                }
            ],
            order: [
                [4, 'desc']
            ],
            responsive: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25],
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

        // Initialize User Stats Chart
        var options = {
            series: [{
                name: 'Total Pengguna',
                data: [31, 40, 28, 51, 42, 109, 100, 120, 110, 125, 140, 150]
            }, {
                name: 'Pengguna Aktif',
                data: [25, 32, 25, 40, 39, 90, 85, 100, 95, 110, 120, 130]
            }, {
                name: 'Admin',
                data: [5, 5, 5, 6, 6, 8, 8, 8, 9, 9, 10, 10]
            }],
            chart: {
                height: 320,
                type: 'area',
                fontFamily: 'Nunito, sans-serif',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Nunito, sans-serif'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return Math.round(value);
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Nunito, sans-serif'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + " pengguna";
                    }
                },
                theme: 'dark',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Nunito, sans-serif'
                }
            },
            colors: ['#2c3e50', '#27ae60', '#f39c12'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                padding: {
                    left: 15,
                    right: 15
                }
            },
            markers: {
                size: 4,
                strokeWidth: 0,
                hover: {
                    size: 6
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                offsetY: -30,
                fontSize: '13px',
                fontFamily: 'Nunito, sans-serif',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 100
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center',
                        offsetY: 0
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#userStatsChart"), options);
        chart.render();

        // Handle task checkbox behavior
        $('.form-check-input').on('change', function() {
            var label = $(this).parent().next('div').find('.fw-bold');
            if (this.checked) {
                label.css('text-decoration', 'line-through');
                label.css('opacity', '0.5');
            } else {
                label.css('text-decoration', 'none');
                label.css('opacity', '1');
            }
        });

        // Handle save task
        $('#saveTask').on('click', function() {
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
            $(this).attr('disabled', true);

            setTimeout(function() {
                $('#saveTask').html('<i class="bi bi-save me-1"></i> Simpan');
                $('#saveTask').attr('disabled', false);

                $('#taskModal').modal('hide');

                Swal.fire({
                    title: 'Sukses',
                    text: 'Tugas berhasil ditambahkan',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }, 1000);
        });

        // Add animation on scroll for task items
        function animateOnScroll() {
            $('.task-item').each(function(i) {
                setTimeout(function() {
                    $('.task-item').eq(i).addClass('animate__animated animate__fadeInRight');
                }, 300 * i);
            });
        }

        animateOnScroll();
    });
</script>
<?= $this->endSection() ?>