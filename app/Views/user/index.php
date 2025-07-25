<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title) ?> | Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
        }
        body {
            background-color: var(--secondary-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .badge-admin {
            background-color: #e74a3b;
        }
        .badge-coord {
            background-color: #f6c23e;
            color: #000;
        }
        .badge-client {
            background-color: #1cc88a;
        }
        .badge-logistic {
            background-color: #36b9cc;
        }
        .badge-security {
            background-color: #5a5c69;
        }
        .action-btns .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        @media (max-width: 768px) {
            .action-btns .btn {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users-cog mr-2"></i><?= esc($title) ?>
            </h1>
            <div>
                <a href="<?= base_url('admin') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Panel de Control
                </a>
                <a href="<?= base_url('users/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-user-plus fa-sm text-white-50"></i> Nuevo Usuario
                </a>
            </div>
        </div>

        <!-- Alertas -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Usuarios</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
    <li><a class="dropdown-item" href="<?= base_url('users/create') ?>"><i class="fas fa-user-plus mr-2"></i>Agregar Usuario</a></li>
    <li><a class="dropdown-item" href="<?= base_url('users/exportar-pdf') ?>"><i class="fas fa-file-pdf mr-2"></i>Exportar a PDF</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-filter mr-2"></i>Filtros Avanzados</a></li>
</ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-bordered table-hover table-striped w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['id']) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm mr-3">
                                                <div class="avatar-title bg-primary rounded-circle text-white" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?= esc($user['username']) ?></h6>
                                                <small class="text-muted">Registrado: <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></small>
                                            </div>
                                        </div>
                                    </td>
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
                                    <td>
                                        <?php if (!empty($user['email'])): ?>
                                            <a href="mailto:<?= esc($user['email']) ?>" class="text-primary">
                                                <i class="fas fa-envelope mr-1"></i> <?= esc($user['email']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-ban mr-1"></i> No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($user['phone'])): ?>
                                            <a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $user['phone'])) ?>" class="text-primary">
                                                <i class="fas fa-phone mr-1"></i> <?= esc($user['phone']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-ban mr-1"></i> No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Activo</span>
                                    </td>
                                    <td class="action-btns">
    <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Editar">
        <i class="fas fa-edit"></i>
    </a>
    <a href="<?= base_url('users/delete/'.$user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')" data-bs-toggle="tooltip" title="Eliminar">
        <i class="fas fa-trash-alt"></i>
    </a>
    <a href="<?= base_url('users/show/'.$user['id']) ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Ver detalles">
        <i class="fas fa-eye"></i>
    </a>
</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#usersTable').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                dom: '<"top"<"row"<"col-md-6"l><"col-md-6"f>>>rt<"bottom"<"row"<"col-md-6"i><"col-md-6"p>>>',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control form-control-sm');
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                }
            });

            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Filtro por rol
            $('.filter-role').click(function() {
                var role = $(this).data('role');
                $('#usersTable').DataTable().column(2).search(role).draw();
            });
        });

        function confirmDelete(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
                window.location.href = e.target.href;
            }
        }
    </script>
</body>
</html>