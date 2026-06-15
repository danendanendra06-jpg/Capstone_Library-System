<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Admin::index', ['filter' => 'auth:admin']);
$routes->get('/dashboard', 'Admin::index', ['filter' => 'auth:admin']);

// Auth Routes
$routes->match(['GET', 'POST'], '/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// CRUD Routes for Admin
$routes->group('', ['filter' => 'auth:admin'], static function($routes) {
    $routes->resource('books');
    $routes->resource('categories');
    $routes->resource('users', ['controller' => 'Users']);
    $routes->post('users/(:num)/suspend', 'Users::suspend/$1');
    $routes->post('users/(:num)/activate', 'Users::activate/$1');
    $routes->get('borrows', 'Borrows::index');
    $routes->match(['GET', 'POST'], 'borrows/new', 'Borrows::create');
    $routes->get('borrows/returns', 'Borrows::returns');
    $routes->match(['GET', 'POST'], 'borrows/process_return/(:num)', 'Borrows::processReturn/$1');
    $routes->get('borrows/(:num)', 'Borrows::show/$1');
    $routes->resource('fines');
    $routes->post('fines/mark_paid/(:num)', 'Fines::markPaid/$1');
    $routes->post('fines/mark_unpaid/(:num)', 'Fines::markUnpaid/$1');
    $routes->get('reports', 'Reports::index');
    $routes->post('reports/generate', 'Reports::generate');
});
