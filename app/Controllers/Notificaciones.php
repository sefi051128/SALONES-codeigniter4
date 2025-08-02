<?php
namespace App\Controllers;

use App\Models\NotificacionesModel;
use App\Models\UserModel;

class Notificaciones extends BaseController
{
    protected $notificacionesModel;
    protected $userModel;

    public function __construct()
    {
        $this->notificacionesModel = new NotificacionesModel();
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'notificaciones' => $this->notificacionesModel->select('notifications.*, users.username')
                                                        ->join('users', 'users.id = notifications.user_id')
                                                        ->orderBy('sent_date', 'DESC')
                                                        ->findAll(),
            'success' => session()->getFlashdata('success'),
            'error' => session()->getFlashdata('error')
        ];
        
        return view('notificaciones/inicioNotificaciones', $data);
    }

    public function crear()
    {
        $data = [
            'users' => $this->userModel->findAll(),
            'validation' => service('validation')
        ];
        
        return view('notificaciones/crearNotificacion', $data);
    }

    public function guardar()
    {
        $rules = [
            'user_id' => 'required|numeric',
            'notification_type' => 'required|in_list[Urgente,Importante,Informativa,Recordatorio]',
            'message' => 'required|min_length[10]|max_length[500]',
            'sent_date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', $this->validator->listErrors());
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'notification_type' => $this->request->getPost('notification_type'),
            'message' => $this->request->getPost('message'),
            'sent_date' => $this->request->getPost('sent_date')
        ];

        if ($this->notificacionesModel->insert($data)) {
            return redirect()->to(site_url('notificaciones'))
                           ->with('success', 'Notificación creada correctamente');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Error al guardar la notificación');
    }

    public function ver($id)
    {
        $notificacion = $this->notificacionesModel->select('notifications.*, users.username')
                                                ->join('users', 'users.id = notifications.user_id')
                                                ->where('notifications.id', $id)
                                                ->first();

        if (!$notificacion) {
            return redirect()->to(site_url('notificaciones'))
                           ->with('error', 'Notificación no encontrada');
        }

        return view('notificaciones/verNotificacion', ['notificacion' => $notificacion]);
    }

    public function eliminar($id)
    {
        if ($this->notificacionesModel->delete($id)) {
            return redirect()->to(site_url('notificaciones'))
                           ->with('success', 'Notificación eliminada correctamente');
        }

        return redirect()->to(site_url('notificaciones'))
                       ->with('error', 'Error al eliminar la notificación');
    }
}