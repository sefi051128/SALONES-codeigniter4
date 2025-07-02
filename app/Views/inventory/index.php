<h1><?= esc($title) ?></h1>

<a href="/inventory/create" class="btn btn-success mb-3">Agregar nuevo</a>

<a href="/inventory/report" class="btn btn-outline-secondary mb-3">Generar reporte</a>

<a href="inicio" class="btn btn-outline-secondary mb-3">Inicio</a>


<form method="get" action="<?= base_url('/inventory') ?>" class="mb-4 row g-2 align-items-center">
    <div class="col-auto">
        <input type="text" name="search" value="<?= esc($search ?? '') ?>" class="form-control" placeholder="Buscar... (código, nombre)">
    </div>
    <div class="col-auto">
        <select name="status" class="form-select">
            <option value="">Estado (todos)</option>
            <option value="Disponible" <?= (isset($status) && $status === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
            <option value="En uso" <?= (isset($status) && $status === 'En uso') ? 'selected' : '' ?>>En uso</option>
            <option value="En reparación" <?= (isset($status) && $status === 'En reparación') ? 'selected' : '' ?>>En reparación</option>
            <option value="Prestado" <?= (isset($status) && $status === 'Prestado') ? 'selected' : '' ?>>Prestado</option>
            <!-- Agrega más estados según tu sistema -->
        </select>
    </div>
    <div class="col-auto">
        <input type="text" name="responsible" value="<?= esc($responsible ?? '') ?>" class="form-control" placeholder="Responsable">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="<?= base_url('/inventory') ?>" class="btn btn-secondary">Limpiar</a>
    </div>
</form>


<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Ubicación</th>
            <th>Estado</th>
            <th>Responsable</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= esc($item['id']) ?></td>
                    <td><?= esc($item['code']) ?></td>
                    <td><?= esc($item['name']) ?></td>
                    <td><?= esc($item['location']) ?></td>
                    <td><?= esc($item['status']) ?></td>
                    <td><?= esc($item['current_responsible']) ?></td>
                    <td>
                        <a href="/inventory/edit/<?= $item['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="/inventory/update-location/<?= $item['id'] ?>" class="btn btn-info btn-sm">Boton de acción pendiente</a>
                        <a href="/inventory/qr/<?= $item['id'] ?>" target="_blank" class="btn btn-secondary btn-sm">QR</a>
                        <a href="/inventory/delete/<?= $item['id'] ?>" 
                           onclick="return confirm('¿Está seguro de eliminar este artículo?')" 
                           class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">No hay artículos registrados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
