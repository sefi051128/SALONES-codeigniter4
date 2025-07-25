<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Carga de API de Google Maps EXACTAMENTE igual que en crearSede.php -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>&libraries=places&callback=initMap" async defer></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<form action="<?= isset($sede) ? base_url('sedes/actualizar/'.$sede['id']) : base_url('sedes/guardar') ?>" method="post" id="sedeForm">
    <div class="mb-3">
        <label for="name" class="form-label">Nombre de la Sede</label>
        <input type="text" class="form-control" id="name" name="name" required 
               value="<?= old('name', $sede['name'] ?? '') ?>">
    </div>
    
    <div class="mb-3">
        <label for="location" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="location" name="location" required
               value="<?= old('location', $sede['location'] ?? '') ?>">
        <small class="text-muted">Comience a escribir y seleccione una dirección del listado</small>
    </div>
    
    <div class="mb-3">
        <label for="capacity" class="form-label">Capacidad</label>
        <input type="number" class="form-control" id="capacity" name="capacity" required
               value="<?= old('capacity', $sede['capacity'] ?? '') ?>">
    </div>
    
    <input type="hidden" id="lat" name="lat" value="<?= old('lat', $sede['lat'] ?? '') ?>">
    <input type="hidden" id="lng" name="lng" value="<?= old('lng', $sede['lng'] ?? '') ?>">
    <input type="hidden" id="place_id" name="place_id" value="<?= old('place_id', $sede['place_id'] ?? '') ?>">
    
    <div class="mb-3">
        <div id="map" style="height: 400px; width: 100%; border-radius: var(--border-radius);"></div>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="<?= base_url('sedes') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?= isset($sede) ? 'Actualizar' : 'Guardar' ?> Sede
        </button>
    </div>
</form>

<script>
// Implementación IDÉNTICA a la de crearSede.php
let map;
let marker;
let autocomplete;

function initMap() {
    // Configuración inicial del mapa
    const initialPosition = { 
        lat: <?= isset($sede) ? $sede['lat'] : '19.4326' ?>, 
        lng: <?= isset($sede) ? $sede['lng'] : '-99.1332' ?> 
    };
    
    map = new google.maps.Map(document.getElementById("map"), {
        center: initialPosition,
        zoom: <?= isset($sede) ? '15' : '12' ?>,
    });
    
    // Configuración del autocompletado (igual que en crearSede.php)
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("location"),
        { types: ["establishment"] }
    );
    
    // Si estamos editando, colocar el marcador inicial
    <?php if(isset($sede)): ?>
        marker = new google.maps.Marker({
            map,
            position: initialPosition,
            draggable: true,
        });
    <?php endif; ?>
    
    // Manejador de selección de lugar (igual que en crearSede.php)
    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        
        if (!place.geometry) {
            alert("No se encontró la ubicación: '" + place.name + "'");
            return;
        }
        
        map.setCenter(place.geometry.location);
        
        if (marker) {
            marker.setMap(null);
        }
        
        marker = new google.maps.Marker({
            map,
            position: place.geometry.location,
            draggable: true,
        });
        
        document.getElementById("lat").value = place.geometry.location.lat();
        document.getElementById("lng").value = place.geometry.location.lng();
        document.getElementById("place_id").value = place.place_id;
        
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
    });
    
    // Permitir colocar marcador haciendo click en el mapa
    map.addListener("click", (event) => {
        if (marker) {
            marker.setMap(null);
        }
        
        marker = new google.maps.Marker({
            map,
            position: event.latLng,
            draggable: true,
        });
        
        document.getElementById("lat").value = event.latLng.lat();
        document.getElementById("lng").value = event.latLng.lng();
        
        new google.maps.Geocoder().geocode({ location: event.latLng }, (results) => {
            if (results[0]) {
                document.getElementById("location").value = results[0].formatted_address;
            }
        });
    });
}
</script>