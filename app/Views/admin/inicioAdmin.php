<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Control</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
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
            transition: transform 0.3s;
        }
        
        .card-dashboard:hover {
            transform: translateY(-5px);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th {
            background-color: var(--dark-color);
            color: white;
            font-weight: 600;
        }
        
        .action-btn {
            margin-right: 0.3rem;
            margin-bottom: 0.3rem;
        }
        
        @media (max-width: 768px) {
            .action-btn {
                width: 100%;
                margin-right: 0;
            }
            
            .nav-links .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        /* Efecto hover para todos los botones */
    .nav-links .btn {
        transition: all 0.3s ease;
        min-width: 110px;
        text-align: center;
    }
    .nav-links .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Responsividad mejorada */
    @media (max-width: 768px) {
        .nav-links {
            gap: 0.5rem;
        }
        .nav-links .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Tarjeta de información del usuario -->
        <div class="user-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h4 mb-2 text-gray-800">Bienvenido, <?= esc(session('username')) ?></h1>
                    <p class="mb-1"><strong>Rol:</strong> <span class="badge-role badge-<?= strtolower(session('role')) ?>"><?= esc(session('role')) ?></span></p>
                    <p class="mb-0"><strong>ID de Usuario:</strong> <?= esc(session('user_id')) ?></p>
                </div>
                <div class="col-md-4">
   <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2 nav-links">
    <!-- Chat Interno - Azul más claro para diferenciar -->
    <a href="<?= base_url('/chat') ?>" class="btn btn-info text-white">
        <i class="fas fa-comment-dots me-1"></i> Chat
    </a>   
    
    <!-- Sedes - Azul oscuro para ubicaciones -->
    <a href="<?= base_url('sedes') ?>" class="btn btn-primary">
        <i class="fas fa-location-dot me-1"></i> Sedes
    </a>
    
    <!-- Inventario - Verde para gestión -->
    <a href="<?= base_url('/inventory') ?>" class="btn btn-success">
        <i class="fas fa-box-open me-1"></i> Inventario
    </a>
    
    <!-- Alertas - Naranja/rojo para urgencia -->
    <a href="<?= base_url('/alertas') ?>" class="btn btn-danger">
        <i class="fas fa-circle-exclamation me-1"></i> Alertas
    </a>
    
    <!-- Accesos - Púrpura para seguridad -->
    <a href="<?= base_url('/almacen') ?>" class="btn btn-purple" style="background-color: #6f42c1; color: white;">
        <i class="fas fa-key me-1"></i> Accesos
    </a>
    
    <!-- Reportes - Gris oscuro para documentos -->
    <a href="<?= base_url('/reportes') ?>" class="btn btn-dark">
        <i class="fas fa-chart-bar me-1"></i> Reportes
    </a>
    
    <!-- Notificaciones - Amarillo claro para avisos -->
    <a href="<?= base_url('/notificaciones') ?>" class="btn btn-warning">
        <i class="fas fa-bell-on me-1"></i> Notificaciones
    </a>
    
    <?php if (session('role') === 'administrador'): ?>
        <!-- Usuarios - Verde más oscuro para admin -->
        <a href="<?= base_url('/users') ?>" class="btn btn-success" style="background-color: #198754;">
            <i class="fas fa-user-gear me-1"></i> Usuarios
        </a>
    <?php endif; ?>
    
    <!-- Cerrar sesión - Rojo para acción crítica -->
    <a href="<?= base_url('/logout') ?>" class="btn btn-danger">
        <i class="fas fa-right-from-bracket me-1"></i> Salir
    </a>
</div>


</div>
</div>
</div>

        <?php if (session('role') === 'administrador'): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
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
                                <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
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
                                            <td class="text-nowrap">
                                                <a href="<?= base_url('/admin/editar/'.$usuario['id']) ?>" class="btn btn-sm btn-warning action-btn" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('/admin/eliminar/'.$usuario['id']) ?>" 
                                                   class="btn btn-sm btn-danger action-btn" 
                                                   title="Eliminar"
                                                   onclick="return confirm('¿Eliminar usuario <?= esc($usuario['username']) ?>?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <a href="<?= base_url('/admin/crear') ?>" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Nuevo Usuario
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Vista por defecto del panel -->
                        <div class="row">
                            <div class="col-xl-6 mb-4">
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
                            
                            <div class="col-xl-6 mb-4">
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
    <!-- DataTables (opcional) -->
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
                    responsive: true
                });
            }
            
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>