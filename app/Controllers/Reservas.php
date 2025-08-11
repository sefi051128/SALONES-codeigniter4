<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\ReservationModel;
use App\Models\EventoModel;
use App\Models\UserModel;
use App\Models\BookingItemModel;
use App\Models\InventoryModel;

class Reservas extends BaseController
{
    protected $bookingModel;
    protected $reservationModel;
    protected $eventoModel;
    protected $userModel;
    protected $bookingItemModel;
    protected $inventoryModel;

    public function __construct()
    {
        $this->bookingModel = model('BookingModel');
        $this->reservationModel = model('ReservationModel');
        $this->eventoModel = model('EventoModel');
        $this->userModel = model('UserModel');
        $this->bookingItemModel = model('BookingItemModel');
        $this->inventoryModel = model('InventoryModel');
        helper(['form', 'url', 'session']);
    }

    /**
     * Muestra el listado principal de reservas
     */
    public function index($filter = 'all')
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $filters = $this->request->getGet();
    $role = session('role');
    
    // Determinar qué reservas mostrar
    $showAll = ($role === 'administrador' && $filter !== 'mine');
    
    $data = [
        'title' => $showAll ? 'Todas las Reservas' : 'Mis Reservas',
        'eventBookings' => $showAll 
            ? $this->bookingModel->getBookingsWithDetails($filters)
            : $this->bookingModel->getUserBookings(session('user_id'), $filters),
        'articleReservations' => $showAll
            ? $this->reservationModel->getReservationsWithDetails($filters)
            : $this->reservationModel->getUserReservations(session('user_id'), $filters),
        'pager' => $this->bookingModel->pager,
        'filter' => $filter,
        'current_user_id' => session('user_id') // Añadido para usar en la vista
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
        'items' => 'permit_empty|is_array' // Nuevo campo para los items
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $eventId = $this->request->getPost('event_id');
    $numberOfGuests = $this->request->getPost('number_of_guests');
    $selectedItems = $this->request->getPost('items') ?? [];

    // Verificar disponibilidad del evento
    $availability = $this->bookingModel->checkAvailability($eventId, $numberOfGuests);
    
    if (!$availability['available']) {
        return redirect()->back()
                       ->withInput()
                       ->with('error', $availability['message']);
    }

    // Verificar disponibilidad de items
    if (!empty($selectedItems)) {
        $unavailableItems = $this->inventoryModel
            ->whereIn('id', $selectedItems)
            ->where('booking_id IS NOT NULL')
            ->findAll();
            
        if (!empty($unavailableItems)) {
            $itemNames = array_column($unavailableItems, 'name');
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Los siguientes items ya están reservados: ' . implode(', ', $itemNames));
        }
    }

    // Iniciar transacción para asegurar integridad
    // Iniciar transacción para asegurar integridad
$db = \Config\Database::connect();
$db->transStart();

try {
    // 1. Crear la reserva principal
    $bookingData = [
        'user_id' => session('user_id'),
        'event_id' => $eventId,
        'number_of_guests' => $numberOfGuests,
        'booking_date' => date('Y-m-d H:i:s'),
        'status' => 'confirmed',
    ];

    $bookingId = $this->bookingModel->insert($bookingData);

    // 2. Asignar items a la reserva
    if (!empty($selectedItems)) {
        $this->inventoryModel
            ->whereIn('id', $selectedItems)
            ->set([
                'booking_id' => $bookingId,
                'status' => 'En uso',
                'updated_at' => date('Y-m-d H:i:s')
            ])
            ->update();
    }

    $db->transComplete();

    return redirect()->to('/reservas')->with('success', 'Reserva creada exitosamente');

} catch (\Exception $e) {
    $db->transRollback();
    return redirect()->back()->withInput()->with('error', 'Error al crear la reserva: ' . $e->getMessage());
}

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
        'items' => 'required',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $items = $this->request->getPost('items') ?? [];
    $quantities = $this->request->getPost('item_quantity') ?? [];

    $data = [
        'customer_id' => session('user_id'),
        'reservation_date' => $this->request->getPost('reservation_date'),
        'items' => json_encode($items),
        'item_quantities' => json_encode($quantities),
        'status' => 'pending', // Cambiado a pending para requerir confirmación
    ];

    if ($this->reservationModel->save($data)) {
        // Actualizar estado de los artículos
        foreach($items as $itemId) {
            $this->inventoryModel->update($itemId, ['status' => 'reservado']);
        }
        
        return redirect()->to('/reservas')->with('success', 'Reserva creada exitosamente. Pendiente de confirmación.');
    }

    return redirect()->back()->withInput()->with('error', 'Error al crear la reserva');
}

