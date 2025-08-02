<?php
namespace App\Models;

use CodeIgniter\Model;

class ChecklistModel extends Model
{
    protected $table = 'checklists';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'event_id',
        'item_id',
        'checklist_type', // 'entrega' o 'devolucion'
        'signed_by',
        'photo_url',
        'checklist_date',
        'review', // nueva columna
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getByEvent($eventId, $type = null)
    {
        $builder = $this->where('event_id', $eventId);
        if ($type) {
            $builder->where('checklist_type', $type);
        }
        return $builder->findAll();
    }

    public function existsForEventAndType($eventId, $type)
    {
        return $this->where('event_id', $eventId)
                    ->where('checklist_type', $type)
                    ->first() !== null;
    }

    /**
     * Opcional: obtener reseñas de un evento (todas las devoluciones)
     */
    public function getReviewsByEvent($eventId)
    {
        return $this->where('event_id', $eventId)
                    ->where('review IS NOT NULL')
                    ->where('review !=', '')
                    ->findAll();
    }
}
