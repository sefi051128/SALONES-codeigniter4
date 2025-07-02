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
