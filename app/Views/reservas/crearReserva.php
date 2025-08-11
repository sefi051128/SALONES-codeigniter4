<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
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
        
        body {
            background-color: #f8f9fc;
            color: #5a5c69;
        }
        
        .card {
            border-radius: 0.35rem;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin: 30px 0 20px;
            color: var(--primary-color);
            cursor: pointer;
        }
        
        .section-title:after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .badge-available { background-color: var(--success-color); }
        .badge-inuse { background-color: var(--info-color); }
        .badge-loaned { background-color: var(--warning-color); color: #000; }
        .badge-unavailable { background-color: var(--secondary-color); }
        
        .item-img {
            height: 120px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
        }
        
        .form-control, .form-select {
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box .form-control {
            padding-left: 2.5rem;
        }
        
        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }
        
        .filter-section {
            background-color: white;
            border-radius: 0.35rem;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .collapsible-section {
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .toggle-icon {
            transition: transform 0.3s ease;
            margin-left: 10px;
        }
        
        .collapsed .toggle-icon {
            transform: rotate(-90deg);
        }
        
        @media (max-width: 768px) {
            .item-img {
                height: 100px;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <?php if(session('role') === 'cliente'): ?>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-plus-circle text-primary me-2"></i><?= esc($title) ?></h2>
            <a href="<?= base_url('sedes') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
        <?php endif; ?>

        <?php if(session('role') === 'administrador'): ?>
            <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-plus-circle text-primary me-2"></i><?= esc($title) ?></h2>
            <a href="<?= base_url('reservas') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
            <?php endif; ?>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Errores de validación</h5>
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-1"><?= $error ?></p>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('reservas/booking/guardar') ?>" method="post" id="reservationForm">
            <?= csrf_field() ?>

            <!-- Sección de Selección de Evento -->
            <h4 class="section-title"><i class="fas fa-calendar-alt me-2"></i>Información del Evento</h4>
            <?php if(isset($event)): ?>
                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                <div class="alert alert-primary d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-1">Estás reservando para: <strong><?= esc($event['name']) ?></strong></h5>
                        <p class="mb-1"><i class="fas fa-calendar-day me-1"></i> <?= date('d/m/Y H:i', strtotime($event['date'])) ?></p>
                        <?php if(isset($event['venue_name'])): ?>
                            <p class="mb-0"><i class="fas fa-map-marker-alt me-1"></i> <?= esc($event['venue_name']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="mb-4">
                    <label class="form-label fw-bold">Seleccionar Evento</label>
                    
                    <?php if(!empty($eventos)): ?>
                        <div class="row g-3">
                            <?php foreach($eventos as $evento): ?>
                                <div class="col-md-6">
                                    <div class="card event-card h-100">
                                        <div class="card-body">
                                            <div class="form-check h-100">
                                                <input class="form-check-input" type="radio" 
                                                       name="event_id" id="event_<?= $evento['id'] ?>" 
                                                       value="<?= $evento['id'] ?>" required>
                                                <label class="form-check-label w-100" for="event_<?= $evento['id'] ?>">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h5 class="mb-1"><?= esc($evento['name']) ?></h5>
                                                        <span class="badge bg-primary">Disponible hasta el: <?= date('d/m/Y', strtotime($evento['date'])) ?></span>
                                                    </div>
                                                    <div class="ms-3 mt-2">
                                                        <p class="mb-1 text-muted">
                                                            <i class="fas fa-clock me-1"></i> <?= date('H:i', strtotime($evento['date'])) ?>
                                                        </p>
                                                        <?php if(isset($evento['venue_name'])): ?>
                                                            <p class="mb-1 text-muted">
                                                                <i class="fas fa-map-marker-alt me-1"></i> <?= esc($evento['venue_name']) ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if(isset($evento['capacity'])): ?>
                                                            <p class="mb-1 text-muted">
                                                                <i class="fas fa-users me-1"></i> Capacidad: <?= $evento['capacity'] ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>No hay eventos disponibles para reservar.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Sección de Información del Cliente -->
            <h4 class="section-title"><i class="fas fa-user me-2"></i>Información del Cliente</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3"><i class="fas fa-id-card me-2"></i>Datos del Usuario</h5>
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
                            <h5 class="card-title text-primary mb-3"><i class="fas fa-address-book me-2"></i>Contacto</h5>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" value="<?= esc($current_user['phone'] ?? 'No registrado') ?>" readonly>
                            </div>
                            <input type="hidden" name="customer_id" value="<?= $current_user['id'] ?>">
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>Para actualizar tu información de contacto, visita tu perfil.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Información de la Reserva -->
            <h4 class="section-title"><i class="fas fa-info-circle me-2"></i>Detalles de la Reserva</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3"><i class="fas fa-users me-2"></i>Configuración</h5>
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
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3"><i class="fas fa-cog me-2"></i>Opciones</h5>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="confirmed" <?= old('status') == 'confirmed' ? 'selected' : '' ?>>Confirmado</option>
                                    <option value="pending" <?= old('status') == 'pending' ? 'selected' : '' ?>>Pendiente</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Notas Especiales</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Indica cualquier requerimiento especial..."><?= old('notes') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Selección de Artículos (Colapsable) -->
            <div class="mb-4">
                <h4 class="section-title" id="toggleItemsSection">
                    <i class="fas fa-boxes me-2"></i>Artículos del Inventario
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </h4>
                
                <div class="collapsible-section" id="itemsSection">
                    <?php if(!empty($items)): ?>
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                <h6 class="mb-1">Información sobre artículos</h6>
                                <p class="mb-0">Solo puedes seleccionar artículos con estado <span class="badge bg-success">Disponible</span>.</p>
                            </div>
                        </div>
                        
                        <!-- Filtros y Búsqueda -->
                        <div class="filter-section mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="search-box">
                                        <i class="fas fa-search"></i>
                                        <input type="text" id="itemSearch" class="form-control" placeholder="Buscar artículos...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <select id="statusFilter" class="form-select">
                                                <option value="">Todos los estados</option>
                                                <option value="Disponible">Disponible</option>
                                                <option value="En uso">En uso</option>
                                                <option value="Prestado">Prestado</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select id="locationFilter" class="form-select">
                                                <option value="">Todas las ubicaciones</option>
                                                <?php 
                                                    $locations = array_unique(array_column($items, 'location'));
                                                    foreach($locations as $location): 
                                                ?>
                                                    <option value="<?= esc($location) ?>"><?= esc($location) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navegación por pestañas -->
                        <ul class="nav nav-pills mb-4" id="itemTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all-items" type="button" role="tab">Todos</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="available-tab" data-bs-toggle="pill" data-bs-target="#available-items" type="button" role="tab">Disponibles</button>
                            </li>
                        </ul>
                        
                        <!-- Contenido de las pestañas -->
                        <div class="tab-content" id="itemTabsContent">
                            <div class="tab-pane fade show active" id="all-items" role="tabpanel">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="itemsContainer">
                                    <?php foreach($items as $item): ?>
                                        <div class="col item-card" data-status="<?= esc($item['status']) ?>" data-location="<?= esc($item['location']) ?>">
                                            <div class="card h-100 border-<?= $item['status'] === 'Disponible' ? 'success' : 'light' ?>">
                                                <?php if(!empty($item['image_url'])): ?>
                                                    <img src="<?= esc($item['image_url']) ?>" class="card-img-top item-img p-3" alt="<?= esc($item['name']) ?>">
                                                <?php else: ?>
                                                    <div class="card-img-top item-img d-flex align-items-center justify-content-center bg-light">
                                                        <i class="fas fa-box-open fa-3x text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
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
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <h5 class="card-title mb-0"><?= esc($item['name']) ?></h5>
                                                                <span class="badge bg-<?= 
                                                                    $item['status'] === 'Disponible' ? 'success' : 
                                                                    ($item['status'] === 'En uso' ? 'primary' :
                                                                    ($item['status'] === 'Prestado' ? 'warning' : 'secondary'))
                                                                ?>">
                                                                    <?= esc($item['status']) ?>
                                                                </span>
                                                            </div>
                                                            <p class="card-text text-muted small mb-1">
                                                                <i class="fas fa-barcode me-1"></i><?= esc($item['code']) ?>
                                                            </p>
                                                            <p class="card-text text-muted small mb-2">
                                                                <i class="fas fa-map-marker-alt me-1"></i><?= esc($item['location']) ?>
                                                            </p>
                                                            
                                                            <?php if(!empty($item['current_responsible'])): ?>
                                                                <p class="card-text small mb-2">
                                                                    <i class="fas fa-user me-1"></i>Responsable: <?= esc($item['current_responsible']) ?>
                                                                </p>
                                                            <?php endif; ?>
                                                            
                                                            
                                                            <?php if($item['status'] === 'Disponible'): ?>
                                                                <div class="mt-3">
                                                                    <label class="form-label small">Cantidad a reservar:</label>
                                                                    <input type="number" 
                                                                           name="item_quantity[<?= $item['id'] ?>]" 
                                                                           class="form-control form-control-sm" 
                                                                           min="1" 
                                                                           max="<?= $item['quantity_available'] ?? '' ?>"
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
                            </div>
                            <div class="tab-pane fade" id="available-items" role="tabpanel">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="availableItemsContainer">
                                    <?php foreach($items as $item): ?>
                                        <?php if($item['status'] === 'Disponible'): ?>
                                            <div class="col item-card" data-status="<?= esc($item['status']) ?>" data-location="<?= esc($item['location']) ?>">
                                                <div class="card h-100 border-success">
                                                    <?php if(!empty($item['image_url'])): ?>
                                                        <img src="<?= esc($item['image_url']) ?>" class="card-img-top item-img p-3" alt="<?= esc($item['name']) ?>">
                                                    <?php else: ?>
                                                        <div class="card-img-top item-img d-flex align-items-center justify-content-center bg-light">
                                                            <i class="fas fa-box-open fa-3x text-secondary"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="items[]" id="item_avail_<?= $item['id'] ?>" 
                                                                   value="<?= $item['id'] ?>"
                                                                   <?= old('items') && in_array($item['id'], old('items')) ? 'checked' : '' ?>>
                                                                
                                                            <label class="form-check-label w-100" for="item_avail_<?= $item['id'] ?>">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <h5 class="card-title mb-0"><?= esc($item['name']) ?></h5>
                                                                    <span class="badge bg-success">Disponible</span>
                                                                </div>
                                                                <p class="card-text text-muted small mb-1">
                                                                    <i class="fas fa-barcode me-1"></i><?= esc($item['code']) ?>
                                                                </p>
                                                                <p class="card-text text-muted small mb-2">
                                                                    <i class="fas fa-map-marker-alt me-1"></i><?= esc($item['location']) ?>
                                                                </p>
                                                                
                                                                <div class="mt-3">
                                                                    <label class="form-label small">Cantidad a reservar:</label>
                                                                    <input type="number" 
                                                                           name="item_quantity[<?= $item['id'] ?>]" 
                                                                           class="form-control form-control-sm" 
                                                                           min="1" 
                                                                           max="<?= $item['quantity_available'] ?? '' ?>"
                                                                           value="<?= old('item_quantity.'.$item['id'], 1) ?>">
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>No se encontraron artículos disponibles en el inventario.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-between border-top pt-4">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Limpiar
                </button>
                <div>
                    <a href="<?= base_url('reservas') ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Confirmar Reserva
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Control para mostrar/ocultar la sección de artículos
            const toggleItemsSection = document.getElementById('toggleItemsSection');
            const itemsSection = document.getElementById('itemsSection');
            
            // Estado inicial (mostrado)
            let itemsSectionVisible = true;
            
            // Guardar preferencia en localStorage
            function savePreference() {
                localStorage.setItem('itemsSectionVisible', itemsSectionVisible);
            }
            
            // Cargar preferencia de localStorage
            if(localStorage.getItem('itemsSectionVisible') !== null) {
                itemsSectionVisible = localStorage.getItem('itemsSectionVisible') === 'true';
                if(!itemsSectionVisible) {
                    itemsSection.style.display = 'none';
                    toggleItemsSection.classList.add('collapsed');
                }
            }
            
            // Alternar visibilidad de la sección
            if(toggleItemsSection && itemsSection) {
                toggleItemsSection.addEventListener('click', function() {
                    itemsSectionVisible = !itemsSectionVisible;
                    
                    if(itemsSectionVisible) {
                        itemsSection.style.display = 'block';
                        toggleItemsSection.classList.remove('collapsed');
                    } else {
                        itemsSection.style.display = 'none';
                        toggleItemsSection.classList.add('collapsed');
                    }
                    
                    savePreference();
                });
            }
            
            // Función de búsqueda
            const itemSearch = document.getElementById('itemSearch');
            if(itemSearch) {
                itemSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const items = document.querySelectorAll('.item-card');
                    
                    items.forEach(item => {
                        const itemName = item.querySelector('.card-title').textContent.toLowerCase();
                        const itemCode = item.querySelector('.card-text').textContent.toLowerCase();
                        
                        if(itemName.includes(searchTerm) || itemCode.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
            
            // Filtros
            const statusFilter = document.getElementById('statusFilter');
            const locationFilter = document.getElementById('locationFilter');
            
            function applyFilters() {
                const statusValue = statusFilter.value;
                const locationValue = locationFilter.value;
                
                document.querySelectorAll('.item-card').forEach(card => {
                    const cardStatus = card.dataset.status;
                    const cardLocation = card.dataset.location;
                    
                    const statusMatch = !statusValue || cardStatus === statusValue;
                    const locationMatch = !locationValue || cardLocation === locationValue;
                    
                    if(statusMatch && locationMatch) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            if(statusFilter) statusFilter.addEventListener('change', applyFilters);
            if(locationFilter) locationFilter.addEventListener('change', applyFilters);
            
            // Validación del formulario
            const reservationForm = document.getElementById('reservationForm');
            if(reservationForm) {
                reservationForm.addEventListener('submit', function(e) {
                    // Validar número de invitados
                    const guests = document.querySelector('[name="number_of_guests"]');
                    if(guests && guests.value < 1) {
                        alert('El número de invitados debe ser al menos 1');
                        e.preventDefault();
                        guests.focus();
                        return;
                    }
                    
                    // Validar fecha de reserva
                    const reservationDate = document.querySelector('[name="reservation_date"]');
                    if(reservationDate && !reservationDate.value) {
                        alert('Debes seleccionar una fecha para la reserva');
                        e.preventDefault();
                        reservationDate.focus();
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
                    
                    // Validar cantidades de artículos
                    const quantityInputs = document.querySelectorAll('input[name^="item_quantity"]');
                    let hasInvalidQuantity = false;
                    
                    quantityInputs.forEach(input => {
                        if(input.closest('.item-card').style.display !== 'none' && 
                           input.value < 1) {
                            hasInvalidQuantity = true;
                            input.focus();
                        }
                    });
                    
                    if(hasInvalidQuantity) {
                        alert('La cantidad de cada artículo debe ser al menos 1');
                        e.preventDefault();
                        return;
                    }
                });
            }
            
            // Mostrar/ocultar elementos según pestaña seleccionada
            const itemTabs = document.getElementById('itemTabs');
            if(itemTabs) {
                itemTabs.addEventListener('click', function(e) {
                    if(e.target.id === 'available-tab') {
                        document.querySelectorAll('.item-card').forEach(card => {
                            if(card.dataset.status !== 'Disponible') {
                                card.style.display = 'none';
                            }
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>