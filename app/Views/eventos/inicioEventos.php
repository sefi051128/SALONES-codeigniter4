<?php
use CodeIgniter\I18n\Time;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - <?= esc($venue['name'] ?? 'Sede') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .event-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .event-header {
            background-color: #4a6fa5;
            color: white;
            padding: 15px;
        }
        .event-date {
            background-color: #ff7e5f;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .venue-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Información de la sede -->
<?php if (!empty($venue)): ?>
<div class="venue-info">
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-map-marker-alt"></i> <?= esc($venue['name']) ?></h2>
        <a href="<?= base_url('sedes') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a sedes
        </a>
    </div>
    <p class="mb-1"><i class="fas fa-location-dot"></i> <?= esc($venue['location']) ?></p>
    <p><i class="fas fa-users"></i> Capacidad: <?= $venue['capacity'] ?> personas</p>
</div>
<?php endif; ?>

        <!-- Listado de eventos -->
        <h3 class="mb-4"><i class="fas fa-calendar-days"></i> Eventos programados</h3>
         <a href="/sedes" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Atrás
    </a>
   <a href="<?= base_url('reservas/verReservas') ?>" class="btn btn-outline-secondary mb-3">
    <i class="fas fa-calendar-check"></i> Ver Reservas Especiales
</a>
    
         
        
        <?php if (empty($events)): ?>
            <div class="alert alert-info">
                No hay eventos programados para esta sede.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-6">
                        <div class="card event-card">
                            <div class="event-header">
                                <h4><?= esc($event['name']) ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="event-date">
                                        <i class="fas fa-clock"></i> <?= Time::parse($event['date'])->toLocalizedString('MMM d, yyyy h:mm a') ?>
                                    </span>
                                   <?php if(session('role') === 'cliente'): ?>
    <a href="<?= site_url('reservas/nueva/'.$event['id']) ?>" class="btn btn-sm btn-success">
    <i class="fas fa-calendar-check"></i> Reservar
</a>
<?php endif; ?>
                                </div>
                                
                                <p class="card-text"><?= esc($event['description']) ?></p>
                                
                                <div class="d-flex justify-content-between mt-3">
                                    <button class="btn btn-outline-primary btn-sm btn-services" data-event-id="<?= $event['id'] ?>">
                                        <i class="fas fa-concierge-bell"></i> Ver servicios
                                    </button>
                                    <?php if(session('role') === 'administrador'): ?>
                                        <div>
                                            <a href="<?= base_url('eventos/editar/'.$event['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-delete-event" data-event-id="<?= $event['id'] ?>">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal para servicios -->
    <div class="modal fade" id="servicesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Servicios para: <span id="eventName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="servicesContainer">
                    <!-- Los servicios se cargarán aquí dinámicamente -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar servicios del evento
        document.querySelectorAll('.btn-services').forEach(button => {
            button.addEventListener('click', function() {
                const eventId = this.getAttribute('data-event-id');
                fetch(`<?= base_url('api/eventos/') ?>${eventId}/servicios`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('eventName').textContent = data.event.name;
                        const container = document.getElementById('servicesContainer');
                        container.innerHTML = data.services.map(service => 
                            `<div class="card mb-2">
                                <div class="card-body">
                                    <h5>${service.name}</h5>
                                    <p>${service.description}</p>
                                    <p class="text-end"><strong>$${service.price}</strong></p>
                                </div>
                            </div>`
                        ).join('');
                        
                        const modal = new bootstrap.Modal(document.getElementById('servicesModal'));
                        modal.show();
                    });
            });
        });

        // Manejar reservas
        document.querySelectorAll('.btn-reserve').forEach(button => {
            button.addEventListener('click', function() {
                const eventId = this.getAttribute('data-event-id');
                window.location.href = `<?= base_url('reservas/nueva/') ?>${eventId}`;
            });
        });

        // Eliminar evento (solo admin)
        document.querySelectorAll('.btn-delete-event').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('¿Estás seguro de eliminar este evento?')) {
                    const eventId = this.getAttribute('data-event-id');
                    fetch(`<?= base_url('api/eventos/') ?>${eventId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>