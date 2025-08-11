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
            background-color: #0dcaf0;
            border-color: #0dcaf0;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-calendar-check me-2"></i>
                <span>EventMobiliario</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="navbar-nav ms-auto align-items-center">
    <!-- Botón de Alertas (prioritario para coordinadores) -->
    <a href="<?= base_url('/alertas') ?>" class="nav-link btn btn-outline-light btn-sm me-2">
        <i class="fas fa-circle-exclamation me-1"></i> Alertas
    </a>
    
    <!-- Botón de Reportes -->
    <a href="<?= base_url('/reportes') ?>" class="nav-link btn btn-outline-light btn-sm me-2">
        <i class="fas fa-chart-bar me-1"></i> Reportes
    </a>
    
    <!-- Usuario y Salir (existente) -->
    <span class="nav-item navbar-text me-3">
        <i class="fas fa-users-cog me-1"></i><?= esc($user['username']) ?>
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
                    <div class="card-header bg-info text-white d-flex align-items-center">
                        <i class="fas fa-tasks me-2"></i>
                        <span>Menú Coordinador</span>
                    </div>
                    <div class="list-group list-group-flush">
    <a href="<?= base_url('coordinador/dashboard') ?>" class="list-group-item list-group-item-action active d-flex align-items-center">
        <i class="fas fa-home me-2"></i>Inicio
    </a>
    <a href="<?= base_url('eventos') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="fas fa-calendar-alt me-2"></i>Eventos
    </a>
    <a href="<?= base_url('inventory') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="fas fa-box-open me-2"></i>Inventario
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
                    <div class="card-header bg-info text-white d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>Dashboard de Coordinación</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                            <div>
                                <h5 class="card-title mb-1">Bienvenido, <?= esc($user['username']) ?></h5>
                                <p class="card-text text-muted mb-0">Panel de control para coordinadores de eventos</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-question-circle me-1"></i> Ayuda
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-cog me-1"></i> Configuración
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjetas Resumen Actualizadas -->
<div class="row g-3 mb-4">
    <!-- Eventos Activos -->
    <div class="col-md-4">
        <a href="<?= base_url('eventos') ?>" class="card border-info h-100 text-decoration-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Eventos Activos</h6>
                        <h3 class="mb-0"><?= isset($eventos_activos) ? count($eventos_activos) : '0' ?></h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="fas fa-calendar-day text-info"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Artículos Disponibles -->
    <div class="col-md-4">
        <a href="<?= base_url('inventory') ?>" class="card border-info h-100 text-decoration-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Artículos Disponibles</h6>
                        <h3 class="mb-0"><?= isset($inventario) ? count($inventario) : '0' ?></h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="fas fa-box-open text-info"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Alertas Pendientes -->
    <div class="col-md-4">
        <a href="<?= base_url('alertas') ?>" class="card border-danger h-100 text-decoration-none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Alertas Pendientes</h6>
                        <h3 class="mb-0"><?= isset($alertas) ? count($alertas) : '0' ?></h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
                        
                        <!-- Sección de acciones rápidas -->
                        <div class="mb-4">
                            <h6 class="mb-3 d-flex align-items-center">
                                <i class="fas fa-bolt text-warning me-2"></i> Acciones rápidas
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
    <!-- Nuevo Evento (link al formulario de creación) -->
    <a href="<?= base_url('eventos/crear') ?>" class="btn btn-outline-info">
        <i class="fas fa-plus-circle me-1"></i> Nuevo Evento
    </a>
    
    <!-- Ver Alertas Pendientes -->
    <a href="<?= base_url('alertas') ?>" class="btn btn-outline-danger">
        <i class="fas fa-circle-exclamation me-1"></i> Alertas Activas
    </a>
    
    <!-- Generar Reporte Rápido -->
    <a href="<?= base_url('reportes/nuevo') ?>" class="btn btn-outline-dark">
        <i class="fas fa-file-export me-1"></i> Generar Reporte
    </a>
</div>
                        </div>
                        
                        <!-- Sección de actividad reciente -->
                        <div>
                            <h6 class="mb-3 d-flex align-items-center">
                                <i class="fas fa-history text-info me-2"></i> Actividad reciente
                            </h6>
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <span>Evento "Conferencia Tech" actualizado</span>
                                        <small class="text-muted">Hace 2 horas</small>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <span>Nuevo equipo asignado</span>
                                        <small class="text-muted">Hace 5 horas</small>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between">
                                        <span>Mensaje recibido de Juan Pérez</span>
                                        <small class="text-muted">Ayer</small>
                                    </div>
                                </a>
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