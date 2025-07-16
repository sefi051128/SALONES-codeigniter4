<?php
namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    /*
    public function index()
    {
        // Redirigir usuarios logueados
        if (session('isLoggedIn')) {
            return redirect()->to('/inicio');
        }
        return view('login');
    }
        */

    public function register()
    {
        return view('register');
    }

    public function createUser() {
    $validation = \Config\Services::validation();
    
    $rules = [
        'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]',
        'password' => 'required|min_length[8]',
        // Elimina la validación del rol (ya no es necesario)
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    $userModel = new UserModel();
    $userModel->createUser([
        'username' => $this->request->getPost('username'),
        'password' => $this->request->getPost('password'),
        'role' => 'cliente', // Fijo para todos los registros
        'contact_info' => $this->request->getPost('contact_info') ?? null
    ]);

    return redirect()->to('/')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
}

    public function login() {
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $userModel = new UserModel();
    $user = $userModel->getUser($username);

    if ($user && password_verify($password, $user['password'])) {
        $sessionData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'isLoggedIn' => true
        ];
        session()->set($sessionData);
        session()->regenerate();

        // Redirigir según el rol
        switch ($user['role']) {
    case 'administrador':
        return redirect()->to('/admin'); // Va a Admin::inicio
    case 'coordinador':
        return redirect()->to('/coordinador/dashboard');
    case 'logística':
        return redirect()->to('/logistica/dashboard');
    case 'seguridad':
        return redirect()->to('/seguridad/dashboard');
    default: // cliente
        return redirect()->to('/inicio');
}
    }

    return redirect()->back()->withInput()->with('error', 'Credenciales inválidas');
}

/*    
public function inicio()
    {
        // Verificar autenticación
        if (!session('isLoggedIn')) {
            return redirect()->to('/')->with('error', 'Acceso no autorizado');
        }

        return view('inicio');
    }
        */
    
    
    public function inicio()
    {
        return view('inicio'); // Esto cargará app/views/inicio.php
    }

    public function logout()
    {
        // Destruir completamente la sesión
        session()->destroy();
        return redirect()->to('/');
    }

    
}