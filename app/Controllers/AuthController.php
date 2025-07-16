<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $helpers = ['form', 'url', 'session'];

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getUser($username);

        if ($user && password_verify($password, $user['password'])) {
            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);
            session()->regenerate();

            return $this->redirectByRole($user['role']);
        }

        return redirect()->back()->withInput()->with('error', 'Credenciales inválidas');
    }

    public function storeUser()
    {
        // Validación
        $rules = [
            'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'contact_info' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                            ->withInput()
                            ->with('errors', $this->validator->getErrors());
        }

        // Preparar datos
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'), // El modelo se encargará del hash
            'role' => $this->request->getPost('role') ?? 'cliente',
            'contact_info' => $this->request->getPost('contact_info')
        ];

        // Usar el método createUser del modelo
        if ($this->userModel->createUser($data)) {
            session()->setFlashdata('success', 'Registro exitoso. Por favor inicia sesión.');
            return redirect()->to('/login');
        }

        session()->setFlashdata('error', 'Error al registrar el usuario. Inténtalo nuevamente.');
        return redirect()->back()->withInput();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    protected function redirectByRole($role)
    {
        switch ($role) {
            case 'administrador':
                return redirect()->to('/admin');
            case 'coordinador':
                return redirect()->to('/coordinador/dashboard');
            case 'logística':
                return redirect()->to('/logistica/dashboard');
            case 'seguridad':
                return redirect()->to('/seguridad/dashboard');
            default:
                return redirect()->to('/inicio');
        }
    }
}