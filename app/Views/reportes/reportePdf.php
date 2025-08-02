<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 5px; font-size: 18px; }
        .filtros { 
            background-color: #f8f9fa; 
            padding: 8px; 
            border-radius: 4px; 
            margin-bottom: 15px;
            font-size: 10px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th { 
            background-color: #343a40; 
            color: white; 
            padding: 6px; 
            text-align: left;
            font-size: 10px;
        }
        td { 
            padding: 5px; 
            border-bottom: 1px solid #ddd;
            font-size: 10px;
            vertical-align: top;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-dano { background-color: #ef4444; color: white; }
        .badge-perdida { background-color: #f59e0b; color: black; }
        .badge-otro { background-color: #3b82f6; color: white; }
        .footer { 
            margin-top: 15px; 
            text-align: right; 
            font-size: 9px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <h1><?= esc($title) ?></h1>
    
    <?php if (!empty($filtros['from']) || !empty($filtros['to'])): ?>
    <div class="filtros">
        <strong>Filtros aplicados:</strong>
        <?php if (!empty($filtros['from'])): ?>
            <span>Desde: <?= date('d/m/Y H:i', strtotime($filtros['from'])) ?></span>
        <?php endif; ?>
        <?php if (!empty($filtros['to'])): ?>
            <span> | Hasta: <?= date('d/m/Y H:i', strtotime($filtros['to'])) ?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th width="15%">Evento</th>
                <th width="15%">Artículo</th>
                <th width="10%">Tipo</th>
                <th width="40%">Descripción</th>
                <th width="20%">Fecha/Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportes as $r): ?>
                <tr>
                    <td><?= esc($r['event_name'] ?? '—') ?></td>
                    <td><?= esc($r['item_name'] ?? '—') ?></td>
                    <td>
                        <?php 
                            $typeLower = strtolower($r['incident_type'] ?? '');
                            $badgeClass = $typeLower === 'daño' || $typeLower === 'danio' 
                                ? 'badge-dano' 
                                : ($typeLower === 'pérdida' ? 'badge-perdida' : 'badge-otro');
                        ?>
                        <span class="badge <?= $badgeClass ?>">
                            <?= esc(ucfirst($r['incident_type'])) ?>
                        </span>
                    </td>
                    <td><?= wordwrap(esc($r['description']), 80, "<br>", true) ?></td>
                    <td><?= esc($r['report_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Generado el <?= esc($generatedAt) ?> | Total de reportes: <?= count($reportes) ?>
    </div>
</body>
</html>