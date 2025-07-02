<h2><?= esc($title) ?></h2>

<?= session()->getFlashdata('success') ?>
<?= session()->getFlashdata('error') ?>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger">
        <?= $validation->listErrors() ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('/inventory/store') ?>" method="post">
    <?= csrf_field() ?>
 

    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
    </div>
   <div class="mb-3">
    <label for="quantity" class="form-label">Cantidad</label>
    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
</div>


    <div class="mb-3">
        <label for="location" class="form-label">Ubicación</label>
        <input type="text" name="location" class="form-control" value="<?= old('location') ?>" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Estado</label>
        <select name="status" class="form-select" required>
            <option value="">Selecciona estado</option>
            <option value="Disponible">Disponible</option>
            <option value="En uso">En uso</option>
            <option value="En reparación">En reparación</option>
            <option value="Prestado">Prestado</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="current_responsible" class="form-label">Responsable</label>
        <input type="text" name="current_responsible" class="form-control" value="<?= old('current_responsible') ?>">
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="<?= base_url('/inventory') ?>" class="btn btn-secondary">Cancelar</a>
</form>
