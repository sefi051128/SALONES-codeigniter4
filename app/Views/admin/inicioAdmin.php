<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Control</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .user-info { 
            background: #f5f5f5; 
            padding: 1rem; 
            border-radius: 5px; 
            margin-bottom: 2rem; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .user-details { flex-grow: 1; }
        .action-buttons { display: flex; gap: 10px; }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            text-align: center;
            min-width: 120px;
        }
        .btn-logout { background: #dc3545; }
        .btn-logout:hover { background: #c82333; }
        .btn-inventario { background: #007bff; }
        .btn-inventario:hover { background: #0056b3; }
        .btn-users { background: #28a745; }
        .btn-users:hover { background: #218838; }
        
        /* Estilos para la sección de usuarios */
        .users-section { margin-top: 2rem; }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .users-table th, .users-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .users-table th {
            background-color: #343a40;
            color: white;
        }
        .users-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .users-table tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            padding: 0.4rem 0.8rem;
            margin: 0 0.2rem;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-edit { 
            background-color: #ffc107; 
            color: #212529;
        }
        .btn-delete { 
            background-color: #dc3545; 
            color: white;
        }
        .btn-create {
            display: inline-block;
            margin-top: 1.5rem;
            background: #17a2b8;
            color: white;
            padding: 0.7rem 1.3rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-create:hover {
            background: #138496;
            transform: translateY(-2px);
        }
        .badge-role {
            display: inline-block;
            padding: 0.25em 0.6em;
            border-radius: 10px;
            font-size: 0.85em;
            font-weight: bold;
            color: white;
        }
        .badge-admin { background-color: #dc3545; }
        .badge-coord { background-color: #6f42c1; }
        .badge-client { background-color: #28a745; }
        .badge-logistic { background-color: #fd7e14; }
        .badge-security { background-color: #17a2b8; }
    </style>
</head>
<body>
    <div class="user-info">
        <div class="user-details">
            <h1>Bienvenido, <?= esc(session('username')) ?></h1>
            <p><strong>Rol:</strong> <?= esc(session('role')) ?></p>
             <p><strong>ID de Usuario:</strong> <?= esc(session('user_id')) ?>
        </div>

        <div class="action-buttons">

    <a href="<?= base_url('/logout') ?>" class="btn btn-logout">Cerrar sesión</a>
    <a href="<?= base_url('/logout') ?>" class="btn btn-logout">Boton pendiente</a>
    <a href="<?= base_url('/inventory') ?>" class="btn btn-inventario">Ver inventario general</a>
    <?php if (session('role') === 'administrador'): ?>
        <a href="<?= base_url('/users') ?>" class="btn btn-users">Ver usuarios</a>

    <?php endif; ?>
</div>
    </div>

    <?php if (session('role') === 'administrador'): ?>
        <div class="admin-content">
            <h2>Panel de Administración</h2>
            
            <?php if (isset($show_users) && $show_users): ?>
                <div class="users-section">
                    <h3>Gestión de Usuarios</h3>
                    
                    <?php if(session()->getFlashdata('success')): ?>
                        <div style="color: green; padding: 10px; background: #e8f5e9; margin-bottom: 15px; border-radius: 4px;">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div style="color: #721c24; padding: 10px; background: #f8d7da; margin-bottom: 15px; border-radius: 4px;">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Contacto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= esc($usuario['id']) ?></td>
                                <td><?= esc($usuario['username']) ?></td>
                                <td><?= esc($usuario['role']) ?></td>
                                <td><?= esc($usuario['contact_info'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="<?= base_url('/admin/editar/'.$usuario['id']) ?>" class="action-btn btn-edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="<?= base_url('/admin/eliminar/'.$usuario['id']) ?>" 
                                       class="action-btn btn-delete" 
                                       onclick="return confirm('¿Eliminar usuario <?= esc($usuario['username']) ?>?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <a href="<?= base_url('/admin/crear') ?>" class="btn-create">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </a>
                </div>
            <?php else: ?>
                <div class="admin-default">
                    <p>Bienvenido al panel de administración. Seleccione una opción del menú superior.</p>
                    <div style="margin-top: 2rem; display: flex; gap: 15px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px; background: #e9ecef; padding: 1.5rem; border-radius: 8px;">
                            <h3>Resumen del Sistema</h3>
                            <p>Total usuarios: <?= count($usuarios ?? []) ?></p>
                        </div>
                        <div style="flex: 1; min-width: 200px; background: #e9ecef; padding: 1.5rem; border-radius: 8px;">
                            <h3>Acciones Rápidas</h3>
                            <a href="<?= base_url('/admin/usuarios') ?>" style="display: block; margin: 5px 0; color: #007bff;">Ver usuarios</a>
                            <a href="<?= base_url('/admin/crear') ?>" style="display: block; margin: 5px 0; color: #28a745;">Crear usuario</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Font Awesome para iconos (opcional) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>