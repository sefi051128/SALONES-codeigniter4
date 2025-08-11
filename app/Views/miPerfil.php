<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - EventSalones</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px 0;
        }
        
        .profile-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .profile-container h1 {
            color: #2c3e50;
        }
        
        .profile-header {
            border-bottom: 1px solid #eee;
        }
        
        .avatar {
            color: #3498db;
        }
        
        .user-meta h2 {
            color: #2c3e50;
        }
        
        .role-badge {
            background-color: #3498db;
            color: white;
            text-transform: capitalize;
        }
        
        .detail-row {
            background: #f9f9f9;
            border-radius: 5px;
        }
        
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        
        /* Mensajes flash */
        .alert {
            margin-bottom: 20px;
        }
        
        /* Ajustes específicos para mantener el diseño original */
        .avatar i {
            font-size: 3.5rem;
        }
        
        @media (max-width: 576px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .avatar {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .profile-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-container p-4 p-md-5 my-4">
            <h1 class="text-center mb-4"><?= esc($title) ?></h1>
            
            <!-- Mostrar mensajes flash -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-info">
                <div class="profile-header d-flex align-items-center pb-3 mb-4 flex-sm-row flex-column">
                    <div class="avatar me-sm-4 mb-sm-0 mb-3">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-meta text-sm-start text-center">
                        <h2 class="mb-1"><?= esc($user['username']) ?></h2>
                        <span class="badge role-badge rounded-pill"><?= esc($user['role']) ?></span>
                    </div>
                </div>
                
                <div class="profile-details mb-4">
                    <div class="detail-row d-flex flex-wrap p-3 mb-3">
                        <span class="detail-label col-12 col-md-3 mb-2 mb-md-0"><i class="fas fa-envelope me-2"></i>Email:</span>
                        <span class="detail-value col-12 col-md-9"><?= esc($user['email']) ?></span>
                    </div>
                    
                    <div class="detail-row d-flex flex-wrap p-3 mb-3">
                        <span class="detail-label col-12 col-md-3 mb-2 mb-md-0"><i class="fas fa-phone me-2"></i>Teléfono:</span>
                        <span class="detail-value col-12 col-md-9"><?= esc($user['phone'] ?? 'No especificado') ?></span>
                    </div>
                    
                    <div class="detail-row d-flex flex-wrap p-3">
                        <span class="detail-label col-12 col-md-3 mb-2 mb-md-0"><i class="fas fa-calendar-alt me-2"></i>Miembro desde:</span>
                        <span class="detail-value col-12 col-md-9"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span>
                    </div>
                </div>
                
                <div class="profile-actions d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Editar Perfil
                    </a>
                    <a href="<?= base_url('inicio') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>