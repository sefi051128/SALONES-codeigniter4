<?php

namespace Tests\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class AuthControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testLoginPageLoads()
    {
        $result = $this->call('get', '/login');
        $result->assertStatus(200);
        $result->assertSee('Iniciar Sesión');
    }

    public function testRegisterPageLoads()
    {
        $result = $this->call('get', '/register');
        $result->assertStatus(200);
        $result->assertSee('Registro de Usuario');
    }

    public function testHomePageLoads()
    {
        $result = $this->call('get', '/inicio');
        $result->assertStatus(200);
        $result->assertSee('Bienvenido');
    }

    public function testRootPageLoads()
    {
        $result = $this->call('get', '/');
        $result->assertStatus(200);
        $result->assertSee('EventMobiliario');
    }
}
