<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Acceso al Almacén</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .card-form {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 650px;
            margin: auto;
        }
        .card-header-form {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 1.5rem;
        }
        .form-icon {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .btn-submit {
            min-width: 150px;
        }
        .btn-cancel {
            min-width: 120px;
        }
        @media (max-width: 576px) {
            .card-form {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
            }
            .form-actions {
                flex-direction: column-reverse;
                gap: 10px;
            }
            .btn-submit, .btn-cancel {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container my-auto">
        <div class="card card-form">
            <div class="card-header card-header-form text-center">
                <h2 class="h4 mb-0">
                    <i class="fas fa-door-open text-primary me-2"></i>Registrar Nuevo Acceso
                </h2>
                <p class="text-muted mb-0 mt-2 small">Complete todos los campos requeridos</p>
            </div>
            
            <div class="card-body">
                <?php if (isset($error) && $error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i><?= esc($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('almacen/guardar') ?>" method="post" class="needs-validation" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label for="user_id" class="form-label fw-semibold">ID de Usuario</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text form-icon"><i class="fas fa-user"></i></span>
                            <input type="number" class="form-control py-2" id="user_id" name="user_id" required
                                value="<?= old('user_id') ?>" placeholder="Ingrese el ID del usuario" min="1">
                            <div class="invalid-feedback">
                                Por favor ingrese un ID de usuario válido
                            </div>
                        </div>
                        <small class="text-muted">Ingrese el número de identificación del usuario</small>
                    </div>

                    <div class="mb-4">
                        <label for="access_type" class="form-label fw-semibold">Tipo de Acceso</label>
                        <select class="form-select py-2" id="access_type" name="access_type" required>
                            <option value="" <?= old('access_type') == '' ? 'selected' : '' ?>>-- Seleccione --</option>
                            <option value="Entrada" <?= old('access_type') == 'Entrada' ? 'selected' : '' ?>>
                                <i class="fas fa-sign-in-alt me-2"></i>Entrada
                            </option>
                            <option value="Salida" <?= old('access_type') == 'Salida' ? 'selected' : '' ?>>
                                <i class="fas fa-sign-out-alt me-2"></i>Salida
                            </option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un tipo de acceso
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5 form-actions">
                        <a href="<?= base_url('almacen') ?>" class="btn btn-outline-secondary btn-cancel">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-submit py-2">
                            <i class="fas fa-save me-1"></i> Registrar Acceso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Validación mejorada del formulario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validación nativa de Bootstrap
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
            const userIdInput = document.getElementById('user_id');
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