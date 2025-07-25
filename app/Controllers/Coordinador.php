<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class Coordinador extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Panel de Coordinación',
            'user' => session()->get()
        ];
        return view('coordinador/inicioCoordinador', $data);
    }
}