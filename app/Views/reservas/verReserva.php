<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .item-img {
            height: 100px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .item-card {
            transition: all 0.3s ease;
        }
        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .badge-status {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .badge-confirmed { background-color: #198754; color: white; }
        .badge-cancelled { background-color: #dc3545; color: white; }
        .badge-pending { background-color: #ffc107; color: black; }
    </style>
</head>
<body>
    <?php 
    use CodeIgniter\I18n\Time;
    
    // Determinar el tipo de items
    $itemsMostrar = [];
    $esBookingConItems = ($type === 'booking' && !empty($itemsReservados) && is_array($itemsReservados) && isset($itemsReservados[0]['id']));
    ?>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header <?= $type === 'booking' ? 'bg-primary' : 'bg-success' ?> text-white">
                        <h3 class="mb-0">
                            <i class="<?= $type === 'booking' ? 'fas fa-calendar-check' : 'fas fa-boxes' ?>"></i> 
                            Detalles de Reserva #<?= $reservation['id'] ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if ($type === 'booking'): ?>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Información del Evento</h5>
                                    <p><strong>Nombre:</strong> <?= esc($reservation['event_name'] ?? 'N/A') ?></p>
                                    <p><strong>Fecha:</strong> <?= isset($reservation['event_date']) ? Time::parse($reservation['event_date'])->toLocalizedString('MMM d, yyyy h:mm a') : 'N/A' ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Información de la Reserva</h5>
                                    <p><strong>Cliente:</strong> <?= esc($reservation['customer_name'] ?? 'N/A') ?></p>
                                    <p><strong>Invitados:</strong> <?= $reservation['number_of_guests'] ?? '0' ?></p>
                                    <p><strong>Fecha Reserva:</strong> <?= Time::parse($reservation['booking_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Información General</h5>
                                    <p><strong>Cliente:</strong> <?= esc($reservation['customer_name'] ?? 'N/A') ?></p>
                                    <p><strong>Fecha Reserva:</strong> <?= Time::parse($reservation['reservation_date'])->toLocalizedString('MMM d, yyyy h:mm a') ?></p>
                                    <p><strong>Sede:</strong> <?= esc($reservation['venue_name'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Estado y Notas</h5>
                                    <p>
                                        <strong>Estado:</strong> 
                                        <span class="badge-status badge-<?= $reservation['status'] ?>">
                                            <?= ucfirst($reservation['status']) ?>
                                        </span>
                                    </p>
                                    <?php if(!empty($reservation['notes'])): ?>
                                        <p><strong>Notas:</strong> <?= esc($reservation['notes']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Sección de Artículos Reservados -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-box-open me-2"></i>Artículos Reservados
                                <span class="badge bg-secondary">
                                    <?= $esBookingConItems ? count($itemsReservados) : count($itemsReservados ?? []) ?>
                                </span>
                            </h5>
                            
                            <?php if(!empty($itemsReservados)): ?>
                                <div class="row row-cols-1 row-cols-md-2 g-3">
                                    <?php if ($esBookingConItems): ?>
                                        <!-- Mostrar items directos (para bookings) -->
                                        <?php foreach($itemsReservados as $item): ?>
                                            <div class="col">
                                                <div class="card item-card h-100">
                                                    <div class="row g-0">
                                                        <div class="col-md-4 d-flex align-items-center justify-content-center p-2">
                                                            <?php if(!empty($item['imagen'])): ?>
                                                                <img src="<?= esc($item['imagen']) ?>" class="img-fluid rounded-start item-img" alt="<?= esc($item['name']) ?>">
                                                            <?php else: ?>
                                                                <div class="item-img d-flex align-items-center justify-content-center bg-light w-100">
                                                                    <i class="fas fa-box-open fa-3x text-secondary"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <h6 class="card-title"><?= esc($item['name']) ?></h6>
                                                                <div class="d-flex justify-content-between">
                                                                    <p class="card-text small text-muted mb-1">
                                                                        <i class="fas fa-barcode me-1"></i><?= esc($item['code']) ?>
                                                                    </p>
                                                                    <span class="badge bg-<?= $item['status'] === 'Disponible' ? 'success' : 'warning' ?>">
                                                                        <?= esc($item['status']) ?>
                                                                    </span>
                                                                </div>
                                                                <?php if (!empty($item['location'])): ?>
    <p class="card-text small text-muted mb-1">
        <i class="fas fa-map-marker-alt me-1"></i><?= esc($item['location']) ?>
    </p>
<?php endif; ?>

                                                                <p class="card-text small">
                                                                    <i class="fas fa-info-circle me-1"></i><?= esc($item['descripcion'] ?? 'Sin descripción') ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Mostrar items por ID (para reservations) -->
                                        <?php foreach($itemsReservados as $itemId): 
                                            $item = $inventoryModel->find($itemId);
                                            if($item): ?>
                                                <div class="col">
                                                    <div class="card item-card h-100">
                                                        <div class="row g-0">
                                                            <div class="col-md-4 d-flex align-items-center justify-content-center p-2">
                                                                <?php if(!empty($item['imagen'])): ?>
                                                                    <img src="<?= esc($item['imagen']) ?>" class="img-fluid rounded-start item-img" alt="<?= esc($item['name']) ?>">
                                                                <?php else: ?>
                                                                    <div class="item-img d-flex align-items-center justify-content-center bg-light w-100">
                                                                        <i class="fas fa-box-open fa-3x text-secondary"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="card-body">
                                                                    <h6 class="card-title"><?= esc($item['name']) ?></h6>
                                                                    <div class="d-flex justify-content-between">
                                                                        <p class="card-text small text-muted mb-1">
                                                                            <i class="fas fa-barcode me-1"></i><?= esc($item['code']) ?>
                                                                        </p>
                                                                        <?php if(isset($cantidades[$itemId])): ?>
                                                                            <span class="badge bg-primary">
                                                                                Cantidad: <?= $cantidades[$itemId] ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <p class="card-text small text-muted mb-1">
                                                                        <i class="fas fa-map-marker-alt me-1"></i><?= esc($item['location']) ?>
                                                                    </p>
                                                                    <p class="card-text small">
                                                                        <i class="fas fa-info-circle me-1"></i><?= esc($item['descripcion'] ?? 'Sin descripción') ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>No se registraron artículos en esta reserva.
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="badge rounded-pill bg-<?= 
                                $reservation['status'] === 'confirmed' ? 'success' : 
                                ($reservation['status'] === 'cancelled' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($reservation['status']) ?>
                            </span>
                            
                            <div>
                                <a href="<?= route_to('reservas.index') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                 <a href="<?= route_to('devoluciones.cliente_form', $booking['id'] ?? $reservation['id']) ?>" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Terminar
</a>


                                <?php if ($type === 'booking' && !empty($itemsReservados) && session('role') === 'administrador'): ?>
                                    <a href="<?= route_to('reservas.liberarItems', $reservation['id']) ?>" class="btn btn-warning ms-2">
                                        <i class="fas fa-unlock"></i> Liberar Artículos
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>