<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - Nana Cat Shop</title>

    <?= $this->include('admin/layouts/style') ?>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <!-- Loading Overlay -->
    <!-- <div id="loading-overlay">
        <lottie-player src="<?= base_url('assets/animasi/cat.json') ?>" background="transparent" speed="1" loop autoplay></lottie-player>
        <div id="loading-text">Memuat...</div>
    </div> -->

    <?= $this->include('admin/layouts/sidebar') ?>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar glassmorphism">
            <button class="navbar-toggler d-md-none" id="navbarToggler" type="button">
                <i class="bi bi-list navbar-toggler-icon"></i>
            </button>
            <h1><?= $title ?? 'Dashboard' ?></h1>
            <div class="topbar-divider"></div>
            <div class="text-secondary small">Selamat Datang, Admin</div>
            <div class="topbar-nav">
                <div class="topbar-item">
                    <a href="#" class="nav-link" data-bs-toggle="tooltip" title="Notifikasi">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">5</span>
                    </a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="nav-link" data-bs-toggle="tooltip" title="Pesan">
                        <i class="bi bi-envelope"></i>
                        <span class="notification-badge">2</span>
                    </a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="nav-link" data-bs-toggle="tooltip" title="Pengaturan">
                        <i class="bi bi-gear"></i>
                    </a>
                </div>
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=e83e8c&color=fff" alt="Admin">
                    <div class="user-info">
                        <h6>Admin</h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid page-content animate__animated animate__fadeIn">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- ApexCharts for beautiful charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // Loading overlay control
        $(window).on('load', function() {
            setTimeout(function() {
                $('#loading-overlay').addClass('hide');
            }, 800); // Menampilkan loading selama 800ms
        });

        // Fungsi untuk menampilkan loading saat navigasi atau aksi
        function showLoading() {
            $('#loading-overlay').removeClass('hide');
        }

        function hideLoading() {
            $('#loading-overlay').addClass('hide');
        }

        // Menampilkan loading saat klik link navigasi
        $(document).on('click', 'a:not([href^="#"]):not([target="_blank"]):not([href^="javascript:void(0)"]):not([href^="tel:"]):not([href^="mailto:"])', function() {
            showLoading();
        });

        // Menampilkan loading saat submit form
        // $(document).on('submit', 'form', function() {
        //     showLoading();
        // });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });

        // Mobile sidebar toggle
        $(document).ready(function() {
            // Auto-open menu groups that contain active links on page load
            $('.menu-group').each(function() {
                if ($(this).find('.nav-link.active').length > 0) {
                    $(this).addClass('open');
                    // Scroll to active menu item
                    const activeLink = $(this).find('.nav-link.active');
                    if (activeLink.length) {
                        $('#sidebar').scrollTop(activeLink.offset().top - 200);
                    }
                }
            });

            // Toggle menu groups
            $('.menu-group-title').on('click', function() {
                const menuGroup = $(this).closest('.menu-group');

                // Close other menu groups
                $('.menu-group').not(menuGroup).removeClass('open');

                // Toggle current menu group
                menuGroup.toggleClass('open');
            });

            $('#sidebarToggle, #navbarToggler').on('click', function() {
                $('#sidebar').toggleClass('show');

                // Change icon based on sidebar state
                if ($('#sidebar').hasClass('show')) {
                    $(this).find('i').removeClass('bi-list').addClass('bi-x');
                } else {
                    $(this).find('i').removeClass('bi-x').addClass('bi-list');
                }
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() < 768) {
                    if (!$(e.target).closest('#sidebar').length &&
                        !$(e.target).closest('#sidebarToggle').length &&
                        !$(e.target).closest('#navbarToggler').length &&
                        $('#sidebar').hasClass('show')) {
                        $('#sidebar').removeClass('show');
                        $('#sidebarToggle, #navbarToggler').find('i').removeClass('bi-x').addClass('bi-list');
                    }
                }
            });

            // Handle window resize
            $(window).resize(function() {
                if ($(window).width() >= 768) {
                    $('#sidebar').removeClass('show');
                    $('#sidebarToggle, #navbarToggler').find('i').removeClass('bi-x').addClass('bi-list');
                }
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>

    <!-- Modal wrapper for handling modal backdrop correctly -->
    <div id="modal-container"></div>
    <script>
        // Move all modals to the end of body to ensure they work correctly
        $(document).ready(function() {
            // Move all modals to modal container at the end of body
            $('.modal').appendTo('#modal-container');

            // Fix modal backdrop handling
            $(document).on('show.bs.modal', '.modal', function() {
                const $modal = $(this);
                const modalZIndex = 1060;

                $modal.css('z-index', modalZIndex);

                // Make sure there's only one backdrop
                if ($('.modal-backdrop').length === 0) {
                    $('<div class="modal-backdrop show"></div>')
                        .css('z-index', modalZIndex - 5)
                        .appendTo('body');
                }

                $('body').addClass('modal-open');
            });

            $(document).on('hidden.bs.modal', '.modal', function() {
                // Only remove backdrop and modal-open class if no modal is visible
                if ($('.modal:visible').length === 0) {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#btn-logout').click(function() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan keluar dari sistem!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Keluar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('auth/logout') ?>',
                            type: 'GET',
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Anda telah berhasil keluar',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '<?= site_url('auth') ?>';
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>


</body>


</html>