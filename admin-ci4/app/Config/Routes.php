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
    $routes->get('transactions', 'Transactions::index');
    $routes->match(['GET', 'POST'], 'transactions/new', 'Transactions::create');
    $routes->get('transactions/returns', 'Transactions::returns');
    $routes->match(['GET', 'POST'], 'transactions/process_return/(:num)', 'Transactions::processReturn/$1');
    $routes->get('transactions/(:num)', 'Transactions::show/$1');
    $routes->resource('fines');
    $routes->post('fines/mark_paid/(:num)', 'Fines::markPaid/$1');
    $routes->post('fines/mark_unpaid/(:num)', 'Fines::markUnpaid/$1');
    $routes->get('reports', 'Reports::index');
    $routes->post('reports/generate', 'Reports::generate');
});
