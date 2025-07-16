<style>
    /* Estilos generales */
    body { 
        font-family: Arial, sans-serif; 
        max-width: 800px; 
        margin: 0 auto; 
        padding: 2rem;
        background-color: #f8f9fa;
    }
    
    /* Estilo para el título */
    h2 {
        color: #343a40;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #007bff;
    }
    
    /* Estilos para alertas */
    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.25rem;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Estilos para el formulario */
    form {
        background-color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    .mb-3 {
        margin-bottom: 1.5rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #495057;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .form-control[readonly] {
        background-color: #e9ecef;
        opacity: 1;
    }
    
    /* Estilos para botones */
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
    
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    
    /* Espaciado entre botones */
    .btn + .btn {
        margin-left: 0.5rem;
    }
</style>

<h2>Editar artículo: <?= esc($item['name']) ?></h2>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<form action="<?= base_url('inventory/update/' . $item['id']) ?>" method="post">
    <?= csrf_field() ?>
    <!-- Elimina el input hidden de _method, usaremos POST directamente -->
    
     <div class="mb-3">
        <label for="code">Código</label>
        <input type="text" name="code" class="form-control" value="<?= esc($item['code']) ?>" readonly>
    </div>
    
    <div class="mb-3">
        <label for="name">Nombre</label>
        <input type="text" name="name" class="form-control" value="<?= esc($item['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="location">Ubicación</label>
        <input type="text" name="location" class="form-control" value="<?= esc($item['location']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="status">Estado</label>
        <select name="status" class="form-select" required>
            <?php foreach (['Disponible','En uso','En reparación','Prestado'] as $estado): ?>
                <option value="<?= $estado ?>" <?= $item['status'] === $estado ? 'selected' : '' ?>><?= $estado ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="current_responsible">Responsable</label>
        <input type="text" name="current_responsible" class="form-control" value="<?= esc($item['current_responsible']) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?= base_url('/inventory') ?>" class="btn btn-secondary">Cancelar</a>
</form>
