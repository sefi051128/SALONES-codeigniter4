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
    return redirect()->to('/inventory');
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


$routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
$routes->post('inventory/update/(:num)', 'Inventory::update/$1');


//Qr y mapa de articulos de inventario
$routes->get('inventory/update-location/(:num)', 'Inventory::updateLocation/$1');
$routes->get('inventory/qr/(:num)', 'Inventory::qr/$1');
$routes->get('inventory/delete/(:num)', 'Inventory::delete/$1');

