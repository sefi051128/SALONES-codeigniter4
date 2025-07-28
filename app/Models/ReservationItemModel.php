<?php 
namespace App\Models;

use CodeIgniter\Model;

class ReservationItemModel extends Model
{
    protected $table      = 'reservation_items';
    protected $primaryKey = ['reservation_id', 'item_id']; // Clave primaria compuesta

    protected $allowedFields = ['reservation_id', 'item_id', 'quantity'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';

    // Validación
    protected $validationRules = [
        'reservation_id' => 'required|numeric',
        'item_id'        => 'required|numeric',
        'quantity'       => 'permit_empty|numeric'
    ];

    /**
     * Obtiene todos los ítems de una reserva
     */
    public function getItemsByReservation($reservationId)
    {
        return $this->where('reservation_id', $reservationId)
                    ->join('inventory_items', 'inventory_items.id = reservation_items.item_id')
                    ->findAll();
    }

    /**
     * Verifica si los ítems están disponibles antes de reservar
     */
    public function checkItemsAvailability(array $itemIds)
    {
        $model = model('InventoryItemModel');
        return $model->whereIn('id', $itemIds)
                     ->where('status', 'disponible')
                     ->countAllResults() === count($itemIds);
    }

    /**
     * Obtiene los ítems con detalles completos
     */
    public function getFullItems($reservationId)
    {
        return $this->select('inventory_items.*, reservation_items.quantity')
                   ->join('inventory_items', 'inventory_items.id = reservation_items.item_id')
                   ->where('reservation_id', $reservationId)
                   ->findAll();
    }
}