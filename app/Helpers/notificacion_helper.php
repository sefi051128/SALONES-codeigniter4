<?php
if (!function_exists('crear_notificacion')) {
    /**
     * Crea una notificación en el sistema
     * 
     * @param int $userId ID del usuario destinatario
     * @param string $type Tipo de notificación
     * @param string $message Mensaje de la notificación
     * @return bool|int ID de la notificación o false en caso de error
     */
    function crear_notificacion($userId, $type, $message)
    {
        $notificacionModel = model('NotificacionesModel');
        return $notificacionModel->crearNotificacion($userId, $type, $message);
    }
}