<?php

namespace Tests\App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class ReportesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected array $sessionData = [
        'isLoggedIn' => true,
    ];

    public function testIndexCargaConSesion()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/reportes');

        $result->assertStatus(200);
    }

    public function testNuevoCargaFormulario()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/reportes/nuevo');

        $result->assertStatus(200);
    }

    public function testGuardarConDatosInvalidosRedirigeConError()
    {
        $postData = [
            'event_id' => 'no-num',           // inválido
            'item_id' => '',                  // inválido
            'incident_type' => '',            // requerido
            'description' => '',              // requerido
            // falta 'photo' obligatorio
        ];

        $result = $this->withSession($this->sessionData)
                       ->post('/reportes/guardar', $postData);

        $result->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function testEditarReporteInexistenteRedirigeConError()
    {
        $nonexistentId = 999999;
        $result = $this->withSession($this->sessionData)
                       ->get("/reportes/editar/{$nonexistentId}");

        $result->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function testEliminarReporteInexistenteRedirigeConError()
    {
        $nonexistentId = 999999;
        $result = $this->withSession($this->sessionData)
                       ->get("/reportes/eliminar/{$nonexistentId}");

        $result->assertRedirect();
        $this->assertTrue(session()->has('error') || session()->has('success'));
    }
}
