<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Control</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .user-info { background: #f5f5f5; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; }
        .logout-btn { display: inline-block; padding: 0.5rem 1rem; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; }
        .logout-btn:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="user-info">
        <h1>Bienvenido, <?= esc(session('username')) ?></h1>
        <p><strong>Rol:</strong> <?= esc(session('role')) ?></p>
        <p><strong>ID de Usuario:</strong> <?= esc(session('user_id')) ?></p>
        <a href="<?= base_url('/logout') ?>" class="logout-btn">Cerrar sesión</a>
    </div>

    <!-- Contenido específico según el rol -->
    <?php if (session('role') === 'administrador'): ?>
        <h2>Panel de Administración</h2>
        <p>Aquí irían las opciones específicas para administradores.</p>
    <?php elseif (session('role') === 'coordinador'): ?>
        <h2>Panel de Coordinación</h2>
        <p>Aquí irían las opciones específicas para coordinadores.</p>
    <?php endif; ?>
</body>
</html>