<?php
namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url', 'session']);
    }

    public function index()
    {
        $data = [
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll(),
            'title' => 'Gestión de Usuarios',
        ];

        return view('layouts/main', [
            'content' => view('user/index', $data)
        ]);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            if (!$this->userModel->validate($data)) {
                return view('layouts/main', [
                    'content' => view('user/create', [
                        'validation' => $this->userModel->errors(),
                        'input' => $data,
                        'title' => 'Agregar Usuario',
                    ])
                ]);
            }

            $this->userModel->save($data);
            session()->setFlashdata('success', 'Usuario creado correctamente.');
            return redirect()->to('/user');
        }

        return view('layouts/main', [
            'content' => view('user/create', ['title' => 'Agregar Usuario'])
        ]);
    }

    public function edit($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Usuario no encontrado: $id");
        }

        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            // Validar username ignorando el usuario actual
            $this->userModel->setValidationRule('username', "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{$id}]");

            // Permitir que password sea opcional en edición
            if (empty($data['password'])) {
                unset($data['password']);
            }

            if (!$this->userModel->validate($data)) {
                return view('layouts/main', [
                    'content' => view('user/edit', [
                        'validation' => $this->userModel->errors(),
                        'user' => array_merge($user, $data),
                        'title' => 'Editar Usuario',
                    ])
                ]);
            }

            $this->userModel->update($id, $data);
            session()->setFlashdata('success', 'Usuario actualizado correctamente.');
            return redirect()->to('/user');
        }

        return view('layouts/main', [
            'content' => view('user/edit', ['user' => $user, 'title' => 'Editar Usuario'])
        ]);
    }

    public function delete($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Usuario no encontrado.');
        } else {
            $this->userModel->delete($id);
            session()->setFlashdata('success', 'Usuario eliminado correctamente.');
        }

        return redirect()->to('/user');
    }
}
