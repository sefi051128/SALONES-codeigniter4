<?php

use App\Models\AlmacenModel;

if (!function_exists('log_almacen_access')) {
    /**
     * Registra una acción en warehouse_access usando tu modelo AlmacenModel.
     */
    function log_almacen_access(int $userId, string $action)
    {
        $model = new AlmacenModel();
        $model->insert([
            'user_id' => $userId,
            'access_type' => $action,
        ]);
    }
}
