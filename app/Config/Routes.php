<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Halaman Utama
$routes->get('/', 'Home::index');

// Route untuk Barang
$routes->group('barang', function($routes) {
    $routes->get('/', 'Barang::index');
    $routes->get('create', 'Barang::create');
    $routes->post('save', 'Barang::save');
    $routes->get('edit/(:segment)', 'Barang::edit/$1');
    $routes->post('update/(:segment)', 'Barang::update/$1');
    $routes->delete('(:segment)', 'Barang::delete/$1');
});

// Route untuk Penjualan
$routes->group('penjualan', function($routes) {
    $routes->get('/', 'Penjualan::index');
    $routes->get('getBarang', 'Penjualan::getBarang');
    $routes->post('save', 'Penjualan::save');
    $routes->get('detail/(:num)', 'Penjualan::getDetailPenjualan/$1');
});

// Route untuk Laporan
$routes->group('laporan', function($routes) {
    $routes->get('/', 'Penjualan::laporan');
    $routes->get('detail/(:num)', 'Penjualan::getDetailLaporan/$1');
});

// Jika menggunakan CSRF Protection
$routes->post('penjualan/save', 'Penjualan::save', ['filter' => 'csrf']);
$routes->post('barang/save', 'Barang::save', ['filter' => 'csrf']);
$routes->post('barang/update/(:segment)', 'Barang::update/$1', ['filter' => 'csrf']);
