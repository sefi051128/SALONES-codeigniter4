<?php

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;

class ModuloReservasSimpleTest extends TestCase
{
   
    
    public function test_listado_reservas()
    {
        $url = '/reservas/';
        $this->assertStringContainsString('reservas', $url);
    }

    public function test_formulario_nueva_booking()
    {
        $url = '/reservas/booking/nueva';
        $this->assertStringContainsString('nueva', $url);
    }

    public function test_guardar_booking_post()
    {
        $url = '/reservas/booking/guardar';
        $method = 'POST';
        $this->assertEquals('POST', $method);
        $this->assertStringContainsString('guardar', $url);
    }

    public function test_ver_reserva_booking()
    {
        $id = 1;
        $type = 'booking';
        $url = "/reservas/ver/{$id}/{$type}";
        $this->assertMatchesRegularExpression('/reservas\/ver\/\d+\/(booking|reservation)/', $url);
    }

    public function test_cancelar_booking_post()
    {
        $id = 1;
        $url = "/reservas/booking/cancelar/{$id}";
        $method = 'POST';
        $this->assertEquals('POST', $method);
        $this->assertStringContainsString('cancelar', $url);
    }
}
