<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión</title>
    <style>
        .error { color: red; }
        .success { color: green; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input { padding: 0.5rem; width: 100%; max-width: 300px; }
    </style>
</head>
<body>
    <div style="max-width: 500px; margin: 0 auto; padding: 2rem;">
        <h1>Iniciar Sesión</h1>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('/login') ?>" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <button type="submit">Entrar</button>
            </div>
        </form>

        <p>¿No tienes cuenta? <a href="<?= base_url('/register') ?>">Regístrate aquí</a></p>
    </div>
</body>
</html>