<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            background-color: #f5f5f5;
        }
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 2rem auto;
        }
        .form-label {
            font-weight: 600;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
            display: block;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="container py-5">

    <div class="form-container">
        <h2 class="mb-4 text-center"><i class="fas fa-user-edit me-2"></i><?= esc($title) ?></h2>

        <!-- Mensajes flash -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Errores de validación -->
        <?php if(isset($validation)): ?>
            <div class="alert alert-danger">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <?php if(empty($user['id'])): ?>
            <div class="alert alert-danger">Usuario no válido</div>
        <?php else: ?>
            <form method="post" action="<?= site_url('users/update/' . $user['id']) ?>">
                <input type="hidden" name="_method" value="PUT">
                <?= csrf_field() ?>

                <!-- Campo Username -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user me-2"></i>Usuario:</label>
                    <input type="text" name="username" class="form-control <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>" 
                           value="<?= old('username', $user['username']) ?>" required>
                    <?php if(isset($validation) && $validation->hasError('username')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('username') ?>
                        </div>
                    <?php endif; ?>
                    <small class="text-muted">Mínimo 3 caracteres, máximo 20</small>
                </div>

                <!-- Campo Password -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock me-2"></i>Contraseña (dejar vacío para no cambiar):</label>
                    <input type="password" name="password" class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>">
                    <?php if(isset($validation) && $validation->hasError('password')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('password') ?>
                        </div>
                    <?php endif; ?>
                    <small class="text-muted">Mínimo 8 caracteres si se desea cambiar</small>
                </div>

                <!-- Campo Email -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope me-2"></i>Correo electrónico:</label>
                    <input type="email" name="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                           value="<?= old('email', $user['email']) ?>" required>
                    <?php if(isset($validation) && $validation->hasError('email')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('email') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Campo Teléfono -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-phone me-2"></i>Teléfono:</label>
                    <input type="tel" name="phone" class="form-control <?= isset($validation) && $validation->hasError('phone') ? 'is-invalid' : '' ?>" 
                           value="<?= old('phone', $user['phone']) ?>" placeholder="Ej: +56912345678">
                    <?php if(isset($validation) && $validation->hasError('phone')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('phone') ?>
                        </div>
                    <?php endif; ?>
                    <small class="text-muted">Formato: +56912345678</small>
                </div>

                <!-- Campo Rol -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user-tag me-2"></i>Rol:</label>
                    <select name="role" class="form-select <?= isset($validation) && $validation->hasError('role') ? 'is-invalid' : '' ?>" required>
                        <option value="">Seleccione un rol</option>
                        <option value="cliente" <?= old('role', $user['role']) == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                        <option value="administrador" <?= old('role', $user['role']) == 'administrador' ? 'selected' : '' ?>>Administrador</option>
                        <option value="coordinador" <?= old('role', $user['role']) == 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                        <option value="logística" <?= old('role', $user['role']) == 'logística' || old('role', $user['role']) == 'logistica' ? 'selected' : '' ?>>Logística</option>
                        <option value="seguridad" <?= old('role', $user['role']) == 'seguridad' ? 'selected' : '' ?>>Seguridad</option>
                    </select>
                    <?php if(isset($validation) && $validation->hasError('role')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('role') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary me-md-2">
                        <i class="fas fa-save me-2"></i>Actualizar
                    </button>
                    <a href="<?= site_url('users') ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>