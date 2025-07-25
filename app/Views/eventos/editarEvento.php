<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container py-5">
        <h2>Editar Evento</h2>
        
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-1"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('eventos/actualizar/'.$evento['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Sede</label>
                <select name="venue_id" class="form-select" required>
                    <option value="">Seleccione una sede</option>
                    <?php foreach($sedes as $sede): ?>
                        <option value="<?= $sede['id'] ?>" <?= $sede['id'] == $evento['venue_id'] ? 'selected' : '' ?>>
                            <?= esc($sede['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nombre del Evento</label>
                <input type="text" name="name" class="form-control" value="<?= esc($evento['name']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Fecha y Hora</label>
                <input type="datetime-local" name="date" class="form-control" 
                       value="<?= date('Y-m-d\TH:i', strtotime($evento['date'])) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-control" rows="4"><?= esc($evento['description']) ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Actualizar Evento</button>
            <a href="<?= base_url('eventos') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>