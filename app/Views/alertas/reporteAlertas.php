<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th { 
            background-color: #343a40; 
            color: white; 
            padding: 8px; 
            text-align: left;
            font-size: 11px;
        }
        td { 
            padding: 6px; 
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .bg-success { background-color: #28a745; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .footer { 
            margin-top: 20px; 
            text-align: right; 
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <h1><?= esc($title) ?></h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Artículo</th>
                <th>Tipo de Alerta</th>
                <th>Fecha de Alerta</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alertas as $alerta): ?>
                <tr>
                    <td><?= esc($alerta['id']) ?></td>
                    <td><?= esc($alerta['item_name'] ?? $alerta['item_id']) ?></td>
                    <td><?= esc($alerta['alert_type']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($alerta['alert_date'])) ?></td>
                    <td>
                        <span class="badge <?= $alerta['resolved'] ? 'bg-success' : 'bg-warning' ?>">
                            <?= $alerta['resolved'] ? 'Resuelta' : 'Pendiente' ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Generado el <?= esc($generatedAt) ?> | Total de alertas: <?= count($alertas) ?>
    </div>
</body>
</html>