<?php use CodeIgniter\I18n\Time; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-box-open text-primary me-2"></i><?= esc($title) ?></h1>
            <a href="<?= base_url('/reservas') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver a Reservas
            </a>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (empty($pendingBookings)): ?>
            <div class="alert alert-info">No hay devoluciones pendientes.</div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach($pendingBookings as $booking): ?>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($booking['event_name'] ?? 'Evento') ?></h5>
                                <p class="mb-1"><i class="fas fa-user me-1"></i> Cliente: <?= esc($booking['customer_name'] ?? 'N/A') ?></p>
                                <p class="mb-1"><i class="fas fa-calendar-day me-1"></i> Fecha del evento: <?= Time::parse($booking['event_date'])->toLocalizedString('dd/MM/yyyy h:mm a') ?></p>
                                <p class="mb-1"><i class="fas fa-users me-1"></i> Invitados: <?= esc($booking['number_of_guests']) ?></p>
                                <div class="mt-3">
                                    <a href="<?= route_to('devoluciones.crear', $booking['event_id']) ?>" class="btn btn-primary">
                                        <i class="fas fa-clipboard-check me-1"></i> Hacer devolución
                                    </a>
                                    <a href="<?= route_to('devoluciones.ver', $booking['event_id']) ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-history me-1"></i> Ver historial
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
