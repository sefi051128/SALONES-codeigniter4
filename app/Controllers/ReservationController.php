<?php 
namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\ReservationItemModel;
use App\Models\InventoryItemModel;

class ReservationController extends BaseController
{
    /**
     * Procesa la creación de una reserva con ítems
     */
    public function storeWithItems()
{
    $data = $this->request->getPost();

    // Validación básica
    $rules = [
        'event_id' => 'required|numeric',
        'customer_id' => 'required|numeric',
        'number_of_guests' => 'required|numeric|greater_than[0]',
        'reservation_date' => 'required|valid_date',
        'items' => 'permit_empty|is_array'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Verificar disponibilidad de ítems
    $inventoryModel = model('InventoryModel');
    $items = $data['items'] ?? [];

    if (!empty($items) && !$inventoryModel->areItemsAvailable($items)) {
        return redirect()->back()->with('error', 'Algunos ítems no están disponibles');
    }

    // Transacción
    db()->transStart();

    try {
        // 1. Crear reserva principal
        $reservationData = [
            'customer_id' => $data['customer_id'],
            'event_id' => $data['event_id'],
            'number_of_guests' => $data['number_of_guests'],
            'reservation_date' => $data['reservation_date'],
            'status' => $data['status'] ?? 'confirmed'
        ];

        $reservationId = model('ReservationModel')->insert($reservationData);

        // 2. Asociar ítems a la reserva
        if (!empty($items)) {
            $reservationItems = [];
            
            foreach ($items as $itemId) {
                $reservationItems[] = [
                    'reservation_id' => $reservationId,
                    'item_id' => $itemId
                ];
            }
            
            model('ReservationItemModel')->insertBatch($reservationItems);
            
            // 3. Actualizar estado de los ítems
            $inventoryModel->updateItemsStatus($items, 'reservado');
        }

        db()->transComplete();

        return redirect()->to('/reservas')->with('success', 'Reserva creada con éxito');

    } catch (\Exception $e) {
        db()->transRollback();
        log_message('error', '[Reserva] Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al procesar la reserva');
    }
}

    
}