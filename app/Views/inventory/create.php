<style>
    :root {
        --primary: #3498db;
        --primary-hover: #2980b9;
        --success: #2ecc71;
        --success-hover: #27ae60;
        --danger: #e74c3c;
        --danger-hover: #c0392b;
        --secondary: #95a5a6;
        --secondary-hover: #7f8c8d;
        --light: #ecf0f1;
        --dark: #2c3e50;
        --border-radius: 8px;
        --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f5f7fa;
        padding: 20px;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    h2 {
        color: var(--dark);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary);
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
    }

    .alert-danger {
        background-color: rgba(231, 76, 60, 0.1);
        border-left: 4px solid var(--danger);
        color: var(--danger);
    }

    .alert-success {
        background-color: rgba(46, 204, 113, 0.1);
        border-left: 4px solid var(--success);
        color: var(--success);
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 5px;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-size: 16px;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .btn {
        display: inline-block;
        padding: 12px 24px;
        border: none;
        border-radius: var(--border-radius);
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-success {
        background-color: var(--success);
        color: white;
    }

    .btn-success:hover {
        background-color: var(--success-hover);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: var(--secondary);
        color: white;
    }

    .btn-secondary:hover {
        background-color: var(--secondary-hover);
        transform: translateY(-2px);
    }

    .form-actions {
        margin-top: 30px;
        display: flex;
        gap: 15px;
    }

    .mb-3 {
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>


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
