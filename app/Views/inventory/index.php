<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --success-color: #1cc88a;
            --info-color: #17a2b8;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.85em;
            font-weight: 700;
            border-radius: 0.25rem;
        }
        
        .status-available { background-color: var(--success-color); color: white; }
        .status-inuse { background-color: var(--primary-color); color: white; }
        .status-repair { background-color: var(--warning-color); color: #000; }
        .status-loaned { background-color: var(--info-color); color: white; }
        
        .action-btn {
            margin-right: 0.3rem;
            margin-bottom: 0.3rem;
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .action-btn {
                width: 100%;
                margin-right: 0;
            }
            
            .filter-form .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="h4 mb-3 mb-md-0 text-gray-800"><?= esc($title) ?></h1>
                <div class="d-flex flex-column flex-md-row gap-2">
                    <a href="/inventory/create" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Agregar nuevo
                    </a>
                    <a href="/inventory/report" class="btn btn-outline-secondary">
                        <i class="fas fa-file-export me-1"></i> Generar reporte
                    </a>
                    <a href="/admin" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Atrás
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <form method="get" action="<?= base_url('/inventory') ?>" class="mb-4 row g-2 align-items-center filter-form">
                    <div class="col-md-3 col-12 mb-2">
                        <input type="text" name="search" value="<?= esc($search ?? '') ?>" class="form-control" placeholder="Buscar... (código, nombre)">
                    </div>
                    <div class="col-md-2 col-12 mb-2">
                        <select name="status" class="form-select">
                            <option value="">Estado (todos)</option>
                            <option value="Disponible" <?= (isset($status) && $status === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                            <option value="En uso" <?= (isset($status) && $status === 'En uso') ? 'selected' : '' ?>>En uso</option>
                            <option value="En reparación" <?= (isset($status) && $status === 'En reparación') ? 'selected' : '' ?>>En reparación</option>
                            <option value="Prestado" <?= (isset($status) && $status === 'Prestado') ? 'selected' : '' ?>>Prestado</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <input type="text" name="responsible" value="<?= esc($responsible ?? '') ?>" class="form-control" placeholder="Responsable">
                    </div>
                    <div class="col-md-4 col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        <a href="<?= base_url('/inventory') ?>" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-broom me-1"></i> Limpiar
                        </a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="inventoryTable" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                                <th>Responsable</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= esc($item['id']) ?></td>
                                        <td><?= esc($item['code']) ?></td>
                                        <td><?= esc($item['name']) ?></td>
                                        <td><?= esc($item['location']) ?></td>
                                        <td>
                                            <?php
                                                $statusClass = 'status-available';
                                                switch($item['status']) {
                                                    case 'En uso': $statusClass = 'status-inuse'; break;
                                                    case 'En reparación': $statusClass = 'status-repair'; break;
                                                    case 'Prestado': $statusClass = 'status-loaned'; break;
                                                }
                                            ?>
                                            <span class="status-badge <?= $statusClass ?>"><?= esc($item['status']) ?></span>
                                        </td>
                                        <td><?= esc($item['current_responsible']) ?></td>
                                        <td class="text-nowrap">
                                            <a href="/inventory/edit/<?= $item['id'] ?>" class="btn btn-warning btn-sm action-btn" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/inventory/update-location/<?= $item['id'] ?>" class="btn btn-info btn-sm action-btn" title="Cambiar ubicación">
                                                <i class="fas fa-location-arrow"></i>
                                            </a>
                                            <a href="/inventory/qr/<?= $item['id'] ?>" target="_blank" class="btn btn-secondary btn-sm action-btn" title="Ver QR">
                                                <i class="fas fa-qrcode"></i>
                                            </a>
                                            <a href="/inventory/delete/<?= $item['id'] ?>" 
                                               onclick="return confirm('¿Está seguro de eliminar este artículo?')" 
                                               class="btn btn-danger btn-sm action-btn" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">No hay artículos registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#inventoryTable').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                dom: '<"top"<"row"<"col-md-6"l><"col-md-6"f>>>rt<"bottom"<"row"<"col-md-6"i><"col-md-6"p>>>',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                }
            });
            
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>