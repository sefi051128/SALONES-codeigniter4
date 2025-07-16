<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4"><?= esc($title) ?></h2>

    <form method="post" action="<?= base_url('users/edit/'.$user['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Usuario:</label>
            <input type="text" name="username" class="form-control" value="<?= old('username', $user['username']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña (opcional):</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
    <label class="form-label">Rol:</label>
    <select name="role" class="form-select">
        <option value="administrador" <?= old('role', $user['role']) == 'administrador' ? 'selected' : '' ?>>Administrador</option>
        <option value="coordinador" <?= old('role', $user['role']) == 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
        <option value="logistica" <?= old('role', $user['role']) == 'logistica' ? 'selected' : '' ?>>Logística</option>
        <option value="seguridad" <?= old('role', $user['role']) == 'seguridad' ? 'selected' : '' ?>>Seguridad</option>
        <option value="cliente" <?= old('role', $user['role']) == 'cliente' ? 'selected' : '' ?>>Cliente</option>
    </select>
</div>

        <div class="mb-3">
            <label class="form-label">Contacto:</label>
            <input type="text" name="contact_info" class="form-control" value="<?= old('contact_info', $user['contact_info']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="<?= base_url('users') ?>" class="btn btn-secondary">Cancelar</a>
    </form>

    <?php if (isset($validation)): ?>
        <div class="alert alert-danger mt-3">
            <?= \Config\Services::validation()->listErrors() ?>
        </div>
    <?php endif; ?>

</body>
</html>
