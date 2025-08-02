<?php

namespace App\Models;

use CodeIgniter\Model;

class AlertasModel extends Model
{
    protected $table = 'inventory_alerts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['item_id', 'alert_type', 'alert_date', 'resolved'];
}
