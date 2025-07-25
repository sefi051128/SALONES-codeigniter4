<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    </style>
</head>
<body class="container py-5">

    <div class="form-container">
        <h2 class="mb-4 text-center"><i class="fas fa-user-plus me-2"></i><?= esc($title) ?></h2>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-1"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('user/createAdminUser') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user me-2"></i>Usuario:</label>
                <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                <small class="text-muted">Mínimo 3 caracteres, máximo 20</small>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock me-2"></i>Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
                <small class="text-muted">Mínimo 8 caracteres</small>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope me-2"></i>Correo electrónico:</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-phone me-2"></i>Teléfono:</label>
                <input type="tel" name="phone" class="form-control" value="<?= old('phone') ?>">
                <small class="text-muted">Formato: +1234567890</small>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user-tag me-2"></i>Rol:</label>
                <select name="role" class="form-select" required>
                    <option value="">Seleccione un rol</option>
                    <option value="cliente" <?= old('role') == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                    <option value="administrador" <?= old('role') == 'administrador' ? 'selected' : '' ?>>Administrador</option>
                    <option value="coordinador" <?= old('role') == 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                    <option value="logística" <?= old('role') == 'logística' ? 'selected' : '' ?>>Logística</option>
                    <option value="seguridad" <?= old('role') == 'seguridad' ? 'selected' : '' ?>>Seguridad</option>
                </select>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-success me-md-2">
                    <i class="fas fa-save me-2"></i>Guardar
                </button>
                <a href="<?= site_url('users') ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>