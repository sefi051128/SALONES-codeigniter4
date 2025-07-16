<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="container mt-5">

    <h2 class="mb-4"><?= esc($title) ?></h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <a href="<?= base_url('users/create') ?>" class="btn btn-primary mb-3">
        <i class="fas fa-user-plus"></i> Agregar Usuario
    </a>
    <a href="/admin" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Atrás
    </a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= esc($user['id']) ?></td>
                <td><?= esc($user['username']) ?></td>
                <td><?= esc($user['role']) ?></td>
                <td>
                    <?php if (!empty($user['email'])): ?>
                        <a href="mailto:<?= esc($user['email']) ?>">
                            <i class="fas fa-envelope"></i> <?= esc($user['email']) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">No especificado</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($user['phone'])): ?>
                        <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $user['phone'])) ?>">
                            <i class="fas fa-phone"></i> <?= esc($user['phone']) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">No especificado</span>
                    <?php endif; ?>
                </td>
                <td class="text-nowrap">
                    <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="<?= base_url('users/delete/'.$user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>