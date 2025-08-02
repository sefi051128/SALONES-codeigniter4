<?php
namespace App\Controllers;

use App\Models\ReportesModel;
use App\Models\EventoModel;
use App\Models\InventoryModel;  // Cambiado de ItemsModel a InventoryModel

use Dompdf\Dompdf;
use Dompdf\Options;

class Reportes extends BaseController
{
    protected $model;
    protected $eventoModel;
    protected $inventoryModel;  // Cambiado de itemsModel a inventoryModel

    public function __construct()
    {
        $this->model = new ReportesModel();
        $this->eventoModel = new EventoModel();
        $this->inventoryModel = new InventoryModel();  // Cambiado a InventoryModel
    }

    public function index()
    {
        $data = [
            'reportes' => $this->model->select('incident_reports.*, events.name as event_name, inventory_items.name as item_name')
                                    ->join('events', 'events.id = incident_reports.event_id')
                                    ->join('inventory_items', 'inventory_items.id = incident_reports.item_id')
                                    ->orderBy('report_date', 'DESC')
                                    ->findAll(),
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ];
        
        return view('reportes/inicioReportes', $data);
    }

    public function nuevo()
    {
        $data = [
            'eventos' => $this->eventoModel->getEventosConSede(),
            'articulos' => $this->inventoryModel->findAll(),  // Usando InventoryModel
            'error' => session()->getFlashdata('error')
        ];
        
        return view('reportes/nuevoReporte', $data);
    }

    public function guardar()
{
    $rules = [
        'event_id' => 'required|numeric',
        'item_id' => 'required|numeric',
        'incident_type' => 'required|string|max_length[255]',
        'description' => 'required|string',
        'photo' => 'uploaded[photo]|max_size[photo,2048]|is_image[photo]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
    }

    $photo = $this->request->getFile('photo');
    $photoName = $photo->getRandomName();
    $photo->move(WRITEPATH . 'uploads/reportes', $photoName);

    $data = [
        'event_id' => $this->request->getPost('event_id'),
        'item_id' => $this->request->getPost('item_id'),
        'incident_type' => $this->request->getPost('incident_type'),
        'description' => $this->request->getPost('description'),
        'photo_url' => $photoName  // Aquí corregido
    ];

    if ($this->model->insert($data)) {
        return redirect()->to(site_url('reportes'))->with('success', 'Reporte creado correctamente');
    }

    return redirect()->back()->withInput()->with('error', 'Error al crear el reporte');
}


    public function editar($id)
    {
        $reporte = $this->model->find($id);
        if (!$reporte) {
            return redirect()->to(site_url('reportes'))->with('error', 'Reporte no encontrado');
        }

        $data = [
            'reporte' => $reporte,
            'eventos' => $this->eventoModel->getEventosConSede(),
            'articulos' => $this->inventoryModel->findAll(),  // Usando InventoryModel
            'error' => session()->getFlashdata('error')
        ];
        
        return view('reportes/editarReporte', $data);
    }

    public function actualizar($id)
    {
        $rules = [
            'event_id' => 'required|numeric',
            'item_id' => 'required|numeric',
            'incident_type' => 'required|string|max_length[255]',
            'description' => 'required|string',
            'photo' => 'max_size[photo,2048]|is_image[photo]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $data = [
            'event_id' => $this->request->getPost('event_id'),
            'item_id' => $this->request->getPost('item_id'),
            'incident_type' => $this->request->getPost('incident_type'),
            'description' => $this->request->getPost('description')
        ];

        $photo = $this->request->getFile('photo');
if ($photo && $photo->isValid()) {
    $photoName = $photo->getRandomName();
    $photo->move(WRITEPATH . 'uploads/reportes', $photoName);
    $data['photo_url'] = $photoName;  // Corregido aquí también
}


        if ($this->model->update($id, $data)) {
            return redirect()->to(site_url('reportes'))->with('success', 'Reporte actualizado correctamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al actualizar el reporte');
    }

    public function eliminar($id)
    {
        $reporte = $this->model->find($id);
        if (!$reporte) {
            return redirect()->to(site_url('reportes'))->with('error', 'Reporte no encontrado');
        }

        if ($this->model->delete($id)) {
            return redirect()->to(site_url('reportes'))->with('success', 'Reporte eliminado correctamente');
        }

        return redirect()->to(site_url('reportes'))->with('error', 'Error al eliminar el reporte');
    }

    public function generarPdf()
{
    // Obtener parámetros de filtro
    $from = $this->request->getGet('from');
    $to = $this->request->getGet('to');

    // Construir consulta sin fotos para mayor eficiencia
    $builder = $this->model->select('incident_reports.id, incident_reports.incident_type, incident_reports.description, incident_reports.report_date, events.name as event_name, inventory_items.name as item_name')
                          ->join('events', 'events.id = incident_reports.event_id')
                          ->join('inventory_items', 'inventory_items.id = incident_reports.item_id')
                          ->orderBy('report_date', 'DESC');

    if ($from) $builder->where('report_date >=', $from);
    if ($to) $builder->where('report_date <=', $to);

    $reportes = $builder->findAll();

    $data = [
        'title' => 'Reporte de Incidentes',
        'reportes' => $reportes,
        'filtros' => ['from' => $from, 'to' => $to],
        'generatedAt' => date('d/m/Y H:i:s')
    ];

    // Cargar vista simplificada
    $html = view('reportes/reportePdf', $data);

    // Configuración optimizada de Dompdf
    $options = new Options();
    $options->set('isRemoteEnabled', false); // Deshabilitar carga remota
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'Arial');
    $options->set('isPhpEnabled', false); // Mejor seguridad

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    
    try {
        $dompdf->render();
        $dompdf->stream("reporte_incidentes_".date('Y-m-d').".pdf", [
            "Attachment" => true,
            "compress" => true
        ]);
        exit;
    } catch (\Exception $e) {
        log_message('error', 'Error al generar PDF: '.$e->getMessage());
        return redirect()->back()->with('error', 'Error al generar el reporte');
    }
}
}