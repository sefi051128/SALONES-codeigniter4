<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesos al Almacén</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .card-header-custom {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.1);
            padding: 1.25rem 1.5rem;
        }
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: #6c757d;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .badge-access {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                gap: 0.5rem;
                margin-top: 1rem;
            }
            .header-actions .btn {
                width: 100%;
            }
            .table-responsive {
                font-size: 0.85rem;
            }
            .empty-state i {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-warehouse text-primary fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h4 mb-0">Accesos al Almacén</h2>
                            <p class="text-muted small mb-0">Registro completo de entradas y salidas</p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 header-actions">
    <a href="<?= base_url('almacen/nuevo') ?>" class="btn btn-success">
        <i class="fas fa-plus-circle me-2"></i> Nuevo Acceso
    </a>
    <button onclick="generarReporte()" class="btn btn-primary">
        <i class="fas fa-file-pdf me-2"></i> Generar Reporte
    </button>
    <a href="/admin" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Volver
    </a>
</div>
                </div>
            </div>
            
            <div class="card-body">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap"># Registro</th>
                                <th>Usuario</th>
                                <th class="text-nowrap">Fecha y Hora</th>
                                <th class="text-nowrap">Tipo de Acceso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($accesos)): ?>
                                <?php foreach ($accesos as $acceso): ?>
                                    <tr>
                                        <td class="fw-semibold text-muted"><?= esc($acceso['id']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= esc($acceso['username']) ?></div>
                                                    <small class="text-muted">ID: <?= esc($acceso['user_id'] ?? 'N/A') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="far fa-calendar-alt text-muted me-2"></i>
                                                <div>
                                                    <div><?= date('d/m/Y', strtotime($acceso['access_time'])) ?></div>
                                                    <small class="text-muted"><?= date('H:i', strtotime($acceso['access_time'])) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-access bg-<?= 
                                                $acceso['access_type'] === 'Entrada' ? 'success' : 
                                                ($acceso['access_type'] === 'Salida' ? 'danger' : 'info') 
                                            ?>-subtle text-<?= 
                                                $acceso['access_type'] === 'Entrada' ? 'success' : 
                                                ($acceso['access_type'] === 'Salida' ? 'danger' : 'info') 
                                            ?>">
                                                <i class="fas fa-<?= 
                                                    $acceso['access_type'] === 'Entrada' ? 'sign-in-alt' : 
                                                    ($acceso['access_type'] === 'Salida' ? 'sign-out-alt' : 'exchange-alt') 
                                                ?> me-1"></i>
                                                <?= esc($acceso['access_type']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <i class="fas fa-door-open"></i>
                                        <h5 class="h5 mt-3">No hay registros de accesos</h5>
                                        <p class="text-muted">Cuando se registren accesos, aparecerán en esta tabla</p>
                                        <a href="<?= base_url('almacen/nuevo') ?>" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-1"></i> Registrar primer acceso
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Activar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Mejorar experiencia en móviles
            if (window.innerWidth < 768) {
                document.querySelectorAll('.table td').forEach(function(cell) {
                    cell.setAttribute('data-bs-toggle', 'tooltip');
                    cell.setAttribute('title', cell.textContent.trim());
                });
            }
        });

        function generarReporte() {
    const params = new URLSearchParams(window.location.search);
    let url = '<?= route_to('almacen.reporte') ?>';
    if (params.toString()) {
        url += '?' + params.toString();
    }
    window.open(url, '_blank');
}
    </script>
    
</body>
</html>