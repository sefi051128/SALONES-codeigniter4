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
$routes->set404Override(function() {
    return redirect()->to('/')->with('error', 'Página no encontrada');
});