<div class="container mt-4">
    <h2><?= esc($sede['name']) ?></h2>
    
    <div class="row">
        <div class="col-md-6">
            <p><strong>Ubicación:</strong> <?= esc($sede['location']) ?></p>
            <p><strong>Capacidad:</strong> <?= number_format($sede['capacity']) ?> personas</p>
            
            <a href="<?= base_url('sedes') ?>" class="btn btn-secondary">Volver</a>
            <a href="<?= base_url('sedes/editar/'.$sede['id']) ?>" class="btn btn-primary">Editar</a>
        </div>
        
        <div class="col-md-6">
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>

<script>
function initMap() {
    const position = {lat: <?= $sede['lat'] ?>, lng: <?= $sede['lng'] ?>};
    const map = new google.maps.Map(document.getElementById('map'), {
        center: position,
        zoom: 15
    });
    
    new google.maps.Marker({
        position: position,
        map: map,
        title: "<?= addslashes($sede['name']) ?>"
    });
}
</script>