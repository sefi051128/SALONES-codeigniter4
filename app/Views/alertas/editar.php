<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alerta #<?= esc($alerta['id']) ?></title>
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Alerta #<?= esc($alerta['id']) ?></h2>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="<?= site_url('alertas/actualizar/' . $alerta['id']) ?>" method="post">
                <div class="mb-3">
                    <label for="item_id" class="form-label">Artículo</label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">Selecciona un artículo</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= set_select('item_id', $item['id'], ($alerta['item_id'] == $item['id'])) ?>>
                                <?= esc($item['name']) ?> (ID: <?= $item['id'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="alert_type" class="form-label">Tipo de alerta</label>
                    <input type="text" name="alert_type" id="alert_type" class="form-control" required value="<?= set_value('alert_type', $alerta['alert_type']) ?>">
                </div>

                <div class="mb-3">
                    <label for="alert_date" class="form-label">Fecha de alerta</label>
                    <input type="date" name="alert_date" id="alert_date" class="form-control" required value="<?= set_value('alert_date', $alerta['alert_date']) ?>">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="resolved" id="resolved" class="form-check-input" value="1" <?= set_checkbox('resolved', '1', ($alerta['resolved'] == 1)) ?>>
                    <label for="resolved" class="form-check-label">Resuelto</label>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2">Actualizar</button>
                    <a href="<?= site_url('alertas') ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>