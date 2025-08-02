<?php

namespace App\Controllers;

use App\Models\AlmacenModel;

use Dompdf\Dompdf;
use Dompdf\Options;

class Almacen extends BaseController
{
    protected $model;

    public function __construct()
    {
        helper(['url', 'session']);
        $this->model = new AlmacenModel();
    }

    public function index()
{
    if (!session()->getFlashdata('isLoggedIn') && !session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $userFilter = $this->request->getGet('user_id');
    $typeFilter = $this->request->getGet('access_type');
    $from = $this->request->getGet('from');
    $to = $this->request->getGet('to');

    $builder = $this->model
        ->select('warehouse_access.*, users.username')
        ->join('users', 'users.id = warehouse_access.user_id', 'left')
        ->orderBy('access_time', 'DESC');

    // Filtrar por usuario: puede ser ID exacto o parte del username
    if ($userFilter) {
        if (is_numeric($userFilter)) {
            $builder->groupStart()
                    ->where('warehouse_access.user_id', $userFilter)
                    ->orWhere('users.username LIKE', "%{$userFilter}%")
                    ->groupEnd();
        } else {
            $builder->like('users.username', $userFilter);
        }
    }

    // Tipo de acceso
    if ($typeFilter) {
        $builder->where('warehouse_access.access_type', $typeFilter);
    }

    // Rango de fechas (asumiendo formato ISO-local: "YYYY-MM-DDTHH:MM")
    if ($from) {
        // convertir a formato compatible con DB: reemplazar T por espacio si viene así
        $fromNormalized = str_replace('T', ' ', $from);
        $builder->where('access_time >=', $fromNormalized);
    }
    if ($to) {
        $toNormalized = str_replace('T', ' ', $to);
        $builder->where('access_time <=', $toNormalized);
    }

    $accesos = $builder->paginate(25);
    $pager = $this->model->pager;

    return view('Almacen/InicioAlmacen', [
        'accesos' => $accesos,
        'pager' => $pager,
        'filter_user_id' => $userFilter,
        'filter_access_type' => $typeFilter,
        'filter_from' => $from,
        'filter_to' => $to,
        'success' => session()->getFlashdata('success'),
        'error' => session()->getFlashdata('error')
    ]);
}


    public function nuevo()
    {
        $data = [
            'error' => session()->getFlashdata('error'),
            'validation' => service('validation')
        ];

        return view('Almacen/NuevoAccesoAlmacen', $data);
    }

    public function guardar()
    {
        $rules = [
            'user_id' => 'required|numeric',
            'access_type' => 'required|in_list[Entrada,Salida]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', $this->validator->getErrors());
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'access_type' => $this->request->getPost('access_type')
        ];

        try {
            if ($this->model->insert($data)) {
                return redirect()->to(site_url('almacen'))
                               ->with('success', 'Acceso registrado correctamente.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al guardar: ' . $e->getMessage());
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Ocurrió un error al guardar el acceso');
    }

    public function reporte()
{
    // Solo verifica autenticación (ya está cubierto por el filtro 'auth' en las rutas)
    if (!session()->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    // Resto del código para generar el PDF...
    $builder = $this->model
        ->select('warehouse_access.*, users.username')
        ->join('users', 'users.id = warehouse_access.user_id', 'left')
        ->orderBy('access_time', 'DESC');
    
    // ... (código existente para aplicar filtros)
    
    $accesos = $builder->findAll();

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    
    $dompdf = new Dompdf($options);
    $html = view('Almacen/ReportePDF', ['accesos' => $accesos]);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    
    $dompdf->stream("reporte_accesos_almacen.pdf", ["Attachment" => true]);
}
}
