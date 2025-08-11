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
        .badge-status {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .badge-activo {
            background-color: #198754; /* verde */
            color: white;
        }
        .badge-cancelado {
            background-color: #dc3545; /* rojo */
            color: white;
        }
        .badge-completado {
            background-color: #0d6efd; /* azul */
            color: white;
        }
        .event-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            height: 100%;
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
            white-space: nowrap;
        }
        .venue-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .btn-action-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        @media (max-width: 576px) {
            .event-date-container {
                flex-direction: column;
                gap: 0.5rem;
            }
            .btn-reserve {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container py-3 py-md-5">
        <!-- Información de la sede -->
        <?php if (!empty($venue)): ?>
        <div class="venue-info">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h2 class="mb-3 mb-md-0"><i class="fas fa-map-marker-alt me-2"></i><?= esc($venue['name']) ?></h2>
                <a href="<?= base_url('sedes') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a sedes
                </a>
            </div>
            <div class="mt-3">
                <p class="mb-1"><i class="fas fa-location-dot me-2"></i><?= esc($venue['location']) ?></p>
                <p class="mb-0"><i class="fas fa-users me-2"></i>Capacidad: <?= $venue['capacity'] ?> personas</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Listado de eventos -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h3 class="mb-3 mb-md-0"><i class="fas fa-calendar-days me-2"></i>Eventos programados</h3>
            <div class="d-flex flex-wrap gap-2">
                <a href="/sedes" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Atrás
                </a>
                
                <?php if (session('role') === 'administrador'): ?>
                <a href="<?= base_url('eventos/crear') ?>" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Crear Nuevo Evento
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (empty($events)): ?>
            <div class="alert alert-info">
                No hay eventos programados para esta sede.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($events as $event): ?>
                    <div class="col">
                        <div class="card event-card h-100">
                            <div class="event-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0"><?= esc($event['name']) ?></h4>
                                    <span class="badge-status 
                                        <?php
                                            switch ($event['status']) {
                                                case 'activo': echo 'badge-activo'; break;
                                                case 'cancelado': echo 'badge-cancelado'; break;
                                                case 'completado': echo 'badge-completado'; break;
                                                default: echo 'bg-secondary text-white';
                                            }
                                        ?>">
                                        <?= ucfirst($event['status']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 event-date-container">
                                    <span class="event-date mb-2 mb-sm-0">
                                        <i class="fas fa-clock me-1"></i><?= Time::parse($event['date'])->toLocalizedString('MMM d, yyyy h:mm a') ?>
                                    </span>
                                    
                                    <!-- Botóin de reservar un evento específico - implementación futura
                                    <a href="<?= site_url('reservas/crearReserva/'.$event['id']) ?>" class="btn btn-sm btn-success btn-reserve">
                                        <i class="fas fa-calendar-check me-1"></i> Reservar
                                    </a>
                                    -->
                                </div>
                                
                                <p class="card-text flex-grow-1"><?= esc($event['description']) ?></p>
                                
                                <div class="d-flex justify-content-between mt-3">

                                <!-- Botón de ver servicios relacionados al evento - implementación futura    
                                <button class="btn btn-outline-primary btn-sm btn-services" data-event-id="<?= $event['id'] ?>">
                                        <i class="fas fa-concierge-bell me-1"></i> Ver servicios
                                    </button>
                                    -->

                                    <?php if(session('role') === 'administrador'): ?>
                                        <div class="btn-action-group">
                                            <a href="<?= base_url('eventos/editar/'.$event['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-sm-inline"> Editar</span>
                                            </a>
                                            <form action="<?= base_url('eventos/eliminar/'.$event['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('¿Seguro que quieres eliminar este evento?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span class="d-none d-sm-inline"> Eliminar</span>
                                                </button>
                                            </form>
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