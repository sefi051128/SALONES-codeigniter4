<?php
namespace App\Controllers;

use App\Models\SedeModel;

class Sedes extends BaseController
{
    protected $sedeModel;

    public function __construct()
    {
        $this->sedeModel = new SedeModel();
        helper(['form', 'url', 'session']);
    }

    public function index()
    {
        $data = [
            'title' => 'Nuestras Sedes',
            'sedes' => $this->sedeModel->findAll()
        ];

        return view('sedes/inicioSedes', $data);
    }

    public function crear()
    {
        // Solo muestra el formulario de creación
        return view('sedes/crearSede');
    }

    public function guardar()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'location' => 'required',
            'capacity' => 'required|numeric',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'place_id' => 'permit_empty|string'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        $data = $this->request->getPost();
        
        // Asegurar que el rol sea cliente si no se especifica
        $data['role'] = $data['role'] ?? 'cliente';
        
        if ($this->sedeModel->save($data)) {
            return redirect()->to('/sedes')
                           ->with('success', 'Sede creada exitosamente');
        }
        
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error al guardar la sede');
    }

    public function editar($id)
    {
        $sede = $this->sedeModel->find($id);
        
        if (!$sede) {
            return redirect()->to('/sedes')
                           ->with('error', 'Sede no encontrada');
        }
        
        return view('sedes/editarSede', ['sede' => $sede]);
    }

    public function actualizar($id)
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'location' => 'required',
            'capacity' => 'required|numeric',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'place_id' => 'permit_empty|string'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }
        
        $data = $this->request->getPost();
        
        if ($this->sedeModel->update($id, $data)) {
            return redirect()->to('/sedes')
                           ->with('success', 'Sede actualizada exitosamente');
        }
        
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error al actualizar la sede');
    }

    public function eliminar($id)
    {
        $sede = $this->sedeModel->find($id);
        
        if (!$sede) {
            return redirect()->to('/sedes')
                           ->with('error', 'Sede no encontrada');
        }
        
        if ($this->sedeModel->delete($id)) {
            return redirect()->to('/sedes')
                           ->with('success', 'Sede eliminada exitosamente');
        }
        
        return redirect()->to('/sedes')
                       ->with('error', 'Error al eliminar la sede');
    }
}