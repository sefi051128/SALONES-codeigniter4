<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 20px; }
        .header { margin-bottom: 20px; }
        .filtros { 
            background-color: #f8f9fa; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px;
            font-size: 11px;
        }
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
        .badge-admin { background-color: #e74a3b; color: white; }
        .badge-coord { background-color: #f6c23e; color: black; }
        .badge-client { background-color: #1cc88a; color: white; }
        .badge-logistic { background-color: #36b9cc; color: white; }
        .badge-security { background-color: #5a5c69; color: white; }
        .footer { 
            margin-top: 20px; 
            text-align: right; 
            font-size: 10px;
            color: #6c757d;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= esc($title) ?></h1>
        
        <?php if (!empty($filtros['role']) || !empty($filtros['search'])): ?>
        <div class="filtros">
            <p><strong>Filtros aplicados:</strong></p>
            <ul>
                <?php if (!empty($filtros['role'])): ?>
                    <li>Rol: <?= esc(ucfirst($filtros['role'])) ?></li>
                <?php endif; ?>
                <?php if (!empty($filtros['search'])): ?>
                    <li>Búsqueda: <?= esc($filtros['search']) ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Registro</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= esc($user['id']) ?></td>
                    <td><?= esc($user['username']) ?></td>
                    <td>
                        <?php 
                            $badgeClass = 'badge-primary';
                            switch(strtolower($user['role'])) {
                                case 'administrador': $badgeClass = 'badge-admin'; break;
                                case 'coordinador': $badgeClass = 'badge-coord'; break;
                                case 'cliente': $badgeClass = 'badge-client'; break;
                                case 'logística': $badgeClass = 'badge-logistic'; break;
                                case 'seguridad': $badgeClass = 'badge-security'; break;
                            }
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= esc(ucfirst($user['role'])) ?></span>
                    </td>
                    <td><?= esc($user['email'] ?? 'N/A') ?></td>
                    <td><?= esc($user['phone'] ?? 'N/A') ?></td>
                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                    <td>Activo</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Generado el <?= esc($generatedAt) ?> | Total de usuarios: <?= count($users) ?>
    </div>
</body>
</html>