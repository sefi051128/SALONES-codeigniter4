<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .card-img-top { height: 150px; object-fit: cover; }
        .event-card, .item-card {
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        .event-card:hover, .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .event-date, .item-code {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .section-title {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin: 30px 0 20px;
        }
    </style>
</head>
<body>


    <div class="container py-5">
        <h2 class="mb-4"><i class="fas fa-plus-circle"></i> <?= esc($title) ?></h2>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-1"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('reservas/booking/guardar') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Sección de Selección de Evento -->
            <h4 class="section-title"><i class="fas fa-calendar-alt"></i> Información del Evento</h4>
            <?php if(isset($event)): ?>
                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                <div class="alert alert-info">
                    <h5>Estás reservando para el evento: <strong><?= esc($event['name']) ?></strong></h5>
                    <p class="mb-0">Fecha: <?= date('d/m/Y H:i', strtotime($event['date'])) ?></p>
                    <?php if(isset($event['venue_name'])): ?>
                        <p class="mb-0">Sede: <?= esc($event['venue_name']) ?></p>
                    <?php endif; ?>

                    
                </div>
            <?php else: ?>
                <div class="mb-4">
                    <label class="form-label">Seleccionar Evento</label>
                    
                    <?php if(!empty($eventos)): ?>
                        <div class="row">
                            <?php foreach($eventos as $evento): ?>
                                <div class="col-md-6">
                                    <div class="card event-card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="event_id" id="event_<?= $evento['id'] ?>" 
                                                       value="<?= $evento['id'] ?>" required>
                                                <label class="form-check-label" for="event_<?= $evento['id'] ?>">
                                                    <h5><?= esc($evento['name']) ?></h5>
                                                    <p class="mb-1 event-date">
                                                        <i class="fas fa-calendar-alt"></i> <?= date('d/m/Y H:i', strtotime($evento['date'])) ?>
                                                    </p>
                                                    <?php if(isset($evento['venue_name'])): ?>
                                                        <p class="mb-1">
                                                            <i class="fas fa-map-marker-alt"></i> <?= esc($evento['venue_name']) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if(isset($evento['capacity'])): ?>
                                                        <p class="mb-1">
                                                            <i class="fas fa-users"></i> Capacidad: <?= $evento['capacity'] ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No hay eventos disponibles para reservar.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

             <!-- Sección de Información del Cliente -->
            <h4 class="section-title"><i class="fas fa-user"></i> Información del Cliente</h4>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" value="<?= esc($current_user['username']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="text" class="form-control" value="<?= esc($current_user['email']) ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" value="<?= esc($current_user['phone'] ?? 'No registrado') ?>" readonly>
                            </div>
                            <input type="hidden" name="customer_id" value="<?= $current_user['id'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Información de la Reserva -->
<h4 class="section-title"><i class="fas fa-info-circle"></i> Detalles de la Reserva</h4>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Número de Invitados</label>
            <input type="number" name="number_of_guests" class="form-control" 
                   min="1" value="<?= old('number_of_guests', 1) ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Fecha de la Reserva</label>
            <input type="datetime-local" name="reservation_date" class="form-control" 
                   value="<?= old('reservation_date') ?>" required>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select" required>
                <option value="confirmed" <?= old('status') == 'confirmed' ? 'selected' : '' ?>>Confirmado</option>
                <option value="pending" <?= old('status') == 'pending' ? 'selected' : '' ?>>Pendiente</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Notas Especiales</label>
            <textarea name="notes" class="form-control" rows="3"><?= old('notes') ?></textarea>
        </div>
    </div>
</div>

            <!-- Sección de Selección de Artículos -->
<h4 class="section-title"><i class="fas fa-boxes"></i> Artículos del Inventario</h4>
<div class="mb-4">
    <?php if(!empty($items)): ?>
        <div class="alert alert-info mb-3">
            <i class="fas fa-info-circle"></i> Solo puedes seleccionar artículos con estado <span class="badge bg-success">Disponible</span>
        </div>
        
        <div class="row">
            <?php foreach($items as $item): ?>
                <div class="col-md-4 mb-3">
                    <div class="card item-card h-100 border-<?= $item['status'] === 'Disponible' ? 'success' : 'light' ?>">
                        <div class="card-body">
                            <div class="form-check">
                                <?php if($item['status'] === 'Disponible'): ?>
                                    <input class="form-check-input" type="checkbox" 
                                           name="items[]" id="item_<?= $item['id'] ?>" 
                                           value="<?= $item['id'] ?>"
                                           <?= old('items') && in_array($item['id'], old('items')) ? 'checked' : '' ?>>
                                <?php else: ?>
                                    <input class="form-check-input" type="checkbox" disabled>
                                <?php endif; ?>
                                
                                <label class="form-check-label w-100" for="item_<?= $item['id'] ?>">
                                    <h5><?= esc($item['name']) ?></h5>
                                    <div class="d-flex justify-content-between">
                                        <span class="item-code text-muted">
                                            <i class="fas fa-barcode"></i> <?= esc($item['code']) ?>
                                        </span>
                                        <span class="badge bg-<?= 
                                            $item['status'] === 'Disponible' ? 'success' : 
                                            ($item['status'] === 'En uso' ? 'primary' :
                                            ($item['status'] === 'Prestado' ? 'warning' : 'secondary'))
                                        ?>">
                                            <?= esc($item['status']) ?>
                                        </span>
                                    </div>
                                    <p class="mt-2 mb-1">
                                        <i class="fas fa-map-marker-alt"></i> <?= esc($item['location']) ?>
                                    </p>
                                    <?php if(!empty($item['current_responsible'])): ?>
                                        <p class="mb-1">
                                            <i class="fas fa-user"></i> Responsable: <?= esc($item['current_responsible']) ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if($item['status'] === 'Disponible'): ?>
                                        <div class="mt-2">
                                            <label class="small">Cantidad:</label>
                                            <input type="number" 
                                                   name="item_quantity[<?= $item['id'] ?>]" 
                                                   class="form-control form-control-sm" 
                                                   min="1" 
                                                   value="<?= old('item_quantity.'.$item['id'], 1) ?>">
                                        </div>
                                    <?php endif; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            No se encontraron artículos disponibles en el inventario.
        </div>
    <?php endif; ?>
</div>
 

           

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-between">
                <a href="<?= base_url('reservas') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Confirmar Reserva
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            // Validar número de invitados
            const guests = document.querySelector('[name="number_of_guests"]');
            if(guests.value < 1) {
                alert('El número de invitados debe ser al menos 1');
                e.preventDefault();
                return;
            }
            
            // Validar fecha de reserva
            const reservationDate = document.querySelector('[name="reservation_date"]');
            if(!reservationDate.value) {
                alert('Debes seleccionar una fecha para la reserva');
                e.preventDefault();
                return;
            }
            
            // Validar que se haya seleccionado un evento (si no viene uno específico)
            <?php if(!isset($event)): ?>
                const selectedEvent = document.querySelector('[name="event_id"]:checked');
                if(!selectedEvent) {
                    alert('Debes seleccionar un evento');
                    e.preventDefault();
                    return;
                }
            <?php endif; ?>
            
            // Opcional: Validar que se haya seleccionado al menos un artículo
            // Descomenta si es requerido
            /*
            const selectedItems = document.querySelectorAll('[name="items[]"]:checked');
            if(selectedItems.length === 0) {
                if(!confirm('No has seleccionado ningún artículo. ¿Deseas continuar?')) {
                    e.preventDefault();
                    return;
                }
            }
            */
        });
    </script>
</body>
</html>