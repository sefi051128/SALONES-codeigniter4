<?php
namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        // Redirigir usuarios logueados
        if (session('isLoggedIn')) {
            return redirect()->to('/inicio');
        }
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function createUser()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[administrador,coordinador,cliente,logística,seguridad]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userModel = new UserModel();
        $userModel->createUser([
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
            'contact_info' => $this->request->getPost('contact_info') ?? null
        ]);

        return redirect()->to('/')->with('success', 'Usuario registrado correctamente.');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->getUser($username);

        if ($user && password_verify($password, $user['password'])) {
            // Configurar datos de sesión
            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            session()->regenerate(); // Prevención de fijación de sesión
            
            return redirect()->to('/inicio');
        }

        return redirect()->back()->withInput()->with('error', 'Credenciales inválidas');
    }

    public function inicio()
    {
        // Verificar autenticación
        if (!session('isLoggedIn')) {
            return redirect()->to('/')->with('error', 'Acceso no autorizado');
        }

        return view('inicio');
    }

    public function logout()
    {
        // Destruir completamente la sesión
        session()->destroy();
        return redirect()->to('/');
    }
}