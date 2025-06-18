<?php

namespace App\Controllers;

use App\Models\SalonModel;

class PruebaDB extends BaseController
{
    public function index()
    {
        $salonModel = new SalonModel();
        $data['salones'] = $salonModel->findAll(); // Obtener todos los registros

        return view('salones', $data); // Enviar datos a la vista
    }
}
