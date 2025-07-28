<?php
namespace App\Models;

use CodeIgniter\Model;

class EventoModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $allowedFields = ['venue_id', 'name', 'date', 'description', 'status'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getEventosConSede()
    {
        return $this->select('events.*, venues.name as venue_name, venues.location')
                   ->join('venues', 'venues.id = events.venue_id')
                   ->findAll();
    }

    public function getEventForBooking($eventId)
{
    return $this->select('events.*, venues.name as venue_name, venues.location, venues.capacity')
               ->join('venues', 'venues.id = events.venue_id')
               ->where('events.id', $eventId)
               ->first();
}

public function checkEventAvailability($eventId, $requiredSpots = 1)
{
    $event = $this->getEventForBooking($eventId);
    
    if (!$event || $event['status'] !== 'activo') {
        return ['available' => false, 'message' => 'Evento no disponible'];
    }

    if (strtotime($event['date']) < time()) {
        return ['available' => false, 'message' => 'Este evento ya ha ocurrido'];
    }

    if (isset($event['capacity'])) {
        $bookingModel = model('BookingModel');
        $totalReservations = $bookingModel->where('event_id', $eventId)
                                        ->where('status', 'confirmed')
                                        ->countAllResults();
        
        $available = ($event['capacity'] - $totalReservations) >= $requiredSpots;
        
        return [
            'available' => $available,
            'message' => $available ? '' : 'Capacidad agotada',
            'capacity' => $event['capacity'],
            'booked' => $totalReservations,
            'available_spots' => $event['capacity'] - $totalReservations
        ];
    }

    return ['available' => true, 'message' => ''];
}

    public function getEventosPorSede($sedeId)
    {
        return $this->where('venue_id', $sedeId)
                   ->orderBy('date', 'ASC')
                   ->findAll();
    }

    public function getEventoCompleto($eventoId)
    {
        return $this->select('events.*, venues.name as venue_name, venues.location, venues.capacity')
                   ->join('venues', 'venues.id = events.venue_id')
                   ->where('events.id', $eventoId)
                   ->first();
    }
}