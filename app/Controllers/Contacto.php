<?php
namespace App\Controllers;

class Contacto extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Contacto - EventSalones',
            'contact_info' => [
                'direccion' => 'Av. Principal 1234, Tlaxcala Centro, Tlaxcala',
                'telefono' => '+56 2 2345 6789',
                'email' => 'contacto@eventsalones.cl',
                'horario' => 'Lunes a Viernes: 9:00 - 18:00 hrs'
            ]
        ];

        return view('contacto', $data);
    }

    public function enviarMensaje()
{
    // Validar los datos del formulario
    $rules = [
        'nombre' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email',
        'asunto' => 'required|min_length[5]|max_length[100]',
        'mensaje' => 'required|min_length[10]|max_length[1000]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
                        ->withInput()
                        ->with('errors', $this->validator->getErrors());
    }

    // Aquí deberías implementar el envío real del mensaje
    // Esto es solo un ejemplo:
    $nombre = $this->request->getPost('nombre');
    $email = $this->request->getPost('email');
    $asunto = $this->request->getPost('asunto');
    $mensaje = $this->request->getPost('mensaje');

    // Ejemplo de guardar en base de datos (necesitarías un modelo para esto)
    // $contactoModel->guardarMensaje($nombre, $email, $asunto, $mensaje);

    // Ejemplo de enviar por email (configura primero el email en CodeIgniter)
    /*
    $emailService = \Config\Services::email();
    $emailService->setTo('contacto@eventsalones.cl');
    $emailService->setSubject("Nuevo mensaje: $asunto");
    $emailService->setMessage("De: $nombre ($email)\n\n$mensaje");
    $emailService->send();
    */

    return redirect()->to('/contacto')
                    ->with('success', 'Tu mensaje ha sido enviado. Nos pondremos en contacto contigo pronto.');
}
}