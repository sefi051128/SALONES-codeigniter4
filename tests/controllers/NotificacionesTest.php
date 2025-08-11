<?php

namespace Tests\App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class NotificacionesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected array $sessionData = [
        'isLoggedIn' => true,
    ];

    public function testIndexCargaConSesion()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/notificaciones');

        $result->assertStatus(200);
    }

    public function testCrearCargaFormulario()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/notificaciones/crear');

        $result->assertStatus(200);
    }

    public function testGuardarConDatosInvalidosRedirigeConError()
    {
        $postData = [
            'user_id' => 'abc', // inválido
            'notification_type' => 'Desconocido', // no válido
            'message' => 'corto', // menos de 10 caracteres
            'sent_date' => 'fecha-mal' // formato inválido
        ];

        $result = $this->withSession($this->sessionData)
                       ->post('/notificaciones/guardar', $postData);

        $result->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function testVerNotificacionInexistenteRedirigeConError()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/notificaciones/ver/999999');

        $result->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function testEliminarNotificacionInexistenteRedirige()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/notificaciones/eliminar/999999');

        $result->assertRedirect();
        $this->assertTrue(session()->has('success') || session()->has('error'));
    }
}
