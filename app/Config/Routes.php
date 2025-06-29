<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingPage::index');

// Auth Routes
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Admin::index');

    // User Management
    $routes->get('users', 'Admin::users');
    $routes->get('getUsers', 'Admin::getUsers');
    $routes->post('addUser', 'Admin::addUser');
    $routes->get('getUser/(:num)', 'Admin::getUser/$1');
    $routes->post('updateUser/(:num)', 'Admin::updateUser/$1');
    $routes->post('deleteUser/(:num)', 'Admin::deleteUser/$1');
    $routes->get('getRoles', 'Admin::getRoles');
});

// Laporan Routes
$routes->group('admin/laporan', ['filter' => 'auth'], function ($routes) {
    // Pelanggan
    $routes->get('pelanggan', 'LaporanController::pelanggan');
    $routes->get('pelanggan/data', 'LaporanController::getPelangganData');
    $routes->get('pelanggan/cetak', 'LaporanController::cetakPelangganPdf');

    // Hewan
    $routes->get('hewan', 'LaporanController::hewan');
    $routes->get('hewan/data', 'LaporanController::getHewanData');
    $routes->get('hewan/pelanggan', 'LaporanController::getPelangganList');
    $routes->get('hewan/cetak', 'LaporanController::cetakHewanPdf');

    // Barang
    $routes->get('barang', 'LaporanController::barang');
    $routes->get('barang/data', 'LaporanController::getBarangData');
    $routes->get('barang/kategori', 'LaporanController::getKategoriList');
    $routes->get('barang/cetak', 'LaporanController::cetakBarangPdf');

    // Fasilitas
    $routes->get('fasilitas', 'LaporanController::fasilitas');
    $routes->get('fasilitas/data', 'LaporanController::getFasilitasData');
    $routes->get('fasilitas/kategori', 'LaporanController::getKategoriFasilitas');
    $routes->get('fasilitas/cetak', 'LaporanController::cetakFasilitasPdf');

    // Supplier
    $routes->get('supplier', 'LaporanController::supplier');
    $routes->get('supplier/data', 'LaporanController::getSupplierData');
    $routes->get('supplier/cetak', 'LaporanController::cetakSupplierPdf');

    // Barang Masuk
    $routes->get('barang-masuk', 'LaporanController::barangMasuk');
    $routes->get('barang-masuk/data', 'LaporanController::getBarangMasukData');
    $routes->get('barang-masuk/supplier', 'LaporanController::getSupplierList');
    $routes->get('barang-masuk/cetak', 'LaporanController::cetakBarangMasukPdf');
    $routes->get('barang-masuk/detail/(:segment)', 'LaporanController::cetakDetailBarangMasuk/$1');

    // Barang Masuk Perbulan
    $routes->get('barang-masuk-perbulan/data', 'LaporanController::getBarangMasukPerbulanData');
    $routes->get('barang-masuk-perbulan/cetak', 'LaporanController::cetakBarangMasukPerbulanPdf');

    // Barang Masuk Pertahun
    $routes->get('barang-masuk-pertahun/data', 'LaporanController::getBarangMasukPertahunData');
    $routes->get('barang-masuk-pertahun/cetak', 'LaporanController::cetakBarangMasukPertahunPdf');

    // Penjualan
    $routes->get('penjualan', 'LaporanController::penjualan');
    $routes->get('penjualan/data', 'LaporanController::getPenjualanData');
    $routes->get('penjualan/cetak', 'LaporanController::cetakPenjualanPdf');
    $routes->get('penjualan/detail/(:segment)', 'LaporanController::cetakDetailPenjualan/$1');
    $routes->get('penjualan/pelanggan', 'LaporanController::getPelangganList');
    $routes->get('penjualan/pelanggan-modal', 'LaporanController::getPelangganForModal');
    $routes->get('penjualan/perbulan/data', 'LaporanController::getPenjualanPerbulanData');
    $routes->get('penjualan/perbulan/cetak', 'LaporanController::cetakPenjualanPerbulanPdf');
    $routes->get('penjualan/pertahun/data', 'LaporanController::getPenjualanPertahunData');
    $routes->get('penjualan/pertahun/cetak', 'LaporanController::cetakPenjualanPertahunPdf');
    // Backward compatibility
    $routes->get('penjualan-perbulan/data', 'LaporanController::getPenjualanPerbulanData');
    $routes->get('penjualan-perbulan/cetak', 'LaporanController::cetakPenjualanPerbulanPdf');
    $routes->get('penjualan-pertahun/data', 'LaporanController::getPenjualanPertahunData');
    $routes->get('penjualan-pertahun/cetak', 'LaporanController::cetakPenjualanPertahunPdf');

    // Penitipan
    $routes->get('penitipan', 'LaporanController::penitipan');
    $routes->get('penitipan/data', 'LaporanController::getPenitipanData');
    $routes->get('penitipan/cetak', 'LaporanController::cetakPenitipanPdf');
    $routes->get('penitipan/detail/(:segment)', 'LaporanController::cetakDetailPenitipan/$1');
    $routes->get('penitipan/pelanggan-modal', 'LaporanController::getPelangganForModal');

    // Perawatan
    $routes->get('perawatan', 'LaporanController::perawatan');
    $routes->get('perawatan/data', 'LaporanController::getPerawatanData');
    $routes->get('perawatan/cetak', 'LaporanController::cetakPerawatanPdf');
    $routes->get('perawatan/detail/(:segment)', 'LaporanController::cetakDetailPerawatan/$1');
    $routes->get('pelanggan/list', 'LaporanController::getPelangganForModal');
});

