<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Accesos al Almacén</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #2c3e50; text-align: center; }
        .header { margin-bottom: 20px; }
        .filtros { 
            background-color: #f8f9fa; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th { 
            background-color: #343a40; 
            color: white; 
            padding: 8px; 
            text-align: left;
        }
        td { 
            padding: 8px; 
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        .badge-entrada { background-color: #d4edda; color: #155724; }
        .badge-salida { background-color: #f8d7da; color: #721c24; }
        .footer { 
            margin-top: 30px; 
            text-align: right; 
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Accesos al Almacén</h1>
        <div class="filtros">
            <p><strong>Filtros aplicados:</strong></p>
            <ul>
                <li>Usuario: <?= !empty($filtros['user_id']) ? esc($filtros['user_id']) : 'Todos' ?></li>
                <li>Tipo de acceso: <?= !empty($filtros['access_type']) ? esc($filtros['access_type']) : 'Todos' ?></li>
                <li>Desde: <?= !empty($filtros['from']) ? esc($filtros['from']) : 'No especificado' ?></li>
                <li>Hasta: <?= !empty($filtros['to']) ? esc($filtros['to']) : 'No especificado' ?></li>
            </ul>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th># Registro</th>
                <th>Usuario</th>
                <th>Fecha y Hora</th>
                <th>Tipo de Acceso</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($accesos)): ?>
                <?php foreach ($accesos as $acceso): ?>
                    <tr>
                        <td><?= esc($acceso['id']) ?></td>
                        <td><?= esc($acceso['username']) ?> (ID: <?= esc($acceso['user_id'] ?? 'N/A') ?>)</td>
                        <td><?= date('d/m/Y H:i', strtotime($acceso['access_time'])) ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($acceso['access_type']) ?>">
                                <?= esc($acceso['access_type']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No hay registros de accesos</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Generado el <?= date('d/m/Y H:i') ?> | Total de registros: <?= count($accesos) ?>
    </div>
</body>
</html>