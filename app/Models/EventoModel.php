<?php
namespace App\Models;

use CodeIgniter\Model;

class EventoModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $allowedFields = ['venue_id', 'name', 'date', 'description'];
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