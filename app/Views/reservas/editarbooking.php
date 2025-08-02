<?php use CodeIgniter\I18n\Time; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        body { background-color: #f8f9fc; color: #5a5c69; }
        .card { border-radius: .35rem; border: none; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); transition: all .3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1.5rem 0 rgba(58,59,69,.2); }
        .section-title { position: relative; padding-bottom: 10px; margin: 30px 0 20px; color: var(--primary-color); cursor: pointer; }
        .section-title:after { content: ""; position: absolute; left: 0; bottom: 0; width: 50px; height: 3px; background: var(--primary-color); }
        .form-control, .form-select { border-radius: .35rem; padding: .75rem 1rem; }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-edit text-primary me-2"></i><?= esc($title) ?></h2>
            <a href="<?= base_url('reservas') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Errores de validación</h5>
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-1"><?= esc($error) ?></p>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= route_to('reservas.actualizar_booking', $reservation['id']) ?>" method="post" id="editBookingForm">
            <?= csrf_field() ?>

            <!-- Información del Evento -->
            <h4 class="section-title"><i class="fas fa-calendar-alt me-2"></i>Información del Evento</h4>
            <div class="card mb-4">
                <div class="card-body">
                    <?php if(isset($reservation['event_name'])): ?>
                        <p><strong>Evento:</strong> <?= esc($reservation['event_name']) ?></p>
                        <p><i class="fas fa-calendar-day me-1"></i> <?= Time::parse($reservation['event_date'])->toLocalizedString('dd/MM/yyyy h:mm a') ?></p>
                    <?php else: ?>
                        <div class="alert alert-warning">Evento no disponible</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Información del Cliente -->
            <h4 class="section-title"><i class="fas fa-user me-2"></i>Información del Cliente</h4>
            <div class="card mb-4">
                <div class="card-body">
                    <p><i class="fas fa-user"></i> <strong>Cliente:</strong> <?= esc($reservation['customer_name'] ?? 'N/A') ?></p>
                    <p><i class="fas fa-users"></i> <strong>Invitados:</strong> 
                        <input type="number" name="number_of_guests" class="form-control d-inline-block w-auto" min="1" 
                               value="<?= old('number_of_guests', $reservation['number_of_guests']) ?>" required>
                    </p>
                </div>
            </div>

            <!-- Opciones de estado y notas -->
            <h4 class="section-title"><i class="fas fa-cog me-2"></i>Configuración</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="status" class="form-select" required>
                                <?php foreach (['confirmed','pending','cancelled'] as $opt): ?>
                                    <option value="<?= esc($opt) ?>" <?= (old('status', $reservation['status']) === $opt) ? 'selected' : '' ?>>
                                        <?= ucfirst($opt) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Puedes agregar más opciones aquí si necesitas notas u otros campos -->
            </div>

            <!-- Acciones -->
            <div class="d-flex justify-content-between border-top pt-4">
                <a href="<?= base_url('reservas') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
