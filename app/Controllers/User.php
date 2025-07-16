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

        return view('user/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            if (!$this->userModel->validate($data)) {
                return view('user/create', [
                    'validation' => $this->userModel->errors(),
                    'input' => $data,
                    'title' => 'Agregar Usuario',
                ]);
            }

            $this->userModel->save($data);
            session()->setFlashdata('success', 'Usuario creado correctamente.');
            return redirect()->to('/users');
        }

        return view('user/create', ['title' => 'Agregar Usuario']);
    }

    public function edit($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Usuario no encontrado: $id");
        }

        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            $this->userModel->setValidationRule('username', "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{$id}]");

            if (empty($data['password'])) {
                unset($data['password']);
            }

            if (!$this->userModel->validate($data)) {
                return view('user/edit', [
                    'validation' => $this->userModel->errors(),
                    'user' => array_merge($user, $data),
                    'title' => 'Editar Usuario',
                ]);
            }

            $this->userModel->update($id, $data);
            session()->setFlashdata('success', 'Usuario actualizado correctamente.');
            return redirect()->to('/users');
        }

        return view('user/edit', ['user' => $user, 'title' => 'Editar Usuario']);
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

        return redirect()->to('/users');
    }

public function createUser()
{
    if ($this->request->getMethod() === 'post') {
        // Obtener datos del formulario
        $data = $this->request->getPost();
        
        // Definir reglas de validación
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'permit_empty|regex_match[/^[0-9\-\+\s\(\)]{7,20}$/]',
            'role' => 'permit_empty'
        ];

        // Validar los datos
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Preparar datos para guardar
        $userData = [
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => 'cliente' // Valor fijo para registros públicos
        ];

        // Insertar usando el modelo
        if ($this->userModel->insert($userData)) {
            session()->setFlashdata('success', 'Registro exitoso. Por favor inicia sesión.');
            return redirect()->to('/login');
        } else {
            session()->setFlashdata('error', 'Error al registrar el usuario.');
            return redirect()->back()->withInput();
        }
    }

    return redirect()->to('/register');
}
}
