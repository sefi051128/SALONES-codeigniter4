<?php
use CodeIgniter\I18n\Time;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Reserva #<?= $reservation['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .item-image {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-calendar-check"></i> Detalles de Reserva #<?= $reservation['id'] ?>
            </h2>
            <a href="/reservas" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card detail-card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <?= esc($reservation['venue_name'] ?? 'Reserva de Sala') ?>
                    <span class="status-badge status-<?= $reservation['status'] ?> ms-2">
                        <?= ucfirst($reservation['status']) ?>
                    </span>
                </h5>
                <?php if(session('role') === 'administrador'): ?>
                    <div class="text-light">
                        <i class="fas fa-user"></i> <?= esc($reservation['customer_name'] ?? 'Usuario no disponible') ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong><i class="fas fa-calendar-day me-2"></i> Fecha y Hora:</strong>
                            <?= Time::parse($reservation['reservation_date'])->toLocalizedString('eeee d MMMM y, h:mm a') ?>
                        </div>
                        
                        <?php if (!empty($reservation['event_name'])): ?>
                            <div class="mb-3">
                                <strong><i class="fas fa-star me-2"></i> Evento:</strong>
                                <?= esc($reservation['event_name']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong><i class="fas fa-user me-2"></i> Cliente:</strong>
                            <?= esc($reservation['customer_name'] ?? 'Usuario no disponible') ?>
                        </div>
                        <div class="mb-3">
                            <strong><i class="fas fa-clock me-2"></i> Fecha de Creación:</strong>
                            <?= Time::parse($reservation['created_at'])->toLocalizedString('eeee d MMMM y') ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($reservation['items'])): ?>
                    <hr>
                    <h5 class="mb-3"><i class="fas fa-boxes me-2"></i> Artículos Reservados</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Ubicación</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservation['items'] as $item): ?>
                                <tr>
                                    <td><?= esc($item['code'] ?? 'N/A') ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if(!empty($item['imagen'])): ?>
                                                <img src="/uploads/<?= esc($item['imagen']) ?>" 
                                                     alt="<?= esc($item['name'] ?? 'Artículo') ?>" 
                                                     class="item-image me-2">
                                            <?php endif; ?>
                                            <span><?= esc($item['name'] ?? 'N/A') ?></span>
                                        </div>
                                    </td>
                                    <td><?= esc($item['location'] ?? 'N/A') ?></td>
                                    <td><?= $item['quantity'] ?? 1 ?></td>
                                    <td>
                                        <span class="badge bg-<?= ($item['status'] ?? '') === 'disponible' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($item['status'] ?? 'desconocido') ?>
                                        </span>
                                    </td>
                                    <td><?= esc($item['current_responsible'] ?? 'N/A') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i> No se han seleccionado artículos para esta reserva.
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-end">
                    <?php if (session('role') === 'administrador' || ($reservation['status'] == 'pending' || $reservation['status'] == 'confirmed')): ?>
                        <?php if(session('role') === 'administrador' && $reservation['status'] == 'pending'): ?>
                            <a href="/reservas/confirmar/<?= $reservation['id'] ?>" class="btn btn-success me-2">
                                <i class="fas fa-check me-1"></i> Confirmar Reserva
                            </a>
                        <?php endif; ?>
                        <a href="/reservas/cancelar/<?= $reservation['id'] ?>" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i> Cancelar Reserva
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>