    /**
     * Muestra los detalles de una reserva
     */
    public function ver($id, $type = 'booking')
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login');
    }

    // Obtener la reserva principal
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

    // Procesamiento de items según el tipo de reserva
    $itemsReservados = [];
    $cantidades = [];
    
    if ($type === 'reservation') {
        // Lógica existente para reservas de artículos
        $itemsReservados = json_decode($reservation['items'] ?? '[]', true) ?? [];
        $cantidades = json_decode($reservation['item_quantities'] ?? '{}', true) ?? [];
    } else {
        // Nueva lógica para bookings (eventos con artículos)
        $itemsReservados = $this->inventoryModel
            ->select('id, code, name, status')
            ->where('booking_id', $id)
            ->findAll();
    }

    return view('reservas/verReserva', [
        'title' => 'Detalles de Reserva',
        'reservation' => $reservation,
        'type' => $type,
        'inventoryModel' => model('InventoryModel'),
        'itemsReservados' => $itemsReservados,
        'cantidades' => $cantidades
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

    public function releaseItems(int $bookingId)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    // Opcional: checar permisos (solo administrador o dueño)
    // Aquí asumo que estás liberando items de una reserva tipo booking:
    $booking = $this->bookingModel->find($bookingId);
    if (!$booking) {
        return redirect()->back()->with('error', 'Reserva no encontrada');
    }

    if (session('role') !== 'administrador' && $booking['user_id'] != session('user_id')) {
        return redirect()->back()->with('error', 'No tienes permiso para hacer esto');
    }

    $inventoryModel = model('InventoryModel');

    // Suponiendo que releaseFromBooking hace: libera items (quita booking_id y pone status disponible)
    $inventoryModel->releaseFromBooking($bookingId);

    return redirect()->back()->with('success', 'Items liberados correctamente');
}

// Mostrar formulario de edición para booking
public function editarBooking($id)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $booking = $this->bookingModel->getBookingDetails($id); // <-- cambio aquí
    if (!$booking) {
        return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
    }

    if (session('role') !== 'administrador' && $booking['user_id'] != session('user_id')) {
        return redirect()->to('/reservas')->with('error', 'No tienes permiso para editar esta reserva');
    }

    return view('reservas/editarbooking', [
        'title' => 'Editar Reserva de Evento',
        'reservation' => $booking,
        'type' => 'booking'
    ]);
}


// Procesar actualización de booking
public function actualizarBooking($id)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $booking = $this->bookingModel->find($id);
    if (!$booking) {
        return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
    }

    if (session('role') !== 'administrador' && $booking['user_id'] != session('user_id')) {
        return redirect()->to('/reservas')->with('error', 'No tienes permiso para editar esta reserva');
    }

    $rules = [
        'number_of_guests' => 'required|integer|greater_than[0]',
        'status' => 'required|in_list[confirmed,cancelled,pending]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'number_of_guests' => $this->request->getPost('number_of_guests'),
        'status' => $this->request->getPost('status')
    ];

    if ($this->bookingModel->update($id, $data)) {
        return redirect()->to('/reservas')->with('success', 'Reserva actualizada exitosamente');
    }

    return redirect()->back()->withInput()->with('error', 'Error al actualizar la reserva');
}

