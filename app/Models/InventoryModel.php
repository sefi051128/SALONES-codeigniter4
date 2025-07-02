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
    'code' => 'required|is_unique[inventory_items.code,id,{id}]',
    'name' => 'required|min_length[3]',
    'location' => 'required',
    'status' => 'required'
];

public function getValidationRules(array $options = []): array
{
    $rules = $this->validationRules;
    
    // Si estamos actualizando, ajustamos la regla para el código
    if (isset($options['id'])) {
        $rules['code'] = str_replace('{id}', $options['id'], $rules['code']);
    }
    
    return $rules;
}

}