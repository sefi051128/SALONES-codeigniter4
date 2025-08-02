<?php
namespace App\Models;

use CodeIgniter\Model;

class NotificacionesModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'notification_type', 'message', 'sent_date'];
    protected $useTimestamps = false;
}
