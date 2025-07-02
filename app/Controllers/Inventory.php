<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

require_once ROOTPATH . 'vendor/autoload.php';


class Inventory extends BaseController
{
    public function index()
    {
        $model = new InventoryModel();

        // Filtros de búsqueda
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $responsible = $this->request->getGet('responsible');

        // Construir consulta
        $builder = $model->builder();

        if ($search) {
            $builder->groupStart()
                   ->like('code', $search)
                   ->orLike('name', $search)
                   ->orLike('location', $search)
                   ->groupEnd();
        }

        if ($status) {
            $builder->where('status', $status);
        }

        if ($responsible) {
            $builder->like('current_responsible', $responsible);
        }

        $items = $builder->get()->getResultArray();

        return view('inventory/index', [
            'title' => 'Inventario de Artículos',
            'items' => $items,
            'search' => $search,
            'status' => $status,
            'responsible' => $responsible,
        ]);
    }

    //Generar código QR para un artículo
    public function qr($id)
{
    $model = new \App\Models\InventoryModel();
    $item = $model->find($id);

    if (!$item) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Artículo no encontrado");
    }

    return view('inventory/qr', ['item' => $item]);
}

//Crear un nuevo artículo
public function create()
{
    return view('inventory/create', [
        'title' => 'Agregar nuevo artículo',
        'validation' => \Config\Services::validation()
    ]);
}

//Contar cantidad de artículos
public function store()
{
    $this->inventoryModel = new \App\Models\InventoryModel();

    $rules = $this->inventoryModel->getValidationRules();
    unset($rules['code']); // 💥 Omitimos la validación de code porque será generado
    $rules['quantity'] = 'required|is_natural_no_zero';

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $data = $this->request->getPost();
    $cantidad = (int) $data['quantity'];
    unset($data['quantity']);

    $insertados = 0;
    for ($i = 0; $i < $cantidad; $i++) {
        $data['code'] = strtoupper(uniqid('INV-'));
        if ($this->inventoryModel->insert($data)) {
            $insertados++;
        }
    }

    return redirect()->to('/inventory')->with('success', "$insertados artículo(s) agregado(s).");
}

//Inserción de prueba
public function testInsert()
{
    $model = new \App\Models\InventoryModel();

    $data = [
        'code' => 'TEST-' . strtoupper(bin2hex(random_bytes(4))),
        'name' => 'Artículo prueba',
        'location' => 'Bodega',
        'status' => 'Disponible',
        'current_responsible' => 'Admin',
    ];

    if ($model->insert($data)) {
        echo "Insert OK";
    } else {
        echo "Error: " . json_encode($model->errors());
    }
    exit; // Finaliza para evitar que CodeIgniter intente renderizar otra cosa
}

protected $inventoryModel;

public function __construct()
{
    $this->inventoryModel = new \App\Models\InventoryModel();
    helper(['form']); // Carga el helper de formularios
}

//Generar reporte de inventario
public function report()
{
    $inventoryModel = new \App\Models\InventoryModel();

    $grouped = $inventoryModel->select('name, COUNT(*) as cantidad')
                              ->groupBy('name')
                              ->orderBy('cantidad', 'DESC')
                              ->findAll();

    $html = view('inventory/report', [
        'title' => 'Reporte de Inventario',
        'items' => $grouped,
        'generatedAt' => date('d/m/Y H:i:s')
    ]);

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('reporte_inventario.pdf', ['Attachment' => false]);
}

// Editar un artículo
public function edit($id)
{
    $model = new \App\Models\InventoryModel();
    $item = $model->find($id);

    if (!$item) {
        return redirect()->to('/inventory')->with('error', 'Artículo no encontrado.');
    }

    return view('inventory/edit', [
        'title' => 'Editar artículo',
        'item' => $item,
        'validation' => \Config\Services::validation()
    ]);
}

// Actualizar un artículo
public function update($id)
{
    $model = new \App\Models\InventoryModel();
    $item = $model->find($id);

    if (!$item) {
        return redirect()->to('/inventory')->with('error', 'Artículo no encontrado.');
    }

    // Obtener reglas de validación incluyendo el ID para is_unique
    $rules = $model->getValidationRules(['id' => $id]);

    // Validar los datos
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    // Resto del método permanece igual...
    $data = [
        'name' => $this->request->getPost('name'),
        'location' => $this->request->getPost('location'),
        'status' => $this->request->getPost('status'),
        'current_responsible' => $this->request->getPost('current_responsible')
    ];

    if ($model->update($id, $data)) {
        return redirect()->to('/inventory')->with('success', 'Artículo actualizado correctamente.');
    } else {
        return redirect()->back()->withInput()->with('error', 'No se pudo actualizar el artículo.');
    }
}

//Eliminar un artículo
public function delete($id)
{
    $model = new \App\Models\InventoryModel();
    
    if (!$model->find($id)) {
        return redirect()->to('/inventory')->with('error', 'Artículo no encontrado.');
    }

    $model->delete($id);

    return redirect()->to('/inventory')->with('success', 'Artículo eliminado exitosamente.');
}

}
