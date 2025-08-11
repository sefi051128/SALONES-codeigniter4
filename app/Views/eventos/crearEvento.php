<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <h2><i class="fas fa-calendar-plus"></i> Crear Nuevo Evento</h2>
        
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-1"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('eventos/guardar') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-building"></i> Sede</label>
                <select name="venue_id" class="form-select" required>
                    <option value="">Seleccione una sede</option>
                    <?php foreach($sedes as $sede): ?>
                        <option value="<?= $sede['id'] ?>" <?= old('venue_id') == $sede['id'] ? 'selected' : '' ?>>
                            <?= esc($sede['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-heading"></i> Nombre del Evento</label>
                <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-calendar-day"></i> Disponoible hasta la Fecha y Hora:</label>
                <input type="datetime-local" name="date" class="form-control" 
                       value="<?= old('date') ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-align-left"></i> Descripción</label>
                <textarea name="description" class="form-control" rows="4"><?= old('description') ?></textarea>
            </div>
            
            <!-- Nuevo campo para el estado -->
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-info-circle"></i> Estado</label>
                <select name="status" class="form-select" required>
                    <option value="">Seleccione un estado</option>
                    <option value="activo" <?= old('status') == 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="completado" <?= old('status') == 'completado' ? 'selected' : '' ?>>Completado</option>
                    <option value="cancelado" <?= old('status') == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>
            
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Evento
                </button>
                <a href="<?= base_url('eventos') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>