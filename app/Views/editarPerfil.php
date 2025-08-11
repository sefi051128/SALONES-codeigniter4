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
        
        .edit-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .edit-container h1 {
            color: #2c3e50;
        }
        
        /* Ajustes para mantener el diseño original */
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }
        
        /* Ajustes específicos para móviles */
        @media (max-width: 576px) {
            .form-actions {
                flex-direction: column-reverse;
                gap: 10px;
            }
            
            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="edit-container p-4 p-md-5 my-4 mx-auto" style="max-width: 600px;">
            <h1 class="text-center mb-4"><?= esc($title) ?></h1>
            
            <?php if (session()->getFlashdata('validation')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('validation')->listErrors() ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('profile/update') ?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Nombre de Usuario</label>
                    <input type="text" name="username" id="username" class="form-control" 
                           value="<?= old('username', $user['username']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" 
                           value="<?= old('email', $user['email']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label fw-bold">Teléfono</label>
                    <input type="tel" name="phone" id="phone" class="form-control" 
                           value="<?= old('phone', $user['phone'] ?? '') ?>" 
                           placeholder="Formato: +56912345678">
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                
                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                </div>
                
                <div class="form-actions d-flex justify-content-between flex-wrap">
                    <a href="<?= base_url('profile') ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>