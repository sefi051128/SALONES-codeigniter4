<?php

namespace Tests\App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AlmacenTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected $sessionData = [
        'isLoggedIn' => true,
    ];

    public function testIndexConSesionDevuelveCodigo200()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/almacen');

        $result->assertStatus(200);
    }

    public function testNuevoDevuelveCodigo200()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/almacen/nuevo');

        $result->assertStatus(200);
    }

    public function testGuardarConDatosValidosRedirige()
    {
        $postData = [
            'user_id' => 1,
            'access_type' => 'Entrada',
        ];

        $result = $this->withSession($this->sessionData)
                       ->post('/almacen/guardar', $postData);

        $result->assertRedirect();
        $this->assertTrue(session()->has('success'));
    }

    public function testGuardarConDatosInvalidosRedirigeConError()
    {
        $postData = [
            'user_id' => 'abc',    // inválido
            'access_type' => 'Nada', // inválido
        ];

        $result = $this->withSession($this->sessionData)
                       ->post('/almacen/guardar', $postData);

        $result->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function testReporteConSesionDevuelveCodigo200()
    {
        $result = $this->withSession($this->sessionData)
                       ->get('/almacen/reporte');

        $result->assertStatus(200);
    }
}
