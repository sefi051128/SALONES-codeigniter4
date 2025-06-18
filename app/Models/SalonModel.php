<?php

namespace App\Models;

use CodeIgniter\Model;

class SalonModel extends Model
{
    protected $table = 'salones';          // Nombre de la tabla
    protected $primaryKey = 'id';          // Clave primaria
    protected $allowedFields = ['nombre', 'capacidad']; // Campos editables
}