// Pelanggan routes
$routes->group('admin/pelanggan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PelangganController::index');
    $routes->get('getPelanggan', 'PelangganController::getPelanggan');
    $routes->post('addPelanggan', 'PelangganController::addPelanggan');
    $routes->get('getPelangganById/(:segment)', 'PelangganController::getPelangganById/$1');
    $routes->post('updatePelanggan/(:segment)', 'PelangganController::updatePelanggan/$1');
    $routes->post('deletePelanggan/(:segment)', 'PelangganController::deletePelanggan/$1');
    $routes->get('getNextIdPelanggan', 'PelangganController::getNextIdPelanggan');
});

// Hewan routes
$routes->group('admin/hewan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'HewanController::index');
    $routes->get('create', 'HewanController::create');
    $routes->get('edit/(:segment)', 'HewanController::edit/$1');
    $routes->get('detail/(:segment)', 'HewanController::detail/$1');
    $routes->get('getHewan', 'HewanController::getHewan');
    $routes->post('addHewan', 'HewanController::addHewan');
    $routes->get('getHewanById/(:segment)', 'HewanController::getHewanById/$1');
    $routes->post('updateHewan/(:segment)', 'HewanController::updateHewan/$1');
    $routes->post('deleteHewan/(:segment)', 'HewanController::deleteHewan/$1');
    $routes->get('getNextIdHewan', 'HewanController::getNextIdHewan');
});

$routes->group('admin/barang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BarangController::index');
    $routes->get('create', 'BarangController::create');
    $routes->get('edit/(:segment)', 'BarangController::edit/$1');
    $routes->get('detail/(:segment)', 'BarangController::detail/$1');
    $routes->get('getBarang', 'BarangController::getBarang');
    $routes->get('getNextKdBarang', 'BarangController::getNextKdBarang');
    $routes->get('getBarangById/(:segment)', 'BarangController::getBarangById/$1');
    $routes->post('addBarang', 'BarangController::addBarang');
    $routes->post('updateBarang/(:segment)', 'BarangController::updateBarang/$1');
    $routes->post('deleteBarang/(:segment)', 'BarangController::deleteBarang/$1');
});

$routes->group('admin/kategori', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KategoriController::index');
    $routes->get('getKategori', 'KategoriController::getKategori');
    $routes->get('getKategoriById/(:segment)', 'KategoriController::getKategoriById/$1');
    $routes->post('addKategori', 'KategoriController::addKategori');
    $routes->post('updateKategori/(:segment)', 'KategoriController::updateKategori/$1');
    $routes->post('deleteKategori/(:segment)', 'KategoriController::deleteKategori/$1');
});

$routes->group('admin/supplier', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'SupplierController::index');
    $routes->get('getSupplier', 'SupplierController::getSupplier');
    $routes->get('getSupplierById/(:segment)', 'SupplierController::getSupplierById/$1');
    $routes->post('addSupplier', 'SupplierController::addSupplier');
    $routes->post('updateSupplier/(:segment)', 'SupplierController::updateSupplier/$1');
    $routes->post('deleteSupplier/(:segment)', 'SupplierController::deleteSupplier/$1');
});

// Barang Masuk routes
$routes->group('admin/barangmasuk', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BarangMasukController::index');
    $routes->get('create', 'BarangMasukController::create');
    $routes->get('edit/(:segment)', 'BarangMasukController::edit/$1');
    $routes->get('detail/(:segment)', 'BarangMasukController::detail/$1');
    $routes->get('getBarangMasuk', 'BarangMasukController::getBarangMasuk');
    $routes->get('getNextKdMasuk', 'BarangMasukController::getNextKdMasuk');
    $routes->get('getBarangMasukById/(:segment)', 'BarangMasukController::getBarangMasukById/$1');
    $routes->get('getDetailBarangMasuk/(:segment)', 'BarangMasukController::getDetailBarangMasuk/$1');
    $routes->get('getBarangById/(:segment)', 'BarangMasukController::getBarangById/$1');
    $routes->post('addBarangMasuk', 'BarangMasukController::addBarangMasuk');
    $routes->post('updateBarangMasuk/(:segment)', 'BarangMasukController::updateBarangMasuk/$1');
    $routes->post('deleteBarangMasuk/(:segment)', 'BarangMasukController::deleteBarangMasuk/$1');
    $routes->post('changeStatus/(:segment)', 'BarangMasukController::changeStatus/$1');
});

