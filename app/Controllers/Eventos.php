<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventoModel;
use App\Models\SedeModel;

class Eventos extends BaseController
{
    protected $eventoModel;
    protected $sedeModel;

    public function __construct()
    {
        $this->eventoModel = new EventoModel();
        $this->sedeModel = new SedeModel();
        helper(['form', 'url', 'session']);
    }

    public function index()
    {
        $data = [
            'title' => 'Todos los Eventos',
            'events' => $this->eventoModel->getEventosConSede(),
            'venue' => null
        ];
        
        return view('eventos/inicioEventos', $data);
    }

    public function eventosPorSede($sedeId)
    {
        $sede = $this->sedeModel->find($sedeId);
        
        if (!$sede) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Sede no encontrada");
        }

        $data = [
            'title' => 'Eventos en '.$sede['name'],
            'events' => $this->eventoModel->where('venue_id', $sedeId)->findAll(),
            'venue' => [
                'name' => $sede['name'],
                'location' => $sede['location'],
                'capacity' => $sede['capacity']
            ]
        ];
        
        return view('eventos/inicioEventos', $data);
    }

    public function crear()
    {
        $data = [
            'title' => 'Crear Nuevo Evento',
            'sedes' => $this->sedeModel->findAll()
        ];
        return view('eventos/crearEvento', $data);
    }

    public function guardar()
    {
        $rules = [
    'venue_id' => 'required|numeric',
    'name' => 'required|min_length[3]|max_length[255]',
    'date' => 'required|valid_date',
    'description' => 'permit_empty|string',
    'status' => 'required|in_list[activo,cancelado,completado]'
];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
    'venue_id' => $this->request->getPost('venue_id'),
    'name' => $this->request->getPost('name'),
    'date' => $this->request->getPost('date'),
    'description' => $this->request->getPost('description'),
    'status' => $this->request->getPost('status')
];

        if ($this->eventoModel->save($data)) {
            session()->setFlashdata('success', 'Evento creado exitosamente');
            return redirect()->to('/eventos');
        }

        session()->setFlashdata('error', 'Error al crear el evento');
        return redirect()->back()->withInput();
    }

    public function editar($id)
    {
        $evento = $this->eventoModel->find($id);
        
        if (!$evento) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Evento no encontrado");
        }

        $data = [
            'title' => 'Editar Evento',
            'evento' => $evento,
            'sedes' => $this->sedeModel->findAll()
        ];
        
        return view('eventos/editarEvento', $data);
    }

    public function actualizar($id)
    {
        $rules = [
    'venue_id' => 'required|numeric',
    'name' => 'required|min_length[3]|max_length[255]',
    'date' => 'required|valid_date',
    'description' => 'permit_empty|string',
    'status' => 'required|in_list[activo,cancelado,completado]'
];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
    'id' => $id,
    'venue_id' => $this->request->getPost('venue_id'),
    'name' => $this->request->getPost('name'),
    'date' => $this->request->getPost('date'),
    'description' => $this->request->getPost('description'),
    'status' => $this->request->getPost('status')
];

        if ($this->eventoModel->save($data)) {
            session()->setFlashdata('success', 'Evento actualizado exitosamente');
            return redirect()->to('/eventos');
        }

        session()->setFlashdata('error', 'Error al actualizar el evento');
        return redirect()->back()->withInput();
    }

    public function eliminar($id)
    {
        if ($this->eventoModel->delete($id)) {
            session()->setFlashdata('success', 'Evento eliminado correctamente');
        } else {
            session()->setFlashdata('error', 'Error al eliminar el evento');
        }
        
        return redirect()->to('/eventos');
    }

    public function ver($id)
    {
        $evento = $this->eventoModel->getEventoCompleto($id);
        
        if (!$evento) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Evento no encontrado");
        }

        $data = [
            'title' => 'Detalles del Evento',
            'event' => $evento
        ];
        
        return view('eventos/verEvento', $data);
    }
}