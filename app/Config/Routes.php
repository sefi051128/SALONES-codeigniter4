<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Página principal (para clientes o sin login)
$routes->get('/', 'Home::inicio');

// Móddulo de Login y registro
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
// Módulo CRUD de Inventario
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
    $routes->get('reservas/test-inventory', 'Reservas::testInventory');
    $routes->get('reservas/test-inventory', 'Reservas::testInventory');
    $routes->get('test-inventory', 'Reservas::testInventory');
});

// -----------------------------
// Módulo CRUD de Usuarios
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
// Módulo de Sedes
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
// Módulo de Eventos
// -----------------------------
$routes->group('eventos', function($routes) {
    $routes->get('/', 'Eventos::index', ['as' => 'eventos.index']);
    $routes->get('eventos/eventosPorSede/(:num)', 'Eventos::eventosPorSede/$1');
    $routes->get('crear', 'Eventos::crear', ['as' => 'eventos.crear']);
    $routes->post('guardar', 'Eventos::guardar', ['as' => 'eventos.guardar']);
    $routes->get('editar/(:num)', 'Eventos::editar/$1', ['as' => 'eventos.editar']);
    $routes->post('actualizar/(:num)', 'Eventos::actualizar/$1', ['as' => 'eventos.actualizar']);
    $routes->post('eliminar/(:num)', 'Eventos::eliminar/$1', ['as' => 'eventos.eliminar']); // <-- aquí el cambio
    $routes->get('por-sede/(:num)', 'Eventos::eventosPorSede/$1', ['as' => 'eventos.por_sede']);
    $routes->get('ver/(:num)', 'Eventos::ver/$1', ['as' => 'eventos.ver']);
});

// -----------------------------
// Módulo de Reservas
// -----------------------------
$routes->group('reservas', ['filter' => 'auth'], function($routes) {
    // Vista principal de reservas
    $routes->get('/', 'Reservas::index', ['as' => 'reservas.index']);
    
    // Nueva ruta para crear reserva (directa)
    $routes->get('crearReserva', 'Reservas::crearReserva', ['as' => 'reservas.crear_reserva']);
    
    // Rutas para reservas de eventos (bookings)
    $routes->group('booking', function($routes) {
        $routes->get('nueva', 'Reservas::nuevaBooking', ['as' => 'reservas.nueva_booking']);
        $routes->post('guardar', 'Reservas::guardarBooking', ['as' => 'reservas.guardar_booking']);
        $routes->get('editar/(:num)', 'Reservas::editarBooking/$1', ['as' => 'reservas.editar_booking']);
        $routes->post('actualizar/(:num)', 'Reservas::actualizarBooking/$1', ['as' => 'reservas.actualizar_booking']);
        $routes->post('cancelar/(:num)', 'Reservas::cancelarBooking/$1', ['as' => 'reservas.cancelar_booking']);
        $routes->delete('eliminar/(:num)', 'Reservas::eliminarBooking/$1', ['as' => 'reservas.eliminar_booking']);
    });
    
    // Rutas para reservas de artículos (reservations)
    $routes->group('reservation', function($routes) {
        $routes->get('nueva', 'Reservas::nuevaReservation', ['as' => 'reservas.nueva_reservation']);
        $routes->post('guardar', 'Reservas::guardarReservation', ['as' => 'reservas.guardar_reservation']);
        $routes->get('editar/(:num)', 'Reservas::editarReservation/$1', ['as' => 'reservas.editar_reservation']);
        $routes->post('actualizar/(:num)', 'Reservas::actualizarReservation/$1', ['as' => 'reservas.actualizar_reservation']);
        $routes->post('cancelar/(:num)', 'Reservas::cancelarReservation/$1', ['as' => 'reservas.cancelar_reservation']);
        $routes->delete('eliminar/(:num)', 'Reservas::eliminarReservation/$1', ['as' => 'reservas.eliminar_reservation']);
    });
    
    // Rutas comunes para ambos tipos
    $routes->get('ver/(:num)/(booking|reservation)', 'Reservas::ver/$1/$2', ['as' => 'reservas.ver']);
    $routes->post('confirmar/(:num)/(booking|reservation)', 'Reservas::confirmar/$1/$2', ['as' => 'reservas.confirmar']);

    $routes->get('reservas/liberar-items/(:num)', 'Reservas::releaseItems/$1', ['as' => 'reservas.liberarItems']);

});

// -----------------------------
// Módulo de Devoluciones
// -----------------------------
$routes->group('devoluciones', ['filter' => 'auth'], function($routes) {
    // Pantalla principal de devoluciones / checklists pendientes
    $routes->get('/', 'Devoluciones::index', ['as' => 'devoluciones.index']);

    // Crear checklist de devolución para un evento específico
    $routes->get('crear/(:num)', 'Devoluciones::crear/$1', ['as' => 'devoluciones.crear']);

    // Guardar checklist (entrega/devolución)
    $routes->post('guardar', 'Devoluciones::guardar', ['as' => 'devoluciones.guardar']);

    // Ver checklists ya capturados para un evento
    $routes->get('ver/(:num)', 'Devoluciones::ver/$1', ['as' => 'devoluciones.ver']);

    $routes->get('cliente/(:num)', 'Devoluciones::clienteForm/$1', ['as' => 'devoluciones.cliente_form']);
$routes->post('cliente/guardar', 'Devoluciones::guardarClienteChecklist', ['as' => 'devoluciones.cliente_guardar']);
});

