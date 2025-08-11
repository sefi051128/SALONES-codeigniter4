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
        'email' => 'required|valid_email|is_unique[users.email]',
        'phone' => 'permit_empty|regex_match[/^[0-9\-\+\s\(\)]{7,20}$/]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
                        ->withInput()
                        ->with('errors', $this->validator->getErrors());
    }

    $data = [
        'username' => $this->request->getPost('username'),
        'password' => $this->request->getPost('password'),
        'email' => $this->request->getPost('email'),
        'phone' => $this->request->getPost('phone'),
        'role' => 'cliente'
    ];

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

    public function testLoginAttemptWithInvalidCredentials()
{
    $result = $this->call('post', '/login', [
        'username' => 'usuario_inexistente',
        'password' => 'password_incorrecta',
    ]);

    $result->assertStatus(302);
    $result->assertRedirect();

    $location = $result->getHeaderLine('Location');
    $this->assertNotEmpty($location, 'La cabecera Location no está presente en la respuesta.');

    $this->assertStringContainsString('/login', $location);
}

public function testLogoutRedirectsToHome()
{
    $result = $this->call('get', '/logout');

    $result->assertStatus(302);
    $result->assertRedirect();

    $location = $result->getHeaderLine('Location');
    $this->assertNotEmpty($location, 'La cabecera Location no está presente en la respuesta.');

    $this->assertStringContainsString('/', $location);
}

// Añade este método a tu AuthController
public function profile()
{
    // Verificar si el usuario está logueado
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión para ver tu perfil');
    }

    // Obtener los datos del usuario
    $userId = session('user_id');
    $user = $this->userModel->getUserById($userId);

    if (!$user) {
        return redirect()->to('/inicio')->with('error', 'Usuario no encontrado');
    }

    // Pasar los datos del usuario a la vista
    $data = [
        'user' => $user
    ];

    return view('miPerfil', $data);
}

}