<style>
    body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 2rem; }
    .user-info { 
        background: #f5f5f5; 
        padding: 1rem; 
        border-radius: 5px; 
        margin-bottom: 2rem; 
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }
    .user-details { flex-grow: 1; }
    .action-buttons { display: flex; gap: 10px; }
    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        text-decoration: none;
        border-radius: 5px;
        color: white;
        font-weight: bold;
        text-align: center;
        min-width: 120px;
    }
    .btn-logout { background: #dc3545; }
    .btn-logout:hover { background: #c82333; }
    .btn-inventario { background: #007bff; }
    .btn-inventario:hover { background: #0056b3; }
    .btn-users { background: #28a745; }
    .btn-users:hover { background: #218838; }
    
    /* Estilos para la sección de usuarios */
    .users-section { margin-top: 2rem; }
    .users-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .users-table th, .users-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    .users-table th {
        background-color: #343a40;
        color: white;
    }
    .users-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .users-table tr:hover {
        background-color: #f1f1f1;
    }
    .action-btn {
        padding: 0.4rem 0.8rem;
        margin: 0 0.2rem;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-edit { 
        background-color: #ffc107; 
        color: #212529;
    }
    .btn-delete { 
        background-color: #dc3545; 
        color: white;
    }
    .btn-create {
        display: inline-block;
        margin-top: 1.5rem;
        background: #17a2b8;
        color: white;
        padding: 0.7rem 1.3rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-create:hover {
        background: #138496;
        transform: translateY(-2px);
    }
    .badge-role {
        display: inline-block;
        padding: 0.25em 0.6em;
        border-radius: 10px;
        font-size: 0.85em;
        font-weight: bold;
        color: white;
    }
    .badge-admin { background-color: #dc3545; }
    .badge-coord { background-color: #6f42c1; }
    .badge-client { background-color: #28a745; }
    .badge-logistic { background-color: #fd7e14; }
    .badge-security { background-color: #17a2b8; }
    
    /* Estilos para la tabla de inventario */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    .table th {
        background-color: #343a40;
        color: white;
    }
    .table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .table tr:hover {
        background-color: #f1f1f1;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
    }
    .btn-success {
        background-color: #28a745;
        color: white;
    }
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .btn-info {
        background-color: #17a2b8;
        color: white;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .form-control, .form-select {
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .mb-3 {
        margin-bottom: 1rem !important;
    }
    .mb-4 {
        margin-bottom: 1.5rem !important;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    .col-auto {
        flex: 0 0 auto;
        width: auto;
        max-width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }
    .align-items-center {
        align-items: center !important;
    }
    .g-2 {
        gap: 0.5rem !important;
    }
    .text-center {
        text-align: center !important;
    }
</style>


<h1><?= esc($title) ?></h1>

<a href="/inventory/create" class="btn btn-success mb-3">Agregar nuevo</a>

<a href="/inventory/report" class="btn btn-outline-secondary mb-3">Generar reporte</a>

<a href="/admin" class="btn btn-outline-secondary mb-3">Atrás</a>




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
