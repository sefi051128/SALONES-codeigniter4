<?php

// Define constantes necesarias para CI4 en testing
define('ROOTPATH', realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR);
define('APPPATH', ROOTPATH . 'app' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', ROOTPATH . 'system' . DIRECTORY_SEPARATOR);
define('WRITEPATH', ROOTPATH . 'writable' . DIRECTORY_SEPARATOR);
define('TESTPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);
define('SUPPORTPATH', TESTPATH . '_support' . DIRECTORY_SEPARATOR);

require_once ROOTPATH . 'vendor/autoload.php';

// Carga el entorno de testing
$env = \CodeIgniter\Config\DotEnv::createUnsafeImmutable(ROOTPATH);
$env->load();

// Opcional: configura el entorno a testing si no está definido
if (!defined('CI_ENVIRONMENT')) {
    define('CI_ENVIRONMENT', 'testing');
}

// Puedes hacer aquí más configuraciones necesarias para testing si quieres

