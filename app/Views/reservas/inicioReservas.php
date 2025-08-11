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
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        @media (max-width: 768px) {
            .header-buttons {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start !important;
            }
            .header-buttons .btn {
                width: 100%;
            }
            .reservation-details {
                flex-direction: column;
            }
            .reservation-details > div {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <div class="container py-3 py-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h1 class="mb-3 mb-md-0"><i class="fas fa-calendar-check me-2"></i><?= esc($title) ?></h1>
            <div class="header-buttons d-flex flex-wrap gap-2">
                <a href="<?= base_url('/sedes') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Atrás
                </a>

                <!-- Botón directo para resrvar artículos
                <?php if (session('role') === 'administrador' || session('role') === 'cliente'): ?>
                    <a href="<?= base_url('reservas/reservation/nueva') ?>" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Nueva Reserva Artículos
                    </a>
                <?php endif; ?>
                -->

            </div>
        </div>

        <!-- Pestañas para tipos de reserva -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#eventBookings">Reservas de Eventos</a>
            </li>

            <!-- Botón para ver reservas de artículos - implementación futura
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#articleReservations">Reservas de Artículos</a>
            </li>
            -->
            
        </ul>

        <div class="tab-content">
            <!-- Reservas de Eventos -->
            <div class="tab-pane fade show active" id="eventBookings">
                <?php if (session('role') === 'administrador' || session('role') === 'cliente'): ?>
                    <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2 mb-3">
                        <a href="<?= route_to('devoluciones.index') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Ver devoluciones
                        </a>
                        <a href="<?= base_url('reservas/crearReserva') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Crear Nueva Reserva de Evento
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (empty($eventBookings)): ?>
                    <div class="alert alert-info">No hay reservas de eventos registradas</div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach ($eventBookings as $booking): ?>
                            <div class="col">
                                <div class="card reservation-card h-100">
                                    <div class="reservation-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><?= esc($booking['event_name'] ?? 'Evento sin nombre') ?></h5>
                                            <span class="badge-status badge-<?= $booking['status'] ?>">
                                                <?= ucfirst($booking['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row reservation-details">
                                            <div class="col-md-6">
                                                <p class="mb-2"><i class="fas fa-user me-1"></i> <strong>Cliente:</strong> 
    <?= (session('user_id') == $booking['user_id']) 
        ? esc(session('username')) 
        : esc($booking['customer_name'] ?? 'N/A') ?>
</p>
                                                <p class="mb-0"><i class="fas fa-users me-1"></i> <strong>Invitados:</strong> <?= $booking['number_of_guests'] ?></p>
                                            </div>
                                            <div class="col-md-6 mt-2 mt-md-0">
                                                <p class="mb-2"><i class="fas fa-calendar-day me-1"></i> <strong>Evento:</strong> <?= Time::parse($booking['event_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                                <p class="mb-0"><i class="fas fa-clock me-1"></i> <strong>Reserva:</strong> <?= Time::parse($booking['booking_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer action-buttons">
                                        <a href="<?= route_to('reservas.ver', $booking['id'], 'booking') ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">Ver</span>
                                        </a>

                                        <?php if ((session('role') === 'administrador' || session('user_id') == $booking['user_id']) && $booking['status'] != 'cancelled'): ?>
                                            <a href="<?= route_to('reservas.editar_booking', $booking['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Editar</span>
                                            </a>

                                            <form action="<?= route_to('reservas.cancelar_booking', $booking['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                                    <i class="fas fa-times"></i> <span class="d-none d-sm-inline">Cancelar</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if (session('role') === 'administrador'): ?>
                                            <form action="<?= route_to('reservas.eliminar_booking', $booking['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('¿Estás seguro de eliminar permanentemente esta reserva?')">
                                                    <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Eliminar</span>
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
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach ($articleReservations as $reservation): ?>
                            <div class="col">
                                <div class="card reservation-card h-100">
                                    <div class="reservation-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Reserva #<?= $reservation['id'] ?></h5>
                                            <span class="badge-status badge-<?= $reservation['status'] ?>">
                                                <?= ucfirst($reservation['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row reservation-details">
                                            <div class="col-md-6">
                                                <p class="mb-2"><i class="fas fa-user me-1"></i> <strong>Cliente:</strong> <?= esc($reservation['customer_name'] ?? 'N/A') ?></p>
                                                <p class="mb-0"><i class="fas fa-calendar-day me-1"></i> <strong>Fecha:</strong> <?= Time::parse($reservation['reservation_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                            </div>
                                            <div class="col-md-6 mt-2 mt-md-0">
                                                <p class="mb-2"><i class="fas fa-boxes me-1"></i> <strong>Artículos:</strong> <?= $reservation['items'] ? count(json_decode($reservation['items'])) : 0 ?></p>
                                                <p class="mb-0"><i class="fas fa-map-marker-alt me-1"></i> <strong>Sede:</strong> <?= esc($reservation['venue_name'] ?? 'N/A') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer action-buttons">
                                        <a href="<?= route_to('reservas.ver', $reservation['id'], 'reservation') ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">Ver</span>
                                        </a>
                                        
                                        <?php if ((session('role') === 'administrador' || session('user_id') == $reservation['customer_id']) && $reservation['status'] != 'cancelled'): ?>
                                            <a href="<?= route_to('reservas.editar_reservation', $reservation['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Editar</span>
                                            </a>
                                            
                                            <form action="<?= route_to('reservas.cancelar_reservation', $reservation['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                                    <i class="fas fa-times"></i> <span class="d-none d-sm-inline">Cancelar</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if (session('role') === 'administrador'): ?>
                                            <form action="<?= route_to('reservas.eliminar_reservation', $reservation['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-sm btn-dark" onclick="return confirm('¿Estás seguro de eliminar permanentemente esta reserva?')">
                                                    <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Eliminar</span>
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
        </div>
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