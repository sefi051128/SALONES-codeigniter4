<?php namespace App\Controllers;

use App\Models\ChatModel;
use App\Models\UserModel;

class Chat extends BaseController
{
    protected $chatModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->chatModel = new ChatModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $usuario = [
            'id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'role' => $this->session->get('role'),
        ];

        $data = [
            'usuarioActual' => $usuario,
            'conversaciones' => $this->chatModel->obtenerConversaciones($usuario['id']),
            'usuarios' => $this->userModel->listarUsuariosParaChat(),
        ];

        echo view('chat/inicioChat', $data);
    }

    public function conversacion($id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $usuario = [
            'id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'role' => $this->session->get('role'),
        ];

        $conv = $this->chatModel->obtenerConversacionPorId($id, $usuario['id']);
        if (!$conv) {
            return redirect()->to(base_url('chat'));
        }

        $data = [
            'usuarioActual' => $usuario,
            'conversaciones' => $this->chatModel->obtenerConversaciones($usuario['id']),
            'conversacionActual' => $conv,
            'usuarios' => $this->userModel->listarUsuariosParaChat(),
        ];

        echo view('chat/inicioChat', $data);
    }

    public function enviar()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $usuario = [
            'id' => $this->session->get('user_id'),
            'username' => $this->session->get('username'),
            'role' => $this->session->get('role'),
        ];

        $post = $this->request->getPost();

        if (isset($post['nueva'])) {
            $convId = $this->chatModel->crearConversacion(
                $usuario['id'],
                (int)$post['destinatario_id'],
                $post['titulo'],
                $post['mensaje']
            );
            if ($convId > 0) {
                return redirect()->to(base_url("chat/conversacion/$convId"));
            } else {
                return redirect()->to(base_url('chat'))->with('error', 'No se pudo crear la conversación.');
            }
        }

        if (isset($post['conversacion_id'])) {
            $this->chatModel->enviarMensaje(
                (int)$post['conversacion_id'],
                $usuario['id'],
                $post['mensaje']
            );
            return redirect()->back();
        }

        return redirect()->to(base_url('chat'));
    }
}
