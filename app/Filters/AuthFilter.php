<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/')->with('error', 'Acceso denegado. Inicia sesión primero.');
        }

        // Verifica roles si se pasa como argumento
        if ($arguments) {
            $role = $session->get('role');
            if (!in_array($role, $arguments)) {
                return redirect()->to('/')->with('error', 'No tienes permisos suficientes.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
