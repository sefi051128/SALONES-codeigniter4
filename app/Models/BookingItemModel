<?php
namespace App\Models;

use CodeIgniter\Model;

class BookingItemModel extends Model
{
    protected $table = 'booking_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['booking_id', 'item_id', 'quantity'];
    protected $returnType = 'array';

    public function getItemsByBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)->findAll();
    }
}