<?php

namespace App\Models;

use CodeIgniter\Model;

class AlmacenModel extends Model
{
    protected $table = 'warehouse_access';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'access_type']; // access_time lo maneja la BD
    protected $returnType = 'array';
    public $useTimestamps = false;
}
