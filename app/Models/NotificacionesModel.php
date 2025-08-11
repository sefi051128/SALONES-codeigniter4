<?php
namespace App\Models;

use CodeIgniter\Model;

class NotificacionesModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'notification_type', 'message', 'sent_date', 'read_at'];
    protected $useTimestamps = false;
    
    /**
     * Crea una nueva notificación
     * 
     * @param int $userId ID del usuario
     * @param string $type Tipo de notificación (Urgente, Importante, Informativa, Recordatorio)
     * @param string $message Mensaje de la notificación
     * @return bool|int ID de la notificación creada o false en caso de error
     */
    public function crearNotificacion($userId, $type, $message)
    {
        $data = [
            'user_id' => $userId,
            'notification_type' => $type,
            'message' => $message,
            'sent_date' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
}