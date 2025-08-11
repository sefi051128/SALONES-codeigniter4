<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedes - EventMobiliario</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>&libraries=places&callback=initMap" async defer></script>
    
    <style>
        :root {
            --primary: #4a6fa5;
            --primary-dark: #3a5a8a;
            --secondary: #ff7e5f;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --border-radius: 0.5rem;
            --box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar mejorada */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            font-size: 1.5rem;
        }

        .navbar-brand i {
            color: var(--secondary);
            margin-right: 0.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark);
            padding: 0.5rem 1rem;
            position: relative;
            transition: var(--transition);
        }

        .nav-link.active {
            color: var(--primary) !important;
        }

        .nav-link.active:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1rem;
            right: 1rem;
            height: 2px;
            background-color: var(--primary);
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            padding: 0.5rem 0;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(74, 111, 165, 0.1);
            color: var(--primary);
        }

        /* Main content */
        main {
            flex: 1;
            padding: 2rem 0;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            color: var(--primary);
            font-weight: 600;
            margin: 0;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--secondary);
        }

        /* Sede cards */
        .sede-card {
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        .sede-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: var(--primary);
            color: white;
            padding: 1.25rem;
            border-bottom: none;
        }

        .card-title {
            font-weight: 600;
            margin: 0;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
            flex: 1;
        }

        .sede-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .sede-info i {
            color: var(--primary);
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .card-footer {
            background-color: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border-color: var(--gray);
        }

        .btn-outline-secondary:hover {
            background-color: var(--gray);
            color: white;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        .btn-warning {
            background-color: var(--warning);
            border-color: var(--warning);
            color: var(--dark);
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-map {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }

        .btn-map:hover {
            background-color: #e06b4d;
            border-color: #e06b4d;
            color: white;
            transform: translateY(-2px);
        }

        /* Modals */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        .modal-header {
            background-color: var(--primary);
            color: white;
            border-bottom: none;
            padding: 1.25rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .btn-close {
            filter: invert(1);
        }

        .modal-map-container {
            height: 400px;
            width: 100%;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        /* Delete confirmation modal */
        .delete-icon {
            font-size: 3rem;
            color: var(--danger);
            margin-bottom: 1.5rem;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 0;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .footer-nav {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .footer-nav-link {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-nav-link:hover {
            color: var(--secondary);
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .social-icon {
            color: white;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .social-icon:hover {
            color: var(--secondary);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .action-buttons {
                width: 100%;
            }
            
            .action-buttons .btn {
                flex: 1;
                min-width: 100%;
            }
            
            .btn-group {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .btn-group .btn {
                flex: 1;
            }
        }

        @media (max-width: 576px) {
            .card-footer-buttons {
                flex-direction: column;
            }
            
            .btn-group {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php if(session('role') !== 'administrador'): ?>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-chair"></i>
                EventMobiliario
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('sedes') ?>">Sedes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                    <?php if (session('isLoggedIn')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i><?= esc(session('username')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-user me-1"></i> Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="container my-4">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <a href="/admin" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left me-1"></i> Atrás
                </a>
                <h1 class="page-title">Nuestras Sedes</h1>
            </div>
            
            <?php if(session('role') === 'cliente'): ?>
            <a href="<?= base_url('reservas/crearReserva') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Nueva Reserva de Evento
            </a>
            <a href="<?= base_url('eventos') ?>" class="btn btn-info me-2">
                        <i class="fas fa-calendar-alt me-1"></i> Ver todos los eventos
                    </a>
                    <a href="<?= route_to('reservas.mine') ?>" class="btn btn-info me-2">
    <i class="fas fa-calendar-alt me-1"></i> Ver mis reservas
</a>
                    <?php endif; ?>

            <?php if(session('role') === 'administrador'): ?>
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('eventos') ?>" class="btn btn-info me-2">
                        <i class="fas fa-calendar-alt me-1"></i> Ver todos los eventos
                    </a>
                    <a href="<?= base_url('/reservas') ?>" class="btn btn-info me-2">
                        <i class="fas fa-calendar-alt me-1"></i> Ver todas las reservas
                    </a>
                    <a href="<?= base_url('sedes/crear') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nueva Sede
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($sedes as $sede): ?>
            <div class="col">
                <div class="card h-100 sede-card">
                    <div class="card-header">
                        <h3 class="card-title"><?= esc($sede['name']) ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="sede-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= esc($sede['location']) ?></span>
                        </div>
                        <div class="sede-info">
                            <i class="fas fa-users"></i>
                            <span>Capacidad: <?= number_format($sede['capacity']) ?> personas</span>
                        </div>
                        <?php if(!empty($sede['description'])): ?>
                        <div class="sede-info">
                            <i class="fas fa-info-circle"></i>
                            <span><?= esc($sede['description']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div class="btn-group mb-2 mb-md-0">
                                <button class="btn btn-sm btn-map view-map" 
                                        data-lat="<?= $sede['lat'] ?>" 
                                        data-lng="<?= $sede['lng'] ?>" 
                                        data-title="<?= esc($sede['name']) ?>">
                                    <i class="fas fa-map-marked-alt me-1"></i> Mapa
                                </button>
                                <a href="<?= base_url('eventos/por-sede/' . $sede['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-calendar-alt me-1"></i> Eventos
                                </a>
                            </div>
                            
                            <?php if(session('role') === 'administrador'): ?>
                                <div class="action-buttons">
                                    <a href="<?= base_url('sedes/editar/'.$sede['id']) ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <button class="btn btn-sm btn-danger confirm-delete" 
                                            data-id="<?= $sede['id'] ?>"
                                            data-name="<?= esc($sede['name']) ?>">
                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalTitle">Ubicación de la Sede</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="modal-map-container">
                        <div id="modalMap" style="height: 100%; width: 100%;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle delete-icon"></i>
                    <p id="deleteConfirmText">¿Estás seguro que deseas eliminar esta sede?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <a id="deleteConfirmBtn" href="#" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php if(session('role') !== 'administrador'): ?>
    <footer>
        <div class="footer-container">
            
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
            </div>
            <p class="copyright">© <?= date('Y') ?> EventMobiliario. Todos los derechos reservados.</p>
        </div>
    </footer>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Initialize modal map
    function initModalMap(lat, lng, title) {
        const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
        const map = new google.maps.Map(document.getElementById('modalMap'), {
            center: position,
            zoom: 15,
            mapTypeControl: true,
            streetViewControl: true,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });
        
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: title,
            animation: google.maps.Animation.DROP
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `<h5 class="mb-1">${title}</h5><p class="mb-0 text-muted">${position.lat.toFixed(6)}, ${position.lng.toFixed(6)}</p>`
        });
        
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
        
        // Open info window by default
        infoWindow.open(map, marker);
    }

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        // Map Modal
        const mapModal = new bootstrap.Modal(document.getElementById('mapModal'));
        document.querySelectorAll('.view-map').forEach(button => {
            button.addEventListener('click', function() {
                const lat = this.dataset.lat;
                const lng = this.dataset.lng;
                const title = this.dataset.title;
                
                document.getElementById('mapModalTitle').textContent = `Ubicación: ${title}`;
                document.getElementById('modalMap').innerHTML = '';
                
                mapModal.show();
                
                // Small delay to ensure modal is visible before initializing map
                setTimeout(() => {
                    initModalMap(lat, lng, title);
                }, 300);
            });
        });
        
        // Delete Confirmation Modal
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        document.querySelectorAll('.confirm-delete').forEach(button => {
            button.addEventListener('click', function() {
                const sedeId = this.dataset.id;
                const sedeName = this.dataset.name;
                
                document.getElementById('deleteConfirmText').textContent = `¿Estás seguro que deseas eliminar la sede "${sedeName}"?`;
                document.getElementById('deleteConfirmBtn').href = `<?= base_url('sedes/eliminar/') ?>${sedeId}`;
                
                deleteModal.show();
            });
        });
        
        // Close map modal when clicking outside
        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) {
                mapModal.hide();
            }
        });
    });

    // Required by Google Maps API
    function initMap() {
        // Function required but does nothing here
    }
    </script>
</body>
</html>