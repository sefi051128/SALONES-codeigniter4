<?php
namespace App\Models;

use CodeIgniter\Model;

class ReportesModel extends Model
{
    protected $table = 'incident_reports';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'event_id', 'item_id', 'incident_type', 'description', 'photo'
    ];
    protected $useTimestamps = false; // BD gestiona report_date automáticamente
}
