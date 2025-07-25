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
        <h2 class="mb-4"><i class="fas fa-plus-circle"></i> <?= esc($title) ?></h2>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-1"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('reservas/guardar') ?>" method="post">
            <?= csrf_field() ?>

            <?php if(isset($event)): ?>
    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
    <div class="alert alert-info">
        Estás reservando para el evento: <strong><?= esc($event['name']) ?></strong>
    </div>
    <div class="mb-3">
        <label class="form-label">Número de Invitados</label>
        <input type="number" name="number_of_guests" class="form-control" min="1" value="1" required>
    </div>
<?php endif; ?>

            <div class="row mb-4">
                <!-- Sección de Información del Cliente -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            Información del Cliente
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" value="<?= esc($current_user['username']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="text" class="form-control" value="<?= esc($current_user['email']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" value="<?= esc($current_user['phone'] ?? 'No registrado') ?>" readonly>
                            </div>
                            <input type="hidden" name="customer_id" value="<?= $current_user['id'] ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Detalles de la Reserva -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            Detalles de la Reserva
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Fecha y Hora</label>
                                <input type="datetime-local" name="reservation_date" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Sede</label>
                                <select name="venue_id" class="form-select">
                                    <option value="">Seleccione una sede</option>
                                    <?php foreach($venues as $venue): ?>
                                        <option value="<?= $venue['id'] ?>"><?= esc($venue['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending">Pendiente</option>
                                    <option value="confirmed">Confirmado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modifica la sección de artículos para permitir selección múltiple -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-boxes"></i> Artículos del Inventario
    </div>
    <div class="card-body">
        <?php if(!empty($items)): ?>
            <div class="row">
                <?php foreach($items as $item): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <?php if(!empty($item['imagen'])): ?>
                                <img src="/uploads/<?= esc($item['imagen']) ?>" class="card-img-top" alt="<?= esc($item['name']) ?>" style="height: 150px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="items[]" value="<?= $item['id'] ?>" 
                                           id="item_<?= $item['id'] ?>">
                                    <label class="form-check-label" for="item_<?= $item['id'] ?>">
                                        <strong><?= esc($item['name']) ?></strong>
                                        <small class="d-block text-muted">Código: <?= esc($item['code']) ?></small>
                                        <small class="d-block">Estado: 
                                            <span class="badge bg-<?= $item['status'] === 'disponible' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($item['status']) ?>
                                            </span>
                                        </small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No hay artículos disponibles en el inventario.
            </div>
        <?php endif; ?>
    </div>
</div>
            
            <!-- Botones de Acción -->
            <div class="d-flex justify-content-between">
                <a href="<?= base_url('eventos') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Confirmar Reserva
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>