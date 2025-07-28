<?php
namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'event_id', 'number_of_guests', 'booking_date', 'status'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function checkAvailability($eventId, $numberOfGuests = 1)
{
    // Obtener información completa del evento
    $event = model('EventoModel')->getEventForBooking($eventId);
    
    if (!$event) {
        log_message('error', "Evento {$eventId} no encontrado");
        return ['available' => false, 'message' => 'Evento no encontrado'];
    }

    // Verificar estado del evento
    if ($event['status'] !== 'activo') {
        log_message('info', "Evento {$eventId} no está activo");
        return ['available' => false, 'message' => 'Evento no disponible'];
    }

    // Verificar fecha del evento
    if (strtotime($event['date']) < time()) {
        log_message('info', "Evento {$eventId} ha expirado");
        return ['available' => false, 'message' => 'Este evento ya ha ocurrido'];
    }

    // Si el evento no tiene capacidad definida, siempre está disponible
    if (!isset($event['capacity'])) {
        return ['available' => true, 'message' => ''];
    }

    // Calcular disponibilidad considerando los nuevos invitados
    $totalReservations = $this->selectSum('number_of_guests')
                            ->where('event_id', $eventId)
                            ->where('status', 'confirmed')
                            ->get()
                            ->getRow()->number_of_guests ?? 0;

    $availableSpots = $event['capacity'] - $totalReservations;
    
    if ($availableSpots >= $numberOfGuests) {
        return [
            'available' => true,
            'message' => '',
            'booked' => $totalReservations,
            'available_spots' => $availableSpots
        ];
    } else {
        return [
            'available' => false,
            'message' => "Capacidad insuficiente. Disponibles: {$availableSpots} lugares",
            'booked' => $totalReservations,
            'available_spots' => $availableSpots
        ];
    }
}

    // Obtener reservas de eventos con detalles
    public function getBookingsWithDetails()
    {
        return $this->select('bookings.*, users.username as customer_name, events.name as event_name, events.date as event_date')
            ->join('users', 'users.id = bookings.user_id')
            ->join('events', 'events.id = bookings.event_id')
            ->orderBy('booking_date', 'DESC')
            ->findAll();
    }

    // Reservas de eventos de un usuario específico
    public function getUserBookings($userId)
    {
        return $this->select('bookings.*, events.name as event_name, events.date as event_date')
            ->join('events', 'events.id = bookings.event_id')
            ->where('bookings.user_id', $userId)
            ->orderBy('booking_date', 'DESC')
            ->findAll();
    }

    // Obtener detalles de una reserva de evento
    public function getBookingDetails($id)
    {
        return $this->select('bookings.*, users.username as customer_name, users.email, events.name as event_name, events.date as event_date')
            ->join('users', 'users.id = bookings.user_id')
            ->join('events', 'events.id = bookings.event_id')
            ->where('bookings.id', $id)
            ->first();
    }
}