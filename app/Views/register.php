<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario</title>
    <style>
        .error { color: red; font-size: 0.9rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input, select { padding: 0.5rem; width: 100%; max-width: 300px; }
    </style>
</head>
<body>
    <div style="max-width: 500px; margin: 0 auto; padding: 2rem;">
        <h1>Registro de Usuario</h1>

        <?php if(session()->getFlashdata('errors')): ?>
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <div class="error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form action="<?= base_url('/createUser') ?>" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña (mínimo 8 caracteres):</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="role">Rol:</label>
                <select id="role" name="role" required>
                    <option value="">Seleccione un rol</option>
                    <option value="administrador" <?= old('role') == 'administrador' ? 'selected' : '' ?>>Administrador</option>
                    <option value="coordinador" <?= old('role') == 'coordinador' ? 'selected' : '' ?>>Coordinador</option>
                    <option value="cliente" <?= old('role') == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                    <option value="logística" <?= old('role') == 'logística' ? 'selected' : '' ?>>Logística</option>
                    <option value="seguridad" <?= old('role') == 'seguridad' ? 'selected' : '' ?>>Seguridad</option>
                </select>
            </div>

            <div class="form-group">
                <label for="contact_info">Información de Contacto (opcional):</label>
                <textarea id="contact_info" name="contact_info" rows="3" style="width: 100%; max-width: 300px;"><?= old('contact_info') ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Registrar</button>
            </div>
        </form>

        <p>¿Ya tienes cuenta? <a href="<?= base_url('/') ?>">Inicia sesión aquí</a></p>
    </div>
</body>
</html>