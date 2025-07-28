<?php
namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['customer_id', 'venue_id', 'item_id', 'event_id', 'items', 'reservation_date', 'status'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Obtener reservas de artículos con detalles
    public function getReservationsWithDetails()
    {
        return $this->select('reservations.*, users.username as customer_name, venues.name as venue_name')
            ->join('users', 'users.id = reservations.customer_id')
            ->join('venues', 'venues.id = reservations.venue_id', 'left')
            ->orderBy('reservation_date', 'DESC')
            ->findAll();
    }

    // Reservas de artículos de un usuario específico
    public function getUserReservations($userId)
    {
        return $this->select('reservations.*, venues.name as venue_name')
            ->join('venues', 'venues.id = reservations.venue_id', 'left')
            ->where('customer_id', $userId)
            ->orderBy('reservation_date', 'DESC')
            ->findAll();
    }

    // Obtener detalles de una reserva de artículo
    public function getReservationDetails($id)
    {
        return $this->select('reservations.*, users.username as customer_name, users.email, venues.name as venue_name')
            ->join('users', 'users.id = reservations.customer_id')
            ->join('venues', 'venues.id = reservations.venue_id', 'left')
            ->where('reservations.id', $id)
            ->first();
    }
}