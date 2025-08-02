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

    public function testUpdate()
{
    $data = [
        'username' => 'nuevo_usuario',
        'email' => 'nuevo@correo.com',
        'phone' => '+521231231234',
        'role' => 'cliente'
    ];

    $resultado = $this->userModel->update(1, $data); // Usa un ID válido en tu tabla

    dd([
        'resultado' => $resultado,
        'errores' => $this->userModel->errors()
    ]);
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

    //Método para crear un usuario cliente desde el registro público
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

//Método para crear un usuario con rol elegible desde el panel de administración
public function createAdminUser()
{
    helper(['form']);

    $rules = [
        'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
        'password' => 'required|min_length[8]',
        'email'    => 'required|valid_email|max_length[100]|is_unique[users.email]',
        'phone'    => 'permit_empty|regex_match[/^\+?[0-9\s\-\(\)]{7,20}$/]',
        'role'     => 'required|in_list[cliente,administrador,coordinador,logística,seguridad]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $data = [
        'username' => $this->request->getPost('username'),
        'password' => $this->request->getPost('password'), // sin hash
        'email'    => $this->request->getPost('email'),
        'phone'    => $this->request->getPost('phone') ?? null,
        'role'     => $this->request->getPost('role'),
    ];

    if ($this->userModel->insert($data)) {
        session()->setFlashdata('success', 'Usuario creado correctamente con rol ' . esc($data['role']));
        return redirect()->to('/users');
    }

    session()->setFlashdata('error', 'No se pudo crear el usuario.');
    return redirect()->back()->withInput();
}

     
    // Método para eliminar un usuario
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

    // Método para mostrar el formulario de edición
public function edit($id)
{
    $user = $this->userModel->getUserById($id);
    
    if (!$user) {
        session()->setFlashdata('error', 'Usuario no encontrado');
        return redirect()->to('/users');
    }

    $data = [
        'title' => 'Editar Usuario',
        'user' => $user,
        'validation' => session()->getFlashdata('validation') ?? null
    ];

    return view('user/edit', $data);
}

// Método para procesar la actualización
public function update($id)
{
    // Verificar si el usuario existe
    $user = $this->userModel->getUserById($id);
    if (!$user) {
        session()->setFlashdata('error', 'Usuario no encontrado');
        return redirect()->to('/users');
    }

    // Reglas de validación
    $rules = [
        'username' => [
            'rules' => "required|min_length[3]|max_length[20]|is_unique[users.username,id,{$id}]",
            'errors' => [
                'required' => 'El nombre de usuario es obligatorio',
                'min_length' => 'El usuario debe tener al menos 3 caracteres',
                'max_length' => 'El usuario no debe exceder 20 caracteres',
                'is_unique' => 'Este nombre de usuario ya está en uso'
            ]
        ],
        'password' => [
            'rules' => 'permit_empty|min_length[8]',
            'errors' => [
                'min_length' => 'La contraseña debe tener al menos 8 caracteres'
            ]
        ],
        'email' => [
            'rules' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'errors' => [
                'required' => 'El correo electrónico es obligatorio',
                'valid_email' => 'Debe ingresar un correo electrónico válido',
                'is_unique' => 'Este correo electrónico ya está en uso'
            ]
        ],
        'phone' => [
            'rules' => 'permit_empty|regex_match[/^\+569\d{8}$/]',
            'errors' => [
                'regex_match' => 'El teléfono debe tener el formato +56912345678'
            ]
        ],
        'role' => [
            'rules' => 'required|in_list[cliente,administrador,coordinador,logística,seguridad]',
            'errors' => [
                'required' => 'Debe seleccionar un rol',
                'in_list' => 'El rol seleccionado no es válido'
            ]
        ]
    ];

    // Validar los datos
    if (!$this->validate($rules)) {
        return redirect()->back()
            ->withInput()
            ->with('validation', $this->validator);
    }

    // Preparar los datos para actualizar
    $data = [
        'username' => $this->request->getPost('username'),
        'email' => $this->request->getPost('email'),
        'phone' => $this->request->getPost('phone'),
        'role' => $this->request->getPost('role')
    ];

    // Solo actualizar la contraseña si se proporcionó una nueva
    $password = $this->request->getPost('password');
    if (!empty($password)) {
        $data['password'] = $password;
    }

    // Intentar actualizar el usuario
    if ($this->userModel->update($id, $data)) {
        session()->setFlashdata('success', 'Usuario actualizado correctamente');
        return redirect()->to('/users');
    } else {
        session()->setFlashdata('error', 'Error al actualizar el usuario');
        return redirect()->back()->withInput();
    }
}

public function exportarPDF()
{
    // Verificar autenticación
    if (!session()->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    // Obtener parámetros de filtro si los hay
    $roleFilter = $this->request->getGet('role');
    $searchTerm = $this->request->getGet('search');

    // Construir la consulta
    $builder = $this->userModel->builder();
    $builder->select('users.*')->orderBy('id', 'DESC');

    // Aplicar filtros
    if ($roleFilter) {
        $builder->where('role', $roleFilter);
    }
    if ($searchTerm) {
        $builder->groupStart()
            ->like('username', $searchTerm)
            ->orLike('email', $searchTerm)
            ->orLike('phone', $searchTerm)
            ->groupEnd();
    }

    $users = $builder->get()->getResultArray();

    $data = [
        'title' => 'Reporte de Usuarios',
        'users' => $users,
        'filtros' => [
            'role' => $roleFilter,
            'search' => $searchTerm
        ],
        'generatedAt' => date('d/m/Y H:i:s')
    ];
    
    // Cargar la vista del reporte
    $html = view('user/reporteUsuarios', $data);
    
    // Configurar Dompdf
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'Arial');
    
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    
    // Descargar el PDF
    $dompdf->stream("reporte_usuarios_".date('Y-m-d').".pdf", [
        "Attachment" => true
    ]);
    
    exit;
}

public function show($id = null)
{
    // Obtener el usuario por ID
    $user = $this->userModel->find($id);
    
    if (!$user) {
        return redirect()->to('/users')->with('error', 'Usuario no encontrado');
    }

    return view('user/detalle', [
        'title' => 'Detalles del Usuario',
        'user' => $user,
        'generatedAt' => date('d/m/Y H:i:s')
    ]);
}



    


}
