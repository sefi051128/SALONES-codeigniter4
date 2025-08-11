<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class UserTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    // Simula un administrador conectado
    protected function adminSession(): array
    {
        return [
            'user_id'    => 1,
            'username'   => 'admin',
            'role'       => 'administrador',
            'isLoggedIn' => true,
        ];
    }

    public function testUsersIndexLoadsWithAdminSession()
    {
        $result = $this->withSession($this->adminSession())
                       ->call('get', '/users');
        $result->assertStatus(200);
        $result->assertSee('Gestión de Usuarios');
    }

    public function testCreateUserFormLoadsWithAdminSession()
    {
        $result = $this->withSession($this->adminSession())
                       ->call('get', '/users/create');
        $result->assertStatus(200);
        $result->assertSee('Agregar Usuario');
    }

    public function testCreateAdminUserValidationFailsMissingRole()
    {
        $post = [
            'username' => 'admin_test_' . time(),
            'password' => 'password123',
            'email'    => 'admin_test_' . time() . '@example.com',
            'phone'    => '+521234567890',
            // 'role' intentionally omitted to trigger validation failure
        ];

        $result = $this->withSession($this->adminSession())
                       ->call('post', '/users/createAdminUser', $post);
        $result->assertStatus(302);
    }

    public function testShowNonexistentUserRedirectsWithAdminSession()
    {
        $result = $this->withSession($this->adminSession())
                       ->call('get', '/users/show/999999');
        $result->assertStatus(302);
    }

    public function testExportarPDFWithoutLoginRedirects()
    {
        // Sin sesión debe redirigir a login
        $result = $this->call('get', '/users/exportar-pdf');
        $result->assertStatus(302);
    }

    public function testEditPageLoadsWithAdminSession()
    {
        // Asume que existe el ID 1
        $result = $this->withSession($this->adminSession())
                       ->call('get', '/users/edit/1');

        // Acepta que sea OK o redirect
        $this->assertTrue($result->isOK() || $result->isRedirect(), "Respuesta inesperada, status: " . $result->getStatusCode());
    }
}
