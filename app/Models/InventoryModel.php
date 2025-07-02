<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'name', 'location', 'status', 'current_responsible'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'code' => 'required|is_unique[inventory_items.code]',
        'name' => 'required|min_length[3]',
        'location' => 'required',
        'status' => 'required'
    ];

}