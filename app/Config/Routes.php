<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

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
