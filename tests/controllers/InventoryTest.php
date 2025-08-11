<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class InventoryTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function userSession(): array
    {
        return [
            'user_id'    => 1,
            'username'   => 'usuario',
            'isLoggedIn' => true,
        ];
    }

    public function testInventoryIndexLoadsWithSession()
    {
        $result = $this->withSession($this->userSession())
                       ->call('get', '/inventory');
        $result->assertStatus(200);
        $result->assertSee('Inventario de Artículos');
    }

    public function testInventoryIndexWithFilters()
    {
        // Simula busqueda, status y responsable como query params
        $result = $this->withSession($this->userSession())
                       ->call('get', '/inventory?search=test&status=Disponible&responsible=Admin');
        $result->assertStatus(200);
        // No asumimos contenido específico más allá de que cargue la página
        $this->assertTrue($result->isOK());
    }

    public function testCreatePageLoads()
    {
        $result = $this->withSession($this->userSession())
                       ->call('get', '/inventory/create');
        $result->assertStatus(200);
        $result->assertSee('Agregar nuevo artículo');
    }

    public function testEditNonexistentItemRedirects()
    {
        $result = $this->withSession($this->userSession())
                       ->call('get', '/inventory/edit/999999');
        $result->assertStatus(302);
    }

    public function testQrNonexistentItemReturns404()
{
    $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);

    $result = $this->withSession($this->userSession())
                   ->call('get', '/inventory/qr/999999');
    // No hace falta hacer assert después porque la excepción interrumpe la ejecución
}


    public function testReportLoadsWithSession()
    {
        $result = $this->withSession($this->userSession())
                       ->call('get', '/inventory/report');
        $this->assertTrue($result->isOK() || $result->isRedirect());
    }

    public function testStoreWithInvalidDataRedirects()
    {
        // Quantity inválido (0) debe fallar validación
        $post = [
            'quantity' => '0',
        ];

        $result = $this->withSession($this->userSession())
                       ->call('post', '/inventory/store', $post);
        $result->assertStatus(302);
    }

    public function testUpdateNonexistentItemRedirects()
    {
        // Aunque se use POST para update en el inexistente
        $post = [
            'name' => 'Nombre',
            'location' => 'Ubicación',
            'status' => 'Disponible',
            'current_responsible' => 'Admin',
        ];

        $result = $this->withSession($this->userSession())
                       ->call('post', '/inventory/update/999999', $post);
        $result->assertStatus(302);
    }

    public function testDeleteNonexistentItemRedirects()
    {
        $result = $this->withSession($this->userSession())
                       ->call('get', '/inventory/delete/999999');
        $result->assertStatus(302);
    }
}
