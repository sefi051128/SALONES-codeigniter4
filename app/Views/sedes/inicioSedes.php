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
            --danger: #dc3545;
            --border-radius: 0.375rem;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary);
        }

        .navbar-brand i {
            margin-right: 0.5rem;
        }

        .nav-link.active {
            font-weight: 500;
            color: var(--primary) !important;
        }

        .sede-card {
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .sede-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.175);
        }

        .card-header {
            background-color: var(--primary);
            color: white;
        }

        .sede-info i {
            color: var(--primary);
            width: 1.25rem;
            text-align: center;
            margin-right: 0.5rem;
        }

        .btn-map {
            background-color: var(--secondary);
            color: white;
        }

        .btn-map:hover {
            background-color: #e06c4b;
            color: white;
        }

        .modal-map-container {
            height: 400px;
            width: 100%;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .btn-group .btn {
                width: auto;
                margin-bottom: 0;
            }
        }

        footer {
            background-color: var(--dark);
        }

        .social-icons a {
            color: white;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .social-icons a:hover {
            color: var(--secondary);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
     <?php if(session('role') !== 'administrador'): ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
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
                                <i class="fas fa-user me-1"></i><?= esc(session('username')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Cerrar Sesión</a></li>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/admin" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Atrás
            </a>
            <h1 class="h2 text-center text-primary mb-0">Nuestras Sedes</h1>
            <div class="d-none d-md-block" style="width: 116px;"></div> <!-- Spacer for alignment -->
        </div>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if(session('role') === 'administrador'): ?>
            <a href="<?= base_url('eventos') ?>" class="btn btn-sm btn-info">
    <i class="fas fa-calendar-alt me-1"></i> Ver Todos los Eventos
</a>

            <div class="d-flex justify-content-end mb-4">
                <a href="<?= base_url('sedes/crear') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Agregar Nueva Sede
                </a>
            </div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($sedes as $sede): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-header text-white">
                        <h3 class="h5 mb-0"><?= esc($sede['name']) ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="ms-2"><?= esc($sede['location']) ?></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users"></i>
                            <span class="ms-2">Capacidad: <?= number_format($sede['capacity']) ?> personas</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
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
                                <div class="action-buttons d-flex flex-column flex-md-row">
                                    <a href="<?= base_url('sedes/editar/'.$sede['id']) ?>" class="btn btn-sm btn-warning me-md-1 mb-1 mb-md-0">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                    <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 3rem;"></i>
                    <p id="deleteConfirmText">¿Estás seguro que deseas eliminar esta sede?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
<footer class="py-4 mt-5 bg-dark text-white" id="contacto">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <ul class="nav justify-content-center mb-3">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('/') ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('sedes') ?>">Sedes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#contacto">Contacto</a>
                    </li>
                </ul>
                <div class="social-icons mb-3">
                    <a href="#" class="mx-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="mx-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="mx-2"><i class="fab fa-twitter"></i></a>
                </div>
                <p class="mb-0">© <?= date('Y') ?> EventMobiliario. Todos los derechos reservados.</p>
            </div>
        </div>
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
            streetViewControl: true
        });
        
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: title
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `<h5>${title}</h5><p>${position.lat.toFixed(6)}, ${position.lng.toFixed(6)}</p>`
        });
        
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
                
                setTimeout(() => {
                    initModalMap(lat, lng, title);
                }, 500);
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
    });

    // Required by Google Maps API
    function initMap() {
        // Function required but does nothing here
    }
    </script>
</body>
</html>