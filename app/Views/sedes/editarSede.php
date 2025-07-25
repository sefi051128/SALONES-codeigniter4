<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>&libraries=places&callback=initMap" async defer></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sede - EventMobiliario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Los mismos estilos que en crearSede.php */
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
        }

        footer {
            background-color: var(--dark);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
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
        <h1><i class="fas fa-edit"></i> Editar Sede: <?= esc($sede['name']) ?></h1>
        
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="<?= base_url('sedes/actualizar/'.$sede['id']) ?>" method="post" id="sedeForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de la Sede</label>
                    <input type="text" class="form-control" id="name" name="name" required 
                           value="<?= old('name', $sede['name']) ?>">
                </div>
                
                <div class="mb-3">
                    <label for="location" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="location" name="location" required
                           value="<?= old('location', $sede['location']) ?>">
                    <small class="text-muted">Comience a escribir y seleccione una dirección del listado</small>
                </div>
                
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacidad</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" required
                           value="<?= old('capacity', $sede['capacity']) ?>">
                </div>
                
                <input type="hidden" id="lat" name="lat" value="<?= old('lat', $sede['lat']) ?>">
                <input type="hidden" id="lng" name="lng" value="<?= old('lng', $sede['lng']) ?>">
                <input type="hidden" id="place_id" name="place_id" value="<?= old('place_id', $sede['place_id']) ?>">
                
                <div class="mb-3">
                    <div id="map"></div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('sedes') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Sede
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer id="contacto">
        <p class="copyright">© <?= date('Y') ?> EventMobiliario. Todos los derechos reservados.</p>
    </footer>

    <script>
    let map;
    let marker;
    let autocomplete;

    function initMap() {
        const initialPosition = { 
            lat: <?= $sede['lat'] ?: '19.4326' ?>, 
            lng: <?= $sede['lng'] ?: '-99.1332' ?> 
        };
        
        map = new google.maps.Map(document.getElementById("map"), {
            center: initialPosition,
            zoom: 15,
        });
        
        // Colocar marcador inicial
        marker = new google.maps.Marker({
            map,
            position: initialPosition,
            draggable: true,
        });
        
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("location"),
            { types: ["establishment"] }
        );
        
        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            
            if (!place.geometry) {
                alert("No se encontró la ubicación: '" + place.name + "'");
                return;
            }
            
            map.setCenter(place.geometry.location);
            
            if (marker) {
                marker.setPosition(place.geometry.location);
            } else {
                marker = new google.maps.Marker({
                    map,
                    position: place.geometry.location,
                    draggable: true,
                });
            }
            
            document.getElementById("lat").value = place.geometry.location.lat();
            document.getElementById("lng").value = place.geometry.location.lng();
            document.getElementById("place_id").value = place.place_id;
        });
        
        marker.addListener("dragend", () => {
            const position = marker.getPosition();
            document.getElementById("lat").value = position.lat();
            document.getElementById("lng").value = position.lng();
            
            new google.maps.Geocoder().geocode({ location: position }, (results) => {
                if (results[0]) {
                    document.getElementById("location").value = results[0].formatted_address;
                }
            });
        });
    }
    </script>
</body>
</html>