<?php
namespace App\Controllers;

use App\Models\ServiceModel;

class Servicios extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Servicios',
            'services' => $this->serviceModel->findAll()
        ];
        return view('servicios/inicioServicios', $data);
    }
}