<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Exceptions\PageNotFoundException;

class EventosTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * 1. Probar que la página principal de eventos carga correctamente
     */
    public function testIndexPageLoads()
    {
        $result = $this->get('/eventos');
        $result->assertStatus(200);
        // No forzar texto exacto, solo comprobar que hay contenido de eventos
        $result->assertSee('Eventos', 'html');
    }

    /**
     * 2. Probar que ver eventos por una sede inexistente lanza excepción 404
     */
    public function testEventosPorSedeNotFound()
    {
        $this->expectException(PageNotFoundException::class);
        $this->call('get', '/eventos/por-sede/999999');
    }

    /**
     * 3. Probar que guardar un evento con datos inválidos redirige con errores
     */
    public function testGuardarEventoInvalidData()
    {
        $postData = [
            'venue_id' => '', // Falta
            'name' => 'Ev', // Muy corto
            'date' => 'fecha_invalida',
            'status' => 'otro'
        ];

        $result = $this->post('/eventos/guardar', $postData);
        $result->assertStatus(302);
        $result->assertSessionHas('errors');
    }

    /**
     * 4. Probar que editar un evento inexistente lanza excepción 404
     */
    public function testEditarEventoNotFound()
    {
        $this->expectException(PageNotFoundException::class);
        $this->call('get', '/eventos/editar/999999');
    }

    /**
     * 5. Probar que ver detalles de un evento inexistente lanza excepción 404
     */
    public function testVerEventoNotFound()
    {
        $this->expectException(PageNotFoundException::class);
        $this->call('get', '/eventos/ver/999999');
    }
}
