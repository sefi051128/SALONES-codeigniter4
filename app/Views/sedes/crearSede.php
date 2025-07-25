<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Sede - EventMobiliario</title>
    <!-- Cargar estilos primero -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: white;
            box-shadow: var(--box-shadow);
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
        }

        .logo i {
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .form-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 8px 16px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-secondary {
            background-color: var(--gray);
            color: white;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: var(--border-radius);
            margin-top: 1rem;
            background-color: #e9ecef;
        }

        footer {
            background-color: var(--dark);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        .map-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            flex-direction: column;
        }
        
        .map-error {
            padding: 1rem;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: var(--border-radius);
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="<?= base_url('/') ?>" class="logo">
            <i class="fas fa-chair"></i>
            EventMobiliario
        </a>
        <div class="nav-links">
            <a href="<?= base_url('/') ?>">Inicio</a>
            <a href="<?= base_url('sedes') ?>" class="active">Sedes</a>
            <a href="#servicios">Servicios</a>
            <a href="#contacto">Contacto</a>
        </div>
    </nav>

    <div class="container">
        <h1><i class="fas fa-plus-circle"></i> Crear Nueva Sede</h1>
        
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="<?= base_url('sedes/guardar') ?>" method="post" id="sedeForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de la Sede</label>
                    <input type="text" class="form-control" id="name" name="name" required 
                           value="<?= old('name') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="location" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="location" name="location" required
                           value="<?= old('location') ?>">
                    <small class="text-muted">Comience a escribir y seleccione una dirección del listado</small>
                </div>
                
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacidad</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required
                           value="<?= old('capacity') ?>">
                </div>
                
                <input type="hidden" id="lat" name="lat" value="<?= old('lat') ?>">
                <input type="hidden" id="lng" name="lng" value="<?= old('lng') ?>">
                <input type="hidden" id="place_id" name="place_id" value="<?= old('place_id') ?>">
                
                <div class="mb-3">
                    <div id="map">
                        <div class="map-loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando mapa...</span>
                            </div>
                            <p class="mt-2">Cargando mapa...</p>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('sedes') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Sede
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer id="contacto">
        <p class="copyright">© <?= date('Y') ?> EventMobiliario. Todos los derechos reservados.</p>
    </footer>

    <!-- Cargar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para manejar Google Maps -->
    <script>
    // Solución para el error "initMap is not a function"
    window.initMap = function() {
        console.log('Google Maps API cargada correctamente');
        
        try {
            const mapElement = document.getElementById('map');
            mapElement.innerHTML = '';
            
            // Crear mapa con configuración mejorada
            const map = new google.maps.Map(mapElement, {
                center: { lat: 19.4326, lng: -99.1332 },
                zoom: 12,
                streetViewControl: true,
                mapTypeControl: true,
                fullscreenControl: true
            });
            
            // Configurar autocompletado con mejores parámetros
            const autocomplete = new google.maps.places.Autocomplete(
                document.getElementById("location"),
                { 
                    types: ["geocode", "establishment"],
                    componentRestrictions: {country: "mx"},
                    fields: ["geometry", "formatted_address", "place_id"]
                }
            );
            
            let marker;
            
            // Manejar selección de lugar
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                if (!place.geometry) {
                    showMapError("No se encontró la ubicación seleccionada");
                    return;
                }
                
                // Ajustar vista del mapa
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                
                // Colocar marcador
                if (marker) marker.setMap(null);
                
                marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });
                
                // Actualizar campos
                updateLocationFields(
                    place.geometry.location, 
                    place.formatted_address,
                    place.place_id
                );
                
                // Manejar arrastre de marcador
                marker.addListener("dragend", () => {
                    reverseGeocode(marker.getPosition());
                });
            });
            
            // Permitir clicks en el mapa
            map.addListener("click", (event) => {
                if (marker) marker.setMap(null);
                
                marker = new google.maps.Marker({
                    map: map,
                    position: event.latLng,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });
                
                reverseGeocode(event.latLng);
            });
            
            // Si hay valores existentes, centrar el mapa allí
            if (document.getElementById("lat").value && document.getElementById("lng").value) {
                const savedPosition = {
                    lat: parseFloat(document.getElementById("lat").value),
                    lng: parseFloat(document.getElementById("lng").value)
                };
                map.setCenter(savedPosition);
                map.setZoom(15);
            }
            
        } catch (error) {
            console.error("Error al inicializar mapa:", error);
            showMapError("Error al cargar el mapa: " + error.message);
        }
    };

    // Función para geocodificación inversa
    function reverseGeocode(position) {
        new google.maps.Geocoder().geocode({ location: position }, (results, status) => {
            if (status === "OK" && results[0]) {
                updateLocationFields(
                    position,
                    results[0].formatted_address,
                    results[0].place_id
                );
            }
        });
    }

    // Función para actualizar campos del formulario
    function updateLocationFields(position, address = "", placeId = "") {
        document.getElementById("lat").value = position.lat();
        document.getElementById("lng").value = position.lng();
        document.getElementById("place_id").value = placeId || "";
        
        if (address) {
            document.getElementById("location").value = address;
        }
    }

    // Función para mostrar errores
    function showMapError(message) {
        const mapElement = document.getElementById('map');
        mapElement.innerHTML = `
            <div class="map-error">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
                <div class="mt-2">
                    <button onclick="window.location.reload()" class="btn btn-sm btn-primary">
                        <i class="fas fa-sync-alt"></i> Recargar
                    </button>
                </div>
            </div>
        `;
    } 

    // Cargar API de Google Maps dinámicamente
    function loadGoogleMaps() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>&libraries=places&callback=initMap`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Cargar API cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', loadGoogleMaps);

    // Verificar si la API no se cargó después de 5 segundos
    setTimeout(() => {
        if (!window.google || !window.google.maps) {
            showMapError("La API de Google Maps no se cargó. Verifica tu conexión a internet.");
        }
    }, 5000);
    </script>
</body>
</html>