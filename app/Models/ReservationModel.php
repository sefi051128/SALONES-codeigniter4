<?php
namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['customer_id', 'venue_id', 'reservation_date', 'status', 'event_id', 'items'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Agrega items a una reserva en la tabla reservation_items
     */
    public function addItems($reservationId, array $items)
    {
        $data = [];
        foreach ($items as $itemId => $quantity) {
            $data[] = [
                'reservation_id' => $reservationId,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return $this->db->table('reservation_items')->insertBatch($data);
    }

    /**
     * Obtiene los items de una reserva con sus detalles completos
     */
    public function getReservationItems($reservationId)
    {
        return $this->db->table('reservation_items')
                      ->select('inventory_items.*, reservation_items.quantity')
                      ->join('inventory_items', 'inventory_items.id = reservation_items.item_id')
                      ->where('reservation_id', $reservationId)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Obtiene una reserva con todos sus detalles relacionados
     */
    public function getReservationWithDetails($id)
    {
        $reservation = $this->select('reservations.*, venues.name as venue_name, events.name as event_name, users.username as customer_name')
                          ->join('venues', 'venues.id = reservations.venue_id', 'left')
                          ->join('events', 'events.id = reservations.event_id', 'left')
                          ->join('users', 'users.id = reservations.customer_id')
                          ->where('reservations.id', $id)
                          ->first();

        if ($reservation) {
            $reservation['items'] = $this->getReservationItems($id);
        }

        return $reservation;
    }

    /**
     * Obtiene reservas con detalles básicos del usuario
     */
    public function getReservationsWithUserDetails()
    {
        $builder = $this->select('reservations.*, users.username as customer_username, venues.name as venue_name')
                      ->join('users', 'users.id = reservations.customer_id')
                      ->join('venues', 'venues.id = reservations.venue_id', 'left')
                      ->orderBy('reservations.reservation_date', 'DESC');

        if (session('role') !== 'administrador') {
            $builder->where('reservations.customer_id', session('user_id'));
        }

        return $builder->findAll();
    }

    /**
     * Obtiene todas las reservas con detalles completos (para admin)
     */
    public function getAllReservationsWithDetails()
    {
        return $this->select('reservations.*, venues.name as venue_name, users.username as customer_name')
                  ->join('venues', 'venues.id = reservations.venue_id', 'left')
                  ->join('users', 'users.id = reservations.customer_id')
                  ->orderBy('reservations.reservation_date', 'DESC')
                  ->findAll();
    }

    /**
     * Obtiene las reservas de un usuario específico
     */
    public function getUserReservations($userId)
    {
        return $this->select('reservations.*, venues.name as venue_name')
                  ->join('venues', 'venues.id = reservations.venue_id', 'left')
                  ->where('reservations.customer_id', $userId)
                  ->orderBy('reservations.reservation_date', 'DESC')
                  ->findAll();
    }

    /**
     * Obtiene reservas asociadas a un evento
     */
    public function getEventReservations($eventId)
    {
        return $this->where('event_id', $eventId)->findAll();
    }
}