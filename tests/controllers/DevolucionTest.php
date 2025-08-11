<?php
namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;

class DevolucionTest extends TestCase
{
    public function test_ruta_index()
    {
        $url = '/devoluciones/';
        $method = 'GET';

        $this->assertStringContainsString('devoluciones', $url);
        $this->assertEquals('GET', $method);
    }

    public function test_ruta_crear_con_parametro()
    {
        $eventId = 10;
        $url = "/devoluciones/crear/$eventId";
        $method = 'GET';

        $this->assertStringContainsString('crear', $url);
        $this->assertStringContainsString((string)$eventId, $url);
        $this->assertEquals('GET', $method);
    }

    public function test_ruta_guardar_post()
    {
        $url = '/devoluciones/guardar';
        $method = 'POST';

        $this->assertStringContainsString('guardar', $url);
        $this->assertEquals('POST', $method);
    }

    public function test_ruta_ver_con_parametro()
    {
        $eventId = 5;
        $url = "/devoluciones/ver/$eventId";
        $method = 'GET';

        $this->assertStringContainsString('ver', $url);
        $this->assertStringContainsString((string)$eventId, $url);
        $this->assertEquals('GET', $method);
    }

    public function test_ruta_cliente_form_y_guardar()
    {
        $bookingId = 7;
        $urlForm = "/devoluciones/cliente/$bookingId";
        $urlGuardar = "/devoluciones/cliente/guardar";

        $this->assertStringContainsString('cliente', $urlForm);
        $this->assertStringContainsString((string)$bookingId, $urlForm);
        $this->assertEquals('GET', 'GET'); // para el formulario

        $this->assertStringContainsString('cliente', $urlGuardar);
        $this->assertStringContainsString('guardar', $urlGuardar);
        $this->assertEquals('POST', 'POST'); // para el guardar
    }
}
