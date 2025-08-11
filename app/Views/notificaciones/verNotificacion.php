<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Notificación</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 1.5rem;
        }
        .badge-urgente { background-color: #ef4444; }
        .badge-importante { background-color: #f59e0b; }
        .badge-informativo { background-color: #3b82f6; }
        .badge-recordatorio { background-color: #10b981; }
        .message-content {
            white-space: pre-wrap;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Detalle de Notificación</h3>
                <div>
                    <a href="<?= base_url('/notificaciones') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Información Básica</h5>
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8"><?= esc($notificacion['id']) ?></dd>
                            
                            <dt class="col-sm-4">Tipo:</dt>
                            <dd class="col-sm-8">
                                <span class="badge rounded-pill <?= 
                                    $notificacion['notification_type'] === 'Urgente' ? 'badge-urgente' : 
                                    ($notificacion['notification_type'] === 'Importante' ? 'badge-importante' : 
                                    ($notificacion['notification_type'] === 'Recordatorio' ? 'badge-recordatorio' : 'badge-informativo')) 
                                ?>">
                                    <?= esc($notificacion['notification_type']) ?>
                                </span>
                            </dd>
                            
                            <dt class="col-sm-4">Estado:</dt>
                            <dd class="col-sm-8">
                                <?= empty($notificacion['read_at']) ? '<span class="badge bg-warning">No leída</span>' : '<span class="badge bg-success">Leída</span>' ?>
                            </dd>
                            
                            <dt class="col-sm-4">Fecha envío:</dt>
                            <dd class="col-sm-8"><?= esc($notificacion['sent_date']) ?></dd>
                            
                            <?php if(!empty($notificacion['read_at'])): ?>
                            <dt class="col-sm-4">Fecha lectura:</dt>
                            <dd class="col-sm-8"><?= esc($notificacion['read_at']) ?></dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Información del Usuario</h5>
                        <div class="d-flex align-items-center mb-3">
                            <?php if(!empty($notificacion['user_avatar'])): ?>
                                <img src="<?= esc($notificacion['user_avatar']) ?>" class="rounded-circle me-3" width="64" height="64" alt="Avatar">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:64px;height:64px;">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h6 class="mb-1"><?= esc($notificacion['username'] ?? 'Usuario #'.esc($notificacion['user_id'])) ?></h6>
                                <?php if(!empty($notificacion['user_role'])): ?>
                                    <span class="text-muted"><?= esc($notificacion['user_role']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if(!empty($notificacion['event_id'])): ?>
                        <div class="alert alert-info py-2">
                            <i class="fas fa-calendar-alt me-2"></i> Relacionado con el Evento #<?= esc($notificacion['event_id']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Mensaje</h5>
                    <div class="message-content">
                        <?= nl2br(esc($notificacion['message'])) ?>
                    </div>
                </div>
                
                <?php if(!empty($notificacion['metadata'])): ?>
                <div class="mb-4">
                    <h5>Datos Adicionales</h5>
                    <pre class="bg-light p-3 rounded"><?= json_encode(json_decode($notificacion['metadata']), JSON_PRETTY_PRINT) ?></pre>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="card-footer bg-white d-flex justify-content-between">
                <div>
                    <?php if(empty($notificacion['read_at'])): ?>
    <form action="<?= base_url('/notificaciones/marcar-leida/'.$notificacion['id']) ?>" method="post" class="d-inline">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle me-1"></i> Marcar como leída
        </button>
    </form>
<?php endif; ?>
                </div>
                <div>
                    <a href="<?= base_url('/notificaciones/editar/'.$notificacion['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <a href="<?= base_url('/notificaciones/eliminar/'.$notificacion['id']) ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta notificación?')">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para confirmación de eliminación
        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('¿Está seguro que desea eliminar esta notificación permanentemente?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>