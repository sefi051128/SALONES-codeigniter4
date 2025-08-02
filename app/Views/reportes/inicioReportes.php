<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Incidentes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #modalImage {
    max-width: 100%;
    height: auto;
}

        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .card-report {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .card-header-report {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 1.5rem;
        }
        .filter-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .table-img {
            max-width: 80px;
            height: auto;
            border-radius: 4px;
            object-fit: cover;
            cursor: pointer;
        }
        .badge-dano { background-color: #ef4444; }
        .badge-perdida { background-color: #f59e0b; }
        .badge-otro { background-color: #3b82f6; }
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }
        @media (max-width: 992px) {
            .filter-buttons {
                flex-direction: column;
                gap: 10px;
            }
            .filter-buttons a, .filter-buttons button {
                width: 100%;
            }
        }
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }
            .table-img {
                max-width: 60px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            .action-buttons a {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-header card-header-report">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h2 class="h4 mb-2 mb-md-0">
                <i class="fas fa-file-alt text-primary me-2"></i>Reportes de Incidentes
            </h2>
            <p class="text-muted mb-0 small">Registro y seguimiento de incidentes en el almacén</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button onclick="generarReporte()" class="btn btn-primary">
                <i class="fas fa-file-pdf me-1"></i> Reporte
            </button>
            <a href="/admin" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Atrás
            </a>
        </div>
    </div>
</div>
            
            <div class="card-body">
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?= esc($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= esc($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filtros por fecha/hora -->
                <div class="filter-card p-3 mb-4">
                    <form method="get" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Desde</label>
                            <input type="datetime-local" name="from" class="form-control" value="<?= esc($from ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hasta</label>
                            <input type="datetime-local" name="to" class="form-control" value="<?= esc($to ?? '') ?>">
                        </div>
                        <div class="col-md-4 d-flex flex-wrap gap-2 align-items-end filter-buttons">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Filtrar
                            </button>
                            <a href="<?= base_url('reportes') ?>" class="btn btn-outline-secondary flex-grow-1">
                                <i class="fas fa-broom me-1"></i> Limpiar
                            </a>
                            <a href="<?= base_url('reportes/nuevo') ?>" class="btn btn-success flex-grow-1">
                                <i class="fas fa-plus-circle me-1"></i> Nuevo
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Historial -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap"><i class="fas fa-calendar me-1"></i> Evento</th>
                                <th><i class="fas fa-box me-1"></i> Artículo</th>
                                <th class="text-nowrap"><i class="fas fa-tag me-1"></i> Tipo</th>
                                <th><i class="fas fa-align-left me-1"></i> Descripción</th>
                                <th class="text-nowrap"><i class="fas fa-clock me-1"></i> Fecha/Hora</th>
                                <th><i class="fas fa-camera me-1"></i> Foto</th>
                                <th class="text-nowrap"><i class="fas fa-cog me-1"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($reportes)): ?>
                            <?php foreach($reportes as $r): ?>
                                <tr>
                                    <td><?= esc($r['event_name'] ?? '—') ?></td>
                                    <td><?= esc($r['item_name'] ?? '—') ?></td>
                                    <td>
                                        <?php 
                                            $typeLower = strtolower($r['incident_type'] ?? '');
                                            $badgeClass = $typeLower === 'daño' || $typeLower === 'danio' 
                                                ? 'badge-dano' 
                                                : ($typeLower === 'pérdida' ? 'badge-perdida' : 'badge-otro');
                                            $displayType = ucfirst($r['incident_type']);
                                        ?>
                                        <span class="badge rounded-pill <?= $badgeClass ?>">
                                            <?= esc($displayType) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" 
                                             data-bs-toggle="tooltip" data-bs-placement="top" 
                                             title="<?= esc($r['description']) ?>">
                                            <?= esc($r['description']) ?>
                                        </div>
                                    </td>
                                    <td class="text-nowrap"><?= esc($r['report_date']) ?></td>
                                    <td>
    <?php if (!empty($r['photo_url'])): ?>
    <img src="<?= base_url('writable/uploads/reportes/' . $r['photo_url']) ?>" 
         alt="Foto reporte" 
         class="table-img img-thumbnail"
         data-bs-toggle="modal" data-bs-target="#imageModal"
         onclick="document.getElementById('modalImage').src = this.src">
<?php else: ?>
    <span class="text-muted">—</span>
<?php endif; ?>

</td>

                                    <td>
                                        <div class="d-flex gap-2 action-buttons">
                                            <a href="<?= base_url('reportes/editar/'.$r['id']) ?>" 
                                               class="btn btn-sm btn-warning flex-grow-1">
                                                <i class="fas fa-edit me-1 d-none d-md-inline"></i> Editar
                                            </a>
                                            <a href="<?= base_url('reportes/eliminar/'.$r['id']) ?>" 
                                               class="btn btn-sm btn-danger flex-grow-1" 
                                               onclick="return confirm('¿Está seguro que desea eliminar este reporte permanentemente?')">
                                                <i class="fas fa-trash me-1 d-none d-md-inline"></i> Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5 class="h6 mt-2">No se encontraron reportes</h5>
                                    <p class="small">No hay incidentes registrados en el rango seleccionado</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para vista de imagen -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-0">
                    <img id="modalImage" src="" class="img-fluid" style="max-height: 80vh;">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script para mejorar la interacción -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>

    <script>
    function generarReporte() {
        // Obtener parámetros de filtro
        const from = document.querySelector('input[name="from"]').value;
        const to = document.querySelector('input[name="to"]').value;
        
        // Construir la URL para el reporte
        let url = '<?= base_url("reportes/generar-pdf") ?>';
        
        // Agregar filtros si existen
        const params = new URLSearchParams();
        if (from) params.append('from', from);
        if (to) params.append('to', to);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        // Abrir en nueva pestaña
        window.open(url, '_blank');
    }
</script>

</body>
</html>
