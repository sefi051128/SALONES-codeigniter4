<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/register', 'Home::register');
$routes->post('/createUser', 'Home::createUser');
$routes->post('/login', 'Home::login');
$routes->get('/inicio', 'Home::inicio');
$routes->get('/logout', 'Home::logout');

// Redirección para páginas no encontradas
$routes->set404Override(function($msg = null) {
    redirect()->to('/inventory')->send();
    exit; // Evita que el flujo continúe
});



// -----------------------------
// CRUD para artículos (inventario)
// -----------------------------
$routes->group('inventory', function($routes) {
    $routes->get('/', 'Inventory::index');
    $routes->match(['get', 'post'], 'create', 'Inventory::create');
    $routes->get('qr/(:num)', 'Inventory::qr/$1');
    $routes->get('testInsert', 'Inventory::testInsert');
});

$routes->get('/inventory/create', 'Inventory::create');
$routes->post('/inventory/store', 'Inventory::store');

$routes->get('inventory/report', 'Inventory::report');


//Qr y mapa de articulos de inventario
$routes->get('inventory/update-location/(:num)', 'Inventory::updateLocation/$1');
$routes->get('inventory/qr/(:num)', 'Inventory::qr/$1');
$routes->get('inventory/delete/(:num)', 'Inventory::delete/$1');

$routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
$routes->post('inventory/update/(:num)', 'Inventory::update/$1');

$routes->get('inventory/delete/(:num)', 'Inventory::delete/$1');


// Login y registro
$routes->get('/', 'Home::index');
$routes->post('/login', 'Home::login');
$routes->get('/register', 'Home::register');
$routes->post('/createUser', 'Home::createUser');
$routes->get('/logout', 'Home::logout');

// Panel de administrador (usa inicio.php)
$routes->get('/inicio', 'Home::inicio');

// Dashboards por rol
$routes->get('/cliente/dashboard', 'Cliente::dashboard');
$routes->get('/coordinador/dashboard', 'Coordinador::dashboard');
$routes->get('/logistica/dashboard', 'Logistica::dashboard');
$routes->get('/seguridad/dashboard', 'Seguridad::dashboard');

// Rutas CRUD para administrador (usa Admin.php)
$routes->group('admin', ['filter' => 'auth:administrador'], function($routes) {
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->get('crear', 'Admin::crear');
    $routes->post('crear', 'Admin::crear');
    $routes->get('editar/(:num)', 'Admin::editar/$1');
    $routes->post('editar/(:num)', 'Admin::editar/$1');
    $routes->get('eliminar/(:num)', 'Admin::eliminar/$1');
});

$routes->get('/cliente/dashboard', 'Cliente::dashboard', ['as' => 'cliente.dashboard']);