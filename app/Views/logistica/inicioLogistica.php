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
            background-color: #198754;
            border-color: #198754;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-truck-loading me-2"></i>
                <span>EventMobiliario</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="navbar-nav ms-auto align-items-center">
                    <span class="nav-item navbar-text me-3">
                        <i class="fas fa-truck me-1"></i><?= esc($user['username']) ?>
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
                    <div class="card-header bg-success text-white d-flex align-items-center">
                        <i class="fas fa-boxes me-2"></i>
                        <span>Menú Logística</span>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active d-flex align-items-center">
                            <i class="fas fa-home me-2"></i>Inicio
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-shipping-fast me-2"></i>Gestión de Envíos
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-warehouse me-2"></i>Inventario
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-map-marked-alt me-2"></i>Rutas
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-clipboard-list me-2"></i>Checklists
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
                    <div class="card-header bg-success text-white d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>Dashboard de Logística</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                            <div>
                                <h5 class="card-title mb-1">Bienvenido, <?= esc($user['username']) ?></h5>
                                <p class="card-text text-muted mb-0">Panel de control para el equipo de logística</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-question-circle me-1"></i> Ayuda
                                    </a>
                                    <a href="#" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-bell me-1"></i> Alertas
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tarjetas resumen -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card border-success h-100 stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Envíos Hoy</h6>
                                                <h3 class="mb-0">8</h3>
                                                <small class="text-success">+2 desde ayer</small>
                                            </div>
                                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                                <i class="fas fa-shipping-fast text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success h-100 stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Items en Inventario</h6>
                                                <h3 class="mb-0">142</h3>
                                                <small class="text-danger">-5 en uso</small>
                                            </div>
                                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                                <i class="fas fa-box-open text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success h-100 stat-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted mb-2">Pendientes</h6>
                                                <h3 class="mb-0">3</h3>
                                                <small class="text-warning">Urgentes: 1</small>
                                            </div>
                                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                                <i class="fas fa-exclamation-triangle text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección de acciones rápidas -->
                        <div class="mb-4">
                            <h6 class="mb-3 d-flex align-items-center">
                                <i class="fas fa-bolt text-warning me-2"></i> Acciones rápidas
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="#" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-1"></i> Nuevo Envío
                                </a>
                                <a href="#" class="btn btn-outline-success">
                                    <i class="fas fa-barcode me-1"></i> Escanear Item
                                </a>
                                <a href="#" class="btn btn-outline-success">
                                    <i class="fas fa-route me-1"></i> Planificar Ruta
                                </a>
                                <a href="#" class="btn btn-outline-success">
                                    <i class="fas fa-file-export me-1"></i> Generar Reporte
                                </a>
                            </div>
                        </div>
                        
                        <!-- Sección de envíos pendientes -->
                        <div class="mb-4">
                            <h6 class="mb-3 d-flex align-items-center">
                                <i class="fas fa-clock text-success me-2"></i> Envíos Pendientes
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Destino</th>
                                            <th>Items</th>
                                            <th>Prioridad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#LOG-7842</td>
                                            <td>Conferencia Tech</td>
                                            <td>12</td>
                                            <td><span class="badge bg-warning">Media</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success">Detalles</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#LOG-7841</td>
                                            <td>Expo Diseño</td>
                                            <td>8</td>
                                            <td><span class="badge bg-danger">Alta</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success">Detalles</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#LOG-7839</td>
                                            <td>Seminario Marketing</td>
                                            <td>5</td>
                                            <td><span class="badge bg-success">Baja</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success">Detalles</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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