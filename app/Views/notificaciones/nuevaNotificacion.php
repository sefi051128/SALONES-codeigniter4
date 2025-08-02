<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Notificación</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2 class="mb-0"><i class="fas fa-bell me-2"></i>Nueva Notificación</h2>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('notificaciones/guardar') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="user_id" class="form-label required-field">ID de Usuario</label>
                    <input type="number" name="user_id" class="form-control" required
                           placeholder="Ingrese el ID del usuario">
                </div>

                <div class="mb-3">
                    <label for="notification_type" class="form-label required-field">Tipo de Notificación</label>
                    <select name="notification_type" class="form-select" required>
                        <option value="evento">Evento</option>
                        <option value="alerta">Alerta</option>
                        <option value="sistema">Sistema</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label required-field">Mensaje</label>
                    <textarea name="message" class="form-control" rows="5" required
                              placeholder="Escriba el mensaje de la notificación"></textarea>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('notificaciones') ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Notificación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Validación del formulario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                const userId = document.querySelector('[name="user_id"]').value;
                const message = document.querySelector('[name="message"]').value;
                
                if(!userId || isNaN(userId) {
                    alert('Por favor ingrese un ID de usuario válido');
                    e.preventDefault();
                    return false;
                }
                
                if(!message || message.trim().length < 10) {
                    alert('El mensaje debe tener al menos 10 caracteres');
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>
</html>