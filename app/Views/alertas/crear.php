<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Alerta</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Crear Nueva Alerta</h2>
            </div>

            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('alertas/guardar') ?>" method="post">
                <div class="mb-3">
                    <label for="item_id" class="form-label">Artículo <span class="text-danger">*</span></label>
                    <select name="item_id" id="item_id" class="form-select" required>
                        <option value="">Selecciona un artículo</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= old('item_id') == $item['id'] ? 'selected' : '' ?>>
                                <?= esc($item['name']) ?> (ID: <?= $item['id'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="alert_type" class="form-label">Tipo de alerta <span class="text-danger">*</span></label>
                    <input type="text" name="alert_type" id="alert_type" class="form-control" required 
                           value="<?= esc(old('alert_type')) ?>" placeholder="Ej: Stock mínimo, Vencimiento, etc.">
                </div>

                <div class="mb-3">
                    <label for="alert_date" class="form-label">Fecha de alerta <span class="text-danger">*</span></label>
                    <input type="date" name="alert_date" id="alert_date" class="form-control" required 
                           value="<?= esc(old('alert_date')) ?>">
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= site_url('alertas') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Alerta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script para mejorar la experiencia de fecha -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer fecha actual por defecto si no hay valor
            if(!document.getElementById('alert_date').value) {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('alert_date').value = today;
            }
        });
    </script>
</body>
</html>