<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservationModel;
use App\Models\SedeModel;
use App\Models\InventoryModel;
use App\Models\EventoModel;
use App\Models\UserModel;

class Reservas extends BaseController
{
    protected $reservationModel;
    protected $sedeModel;
    protected $inventoryModel;
    protected $eventoModel;
    protected $userModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->sedeModel = new SedeModel();
        $this->inventoryModel = new InventoryModel();
        $this->eventoModel = new EventoModel();
        $this->userModel = new UserModel();
        helper(['form', 'url', 'session']);
    }

   public function index()
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $data = [
        'title' => (session('role') === 'administrador') ? 'Todas las Reservas' : 'Mis Reservas',
        'reservations' => $this->reservationModel->getReservationsWithUserDetails()
    ];

    return view('reservas/inicioReservas', $data);
}

    public function verReservas()
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $data = [
        'title' => 'Vista Especial de Reservas',
        'reservations' => $this->getReservationsForCurrentUser()
    ];

    return view('reservas/verReservas', $data);
}

    public function nueva($eventId = null)
{
    // Verificar autenticación
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    // Verificar rol (cliente o administrador)
    if (session('role') !== 'cliente' && session('role') !== 'administrador') {
        return redirect()->to('/dashboard')->with('error', 'Acceso no autorizado');
    }

        $event = null;
        if ($eventId) {
            $event = $this->eventoModel->find($eventId);
            if (!$event) {
                return redirect()->to('/reservas/nueva')->with('error', 'Evento no encontrado');
            }
        }

        $currentUser = $this->userModel->find(session('user_id'));

        $data = [
            'title' => isset($event) ? 'Reservar Evento' : 'Nueva Reserva',
            'event' => $event,
            'venues' => $this->sedeModel->findAll(),
            'items' => $this->inventoryModel->where('status', 'disponible')->findAll(),
            'current_user' => [
                'id' => session('user_id'),
                'username' => $currentUser['username'],
                'email' => $currentUser['email'],
                'phone' => $currentUser['phone'] ?? ''
            ]
        ];

        return view('reservas/nuevaReserva', $data);
    }

    public function guardar()
{
    // Verificar autenticación
    if (!session('isLoggedIn') || session('role') !== 'cliente') {
        return redirect()->to('/login')->with('error', 'Acceso no autorizado');
    }

    $rules = [
        'venue_id' => 'permit_empty|numeric',
        'reservation_date' => 'required|valid_date',
        'items' => 'permit_empty',
        'items.*' => 'numeric',
        'status' => 'required|in_list[pending,confirmed,cancelled]',
        'event_id' => 'permit_empty|numeric',
        'number_of_guests' => 'permit_empty|numeric'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
                       ->withInput()
                       ->with('errors', $this->validator->getErrors());
    }

    // Obtener los items seleccionados
    $selectedItems = $this->request->getPost('items') ?? [];
    
    // Guardar la reserva principal
    $reservationData = [
        'customer_id' => session('user_id'),
        'venue_id' => $this->request->getPost('venue_id'),
        'reservation_date' => $this->request->getPost('reservation_date'),
        'status' => $this->request->getPost('status'),
        'event_id' => $this->request->getPost('event_id'),
        'items' => json_encode($selectedItems) // Guardar como backup
    ];

    $this->reservationModel->save($reservationData);
    $reservationId = $this->reservationModel->getInsertID();

    // Guardar en reservation_items
    foreach ($selectedItems as $itemId) {
        $this->db->table('reservation_items')->insert([
            'reservation_id' => $reservationId,
            'item_id' => $itemId,
            'quantity' => 1 // O puedes recibir la cantidad del formulario
        ]);
    }

    session()->setFlashdata('success', 'Reserva creada exitosamente');
    return redirect()->to('/reservas/ver/'.$reservationId);
}

protected function guardarReservaArticulo()
{
    $rules = [
        'venue_id' => 'permit_empty|numeric',
        'reservation_date' => 'required|valid_date',
        'items' => 'permit_empty',
        'items.*' => 'numeric',
        'status' => 'required|in_list[pending,confirmed,cancelled]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
                       ->withInput()
                       ->with('errors', $this->validator->getErrors());
    }

    $reservationData = [
        'customer_id' => session('user_id'),
        'venue_id' => $this->request->getPost('venue_id'),
        'item_id' => $this->request->getPost('items') ? $this->request->getPost('items')[0] : null,
        'reservation_date' => $this->request->getPost('reservation_date'),
        'status' => $this->request->getPost('status')
    ];

    if ($this->reservationModel->save($reservationData)) {
        session()->setFlashdata('success', 'Reserva creada exitosamente');
        return redirect()->to('/reservas');
    }

    session()->setFlashdata('error', 'Error al crear la reserva');
    return redirect()->back()->withInput();
}

protected function guardarReservaEvento()
{
    $bookingModel = new BookingModel();
    $user = $this->userModel->find(session('user_id'));

    $bookingData = [
        'event_id' => $this->request->getPost('event_id'),
        'customer_name' => $user['username'],
        'customer_contact' => $user['email'],
        'number_of_guests' => $this->request->getPost('number_of_guests') ?? 1
    ];

    if ($bookingModel->save($bookingData)) {
        session()->setFlashdata('success', 'Reserva de evento creada exitosamente');
        return redirect()->to('/eventos');
    }

    session()->setFlashdata('error', 'Error al reservar el evento');
    return redirect()->back()->withInput();
}

    public function ver($id)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login');
    }

    $reservation = $this->reservationModel->getReservationWithDetails($id);

    // Verificar permisos
    if (session('role') !== 'administrador' && $reservation['customer_id'] != session('user_id')) {
        return redirect()->to('/reservas')->with('error', 'No tienes permiso para ver esta reserva');
    }

    $data = [
        'title' => 'Detalles de Reserva',
        'reservation' => $reservation
    ];

    return view('reservas/verReserva', $data);
}

    protected function getReservationsForCurrentUser()
    {
        if (session('role') === 'administrador') {
            return $this->reservationModel->getAllReservationsWithDetails();
        }

        return $this->reservationModel->getUserReservations(session('user_id'));
    }
}