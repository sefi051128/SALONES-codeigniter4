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
$routes->group('coordinador', ['filter' => 'auth:coordinador'], function($routes) {
    $routes->get('dashboard', 'Coordinador::dashboard', ['as' => 'coordinador.dashboard']);
});

$routes->group('logistica', ['filter' => 'auth:logística'], function($routes) {
    $routes->get('dashboard', 'Logistica::dashboard', ['as' => 'logistica.dashboard']);
});

$routes->group('seguridad', ['filter' => 'auth:seguridad'], function($routes) {
    $routes->get('dashboard', 'Seguridad::dashboard', ['as' => 'seguridad.dashboard']);
});
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
    $routes->post('createAdminUser', 'User::createAdminUser');
    $routes->get('edit/(:num)', 'User::edit/$1');
    
    // Cambia esta línea para aceptar tanto POST como PUT
    $routes->match(['put', 'post'], 'update/(:num)', 'User::update/$1');
    
    $routes->get('delete/(:num)', 'User::delete/$1');
    $routes->get('exportar-pdf', 'User::exportarPDF');
    $routes->get('show/(:num)', 'User::show/$1'); // Ruta para ver detalles
    // Elimina esta línea duplicada
    // $routes->match(['get', 'post'], 'users/edit/(:num)', 'UserController::edit/$1');
});

// -----------------------------
// Sedes
// -----------------------------
$routes->group('sedes', function($routes) {
    $routes->get('/', 'Sedes::index');
    $routes->get('crear', 'Sedes::crear'); // Muestra el formulario
    $routes->post('guardar', 'Sedes::guardar'); // Procesa el formulario
    $routes->get('editar/(:num)', 'Sedes::editar/$1'); // Muestra formulario edición
    $routes->post('actualizar/(:num)', 'Sedes::actualizar/$1'); // Procesa edición
    $routes->get('eliminar/(:num)', 'Sedes::eliminar/$1'); // Elimina sede
});

// -----------------------------
// Eventos
// -----------------------------
$routes->group('eventos', function($routes) {
    $routes->get('/', 'Eventos::index', ['as' => 'eventos.index']);
    $routes->get('crear', 'Eventos::crear', ['as' => 'eventos.crear']);
    $routes->post('guardar', 'Eventos::guardar', ['as' => 'eventos.guardar']);
    $routes->get('editar/(:num)', 'Eventos::editar/$1', ['as' => 'eventos.editar']);
    $routes->post('actualizar/(:num)', 'Eventos::actualizar/$1', ['as' => 'eventos.actualizar']);
    $routes->get('eliminar/(:num)', 'Eventos::eliminar/$1', ['as' => 'eventos.eliminar']);
    $routes->get('por-sede/(:num)', 'Eventos::eventosPorSede/$1', ['as' => 'eventos.por_sede']);
    $routes->get('ver/(:num)', 'Eventos::ver/$1', ['as' => 'eventos.ver']);
});

// -----------------------------
// Reservas
// -----------------------------
$routes->group('reservas', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Reservas::index', ['as' => 'reservas.index']);
    $routes->get('verReservas', 'Reservas::verReservas', ['as' => 'reservas.verReservas']); // Ruta especial para vista alternativa
    $routes->get('ver/(:num)', 'Reservas::ver/$1', ['as' => 'reservas.ver']); // Detalles de una reserva específica
    $routes->get('nueva', 'Reservas::nueva', ['as' => 'reservas.nueva']);
    $routes->get('nueva/(:num)', 'Reservas::nueva/$1', ['as' => 'reservas.nueva_evento']);
    $routes->post('guardar', 'Reservas::guardar', ['as' => 'reservas.guardar']);
    $routes->post('confirmar/(:num)', 'Reservas::confirmar/$1', ['as' => 'reservas.confirmar']);
    $routes->post('cancelar/(:num)', 'Reservas::cancelar/$1', ['as' => 'reservas.cancelar']);
});

// -----------------------------
// Página 404 personalizada
// -----------------------------
$routes->set404Override(); // Usa la vista 404 por defecto
