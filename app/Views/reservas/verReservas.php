<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= session('role') === 'administrador' ? 'Todas las Reservas' : 'Mis Reservas' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .reservation-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .admin-actions {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .items-table {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-calendar-check me-2"></i>
                <?= session('role') === 'administrador' ? 'Todas las Reservas' : 'Mis Reservas' ?>
            </h2>
            <div>
                <a href="/eventos" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Eventos
                </a>
                <?php if(session('role') === 'administrador'): ?>
                    <a href="/reservas/nueva" class="btn btn-primary ms-2">
                        <i class="fas fa-plus-circle"></i> Crear Reserva
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($reservations)): ?>
            <div class="alert alert-info">
                No hay reservas registradas.
            </div>
        <?php else: ?>
            <?php if(session('role') === 'administrador'): ?>
                <div class="admin-actions mb-4">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label for="filterStatus" class="form-label">Filtrar por estado:</label>
                            <select class="form-select" id="filterStatus">
                                <option value="">Todos</option>
                                <option value="pending">Pendientes</option>
                                <option value="confirmed">Confirmadas</option>
                                <option value="cancelled">Canceladas</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterDate" class="form-label">Filtrar por fecha:</label>
                            <input type="date" class="form-control" id="filterDate">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="col-md-12 mb-4"> <!-- Cambiado a col-md-12 para más ancho -->
                        <div class="card reservation-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <?= esc($reservation['venue_name'] ?? 'Reserva de Sala') ?>
                                    <?php if(session('role') === 'administrador'): ?>
                                        <small class="text-muted">(Cliente: <?= esc($reservation['customer_name'] ?? 'N/A') ?>)</small>
                                    <?php endif; ?>
                                </h5>
                                <span class="status-badge status-<?= $reservation['status'] ?>">
                                    <?= ucfirst($reservation['status']) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong><i class="fas fa-calendar-day me-2"></i> Fecha:</strong>
                                            <?= date('d/m/Y H:i', strtotime($reservation['reservation_date'])) ?>
                                        </div>
                                        
                                        <?php if (!empty($reservation['event_name'])): ?>
                                            <div class="mb-3">
                                                <strong><i class="fas fa-star me-2"></i> Evento:</strong>
                                                <?= esc($reservation['event_name']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (!empty($reservation['items'])): ?>
                                            <div class="mb-3">
                                                <strong><i class="fas fa-boxes me-2"></i> Artículos:</strong>
                                                <ul class="list-unstyled">
                                                    <?php foreach ($reservation['items'] as $item): ?>
                                                        <li>
                                                            <?= esc($item['name']) ?> 
                                                            <span class="badge bg-<?= $item['status'] === 'disponible' ? 'success' : 'warning' ?>">
                                                                <?= ucfirst($item['status']) ?>
                                                            </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <a href="/reservas/ver/<?= $reservation['id'] ?>" class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-eye"></i> Ver Detalles Completos
                                    </a>
                                    
                                    <?php if (session('role') === 'administrador' || ($reservation['status'] == 'pending' || $reservation['status'] == 'confirmed')): ?>
                                        <?php if(session('role') === 'administrador'): ?>
                                            <?php if($reservation['status'] == 'pending'): ?>
                                                <a href="/reservas/confirmar/<?= $reservation['id'] ?>" class="btn btn-sm btn-success me-2">
                                                    <i class="fas fa-check"></i> Confirmar
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <a href="/reservas/cancelar/<?= $reservation['id'] ?>" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                Creada el <?= date('d/m/Y', strtotime($reservation['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para filtros (solo admin)
        <?php if(session('role') === 'administrador'): ?>
        document.getElementById('filterStatus').addEventListener('change', function() {
            const status = this.value;
            window.location.href = `/reservas?status=${status}`;
        });

        document.getElementById('filterDate').addEventListener('change', function() {
            const date = this.value;
            window.location.href = `/reservas?date=${date}`;
        });
        <?php endif; ?>
    </script>
</body>
</html>