<?php
namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'name', 'location', 'status', 'current_responsible', 'imagen', 'descripcion'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

      // Definir constantes para los estados
    const STATUS_AVAILABLE = 'Disponible';
    const STATUS_IN_USE = 'En uso';
    const STATUS_IN_REPAIR = 'En reparación';
    const STATUS_BORROWED = 'Prestado';
    
    protected $validationRules = [
        'code' => 'required|is_unique[inventory_items.code,id,{id}]',
        'name' => 'required|min_length[3]',
        'location' => 'required',
        'status' => 'required|in_list[Disponible,En uso,En reparación,Prestado]'
    ];

    public function getValidationRules(array $options = []): array
    {
        $rules = $this->validationRules;
        
        if (isset($options['id'])) {
            $rules['code'] = str_replace('{id}', $options['id'], $rules['code']);
        }
        
        return $rules;
    }

    /**
     * Verifica si los ítems están disponibles para reserva
     */
    public function areItemsAvailable(array $itemIds): bool
    {
        if (empty($itemIds)) return true;
        
        $count = $this->whereIn('id', $itemIds)
                     ->where('status', 'disponible')
                     ->countAllResults();
                     
        return $count === count($itemIds);
    }

    /**
     * Actualiza el estado de múltiples ítems (ej: al reservar)
     */
    public function updateItemsStatus(array $itemIds, string $status)
    {
        return $this->whereIn('id', $itemIds)
                   ->set(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')])
                   ->update();
    }

      // Método para obtener solo items disponibles
    public function getAvailableItems()
    {
        return $this->where('status', self::STATUS_AVAILABLE)->findAll();
    }
}