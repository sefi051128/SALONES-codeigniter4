<?php
use CodeIgniter\I18n\Time;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .badge-status {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .badge-confirmed { background-color: #198754; color: white; }
        .badge-cancelled { background-color: #dc3545; color: white; }
        .badge-pending { background-color: #ffc107; color: black; }
        .reservation-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            height: 100%;
        }
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .reservation-header {
            background-color: #4a6fa5;
            color: white;
            padding: 15px;
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid #4a6fa5;
        }
        .action-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .btn-new-reservation {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-calendar-check"></i> <?= esc($title) ?></h1>
            <div>
                <a href="<?= base_url('/sedes') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Atrás
                </a>
                <?php if (session('role') === 'administrador' || session('role') === 'cliente'): ?>
                    <a href="<?= base_url('reservas/reservation/nueva') ?>" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nueva Reserva Artículos
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pestañas para tipos de reserva -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#eventBookings">Reservas de Eventos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#articleReservations">Reservas de Artículos</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Reservas de Eventos -->
            <div class="tab-pane fade show active" id="eventBookings">
                <!-- En la sección de Reservas de Eventos, reemplaza el botón con: -->
<?php if (session('role') === 'administrador' || session('role') === 'cliente'): ?>
    <div class="text-end mb-3">
        <a href="<?= base_url('reservas/crearReserva') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nueva Reserva de Evento
        </a>
        <a href="<?= route_to('devoluciones.index') ?>" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Ver devoluciones
</a>
    </div>
<?php endif; ?>

                <?php if (empty($eventBookings)): ?>
                    <div class="alert alert-info">No hay reservas de eventos registradas</div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($eventBookings as $booking): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card reservation-card">
                                    <div class="reservation-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5><?= esc($booking['event_name'] ?? 'Evento sin nombre') ?></h5>
                                            <span class="badge-status badge-<?= $booking['status'] ?>">
                                                <?= ucfirst($booking['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><i class="fas fa-user"></i> <strong>Cliente:</strong> <?= esc($booking['customer_name'] ?? 'N/A') ?></p>
                                                <p><i class="fas fa-users"></i> <strong>Invitados:</strong> <?= $booking['number_of_guests'] ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><i class="fas fa-calendar-day"></i> <strong>Evento:</strong> <?= Time::parse($booking['event_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                                <p><i class="fas fa-clock"></i> <strong>Reserva:</strong> <?= Time::parse($booking['booking_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer action-buttons">
    <a href="<?= route_to('reservas.ver', $booking['id'], 'booking') ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-eye"></i> Ver
    </a>

    <?php if ((session('role') === 'administrador' || session('user_id') == $booking['user_id']) && $booking['status'] != 'cancelled'): ?>
        <a href="<?= route_to('reservas.editar_booking', $booking['id']) ?>" class="btn btn-sm btn-warning">
            <i class="fas fa-edit"></i> Editar
        </a>

        <form action="<?= route_to('reservas.cancelar_booking', $booking['id']) ?>" method="post" style="display:inline;">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                <i class="fas fa-times"></i> Cancelar
            </button>
        </form>
    <?php endif; ?>

    <?php if (session('role') === 'administrador'): ?>
        <form action="<?= route_to('reservas.eliminar_booking', $booking['id']) ?>" method="post" style="display:inline;">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('¿Estás seguro de eliminar permanentemente esta reserva?')">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </form>
    <?php endif; ?>
</div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Reservas de Artículos -->
<div class="tab-pane fade" id="articleReservations">
    <?php if (empty($articleReservations)): ?>
        <div class="alert alert-info">No hay reservas de artículos registradas</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($articleReservations as $reservation): ?>
                <div class="col-md-6 mb-4">
                    <div class="card reservation-card">
                        <div class="reservation-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Reserva #<?= $reservation['id'] ?></h5>
                                <span class="badge-status badge-<?= $reservation['status'] ?>">
                                    <?= ucfirst($reservation['status']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><i class="fas fa-user"></i> <strong>Cliente:</strong> <?= esc($reservation['customer_name'] ?? 'N/A') ?></p>
                                    <p><i class="fas fa-calendar-day"></i> <strong>Fecha:</strong> <?= Time::parse($reservation['reservation_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><i class="fas fa-boxes"></i> <strong>Artículos:</strong> <?= $reservation['items'] ? count(json_decode($reservation['items'])) : 0 ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <strong>Sede:</strong> <?= esc($reservation['venue_name'] ?? 'N/A') ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer action-buttons">
                            <a href="<?= route_to('reservas.ver', $reservation['id'], 'reservation') ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            
                            <?php if ((session('role') === 'administrador' || session('user_id') == $reservation['customer_id']) && $reservation['status'] != 'cancelled'): ?>
                                <a href="<?= route_to('reservas.editar_reservation', $reservation['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                
                                <form action="<?= route_to('reservas.cancelar_reservation', $reservation['id']) ?>" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <?php if (session('role') === 'administrador'): ?>
                                <form action="<?= route_to('reservas.eliminar_reservation', $reservation['id']) ?>" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('¿Estás seguro de eliminar permanentemente esta reserva?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirmación antes de acciones importantes
        function confirmAction(message) {
            return confirm(message || '¿Estás seguro de realizar esta acción?');
        }
    </script>
</body>
</html>