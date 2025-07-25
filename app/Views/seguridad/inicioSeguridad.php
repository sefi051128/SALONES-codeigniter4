<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - EventMobiliario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EventMobiliario</a>
            <span class="navbar-text ms-auto">
                <i class="fas fa-user-shield me-2"></i><?= esc($user['username']) ?>
            </span>
            <a href="<?= site_url('logout') ?>" class="btn btn-outline-light ms-3">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-shield-alt me-2"></i>Menú Seguridad
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active">
                            <i class="fas fa-home me-2"></i>Inicio
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-clipboard-check me-2"></i>Control de Accesos
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-exclamation-triangle me-2"></i>Incidentes
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard de Seguridad
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bienvenido, <?= esc($user['username']) ?></h5>
                        <p class="card-text">Panel de control para el equipo de seguridad.</p>
                        <!-- Contenido específico del dashboard aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>