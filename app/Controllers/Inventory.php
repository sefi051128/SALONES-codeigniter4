<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

require_once ROOTPATH . 'vendor/autoload.php';

class Inventory extends BaseController
{
    protected $inventoryModel;

    public function __construct()
    {
        $this->inventoryModel = new \App\Models\InventoryModel();
        helper(['form', 'almacen_access']); // cargar helper de formularios y logging
    }

    public function index()
    {
        $model = $this->inventoryModel;

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

        // Logear que se vio listado (opcional, comentar si genera mucho ruido)
        if (session('user_id')) {
            log_almacen_access(session('user_id'), 'ver listado de inventario');
        }

        return view('inventory/index', [
            'title' => 'Inventario de Artículos',
            'items' => $items,
            'search' => $search,
            'status' => $status,
            'responsible' => $responsible,
        ]);
    }

    // Generar código QR para un artículo
    public function qr($id)
    {
        $model = $this->inventoryModel;
        $item = $model->find($id);

        if (!$item) {
            throw PageNotFoundException::forPageNotFound("Artículo no encontrado");
        }

        if (session('user_id')) {
            log_almacen_access(session('user_id'), "ver QR artículo id={$id}");
        }

        return view('inventory/qr', ['item' => $item]);
    }

    // Crear un nuevo artículo (vista)
    public function create()
    {
        return view('inventory/create', [
            'title' => 'Agregar nuevo artículo',
            'validation' => \Config\Services::validation()
        ]);
    }

    // Contar y guardar artículos
    public function store()
    {
        $model = $this->inventoryModel;

        $rules = $model->getValidationRules();
        unset($rules['code']); // omitimos code porque será generado
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
            if ($model->insert($data)) {
                $newId = $model->getInsertID();
                $insertados++;
                if (session('user_id')) {
                    log_almacen_access(session('user_id'), "crear item id={$newId}");
                }
            }
        }

        return redirect()->to('/inventory')->with('success', "$insertados artículo(s) agregado(s).");
    }

    // Inserción de prueba
    public function testInsert()
    {
        $model = $this->inventoryModel;

        $data = [
            'code' => 'TEST-' . strtoupper(bin2hex(random_bytes(4))),
            'name' => 'Artículo prueba',
            'location' => 'Bodega',
            'status' => 'Disponible',
            'current_responsible' => 'Admin',
        ];

        if ($model->insert($data)) {
            if (session('user_id')) {
                log_almacen_access(session('user_id'), "crear item prueba id={$model->getInsertID()}");
            }
            echo "Insert OK";
        } else {
            echo "Error: " . json_encode($model->errors());
        }
        exit;
    }

    // Generar reporte de inventario
    public function report()
    {
        $inventoryModel = $this->inventoryModel;

        $grouped = $inventoryModel->select('name, COUNT(*) as cantidad')
                                  ->groupBy('name')
                                  ->orderBy('cantidad', 'DESC')
                                  ->findAll();

        $html = view('inventory/report', [
            'title' => 'Reporte de Inventario',
            'items' => $grouped,
            'generatedAt' => date('d/m/Y H:i:s')
        ]);

        if (session('user_id')) {
            log_almacen_access(session('user_id'), 'generar reporte de inventario');
        }

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('reporte_inventario.pdf', ['Attachment' => false]);
    }

    // Editar un artículo (vista)
    public function edit($id)
    {
        $model = $this->inventoryModel;
        $item = $model->find($id);

        if (!$item) {
            return redirect()->to('/inventory')->with('error', 'Artículo no encontrado.');
        }

        if (session('user_id')) {
            log_almacen_access(session('user_id'), "ver edición artículo id={$id}");
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
        $model = $this->inventoryModel;
        $item = $model->find($id);

        if (!$item) {
            return redirect()->to('/inventory')->with('error', 'Artículo no encontrado.');
        }

        // Obtener reglas de validación incluyendo el ID para is_unique
        $rules = $model->getValidationRules(['id' => $id]);

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'location' => $this->request->getPost('location'),
            'status' => $this->request->getPost('status'),
            'current_responsible' => $this->request->getPost('current_responsible')
        ];

        if ($model->update($id, $data)) {
            if (session('user_id')) {
                log_almacen_access(session('user_id'), "editar item id={$id}");
            }
            return redirect()->to('/inventory')->with('success', 'Artículo actualizado correctamente.');
        } else {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar el artículo.');
        }
    }

    // Eliminar un artículo
    public function delete($id)
    {
        $model = $this->inventoryModel;

        if (!$model->find($id)) {
            return redirect()->to('/inventory')->with('error', 'Artículo no encontrado.');
        }

        if ($model->delete($id)) {
            if (session('user_id')) {
                log_almacen_access(session('user_id'), "eliminar item id={$id}");
            }
            return redirect()->to('/inventory')->with('success', 'Artículo eliminado exitosamente.');
        }

        return redirect()->to('/inventory')->with('error', 'No se pudo eliminar el artículo.');
    }
}
