<?php

namespace App\Controllers;

use App\Models\AlertasModel;
use App\Models\InventoryModel; // Cambiado de ItemsModel a InventoryModel
use Dompdf\Dompdf;
use Dompdf\Options;

class Alertas extends BaseController
{
    // Lista todas las alertas (ahora se llama inicio en lugar de index)
    public function inicio()
    {
        $model = new AlertasModel();
        $data['alertas'] = $model->findAll();

        return view('alertas/inicioAlertas', $data);
    }

    // Muestra el formulario para crear una alerta
        public function crear()
    {
        helper('form');

        $itemsModel = new InventoryModel(); // Cambiado aquí
        $data['items'] = $itemsModel->findAll();

        return view('alertas/crear', $data);
    }

    // Guarda la nueva alerta en la base de datos
    public function guardar()
    {
        helper('form');

        $validationRules = [
            'item_id'    => 'required|integer|is_not_unique[inventory_items.id]',
            'alert_type' => 'required|string|max_length[255]',
            'alert_date' => 'required|valid_date',
        ];

        if (! $this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AlertasModel();

        $model->save([
            'item_id'    => $this->request->getPost('item_id'),
            'alert_type' => $this->request->getPost('alert_type'),
            'alert_date' => $this->request->getPost('alert_date'),
            'resolved'   => 0,
        ]);

        return redirect()->to(site_url('alertas'))->with('success', 'Alerta creada correctamente.');
    }

    // Muestra el formulario para editar una alerta
    public function editar($id)
    {
        helper('form');

        $model = new AlertasModel();
        $alerta = $model->find($id);

        if (!$alerta) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("No se encontró la alerta con ID: $id");
        }

        $itemsModel = new InventoryModel(); // Cambiado aquí
        $data['items'] = $itemsModel->findAll();
        $data['alerta'] = $alerta;

        return view('alertas/editar', $data);
    }


    // Actualiza la alerta en la base de datos
    public function actualizar($id)
    {
        helper('form');

        $validationRules = [
            'item_id'    => 'required|integer|is_not_unique[inventory_items.id]',
            'alert_type' => 'required|string|max_length[255]',
            'alert_date' => 'required|valid_date',
            'resolved'   => 'permit_empty|in_list[0,1]'
        ];

        if (! $this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AlertasModel();

        $model->update($id, [
            'item_id'    => $this->request->getPost('item_id'),
            'alert_type' => $this->request->getPost('alert_type'),
            'alert_date' => $this->request->getPost('alert_date'),
            'resolved'   => $this->request->getPost('resolved') ?? 0
        ]);

        return redirect()->to(site_url('alertas'))->with('success', 'Alerta actualizada correctamente.');
    }

    // Marca la alerta como resuelta
    public function resolver($id)
    {
        $model = new AlertasModel();
        $model->update($id, ['resolved' => 1]);

        return redirect()->to(site_url('alertas'))->with('success', 'Alerta marcada como resuelta.');
    }

    // Genera un reporte PDF de las alertas
      public function reporte()
    {
        // Obtener todas las alertas con información del ítem
        $model = new AlertasModel();
        $itemsModel = new InventoryModel(); // Cambiado aquí
        
        $alertas = $model->select('inventory_alerts.*, inventory_items.name as item_name')
                       ->join('inventory_items', 'inventory_items.id = inventory_alerts.item_id')
                       ->orderBy('alert_date', 'DESC')
                       ->findAll();

        $data = [
            'title' => 'Reporte de Alertas de Inventario',
            'alertas' => $alertas,
            'generatedAt' => date('d/m/Y H:i:s')
        ];
        
        // Cargar la vista del reporte
        $html = view('alertas/reporteAlertas', $data);
        
        // Configurar Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Descargar el PDF
        $dompdf->stream("reporte_alertas_".date('Y-m-d').".pdf", [
            "Attachment" => true
        ]);
        
        exit;
    }
}