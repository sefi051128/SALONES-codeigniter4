<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Notificación</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card-form {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 850px;
            margin: auto;
        }
        .card-header-form {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 1.5rem;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .form-icon {
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column-reverse;
                gap: 10px;
            }
            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column" style="min-height: 100vh;">
        <div class="my-auto">
            <div class="card card-form">
                <div class="card-header card-header-form text-center">
                    <h2 class="h4 mb-2">
                        <i class="fas fa-bell text-primary me-2"></i>Crear Notificación
                    </h2>
                    <p class="text-muted mb-0 small">Complete todos los campos requeridos</p>
                </div>
                
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('notificaciones/guardar') ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-semibold required-field">Usuario</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text form-icon"><i class="fas fa-user"></i></span>
                                <input type="number" class="form-control py-2" name="user_id" 
                                       value="<?= old('user_id') ?>" 
                                       placeholder="Ingrese el ID del usuario"
                                       min="1"
                                       required>
                                <div class="invalid-feedback">
                                    Por favor ingrese un ID de usuario válido
                                </div>
                            </div>
                            <small class="text-muted">Ingrese el número de identificación del usuario</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold required-field">Tipo de Notificación</label>
                            <select class="form-select py-2" name="notification_type" required>
                                <option value="" disabled selected>Seleccione un tipo</option>
                                <option value="Urgente" <?= old('notification_type') == 'Urgente' ? 'selected' : '' ?>>Urgente</option>
                                <option value="Importante" <?= old('notification_type') == 'Importante' ? 'selected' : '' ?>>Importante</option>
                                <option value="Informativa" <?= old('notification_type') == 'Informativa' ? 'selected' : '' ?>>Informativa</option>
                                <option value="Recordatorio" <?= old('notification_type') == 'Recordatorio' ? 'selected' : '' ?>>Recordatorio</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione un tipo de notificación
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold required-field">Mensaje</label>
                            <textarea class="form-control py-2" name="message" rows="5"
                                      placeholder="Escriba el contenido completo de la notificación"
                                      minlength="10"
                                      required><?= old('message') ?></textarea>
                            <div class="invalid-feedback">
                                El mensaje debe contener al menos 10 caracteres
                            </div>
                            <small class="text-muted">Mínimo 10 caracteres</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold required-field">Fecha de Envío</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text form-icon"><i class="fas fa-calendar-day"></i></span>
                                <input type="datetime-local" class="form-control py-2" name="sent_date" 
                                       value="<?= old('sent_date') ?? date('Y-m-d\TH:i') ?>"
                                       required>
                                <div class="invalid-feedback">
                                    Por favor seleccione una fecha válida
                                </div>
                            </div>
                            <small class="text-muted">Fecha y hora programada para el envío</small>
                        </div>

                        <div class="d-flex justify-content-between mt-5 form-actions">
                            <a href="<?= base_url('notificaciones') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-1"></i> Enviar Notificación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Validación y funcionalidad adicional -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer fecha mínima como la actual
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
            const dateInput = document.querySelector('[name="sent_date"]');
            dateInput.min = localISOTime;

            // Validación del formulario
            const forms = document.querySelectorAll('.needs-validation');
            
            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });

            // Validación adicional para el ID de usuario
            const userIdInput = document.querySelector('[name="user_id"]');
            userIdInput.addEventListener('input', function() {
                if (this.value <= 0) {
                    this.setCustomValidity('El ID debe ser mayor a 0');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
</body>
</html>