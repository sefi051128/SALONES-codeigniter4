<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3><?= esc($event['name']) ?></h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-map-marker-alt"></i> Sede:</strong> <?= esc($event['venue_name']) ?></p>
                        <p><strong><i class="fas fa-location-dot"></i> Ubicación:</strong> <?= esc($event['location']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar-day"></i> Fecha:</strong> <?= date('d/m/Y H:i', strtotime($event['date'])) ?></p>
                        <p><strong><i class="fas fa-users"></i> Capacidad:</strong> <?= $event['capacity'] ?> personas</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Descripción</h5>
                    <p><?= nl2br(esc($event['description'])) ?></p>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('eventos') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    
                    <?php if(session('role') === 'administrador'): ?>
                        <div>
                            <a href="<?= base_url('eventos/editar/'.$event['id']) ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="<?= base_url('eventos/eliminar/'.$event['id']) ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este evento?')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>