<?php
namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url', 'session']);
    }

    // Añade este nuevo método para el dashboard del admin
    public function inicio()
{
    if (session('role') !== 'administrador') {
        return redirect()->to('/')->with('error', 'Acceso no autorizado');
    }

    $data = [
        'titulo' => 'Panel de Administración',
        'usuario' => session('username'),
        'fecha' => date('Y-m-d H:i:s')
    ];

    return view('admin/inicioAdmin', $data);
}

    public function usuarios()
    {
        // Verificar si es administrador
        if (session('role') !== 'administrador') {
            return redirect()->to('/admin/inicio')->with('error', 'Acceso no autorizado');
        }

        $data = [
            'show_users' => true,
            'usuarios' => $this->userModel->findAll()
        ];

        return view('admin/inicio', $data);
    }

    public function crear()
    {
        if (session('role') !== 'administrador') {
            return redirect()->to('/admin/inicio')->with('error', 'Acceso no autorizado');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[255]|is_unique[users.username]',
                'password' => 'required|min_length[8]',
                'role' => 'required|in_list[administrador,coordinador,cliente,logística,seguridad]'
            ];

            if ($this->validate($rules)) {
                $this->userModel->save([
                    'username' => $this->request->getPost('username'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => $this->request->getPost('role'),
                    'contact_info' => $this->request->getPost('contact_info')
                ]);
                
                return redirect()->to('/admin/usuarios')->with('success', 'Usuario creado correctamente');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return view('admin/crear_usuario'); // Crear esta vista (similar a la que te mostré antes)
    }

    public function editar($id)
    {
        if (session('role') !== 'administrador') {
            return redirect()->to('/inicio')->with('error', 'Acceso no autorizado');
        }

        $usuario = $this->userModel->find($id);
        
        if (!$usuario) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => "required|min_length[3]|max_length[255]|is_unique[users.username,id,$id]",
                'role' => 'required|in_list[administrador,coordinador,cliente,logística,seguridad]'
            ];

            if ($this->request->getPost('password')) {
                $rules['password'] = 'min_length[8]';
            }

            if ($this->validate($rules)) {
                $data = [
                    'username' => $this->request->getPost('username'),
                    'role' => $this->request->getPost('role'),
                    'contact_info' => $this->request->getPost('contact_info')
                ];

                if ($this->request->getPost('password')) {
                    $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                }

                $this->userModel->update($id, $data);
                return redirect()->to('/admin/usuarios')->with('success', 'Usuario actualizado');
            }
            
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return view('admin/editar_usuario', ['usuario' => $usuario]); // Crear esta vista
    }

    public function eliminar($id)
    {
        if (session('role') !== 'administrador') {
            return redirect()->to('/inicio')->with('error', 'Acceso no autorizado');
        }

        // No permitir auto-eliminación
        if ($id == session('user_id')) {
            return redirect()->to('/admin/usuarios')->with('error', 'No puedes eliminar tu propio usuario');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/usuarios')->with('success', 'Usuario eliminado');
    }
}