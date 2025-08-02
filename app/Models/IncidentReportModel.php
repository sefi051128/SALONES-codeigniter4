<?php
namespace App\Models;

use CodeIgniter\Model;

class IncidentReportModel extends Model
{
    protected $table = 'incident_reports';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'event_id',
        'item_id',
        'incident_type',
        'description',
        'photo_url',   // Nueva columna para guardar la ruta de la foto
        'report_date'
    ];
    protected $returnType = 'array';
    public $useTimestamps = false;
}
