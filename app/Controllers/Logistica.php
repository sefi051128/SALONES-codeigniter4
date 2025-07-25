<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class Logistica extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Panel de Logística',
            'user' => session()->get()
        ];
        return view('logistica/inicioLogistica', $data);
    }
}