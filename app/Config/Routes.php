<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Página principal (para clientes o sin login)
$routes->get('/', 'Home::inicio');

// Login y registro
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/register', 'AuthController::register');
$routes->post('/createUser', 'AuthController::storeUser');
$routes->get('/logout', 'Home::logout');

// Dashboard general (después de login por rol)
$routes->get('/inicio', 'Home::inicio');

// -----------------------------
// Panel de administrador
// -----------------------------
$routes->group('admin', ['filter' => 'auth:administrador'], function($routes) {
    $routes->get('/', 'Admin::inicio', ['as' => 'admin.dashboard']);
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->get('crear', 'Admin::crear');
    $routes->post('crear', 'Admin::crear');
    $routes->get('editar/(:num)', 'Admin::editar/$1');
    $routes->post('editar/(:num)', 'Admin::editar/$1');
    $routes->get('eliminar/(:num)', 'Admin::eliminar/$1');
});

// -----------------------------
// Dashboards para otros roles
// -----------------------------
$routes->get('/cliente/dashboard', 'Cliente::dashboard', ['as' => 'cliente.dashboard']);
$routes->get('/coordinador/dashboard', 'Coordinador::dashboard');
$routes->get('/logistica/dashboard', 'Logistica::dashboard');
$routes->get('/seguridad/dashboard', 'Seguridad::dashboard');

// -----------------------------
// CRUD de Inventario
// -----------------------------
$routes->group('inventory', function($routes) {
    $routes->get('/', 'Inventory::index');
    $routes->match(['get', 'post'], 'create', 'Inventory::create');
    $routes->post('store', 'Inventory::store');
    $routes->get('report', 'Inventory::report');
    $routes->get('qr/(:num)', 'Inventory::qr/$1');
    $routes->get('testInsert', 'Inventory::testInsert');
    $routes->get('update-location/(:num)', 'Inventory::updateLocation/$1');
    $routes->get('delete/(:num)', 'Inventory::delete/$1');
    $routes->get('edit/(:num)', 'Inventory::edit/$1');
    $routes->post('update/(:num)', 'Inventory::update/$1');
});

// -----------------------------
// CRUD de Usuarios
// -----------------------------
$routes->group('users', ['filter' => 'auth:administrador'], function($routes) {
    $routes->get('/', 'User::index');
    $routes->get('create', 'User::create');
    $routes->post('create', 'User::create');
    $routes->get('edit/(:num)', 'User::edit/$1');
    $routes->post('edit/(:num)', 'User::edit/$1');
    $routes->get('delete/(:num)', 'User::delete/$1');
});



// -----------------------------
// Página 404 personalizada
// -----------------------------
$routes->set404Override(); // Usa la vista 404 por defecto
