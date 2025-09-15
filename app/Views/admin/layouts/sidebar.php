 <!-- Sidebar -->
 <div class="sidebar" id="sidebar">
     <div class="sidebar-brand">
         <div class="text-center">
             <h3>NANA CATSHOP</h3>
             <p class="small">Admin Panel</p>
         </div>
     </div>
     <hr class="sidebar-divider">

     <?php if (session()->get('role') == 'admin') : ?>
         <div class="sidebar-menu">
             <ul class="nav flex-column">
                 <li class="nav-item">
                     <a class="nav-link <?= str_contains(current_url(), 'admin') && !str_contains(current_url(), 'admin/') ? 'active' : '' ?>" href="<?= site_url('admin') ?>">
                         <i class="bi bi-speedometer2"></i>
                         <span>Dashboard</span>
                     </a>
                 </li>

                 <li class="nav-item">
                     <a class="nav-link <?= str_contains(current_url(), 'admin/users') ? 'active' : '' ?>" href="<?= site_url('admin/users') ?>">
                         <i class="bi bi-people"></i>
                         <span>Manajemen Pengguna</span>
                     </a>
                 </li>

                 <!-- Kelompok Data Master -->
                 <div class="menu-group">
                     <div class="menu-group-title">
                         <i class="bi bi-database"></i>
                         <span>Data Master</span>
                         <i class="bi bi-chevron-down toggle-icon"></i>
                     </div>
                     <div class="menu-group-items">
                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/pelanggan') ? 'active' : '' ?>" href="<?= site_url('admin/pelanggan') ?>">
                                 <i class="bi bi-person-vcard"></i>
                                 <span>Pelanggan</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/hewan') ? 'active' : '' ?>" href="<?= site_url('admin/hewan') ?>">
                                 <i class="bi bi-github"></i>
                                 <span>Data Hewan</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/kategori') ? 'active' : '' ?>" href="<?= site_url('admin/kategori') ?>">
                                 <i class="bi bi-tags"></i>
                                 <span>Kategori</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/barang') && !str_contains(current_url(), 'admin/barangmasuk') ? 'active' : '' ?>" href="<?= site_url('admin/barang') ?>">
                                 <i class="bi bi-box"></i>
                                 <span>Barang</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/fasilitas') ? 'active' : '' ?>" href="<?= site_url('admin/fasilitas') ?>">
                                 <i class="bi bi-building-check"></i>
                                 <span>Fasilitas</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/supplier') ? 'active' : '' ?>" href="<?= site_url('admin/supplier') ?>">
                                 <i class="bi bi-truck"></i>
                                 <span>Supplier</span>
                             </a>
                         </li>
                     </div>
                 </div>

                 <!-- Kelompok Transaksi -->
                 <div class="menu-group">
                     <div class="menu-group-title">
                         <i class="bi bi-cash-coin"></i>
                         <span>Transaksi</span>
                         <i class="bi bi-chevron-down toggle-icon"></i>
                     </div>
                     <div class="menu-group-items">
                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/barangmasuk') ? 'active' : '' ?>" href="<?= site_url('admin/barangmasuk') ?>">
                                 <i class="bi bi-box-arrow-in-down"></i>
                                 <span>Barang Masuk</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/penjualan') ? 'active' : '' ?>" href="<?= site_url('admin/penjualan') ?>">
                                 <i class="bi bi-cart"></i>
                                 <span>Penjualan</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/penitipan') ? 'active' : '' ?>" href="<?= site_url('admin/penitipan') ?>">
                                 <i class="bi bi-house-heart"></i>
                                 <span>Penitipan</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/perawatan') ? 'active' : '' ?>" href="<?= site_url('admin/perawatan') ?>">
                                 <i class="bi bi-scissors"></i>
                                 <span>Perawatan</span>
                             </a>
                         </li>
                     </div>
                 </div>

                 <!-- Kelompok Pengaturan -->
                 <div class="menu-group">
                     <div class="menu-group-title">
                         <i class="bi bi-file-earmark-text"></i>
                         <span>Laporan</span>
                         <i class="bi bi-chevron-down toggle-icon"></i>
                     </div>
                     <div class="menu-group-items">
                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/pelanggan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/pelanggan') ?>">
                                 <i class="bi bi-person-vcard"></i>
                                 <span>Laporan Pelanggan</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/hewan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/hewan') ?>">
                                 <i class="bi bi-github"></i>
                                 <span>Laporan Data Hewan</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/barang') && !str_contains(current_url(), 'admin/laporan/barang-masuk') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/barang') ?>">
                                 <i class="bi bi-box-seam"></i>
                                 <span>Laporan Data Barang</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/fasilitas') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/fasilitas') ?>">
                                 <i class="bi bi-clipboard2-check"></i>
                                 <span>Laporan Fasilitas</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/supplier') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/supplier') ?>">
                                 <i class="bi bi-truck"></i>
                                 <span>Laporan Supplier</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/barang-masuk') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/barang-masuk') ?>">
                                 <i class="bi bi-box-arrow-in-down"></i>
                                 <span>Laporan Barang Masuk</span>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/penjualan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/penjualan') ?>">
                                 <i class="bi bi-cart"></i>
                                 <span>Laporan Penjualan</span>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/penitipan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/penitipan') ?>">
                                 <i class="bi bi-house-heart"></i>
                                 <span>Laporan Penitipan</span>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/perawatan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/perawatan') ?>">
                                 <i class="bi bi-scissors"></i>
                                 <span>Laporan Perawatan</span>
                             </a>
                         </li>
                     </div>
                 </div>

                 <li class="nav-item">
                     <a class="nav-link" id="btn-logout">
                         <i class="bi bi-box-arrow-left"></i>
                         <span>Keluar</span>
                     </a>
                 </li>
             </ul>
         </div>
 </div>
 <?php endif; ?>


 <?php if (session()->get('role') == 'pimpinan') : ?>
     <div class="sidebar-menu">
         <ul class="nav flex-column">
             <li class="nav-item">
                 <a class="nav-link <?= str_contains(current_url(), 'admin') && !str_contains(current_url(), 'admin/') ? 'active' : '' ?>" href="<?= site_url('admin') ?>">
                     <i class="bi bi-speedometer2"></i>
                     <span>Dashboard</span>
                 </a>
             </li>

             <!-- Kelompok Pengaturan -->
             <div class="menu-group">
                 <div class="menu-group-title">
                     <i class="bi bi-file-earmark-text"></i>
                     <span>Laporan</span>
                     <i class="bi bi-chevron-down toggle-icon"></i>
                 </div>
                 <div class="menu-group-items">
                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/pelanggan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/pelanggan') ?>">
                             <i class="bi bi-person-vcard"></i>
                             <span>Laporan Pelanggan</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/hewan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/hewan') ?>">
                             <i class="bi bi-github"></i>
                             <span>Laporan Data Hewan</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/barang') && !str_contains(current_url(), 'admin/laporan/barang-masuk') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/barang') ?>">
                             <i class="bi bi-box-seam"></i>
                             <span>Laporan Data Barang</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/fasilitas') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/fasilitas') ?>">
                             <i class="bi bi-clipboard2-check"></i>
                             <span>Laporan Fasilitas</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/supplier') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/supplier') ?>">
                             <i class="bi bi-truck"></i>
                             <span>Laporan Supplier</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/barang-masuk') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/barang-masuk') ?>">
                             <i class="bi bi-box-arrow-in-down"></i>
                             <span>Laporan Barang Masuk</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/penjualan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/penjualan') ?>">
                             <i class="bi bi-cart"></i>
                             <span>Laporan Penjualan</span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/penitipan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/penitipan') ?>">
                             <i class="bi bi-house-heart"></i>
                             <span>Laporan Penitipan</span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link <?= str_contains(current_url(), 'admin/laporan/perawatan') ? 'active' : '' ?>" href="<?= site_url('admin/laporan/perawatan') ?>">
                             <i class="bi bi-scissors"></i>
                             <span>Laporan Perawatan</span>
                         </a>
                     </li>
                 </div>
             </div>

             <li class="nav-item">
                 <a class="nav-link" id="btn-logout">
                     <i class="bi bi-box-arrow-left"></i>
                     <span>Keluar</span>
                 </a>
             </li>
         </ul>
     </div>
     </div>
 <?php endif; ?>
 <!-- Mobile Toggle Button -->
 <div class="sidebar-toggle d-lg-none" id="sidebarToggle">
     <i class="bi bi-list"></i>
 </div>