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
    protected $afterInsert = ['saveBookingItems'];

    // Instancia perezosa
    protected function getBookingItemModel()
    {
        try {
            return model(BookingItemModel::class);
        } catch (\Exception $e) {
            log_message('error', 'Error al cargar BookingItemModel: ' . $e->getMessage());
            return null;
        }
    }

    public function checkAvailability($eventId, $numberOfGuests = 1)
    {
        $event = model(EventoModel::class)->getEventForBooking($eventId);

        if (!$event) {
            log_message('error', "Evento {$eventId} no encontrado");
            return ['available' => false, 'message' => 'Evento no encontrado'];
        }

        if ($event['status'] !== 'activo') {
            log_message('info', "Evento {$eventId} no está activo");
            return ['available' => false, 'message' => 'Evento no disponible'];
        }

        if (strtotime($event['date']) < time()) {
            log_message('info', "Evento {$eventId} ha expirado");
            return ['available' => false, 'message' => 'Este evento ya ha ocurrido'];
        }

        if (!isset($event['capacity'])) {
            return ['available' => true, 'message' => ''];
        }

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

    public function getBookingsWithDetails()
    {
        return $this->select('bookings.*, users.username as customer_name, events.name as event_name, events.date as event_date')
                    ->join('users', 'users.id = bookings.user_id')
                    ->join('events', 'events.id = bookings.event_id')
                    ->orderBy('booking_date', 'DESC')
                    ->findAll();
    }

    public function getBookingsWithItems(array $filters = [])
{
    $builder = $this->db->table('bookings b')
        ->select('b.*, GROUP_CONCAT(i.name SEPARATOR ", ") as items')
        ->join('inventory_items i', 'i.booking_id = b.id', 'left')
        ->groupBy('b.id');

    // Filtros
    if (!empty($filters['event_id'])) {
        $builder->where('b.event_id', $filters['event_id']);
    }
    if (!empty($filters['status'])) {
        $builder->where('b.status', $filters['status']);
    }

    return $builder->get()->getResultArray();
}

    public function getUserBookings($userId)
    {
        $bookings = $this->select('bookings.*, events.name as event_name, events.date as event_date')
                         ->join('events', 'events.id = bookings.event_id')
                         ->where('bookings.user_id', $userId)
                         ->orderBy('booking_date', 'DESC')
                         ->findAll();

        $bookingItemModel = $this->getBookingItemModel();

        foreach ($bookings as &$booking) {
            $booking['items'] = $bookingItemModel
                ? $bookingItemModel->getItemsByBooking($booking['id'])
                : [];
        }

        return $bookings;
    }

    public function getBookingDetails($id)
    {
        $booking = $this->select('bookings.*, users.username as customer_name, users.email, events.name as event_name, events.date as event_date')
                        ->join('users', 'users.id = bookings.user_id')
                        ->join('events', 'events.id = bookings.event_id')
                        ->where('bookings.id', $id)
                        ->first();

        $bookingItemModel = $this->getBookingItemModel();

        if ($booking && $bookingItemModel) {
            $booking['items'] = $bookingItemModel->getItemsByBooking($id);
        }

        return $booking;
    }

    protected function saveBookingItems(array $data)
    {
        $bookingItemModel = $this->getBookingItemModel();
        if (!$bookingItemModel) {
            log_message('error', 'No se pudo cargar BookingItemModel en afterInsert');
            return $data;
        }

        if (isset($data['data']['items']) && !empty($data['data']['items'])) {
            foreach ($data['data']['items'] as $itemId) {
                $bookingItemModel->insert([
                    'booking_id' => $data['id'],
                    'item_id' => $itemId,
                    'quantity' => 1
                ]);

                model('InventoryModel')->update($itemId, ['status' => 'Reservado']);
            }
        }
        return $data;
    }

    public function deleteBookingItems($bookingId)
    {
        $bookingItemModel = $this->getBookingItemModel();
        if (!$bookingItemModel) {
            log_message('error', 'No se pudo cargar BookingItemModel en deleteBookingItems');
            return false;
        }

        $items = $bookingItemModel->where('booking_id', $bookingId)->findAll();

        foreach ($items as $item) {
            model('InventoryModel')->update($item['item_id'], ['status' => 'Disponible']);
        }

        return $bookingItemModel->where('booking_id', $bookingId)->delete();
    }

    public function delete($id = null, bool $purge = false)
    {
        $this->deleteBookingItems($id);
        return parent::delete($id, $purge);
    }

    public function updateBookingItems($bookingId, $newItems)
    {
        $bookingItemModel = $this->getBookingItemModel();
        if (!$bookingItemModel) {
            log_message('error', 'No se pudo cargar BookingItemModel en updateBookingItems');
            return;
        }

        $currentItems = $bookingItemModel->where('booking_id', $bookingId)->findAll();
        $currentItemIds = array_column($currentItems, 'item_id');

        $itemsToRemove = array_diff($currentItemIds, $newItems);
        if (!empty($itemsToRemove)) {
            $bookingItemModel->where('booking_id', $bookingId)
                             ->whereIn('item_id', $itemsToRemove)
                             ->delete();

            model('InventoryModel')->whereIn('id', $itemsToRemove)
                                    ->set(['status' => 'Disponible'])
                                    ->update();
        }

        $itemsToAdd = array_diff($newItems, $currentItemIds);
        foreach ($itemsToAdd as $itemId) {
            $bookingItemModel->insert([
                'booking_id' => $bookingId,
                'item_id' => $itemId,
                'quantity' => 1
            ]);

            model('InventoryModel')->update($itemId, ['status' => 'Reservado']);
        }
    }

    // Agrega estos métodos al BookingModel



public function getBookingWithItems($bookingId)
{
    return $this->db->table('bookings b')
        ->select('b.*, i.id as item_id, i.name as item_name, i.code as item_code')
        ->join('inventory_items i', 'i.booking_id = b.id', 'left')
        ->where('b.id', $bookingId)
        ->get()
        ->getResultArray();
}
}
