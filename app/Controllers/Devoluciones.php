<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ChecklistModel;
use App\Models\BookingModel;
use App\Models\EventoModel;
use App\Models\InventoryModel;
use App\Models\IncidentReportModel;

class Devoluciones extends BaseController
{
    protected $checklistModel;
    protected $bookingModel;
    protected $eventoModel;
    protected $inventoryModel;
    protected $incidentReportModel;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->checklistModel      = model(ChecklistModel::class);
        $this->bookingModel        = model('BookingModel');
        $this->eventoModel         = model('EventoModel');
        $this->inventoryModel      = model('InventoryModel');
        $this->incidentReportModel = model(IncidentReportModel::class);
    }

    /**
     * Lista eventos que ya pasaron y están confirmados y que potencialmente requieren devolución.
     */
    public function index()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $allBookings = $this->bookingModel->getBookingsWithDetails();
        $pending = [];
        $now = time();

        foreach ($allBookings as $booking) {
            $eventDate = isset($booking['event_date']) ? strtotime($booking['event_date']) : 0;

            if ($booking['status'] === 'confirmed' && $eventDate <= $now) {
                $hasDevolucion = $this->checklistModel->existsForEventAndType($booking['event_id'], 'devolucion');
                if (!$hasDevolucion) {
                    $pending[] = $booking;
                }
            }
        }

        return view('devoluciones/inicioDevoluciones', [
            'title' => 'Devoluciones pendientes',
            'pendingBookings' => $pending
        ]);
    }

    /**
     * Mostrar formulario para crear checklist de devolución para un evento.
     */
    public function crear($eventId)
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $bookings = $this->bookingModel->where('event_id', $eventId)
                                       ->where('status', 'confirmed')
                                       ->findAll();
        if (empty($bookings)) {
            return redirect()->back()->with('error', 'No se encontró una reserva válida para este evento');
        }

        $booking = $bookings[0];
        $bookingDetails = $this->bookingModel->getBookingDetails($booking['id']);

        $itemsReservados = $this->inventoryModel
            ->where('booking_id', $booking['id'])
            ->findAll();

        return view('devoluciones/crearChecklist', [
            'title' => 'Checklist de Devolución',
            'booking' => $bookingDetails,
            'itemsReservados' => $itemsReservados,
        ]);
    }

    /**
     * Guarda el checklist genérico (entrega/devolución).
     */
    public function guardar()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $rules = [
            'event_id' => 'required|numeric',
            'checklist_type' => 'required|in_list[entrega,devolucion]',
            'signed_by' => 'required|string',
            'items' => 'required|is_array'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $eventId = $this->request->getPost('event_id');
        $type = $this->request->getPost('checklist_type');
        $signedBy = $this->request->getPost('signed_by');
        $items = $this->request->getPost('items');
        $photoUrl = $this->request->getPost('photo_url') ?: null;

        foreach ($items as $itemId) {
            $this->checklistModel->insert([
                'event_id' => $eventId,
                'item_id' => $itemId,
                'checklist_type' => $type,
                'signed_by' => $signedBy,
                'photo_url' => $photoUrl,
                'checklist_date' => date('Y-m-d H:i:s'),
            ]);

            if ($type === 'devolucion') {
                $this->inventoryModel->update($itemId, [
                    'booking_id' => null,
                    'status' => 'Disponible',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return redirect()->to(route_to('devoluciones.index'))->with('success', 'Checklist de devolución guardado correctamente');
    }

    /**
     * Ver checklists de un evento
     */
    public function ver($eventId)
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $checklists = $this->checklistModel->getByEvent($eventId);
        return view('devoluciones/verChecklist', [
            'title' => "Historial de Devolución del evento #{$eventId}",
            'checklists' => $checklists
        ]);
    }

    /**
     * Formulario de devolución desde la perspectiva del cliente.
     */
    public function clienteForm($bookingId)
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        $booking = $this->bookingModel->getBookingDetails($bookingId);
        if (!$booking) {
            return redirect()->to('/reservas')->with('error', 'Reserva no encontrada');
        }

        if (session('role') !== 'administrador' && $booking['user_id'] != session('user_id')) {
            return redirect()->to('/reservas')->with('error', 'No puedes hacer la devolución de esta reserva');
        }

        $itemsReservados = $this->inventoryModel
            ->where('booking_id', $bookingId)
            ->findAll();

        return view('devoluciones/clienteChecklist', [
            'title' => 'Devolución de Artículos',
            'booking' => $booking,
            'itemsReservados' => $itemsReservados,
        ]);
    }

    /**
     * Guardar checklist desde cliente con incidentes (daño/perdida), reseña y liberación.
     */
    public function guardarClienteChecklist()
{
    if (!session('isLoggedIn')) {
        return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
    }

    $rules = [
        'booking_id' => 'required|numeric',
        'signed_by' => 'required|string',
        'rating' => 'required|integer|greater_than[0]|less_than[6]',
        'items' => 'required|is_array',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Iniciar transacción para consistencia
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        $bookingId    = $this->request->getPost('booking_id');
        $eventId      = $this->request->getPost('event_id');
        $signedBy     = $this->request->getPost('signed_by');
        $rating       = $this->request->getPost('rating');
        $globalReview = $this->request->getPost('review') ?: null;
        $items        = $this->request->getPost('items');
        $checklistType = $this->request->getPost('checklist_type') ?? 'devolucion';

        // Obtener detalles completos de la reserva para la notificación
        $booking = $this->bookingModel->getBookingDetails($bookingId);
        if (!$booking) {
            throw new \Exception('Reserva no encontrada');
        }

        if (session('role') !== 'administrador' && $booking['user_id'] != session('user_id')) {
            throw new \Exception('No tienes permiso para realizar esta acción');
        }

        // Procesar cada ítem
        foreach ($items as $itemId) {
            $status = $this->request->getPost("status_{$itemId}") ?? 'devuelto';
            $comment = $this->request->getPost("comment_{$itemId}") ?: null;

            // Procesar foto si aplica
            $photoUrl = null;
            if (in_array($status, ['danio', 'perdida'])) {
                $fileKey = "photo_{$itemId}";
                $file = $this->request->getFile($fileKey);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = WRITEPATH . 'uploads/reportes/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $file->move($uploadPath, $newName);
                    $photoUrl = $newName;
                }
            }

            // Guardar checklist por ítem
            $this->checklistModel->insert([
                'event_id' => $eventId,
                'item_id' => $itemId,
                'checklist_type' => $checklistType,
                'signed_by' => $signedBy,
                'photo_url' => $photoUrl,
                'checklist_date' => date('Y-m-d H:i:s'),
                'review' => $comment,
            ]);

            // Crear reporte de incidente si aplica
            if (in_array($status, ['danio', 'perdida'])) {
                $this->incidentReportModel->insert([
                    'event_id' => $eventId,
                    'item_id' => $itemId,
                    'incident_type' => $status,
                    'description' => $comment,
                    'photo_url' => $photoUrl,
                    'report_date' => date('Y-m-d H:i:s'),
                ]);
            }

            // Actualizar inventario según estado
            $updateData = ['updated_at' => date('Y-m-d H:i:s')];
            
            if ($status === 'devuelto') {
                $updateData['booking_id'] = null;
                $updateData['status'] = 'Disponible';
            } elseif ($status === 'danio') {
                $updateData['booking_id'] = null;
                $updateData['status'] = 'Con daño';
            } elseif ($status === 'perdida') {
                $updateData['status'] = 'Perdido';
            }

            $this->inventoryModel->update($itemId, $updateData);
        }

        // Eliminar la reserva después de procesar todos los items
        $this->bookingModel->delete($bookingId);

        // Crear notificación de reserva finalizada
        $notificacionModel = model('NotificacionesModel');
        $notificacionModel->insert([
            'user_id' => $booking['user_id'],
            'notification_type' => 'Informativa',
            'message' => "Reserva #{$bookingId} finalizada - Evento: ".esc($booking['event_name']),
            'sent_date' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception('Ocurrió un error procesando la devolución.');
        }

        return redirect()->to(route_to('reservas.index'))->with('success', 'Devolución completada y reserva finalizada. Gracias por tu reseña.');

    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->withInput()->with('error', $e->getMessage());
    }
}
}
