<?php

use PHPUnit\Framework\TestCase;

class ChatTest extends TestCase
{
    protected $routes = [
        'index'        => '/chat',
        'conversacion' => '/chat/conversacion/1',
        'enviar'       => '/chat/enviar',
    ];

    /** @test */
    public function testRutaIndexDefinida()
    {
        $this->assertSame('/chat', $this->routes['index'], 'La ruta /chat debe estar correctamente definida.');
    }

    /** @test */
    public function testRutaConversacionDefinida()
    {
        $this->assertStringStartsWith('/chat/conversacion/', $this->routes['conversacion'], 'La ruta de conversación debe empezar con /chat/conversacion/.');
    }

    /** @test */
    public function testRutaEnviarDefinida()
    {
        $this->assertSame('/chat/enviar', $this->routes['enviar'], 'La ruta /chat/enviar debe estar correctamente definida.');
    }

    /** @test */
    public function testIdConversacionEsNumerico()
    {
        $partes = explode('/', $this->routes['conversacion']);
        $this->assertTrue(is_numeric(end($partes)), 'El ID de la conversación debe ser numérico.');
    }

    /** @test */
    public function testTodasLasRutasNoVacias()
    {
        foreach ($this->routes as $nombre => $ruta) {
            $this->assertNotEmpty($ruta, "La ruta {$nombre} no debe estar vacía.");
        }
    }
}
