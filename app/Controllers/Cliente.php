<?php
namespace App\Controllers;

use App\Models\UserModel;

class Cliente extends BaseController
{
    public function dashboard()
    {
        // Verificar si el usuario está logueado y es cliente
        if (!session('isLoggedIn') || session('role') !== 'cliente') {
            return redirect()->to('/')->with('error', 'Acceso no autorizado');
        }

        $data = [
            'title' => 'Dashboard del Cliente',
            'user_info' => [
                'username' => session('username'),
                'user_id' => session('user_id')
            ]
        ];

        return view('cliente/dashboard', $data);
    }
}