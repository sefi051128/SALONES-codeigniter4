<?php
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="reporte_usuarios_'.date('Y-m-d').'.pdf"');
?>
<h2><?= esc($title) ?></h2>
<p><strong>Generado:</strong> <?= esc($generatedAt) ?></p>
<p><strong>Total de usuarios:</strong> <?= count($users) ?></p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Fecha Registro</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= esc($user['id']) ?></td>
            <td><?= esc($user['username']) ?></td>
            <td><?= esc(ucfirst($user['role'])) ?></td>
            <td><?= !empty($user['email']) ? esc($user['email']) : 'N/A' ?></td>
            <td><?= !empty($user['phone']) ? esc($user['phone']) : 'N/A' ?></td>
            <td><?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>