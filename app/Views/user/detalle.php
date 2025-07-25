<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            font-size: 48px;
        }
        .detail-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .badge-role {
            font-size: 1rem;
            padding: 8px 12px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary mb-4">
                    <i class="fas fa-arrow-left me-2"></i>Volver al listado
                </a>
                
                <div class="profile-header d-flex align-items-center">
                    <div class="avatar bg-primary text-white d-flex align-items-center justify-content-center rounded-circle profile-avatar me-4">
                        <?= strtoupper(substr($user['username'] ?? '', 0, 1)) ?>
                    </div>
                    <div>
                        <h1 class="mb-2"><?= esc($user['username'] ?? 'Usuario sin nombre') ?></h1>
                        <?php 
                            $role = $user['role'] ?? 'sin rol';
                            $badgeClass = 'bg-primary';
                            switch(strtolower($role)) {
                                case 'administrador': $badgeClass = 'bg-danger'; break;
                                case 'coordinador': $badgeClass = 'bg-warning text-dark'; break;
                                case 'cliente': $badgeClass = 'bg-success'; break;
                                case 'logística': $badgeClass = 'bg-info'; break;
                                case 'seguridad': $badgeClass = 'bg-dark'; break;
                                default: $badgeClass = 'bg-secondary';
                            }
                        ?>
                        <span class="badge badge-role <?= $badgeClass ?>"><?= esc(ucfirst($role)) ?></span>
                    </div>
                </div>

                <div class="row">
                    <!-- Información Básica -->
                    <div class="col-md-6">
                        <div class="card detail-card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="fas fa-id-card me-2"></i>Información Básica</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>ID:</strong> <?= esc($user['id'] ?? 'N/A') ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Nombre de usuario:</strong> <?= esc($user['username'] ?? 'No especificado') ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Rol:</strong> <span class="badge <?= $badgeClass ?>"><?= esc(ucfirst($role)) ?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Fecha de registro:</strong> 
                                        <?= isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : 'Fecha no disponible' ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="col-md-6">
                        <div class="card detail-card">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="fas fa-address-book me-2"></i>Información de Contacto</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Email:</strong> 
                                        <?php if (!empty($user['email'])): ?>
                                            <a href="mailto:<?= esc($user['email']) ?>"><?= esc($user['email']) ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Teléfono:</strong> 
                                        <?php if (!empty($user['phone'])): ?>
                                            <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $user['phone'])) ?>"><?= esc($user['phone']) ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="d-flex justify-content-end mt-4">
                    <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="<?= base_url('users/delete/'.$user['id']) ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                        <i class="fas fa-trash-alt me-2"></i>Eliminar
                    </a>
                </div>

                <div class="text-muted mt-4">
                    <small>Reporte generado el: <?= $generatedAt ?></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>