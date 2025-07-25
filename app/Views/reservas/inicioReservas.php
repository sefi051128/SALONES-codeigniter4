<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between mb-4">
            <h2><i class="fas fa-calendar-check"></i> <?= esc($title) ?></h2>
            <a href="<?= base_url('eventos') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Atrás
            </a>
            <a href="<?= base_url('reservas/nueva') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Reserva
            </a>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Sede</th>
                                <th>Artículo</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?= $reservation['id'] ?></td>
                                <td><?= esc($reservation['customer_name']) ?></td>
                                <td><?= esc($reservation['venue_name'] ?? 'N/A') ?></td>
                                <td><?= esc($reservation['item_name'] ?? 'N/A') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($reservation['reservation_date'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $reservation['status'] == 'confirmed' ? 'success' : 
                                        ($reservation['status'] == 'cancelled' ? 'danger' : 'warning') 
                                    ?>">
                                        <?= ucfirst($reservation['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($reservation['status'] == 'pending'): ?>
                                            <a href="<?= base_url("reservas/cambiarEstado/{$reservation['id']}/confirmed") ?>" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?= base_url("reservas/cambiarEstado/{$reservation['id']}/cancelled") ?>" 
                                               class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>