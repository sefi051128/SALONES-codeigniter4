<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .card-notifications {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .card-header-notifications {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 1.5rem;
        }
        .badge-urgente { background-color: #ef4444; }
        .badge-importante { background-color: #f59e0b; }
        .badge-informativo { background-color: #3b82f6; }
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }
        .message-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                gap: 10px;
                margin-top: 15px;
            }
            .header-actions a {
                width: 100%;
            }
            .table-responsive {
                font-size: 0.9rem;
            }
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            .action-buttons a {
                width: 100%;
            }
            .message-cell {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-notifications">
            <div class="card-header card-header-notifications">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h2 class="h4 mb-2 mb-md-0">
                            <i class="fas fa-bell text-primary me-2"></i>Notificaciones
                        </h2>
                        <p class="text-muted mb-0 small">Registro de notificaciones del sistema</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0 header-actions">
                        <a href="<?= base_url('/notificaciones/crear') ?>" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Nueva
                        </a>
                        <a href="/admin" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Atrás
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">ID</th>
                                <th>Usuario</th>
                                <th class="text-nowrap">Tipo</th>
                                <th>Mensaje</th>
                                <th class="text-nowrap">Fecha</th>
                                <th class="text-nowrap">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($notificaciones)): ?>
                                <?php foreach ($notificaciones as $n): ?>
                                    <tr>
                                        <td><?= esc($n['id']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="fas fa-user-circle text-secondary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <?= esc($n['user_id']) ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill <?= 
                                                $n['notification_type'] === 'Urgente' ? 'badge-urgente' : 
                                                ($n['notification_type'] === 'Importante' ? 'badge-importante' : 'badge-informativo') 
                                            ?>">
                                                <?= esc($n['notification_type']) ?>
                                            </span>
                                        </td>
                                        <td class="message-cell" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= esc($n['message']) ?>">
                                            <?= esc($n['message']) ?>
                                        </td>
                                        <td class="text-nowrap">
                                            <i class="far fa-clock text-secondary me-1"></i>
                                            <?= esc($n['sent_date']) ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 action-buttons">
                                                <a href="<?= base_url('/notificaciones/ver/' . $n['id']) ?>" class="btn btn-sm btn-info flex-grow-1">
                                                    <i class="fas fa-eye me-1 d-none d-md-inline"></i> Ver
                                                </a>
                                                <a href="<?= base_url('/notificaciones/eliminar/' . $n['id']) ?>" class="btn btn-sm btn-danger flex-grow-1">
                                                    <i class="fas fa-trash me-1 d-none d-md-inline"></i> Eliminar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fas fa-bell-slash"></i>
                                        <h5 class="h6 mt-2">No hay notificaciones</h5>
                                        <p class="small">No se encontraron notificaciones registradas</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script para mejoras de interacción -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltips para mensajes largos
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Confirmación mejorada para eliminación
            document.querySelectorAll('.btn-danger').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('¿Está seguro que desea eliminar esta notificación permanentemente?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>