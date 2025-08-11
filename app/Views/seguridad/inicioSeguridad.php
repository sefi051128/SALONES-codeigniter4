<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= esc($title) ?> - EventMobiliario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .sidebar {
            position: sticky;
            top: 20px;
        }
        .navbar-brand {
            font-weight: 600;
        }
        .card-header {
            font-weight: 500;
        }
        .list-group-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .alert-card {
            transition: transform 0.2s;
            border-left: 4px solid;
        }
        .alert-card:hover {
            transform: translateY(-3px);
        }
        .security-badge {
            font-size: 0.75rem;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                position: static;
                margin-bottom: 20px;
            }
            .navbar-text {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Barra de navegación mejorada -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-shield-alt me-2"></i>
                <span>EventMobiliario</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="navbar-nav ms-auto align-items-center">
    <!-- Botón de Accesos al Almacén (prioritario) -->
    <a href="<?= base_url('almacen') ?>" class="nav-link btn btn-outline-light btn-sm me-2">
        <i class="fas fa-key me-1"></i> Accesos
    </a>
    
    <!-- Botón de Incidentes -->
    <a href="<?= base_url('reportes') ?>" class="nav-link btn btn-outline-light btn-sm me-2">
        <i class="fas fa-clipboard-exclamation me-1"></i> Incidentes
    </a>
    
    <!-- Usuario y Salir (existente) -->
    <span class="nav-item navbar-text me-3">
        <i class="fas fa-user-shield me-1"></i><?= esc($user['username']) ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="nav-link btn btn-outline-light btn-sm">
        <i class="fas fa-sign-out-alt"></i> Salir
    </a>
</div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container my-4 my-lg-5">
        <div class="row">
            <!-- Menú lateral - Versión responsiva -->
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="card shadow-sm sidebar">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-shield-alt me-2"></i>
                        <span>Menú Seguridad</span>
                    </div>
                    <div class="list-group list-group-flush">
    <a href="<?= base_url('seguridad/dashboard') ?>" class="list-group-item list-group-item-action active d-flex align-items-center">
        <i class="fas fa-home me-2"></i>Inicio
    </a>
    <a href="<?= base_url('almacen') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="fas fa-clipboard-check me-2"></i>Control de Accesos
    </a>
    <a href="<?= base_url('reportes/nuevo?tipo=incidente') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="fas fa-exclamation-triangle me-2"></i>Reportar Incidente
    </a>
    <a href="<?= base_url('monitoreo') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="fas fa-camera me-2"></i>Monitoreo (CCTV)
    </a>
    <a href="<?= base_url('reportes') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="fas fa-file-alt me-2"></i>Reportes Diarios
    </a>
    <a href="<?= base_url('chat') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="fas fa-comments me-2"></i>Chat Interno
    </a>
</div>
                </div>
            </div>
            
            <!-- Panel principal -->
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>Dashboard de Seguridad</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                            <div>
                                <h5 class="card-title mb-1">Bienvenido, <?= esc($user['username']) ?></h5>
                                <p class="card-text text-muted mb-0">Panel de control para el equipo de seguridad</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-bell me-1"></i> Alertas
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-cog me-1"></i> Configuración
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjetas de estado -->
                        <div class="row g-3 mb-4">
    <!-- Incidentes (link a reportes) -->
    <div class="col-md-4">
        <a href="<?= base_url('reportes?tipo=incidente') ?>" class="card border-primary h-100 alert-card border-left-warning text-decoration-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Incidentes Hoy</h6>
                        <h3 class="mb-0"><?= $incidentes_hoy ?? '0' ?></h3>
                        <small class="text-warning"><?= $incidentes_sin_resolver ?? '0' ?> sin resolver</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-exclamation-triangle text-primary"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Accesos al Almacén -->
    <div class="col-md-4">
        <a href="<?= base_url('almacen') ?>" class="card border-primary h-100 alert-card border-left-success text-decoration-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Accesos Hoy</h6>
                        <h3 class="mb-0"><?= $accesos_hoy ?? '0' ?></h3>
                        <small class="text-success"><?= $accesos_autorizados ?? '0' ?> autorizados</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-door-open text-primary"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Alertas Críticas -->
    <div class="col-md-4">
        <a href="<?= base_url('alertas') ?>" class="card border-primary h-100 alert-card border-left-danger text-decoration-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Alertas Activas</h6>
                        <h3 class="mb-0"><?= $alertas_activas ?? '0' ?></h3>
                        <small class="text-danger"><?= $alertas_criticas ?? '0' ?> críticas</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-bell text-primary"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
                        
                        <!-- Sección de acciones rápidas -->
                        <div class="d-flex flex-wrap gap-2">
    <!-- Reportar Incidente Rápido -->
    <a href="<?= base_url('reportes/nuevo?tipo=incidente') ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Nuevo Incidente
    </a>
    
    <!-- Registrar Acceso No Programado -->
    <a href="<?= base_url('almacen/nuevo') ?>" class="btn btn-outline-primary">
        <i class="fas fa-user-clock me-1"></i> Registrar Acceso
    </a>
    
    <!-- Ver Cámaras en Tiempo Real -->
    <a href="<?= base_url('monitoreo') ?>" class="btn btn-outline-primary">
        <i class="fas fa-video me-1"></i> Monitoreo CCTV
    </a>
    
    <!-- Checklist de Seguridad -->
    <a href="<?= base_url('reportes/nuevo?tipo=checklist') ?>" class="btn btn-outline-primary">
        <i class="fas fa-clipboard-list me-1"></i> Checklist Diario
    </a>
</div>
                        
                        <!-- Sección de incidentes recientes -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle text-danger me-2"></i> Incidentes Recientes
                                </h6>
                                <a href="#" class="btn btn-sm btn-outline-primary">Ver todos</a>
                            </div>
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">Acceso no autorizado</div>
                                            <small class="text-muted">Área de almacén - 10:32 AM</small>
                                        </div>
                                        <span class="badge bg-danger security-badge">Crítico</span>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">Visitante sin identificación</div>
                                            <small class="text-muted">Entrada principal - 09:15 AM</small>
                                        </div>
                                        <span class="badge bg-warning security-badge">Medio</span>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">Cámara desconectada</div>
                                            <small class="text-muted">Estacionamiento - Ayer 5:45 PM</small>
                                        </div>
                                        <span class="badge bg-success security-badge">Bajo</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Sección de estado de sistemas -->
                        <div>
                            <h6 class="mb-3 d-flex align-items-center">
                                <i class="fas fa-server text-primary me-2"></i> Estado de Sistemas
                            </h6>
                            <div class="row g-2">
                                <div class="col-sm-6 col-md-4">
                                    <div class="p-3 bg-white rounded shadow-sm d-flex align-items-center">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-circle-check fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Sistema de CCTV</div>
                                            <small class="text-muted">12/12 cámaras activas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="p-3 bg-white rounded shadow-sm d-flex align-items-center">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-circle-check fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Control de Accesos</div>
                                            <small class="text-muted">Operativo</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="p-3 bg-white rounded shadow-sm d-flex align-items-center">
                                        <div class="me-3 text-warning">
                                            <i class="fas fa-triangle-exclamation fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Alarmas</div>
                                            <small class="text-muted">Prueba pendiente</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>