// Penjualan Routes
$routes->group('admin/penjualan', static function ($routes) {
    $routes->get('/', 'PenjualanController::index');
    $routes->get('create', 'PenjualanController::create');
    $routes->get('edit/(:segment)', 'PenjualanController::edit/$1');
    $routes->get('detail/(:segment)', 'PenjualanController::detail/$1');
    $routes->get('cetak/(:segment)', 'PenjualanController::cetak/$1');
    $routes->get('getPenjualan', 'PenjualanController::getPenjualan');
    $routes->get('getNextKdPenjualan', 'PenjualanController::getNextKdPenjualan');
    $routes->get('getPenjualanById/(:segment)', 'PenjualanController::getPenjualanById/$1');
    $routes->get('getDetailPenjualan/(:segment)', 'PenjualanController::getDetailPenjualan/$1');
    $routes->get('getBarangById/(:segment)', 'PenjualanController::getBarangById/$1');
    $routes->post('add', 'PenjualanController::addPenjualan');
    $routes->post('update/(:segment)', 'PenjualanController::updatePenjualan/$1');
    $routes->delete('delete/(:segment)', 'PenjualanController::deletePenjualan/$1');
    $routes->post('changeStatus/(:segment)', 'PenjualanController::changeStatus/$1');
});

// Fasilitas Routes
$routes->group('admin/fasilitas', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'FasilitasController::index');
    $routes->get('getFasilitas', 'FasilitasController::getFasilitas');
    $routes->get('getNextKdFasilitas', 'FasilitasController::getNextKdFasilitas');
    $routes->get('getFasilitasById/(:segment)', 'FasilitasController::getFasilitasById/$1');
    $routes->post('addFasilitas', 'FasilitasController::addFasilitas');
    $routes->post('updateFasilitas/(:segment)', 'FasilitasController::updateFasilitas/$1');
    $routes->post('deleteFasilitas/(:segment)', 'FasilitasController::deleteFasilitas/$1');
});

// Penitipan Routes
$routes->group('admin/penitipan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PenitipanController::index');
    $routes->get('create', 'PenitipanController::create');
    $routes->get('edit/(:segment)', 'PenitipanController::edit/$1');
    $routes->get('detail/(:segment)', 'PenitipanController::detail/$1');
    $routes->get('cetak/(:segment)', 'PenitipanController::cetak/$1');
    $routes->get('getPenitipan', 'PenitipanController::getPenitipan');
    $routes->get('getNextKdPenitipan', 'PenitipanController::getNextKdPenitipan');
    $routes->get('getPenitipanById/(:segment)', 'PenitipanController::getPenitipanById/$1');
    $routes->get('getDetailPenitipan/(:segment)', 'PenitipanController::getDetailPenitipan/$1');
    $routes->get('getHewanById/(:segment)', 'PenitipanController::getHewanById/$1');
    $routes->get('getHewanByPelanggan/(:segment)', 'PenitipanController::getHewanByPelanggan/$1');
    $routes->get('getFasilitasById/(:segment)', 'PenitipanController::getFasilitasById/$1');
    $routes->post('store', 'PenitipanController::addPenitipan');
    $routes->post('update/(:segment)', 'PenitipanController::update/$1');
    $routes->post('delete/(:segment)', 'PenitipanController::deletePenitipan/$1');
    $routes->post('changeStatus/(:segment)', 'PenitipanController::changeStatus/$1');
    $routes->post('hitungDenda', 'PenitipanController::hitungDenda');
    $routes->post('penjemputan', 'PenitipanController::penjemputan');
});

// Perawatan
$routes->group('admin/perawatan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PerawatanController::index');
    $routes->get('create', 'PerawatanController::create');
    $routes->get('edit/(:segment)', 'PerawatanController::edit/$1');
    $routes->get('detail/(:segment)', 'PerawatanController::detail/$1');
    $routes->get('cetak/(:segment)', 'PerawatanController::cetak/$1');
    $routes->post('store', 'PerawatanController::store');
    $routes->post('update/(:segment)', 'PerawatanController::update/$1');
    $routes->post('updateStatus', 'PerawatanController::updateStatus');
    $routes->delete('delete/(:segment)', 'PerawatanController::deletePerawatan/$1');

    // API endpoints
    $routes->get('getPerawatan', 'PerawatanController::getPerawatan');
    $routes->get('getNextKdPerawatan', 'PerawatanController::getNextKdPerawatan');
    $routes->get('getPerawatanById/(:segment)', 'PerawatanController::getPerawatanById/$1');
    $routes->get('getDetailPerawatan/(:segment)', 'PerawatanController::getDetailPerawatan/$1');
    $routes->get('getHewanByPelanggan/(:segment)', 'PerawatanController::getHewanByPelanggan/$1');
});
