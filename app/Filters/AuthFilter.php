<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está logueado
        if (!session('isLoggedIn')) {
            return redirect()->to('/')->with('error', 'Por favor inicia sesión');
        }

        // Verificar roles si se especifican
        if (!empty($arguments)) {
            $userRole = session('role');
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/inicio')->with('error', 'No tienes permisos para acceder a esta sección');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después de la respuesta
    }
}