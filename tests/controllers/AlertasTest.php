<?php

namespace Tests\App\Controllers;

use App\Controllers\Alertas;
use CodeIgniter\Test\CIUnitTestCase;

class AlertasTest extends CIUnitTestCase
{
    protected $alertas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->alertas = new Alertas();
    }

    public function testInicio()
    {
        $result = $this->alertas->inicio();
        $this->assertIsString($result); // devuelve la vista como string
    }

    public function testCrear()
    {
        $result = $this->alertas->crear();
        $this->assertIsString($result);
    }

    // Para guardar, como depende de $this->request y validación, lo omitimos

    // Para editar, solo probamos que con un ID cualquiera retorne string o lance excepción
    public function testEditar()
    {
        try {
            $result = $this->alertas->editar(1);
            $this->assertIsString($result);
        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            $this->assertTrue(true); // excepción esperada si no existe el ID 1
        }
    }

    // Similar para actualizar y resolver, solo probamos que no falle al llamarlos con ID 1

    public function testResolver()
    {
        $result = $this->alertas->resolver(1);
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $result);
    }
}