// -----------------------------
// Módulo de Chat Interno
// -----------------------------
$routes->group('chat', ['filter' => 'auth'], function($routes) {
    // pantalla principal del chat
    $routes->get('', 'Chat::index');

    // ver una conversación específica (por id de conversación)
    $routes->get('conversacion/(:num)', 'Chat::conversacion/$1');

    // enviar mensaje (puede venir por POST desde el form)
    $routes->post('enviar', 'Chat::enviar');

    // opcional: marcar como leído, obtener usuarios, etc., más adelante
});



// -----------------------------
// Módulo de Sistema de Alertas
// -----------------------------
$routes->group('alertas', ['filter' => 'auth'], function($routes) {
    // Ruta principal (a donde lleva el botón)
    $routes->get('/', 'Alertas::inicio', ['as' => 'alertas']);
    
    // Creación
    $routes->get('crear', 'Alertas::crear');
    $routes->post('guardar', 'Alertas::guardar');
    
    // Edición
    $routes->get('editar/(:num)', 'Alertas::editar/$1');
    $routes->post('actualizar/(:num)', 'Alertas::actualizar/$1');
    
    // Resolución
    $routes->get('resolver/(:num)', 'Alertas::resolver/$1');
    
    // Reportes (solo admin)
    $routes->group('', ['filter' => 'role:administrador'], function($routes) {
        $routes->get('reportes', 'Alertas::reportes');
    });

    // Reporte PDF
    $routes->get('reporte', 'Alertas::reporte');
});

// -----------------------------
// Módulo de Accesos al Almacén
// -----------------------------
$routes->group('almacen', ['filter' => 'auth'], function($routes) {
    // Ruta principal (listado)
    $routes->get('/', 'Almacen::index', ['as' => 'almacen']);
    
    // Creación de accesos
    $routes->get('nuevo', 'Almacen::nuevo', ['as' => 'almacen.nuevo']);
    $routes->post('guardar', 'Almacen::guardar', ['as' => 'almacen.guardar']);
    
    // Reporte PDF (accesible para cualquier usuario autenticado)
    $routes->get('reporte', 'Almacen::reporte', ['as' => 'almacen.reporte']);
    
    // Otras rutas de reportes (solo para administradores si las tienes)
    $routes->group('', ['filter' => 'role:administrador'], function($routes) {
        $routes->get('reportes', 'Almacen::reportes', ['as' => 'almacen.reportes']);
    });
});

// -----------------------------
// Módulo de Reportes
// -----------------------------
$routes->group('reportes', ['filter' => 'auth'], function($routes) {
    // Listado principal
    $routes->get('/', 'Reportes::index', ['as' => 'reportes']);
    
    // Creación
    $routes->get('nuevo', 'Reportes::nuevo', ['as' => 'reportes.nuevo']);
    $routes->post('guardar', 'Reportes::guardar', ['as' => 'reportes.guardar']);
    
    // Edición
    $routes->get('editar/(:num)', 'Reportes::editar/$1', ['as' => 'reportes.editar']);
    $routes->post('actualizar/(:num)', 'Reportes::actualizar/$1', ['as' => 'reportes.actualizar']);
    
    // Eliminación
    $routes->get('eliminar/(:num)', 'Reportes::eliminar/$1', ['as' => 'reportes.eliminar']);
    
    // Reportes especiales (solo para administradores)
    $routes->group('', ['filter' => 'role:administrador'], function($routes) {
        $routes->get('exportar', 'Reportes::exportar', ['as' => 'reportes.exportar']);
    });

    // Generación de PDF
    $routes->get('generar-pdf', 'Reportes::generarPdf');
});

// -----------------------------
// Módulo de Notificaciones
// -----------------------------
$routes->group('notificaciones', ['filter' => 'auth'], function($routes) {
    // Listado principal
    $routes->get('/', 'Notificaciones::index', ['as' => 'notificaciones']);
    
    // Creación
    $routes->get('crear', 'Notificaciones::crear', ['as' => 'notificaciones.crear']);
    $routes->post('guardar', 'Notificaciones::guardar', ['as' => 'notificaciones.guardar']);
    
    // Visualización
    $routes->get('ver/(:num)', 'Notificaciones::ver/$1', ['as' => 'notificaciones.ver']);
    
    // Eliminación
    $routes->get('eliminar/(:num)', 'Notificaciones::eliminar/$1', ['as' => 'notificaciones.eliminar']);
});

// -----------------------------
// Página 404 personalizada
// -----------------------------
$routes->set404Override(); // Usa la vista 404 por defecto
