<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class Seguridad extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Panel de Seguridad',
            'user' => session()->get()
        ];
        return view('seguridad/inicioSeguridad', $data);
    }

    
}