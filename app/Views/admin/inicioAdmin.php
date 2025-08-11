<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Panel de Control</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
            --purple-color: #6f42c1;
            --dark-green: #198754;
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
        }
        
        .user-card {
            background: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .badge-role {
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.5rem 0.8rem;
            border-radius: 0.35rem;
            color: white;
        }
        
        .badge-admin { background-color: var(--danger-color); }
        .badge-coord { background-color: var(--warning-color); color: #000; }
        .badge-client { background-color: var(--success-color); }
        .badge-logistic { background-color: var(--info-color); }
        .badge-security { background-color: var(--dark-color); }
        
        .card-dashboard {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table th {
            background-color: var(--dark-color);
            color: white;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .action-btn {
            margin-right: 0.3rem;
            margin-bottom: 0.3rem;
            min-width: 32px;
        }
        
        .nav-links .btn {
            transition: all 0.3s ease;
            min-width: 110px;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        
        .nav-links .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: rgba(78, 115, 223, 0.1);
            border-bottom: 1px solid rgba(78, 115, 223, 0.2);
        }
        
        .alert {
            border-left: 4px solid transparent;
        }
        
        .alert-success {
            border-left-color: var(--success-color);
        }
        
        .alert-danger {
            border-left-color: var(--danger-color);
        }
        
        /* Estilos responsivos mejorados */
        @media (max-width: 992px) {
            .user-card .col-md-8, 
            .user-card .col-md-4 {
                text-align: center;
            }
            
            .user-card .col-md-4 {
                margin-top: 1.5rem;
            }
            
            .nav-links {
                justify-content: center !important;
            }
        }
        
        @media (max-width: 768px) {
            .action-btn {
                width: 100%;
                margin-right: 0;
            }
            
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
            
            .badge-role {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }
            
            .user-card {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .h4 {
                font-size: 1.25rem;
            }
        }
        
        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .user-card {
                margin-bottom: 1rem;
            }
            
            .nav-links .btn {
                width: 100%;
                margin-right: 0;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        
        /* Efecto de carga suave para las tarjetas */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-dashboard {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Retrasos para el efecto escalonado */
        .card-dashboard:nth-child(1) { animation-delay: 0.1s; }
        .card-dashboard:nth-child(2) { animation-delay: 0.2s; }
    </style>
</head>
<body>
    <div class="container-fluid py-3 py-md-4">
        <!-- Tarjeta de información del usuario -->
        <div class="user-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h4 mb-2 text-gray-800">Bienvenido, <?= esc(session('username')) ?></h1>
                    <!--
                    <p class="mb-1"><strong>Rol:</strong> <span class="badge-role badge-<?= strtolower(session('role')) ?>"><?= esc(session('role')) ?></span></p>
                    -->
                    <p class="mb-0"><strong>ID de Usuario:</strong> <?= esc(session('user_id')) ?></p>
                </div>
                <div class="col-md-4">
                    <div class="d-flex flex-column flex-md-row flex-wrap justify-content-md-end gap-2 nav-links">
                        <!-- Chat Interno -->
                        <a href="<?= base_url('/chat') ?>" class="btn btn-info text-white">
                            <i class="fas fa-comment-dots me-1"></i> Chat
                        </a>   
                        
                        <!-- Sedes -->
                        <a href="<?= base_url('sedes') ?>" class="btn btn-primary">
                            <i class="fas fa-location-dot me-1"></i> Sedes
                        </a>
                        
                        <!-- Inventario -->
                        <a href="<?= base_url('/inventory') ?>" class="btn btn-success">
                            <i class="fas fa-box-open me-1"></i> Inventario
                        </a>
                        
                        <!-- Alertas -->
                        <a href="<?= base_url('/alertas') ?>" class="btn btn-danger">
                            <i class="fas fa-circle-exclamation me-1"></i> Alertas
                        </a>
                        
                        <!-- Accesos -->
                        <a href="<?= base_url('/almacen') ?>" class="btn text-white" style="background-color: var(--purple-color);">
                            <i class="fas fa-key me-1"></i> Accesos
                        </a>
                        
                        <!-- Reportes -->
                        <a href="<?= base_url('/reportes') ?>" class="btn btn-dark">
                            <i class="fas fa-chart-bar me-1"></i> Reportes
                        </a>
                        
                        <!-- Notificaciones -->
                        <a href="<?= base_url('/notificaciones') ?>" class="btn btn-warning">
                            <i class="fas fa-bell me-1"></i> Notificaciones
                        </a>
                        
                        <?php if (session('role') === 'administrador'): ?>
                            <!-- Usuarios -->
                            <a href="<?= base_url('/users') ?>" class="btn text-white" style="background-color: var(--dark-green);">
                                <i class="fas fa-user-gear me-1"></i> Usuarios
                            </a>
                        <?php endif; ?>
                        
                        <!-- Cerrar sesión -->
                        <a href="<?= base_url('/logout') ?>" class="btn btn-danger">
                            <i class="fas fa-right-from-bracket me-1"></i> Salir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (session('role') === 'administrador'): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>Panel de Administración
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (isset($show_users) && $show_users): ?>
                        <!-- Sección de Gestión de Usuarios -->
                        <div class="users-section">
                            <?php if(session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped" id="usersTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuario</th>
                                            <th>Rol</th>
                                            <th>Contacto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($usuarios as $usuario): ?>
                                        <tr>
                                            <td><?= esc($usuario['id']) ?></td>
                                            <td><?= esc($usuario['username']) ?></td>
                                            <td>
                                                <?php 
                                                    $badgeClass = 'badge-primary';
                                                    switch(strtolower($usuario['role'])) {
                                                        case 'administrador': $badgeClass = 'badge-admin'; break;
                                                        case 'coordinador': $badgeClass = 'badge-coord'; break;
                                                        case 'cliente': $badgeClass = 'badge-client'; break;
                                                        case 'logística': $badgeClass = 'badge-logistic'; break;
                                                        case 'seguridad': $badgeClass = 'badge-security'; break;
                                                    }
                                                ?>
                                                <span class="badge-role <?= $badgeClass ?>"><?= esc($usuario['role']) ?></span>
                                            </td>
                                            <td><?= esc($usuario['contact_info'] ?? 'N/A') ?></td>
                                            <td>
                                                <div class="d-flex flex-wrap">
                                                    <a href="<?= base_url('/admin/editar/'.$usuario['id']) ?>" class="btn btn-sm btn-warning action-btn" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('/admin/eliminar/'.$usuario['id']) ?>" 
                                                       class="btn btn-sm btn-danger action-btn" 
                                                       title="Eliminar"
                                                       onclick="return confirm('¿Eliminar usuario <?= esc($usuario['username']) ?>?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <a href="<?= base_url('/admin/crear') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Nuevo Usuario
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Vista por defecto del panel -->
                        <div class="row">
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2 card-dashboard">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total de Usuarios</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($usuarios ?? []) ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2 card-dashboard">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Acciones Rápidas</div>
                                                <div class="mb-1">
                                                    <a href="<?= base_url('/admin/usuarios') ?>" class="text-success"><i class="fas fa-arrow-right me-1"></i>Ver usuarios</a>
                                                </div>
                                                <div class="mb-1">
                                                    <a href="<?= base_url('/admin/crear') ?>" class="text-success"><i class="fas fa-arrow-right me-1"></i>Crear usuario</a>
                                                </div>
                                                <div>
                                                    <a href="#" class="text-success"><i class="fas fa-arrow-right me-1"></i>Accesos al almacén</a>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-bolt fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted">Seleccione una opción del menú superior o de las tarjetas de resumen.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Inicializar DataTable si existe la tabla
            if ($('#usersTable').length) {
                $('#usersTable').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                    },
                    responsive: true,
                    autoWidth: false,
                    columnDefs: [
                        { responsivePriority: 1, targets: 0 }, // ID
                        { responsivePriority: 2, targets: 1 }, // Usuario
                        { responsivePriority: 3, targets: -1 }, // Acciones
                        { responsivePriority: 4, targets: 2 }, // Rol
                        { responsivePriority: 5, targets: 3 } // Contacto
                    ]
                });
            }
            
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Ajustar el ancho de los botones en dispositivos móviles
            function adjustButtons() {
                if ($(window).width() < 768) {
                    $('.action-btn').addClass('w-100');
                } else {
                    $('.action-btn').removeClass('w-100');
                }
            }
            
            // Ejecutar al cargar y al redimensionar
            adjustButtons();
            $(window).resize(adjustButtons);
        });
    </script>
</body>
</html>