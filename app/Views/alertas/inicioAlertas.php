<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas de Inventario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .btn-sm-block {
                display: block;
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h2 class="h4 mb-0">Lista de Alertas</h2>
    <div>
        <button onclick="generarReporte()" class="btn btn-primary btn-sm me-2">
            <i class="fas fa-file-pdf me-1"></i> Reporte
        </button>
        <a href="/admin" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Atrás
        </a>
    </div>
</div>
            
            <div class="card-body">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">ID</th>
                                <th>Artículo</th>
                                <th class="text-nowrap">Tipo de alerta</th>
                                <th class="text-nowrap">Fecha de alerta</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($alertas) && is_array($alertas)): ?>
                                <?php foreach ($alertas as $alerta): ?>
                                    <tr>
                                        <td><?= esc($alerta['id']) ?></td>
                                        <td><?= esc($alerta['item_name'] ?? $alerta['item_id']) ?></td>
                                        <td><?= esc($alerta['alert_type']) ?></td>
                                        <td><?= esc($alerta['alert_date']) ?></td>
                                        <td>
                                            <span class="badge <?= $alerta['resolved'] ? 'bg-success' : 'bg-warning' ?>">
                                                <?= $alerta['resolved'] ? 'Resuelta' : 'Pendiente' ?>
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <?php if (! $alerta['resolved']): ?>
                                                <a href="<?= site_url('alertas/resolver/' . $alerta['id']) ?>" class="btn btn-success btn-sm btn-sm-block">
                                                    <i class="fas fa-check"></i> Resolver
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= site_url('alertas/editar/' . $alerta['id']) ?>" class="btn btn-warning btn-sm btn-sm-block">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No hay alertas registradas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                    <a href="<?= site_url('alertas/crear') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Crear Nueva Alerta
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function generarReporte() {
        // Obtener parámetros de filtro si los hay
        const params = new URLSearchParams(window.location.search);
        
        // Construir la URL para el reporte
        let url = '<?= base_url("alertas/reporte") ?>';
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        // Abrir en nueva pestaña
        window.open(url, '_blank');
    }
</script>
</body>
</html>