// Cancelar booking
public function cancelarBooking($id)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $booking = $this->bookingModel->find($id);
    if (!$booking) {
        return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
    }

    if (session('role') !== 'administrador' && $booking['user_id'] != session('user_id')) {
        return redirect()->to('/reservas')->with('error', 'No tienes permiso para cancelar esta reserva');
    }

    if ($this->bookingModel->update($id, ['status' => 'cancelled'])) {
        return redirect()->to('/reservas')->with('success', 'Reserva cancelada exitosamente');
    }

    return redirect()->to('/reservas')->with('error', 'Error al cancelar la reserva');
}

// Eliminar booking (solo admin)
public function eliminarBooking($id)
{
    if (!session('isLoggedIn') || session('role') !== 'administrador') {
        return redirect()->to('/login');
    }

    if ($this->bookingModel->delete($id)) {
        return redirect()->to('/reservas')->with('success', 'Reserva eliminada exitosamente');
    }

    return redirect()->to('/reservas')->with('error', 'Error al eliminar la reserva');
}

// Mostrar formulario de edición para reservation
public function editarReservation($id)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $reservation = $this->reservationModel->getReservationDetails($id); // usar el detalle completo
    if (!$reservation) {
        return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
    }

    if (session('role') !== 'administrador' && $reservation['customer_id'] != session('user_id')) {
        return redirect()->to('/reservas')->with('error', 'No tienes permiso para editar esta reserva');
    }

    return view('reservas/editarreservation', [
        'title' => 'Editar Reserva de Artículos',
        'reservation' => $reservation,
        'type' => 'reservation',
        'items_disponibles' => model('InventoryModel')->where('status', 'disponible')->findAll()
    ]);
}

// Procesar actualización de reservation
public function actualizarReservation($id)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $reservation = $this->reservationModel->find($id);
    if (!$reservation) {
        return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
    }

    if (session('role') !== 'administrador' && $reservation['customer_id'] != session('user_id')) {
        return redirect()->to('/reservas')->with('error', 'No tienes permiso para editar esta reserva');
    }

    $rules = [
        'reservation_date' => 'required|valid_date|future_date',
        'items' => 'permit_empty|string',
        'status' => 'required|in_list[confirmed,cancelled,pending]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'reservation_date' => $this->request->getPost('reservation_date'),
        'items' => json_encode($this->request->getPost('items') ?? []),
        'status' => $this->request->getPost('status')
    ];

    if ($this->reservationModel->update($id, $data)) {
        return redirect()->to('/reservas')->with('success', 'Reserva actualizada exitosamente');
    }

    return redirect()->back()->withInput()->with('error', 'Error al actualizar la reserva');
}

// Cancelar reservation
public function cancelarReservation($id)
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $reservation = $this->reservationModel->find($id);
    if (!$reservation) {
        return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
    }

    if (session('role') !== 'administrador' && $reservation['customer_id'] != session('user_id')) {
        return redirect()->to('/reservas')->with('error', 'No tienes permiso para cancelar esta reserva');
    }

    if ($this->reservationModel->update($id, ['status' => 'cancelled'])) {
        return redirect()->to('/reservas')->with('success', 'Reserva cancelada exitosamente');
    }

    return redirect()->to('/reservas')->with('error', 'Error al cancelar la reserva');
}

// Eliminar reservation (solo admin)
public function eliminarReservation($id)
{
    if (!session('isLoggedIn') || session('role') !== 'administrador') {
        return redirect()->to('/login');
    }

    if ($this->reservationModel->delete($id)) {
        return redirect()->to('/reservas')->with('success', 'Reserva eliminada exitosamente');
    }

    return redirect()->to('/reservas')->with('error', 'Error al eliminar la reserva');
}


}