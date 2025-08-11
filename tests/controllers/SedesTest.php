<?php

namespace Tests\Controllers;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class SedesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected $baseRoute = '/sedes';

    public function testIndexPageLoads()
    {
        $result = $this->get($this->baseRoute . '/');

        $result->assertStatus(200);
        $result->assertSee('Nuestras Sedes');
    }

    public function testCrearPageLoads()
    {
        $result = $this->get($this->baseRoute . '/crear');

        $result->assertStatus(200);
        $result->assertSee('Crear');
    }

    public function testGuardarWithInvalidDataRedirectsBackWithErrors()
    {
        $postData = [
            'name' => 'AB',    // menos de 3 caracteres para forzar error
            'location' => '',
            'capacity' => 'no-num',
            'lat' => 'invalid-decimal',
            'lng' => 'invalid-decimal'
        ];

        $result = $this->post($this->baseRoute . '/guardar', $postData);

        $result->assertStatus(302);
        $result->assertRedirect(); // Debe redirigir hacia atrás
        $result->assertSessionHas('errors');
    }

    public function testEditarNonExistentSedeRedirectsWithError()
    {
        $nonExistentId = 999999;

        $result = $this->get($this->baseRoute . '/editar/' . $nonExistentId);

        $result->assertStatus(302);
        $result->assertRedirect($this->baseRoute . '/');
        $result->assertSessionHas('error', 'Sede no encontrada');
    }

    public function testEliminarNonExistentSedeRedirectsWithError()
    {
        $nonExistentId = 999999;

        $result = $this->get($this->baseRoute . '/eliminar/' . $nonExistentId);

        $result->assertStatus(302);
        $result->assertRedirect($this->baseRoute . '/');
        $result->assertSessionHas('error', 'Sede no encontrada');
    }
}
