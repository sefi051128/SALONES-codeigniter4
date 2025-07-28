<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ReservationModel;
use App\Models\EventoModel;
use App\Models\UserModel;

class Reservas extends BaseController
{
    protected $bookingModel;
    protected $reservationModel;
    protected $eventoModel;
    protected $userModel;

    public function __construct()
    {
        $this->bookingModel = model('BookingModel');
        $this->reservationModel = model('ReservationModel');
        $this->eventoModel = model('EventoModel');
        $this->userModel = model('UserModel');
        helper(['form', 'url', 'session']);
    }

    /**
     * Muestra el listado principal de reservas
     */
    public function index()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $filters = $this->request->getGet();
        $role = session('role');

        $data = [
            'title' => ($role === 'administrador') ? 'Todas las Reservas' : 'Mis Reservas',
            'eventBookings' => ($role === 'administrador') 
                ? $this->bookingModel->getBookingsWithDetails($filters)
                : $this->bookingModel->getUserBookings(session('user_id'), $filters),
            'articleReservations' => ($role === 'administrador')
                ? $this->reservationModel->getReservationsWithDetails($filters)
                : $this->reservationModel->getUserReservations(session('user_id'), $filters),
            'pager' => $this->bookingModel->pager
        ];

        return view('reservas/inicioReservas', $data);
    }

    public function crearReserva()
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    // Estados considerados como disponibles
    $availableStatuses = ['Disponible'];
    
    $data = [
        'title' => 'Crear Nueva Reserva de Evento',
        'eventos' => $this->eventoModel->where('status', 'activo')->findAll(),
        'current_user' => $this->userModel->find(session('user_id')),
        'items' => model('InventoryModel')
                    ->whereIn('status', $availableStatuses)
                    ->findAll()
    ];

    return view('reservas/crearReserva', $data);
}

    /**
     * Muestra el formulario para nueva reserva de evento
     */
    public function nuevaBooking($eventId = null)
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $event = $this->eventoModel->getEventForBooking($eventId);
        
        if (!$event) {
            return redirect()->to('/eventos')->with('error', 'Evento no encontrado');
        }

        // Verificar disponibilidad
        if (!$this->bookingModel->checkAvailability($eventId, $event['date'])) {
            return redirect()->to('/eventos')->with('error', 'Este evento ya no tiene disponibilidad');
        }

        $data = [
            'title' => 'Reservar Evento: ' . esc($event['name']),
            'event' => $event,
            'current_user' => $this->userModel->find(session('user_id')),
        ];

        return view('reservas/nuevaBooking', $data);
    }

    /**
     * Procesa el formulario de nueva reserva de evento
     */
    public function guardarBooking()
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $rules = [
        'event_id' => 'required|numeric',
        'number_of_guests' => 'required|integer|greater_than[0]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $eventId = $this->request->getPost('event_id');
    $numberOfGuests = $this->request->getPost('number_of_guests');

    // Verificar disponibilidad con la nueva lógica
    $availability = $this->bookingModel->checkAvailability($eventId, $numberOfGuests);
    
    if (!$availability['available']) {
        return redirect()->back()
                       ->withInput()
                       ->with('error', $availability['message']);
    }

    $data = [
        'user_id' => session('user_id'),
        'event_id' => $eventId,
        'number_of_guests' => $numberOfGuests,
        'booking_date' => date('Y-m-d H:i:s'),
        'status' => 'confirmed',
    ];

    if ($this->bookingModel->save($data)) {
        return redirect()->to('/reservas')->with('success', 'Reserva creada exitosamente');
    }

    return redirect()->back()->withInput()->with('error', 'Error al crear la reserva');
}

    /**
     * Muestra el formulario para nueva reserva de artículos
     */
    public function nuevaReservation()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $data = [
            'title' => 'Nueva Reserva de Artículos',
            'current_user' => $this->userModel->find(session('user_id')),
            'items_disponibles' => model('InventoryModel')->where('status', 'disponible')->findAll()
        ];

        return view('reservas/nuevaReservation', $data);
    }

    /**
     * Procesa el formulario de nueva reserva de artículos
     */
    public function guardarReservation()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $rules = [
            'reservation_date' => 'required|valid_date|future_date',
            'items' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'customer_id' => session('user_id'),
            'reservation_date' => $this->request->getPost('reservation_date'),
            'items' => json_encode($this->request->getPost('items') ?? []),
            'status' => 'confirmed',
        ];

        if ($this->reservationModel->save($data)) {
            return redirect()->to('/reservas')->with('success', 'Reserva de artículos creada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al crear la reserva de artículos');
    }

    /**
     * Muestra los detalles de una reserva
     */
    public function ver($id, $type = 'booking')
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $reservation = ($type === 'booking')
            ? $this->bookingModel->getBookingDetails($id)
            : $this->reservationModel->getReservationDetails($id);

        if (!$reservation) {
            return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
        }

        // Verificar permisos
        $userIdField = ($type === 'booking') ? 'user_id' : 'customer_id';
        if (session('role') !== 'administrador' && $reservation[$userIdField] != session('user_id')) {
            return redirect()->to('/reservas')->with('error', 'No tienes permiso para ver esta reserva');
        }

        return view('reservas/verReserva', [
            'title' => 'Detalles de Reserva',
            'reservation' => $reservation,
            'type' => $type
        ]);
    }

    /**
     * Muestra el formulario para editar una reserva
     */
    public function editar($id, $type = 'booking')
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = ($type === 'booking') ? $this->bookingModel : $this->reservationModel;
        $reservation = $model->find($id);

        if (!$reservation) {
            return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
        }

        // Verificar permisos
        $userIdField = ($type === 'booking') ? 'user_id' : 'customer_id';
        if (session('role') !== 'administrador' && $reservation[$userIdField] != session('user_id')) {
            return redirect()->to('/reservas')->with('error', 'No tienes permiso para editar esta reserva');
        }

        return view("reservas/editar{$type}", [
            'title' => 'Editar Reserva',
            'reservation' => $reservation,
            'type' => $type,
            'items_disponibles' => ($type === 'reservation') 
                ? model('InventoryModel')->where('status', 'disponible')->findAll()
                : []
        ]);
    }

    /**
     * Procesa la actualización de una reserva
     */
    public function actualizar($id, $type = 'booking')
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = ($type === 'booking') ? $this->bookingModel : $this->reservationModel;
        $reservation = $model->find($id);

        if (!$reservation) {
            return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
        }

        // Verificar permisos
        $userIdField = ($type === 'booking') ? 'user_id' : 'customer_id';
        if (session('role') !== 'administrador' && $reservation[$userIdField] != session('user_id')) {
            return redirect()->to('/reservas')->with('error', 'No tienes permiso para editar esta reserva');
        }

        $rules = ($type === 'booking') 
            ? [
                'number_of_guests' => 'required|integer|greater_than[0]',
                'status' => 'required|in_list[confirmed,cancelled,pending]'
              ]
            : [
                'reservation_date' => 'required|valid_date|future_date',
                'items' => 'permit_empty|string',
                'status' => 'required|in_list[confirmed,cancelled,pending]'
              ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = ($type === 'booking')
            ? [
                'number_of_guests' => $this->request->getPost('number_of_guests'),
                'status' => $this->request->getPost('status')
              ]
            : [
                'reservation_date' => $this->request->getPost('reservation_date'),
                'items' => json_encode($this->request->getPost('items') ?? []),
                'status' => $this->request->getPost('status')
              ];

        if ($model->update($id, $data)) {
            return redirect()->to('/reservas')->with('success', 'Reserva actualizada exitosamente');
        }

        return redirect()->back()->withInput()->with('error', 'Error al actualizar la reserva');
    }

    /**
     * Cancela una reserva
     */
    public function cancelar($id, $type = 'booking')
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = ($type === 'booking') ? $this->bookingModel : $this->reservationModel;
        $reservation = $model->find($id);

        if (!$reservation) {
            return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
        }

        // Verificar permisos
        $userIdField = ($type === 'booking') ? 'user_id' : 'customer_id';
        if (session('role') !== 'administrador' && $reservation[$userIdField] != session('user_id')) {
            return redirect()->to('/reservas')->with('error', 'No tienes permiso para cancelar esta reserva');
        }

        if ($model->update($id, ['status' => 'cancelled'])) {
            return redirect()->to('/reservas')->with('success', 'Reserva cancelada exitosamente');
        }

        return redirect()->to('/reservas')->with('error', 'Error al cancelar la reserva');
    }

    /**
     * Elimina una reserva (solo administrador)
     */
    public function eliminar($id, $type = 'booking')
    {
        if (!session('isLoggedIn') || session('role') !== 'administrador') {
            return redirect()->to('/login');
        }

        $model = ($type === 'booking') ? $this->bookingModel : $this->reservationModel;
        
        if ($model->delete($id)) {
            return redirect()->to('/reservas')->with('success', 'Reserva eliminada exitosamente');
        }

        return redirect()->to('/reservas')->with('error', 'Error al eliminar la reserva');
    }

    /**
     * Muestra la página para seleccionar evento para reserva
     */
    public function seleccionarEventoParaReserva()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $data = [
            'title' => 'Seleccionar Evento para Reservar',
            'eventos' => $this->eventoModel->where('status', 'activo')->findAll()
        ];

        return view('reservas/seleccionar_evento', $data);
    }
}