<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel de Cliente</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .user-info { background: #f5f5f5; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; }
        .logout-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="user-info">
        <h1>Bienvenido Cliente, <?= esc(session('username')) ?></h1>
        <a href="<?= base_url('/logout') ?>" class="logout-btn">Cerrar sesión</a>
    </div>

    <h2>Contenido Exclusivo para Clientes</h2>
    <!-- Aquí va el contenido específico para clientes -->
</body>
</html>