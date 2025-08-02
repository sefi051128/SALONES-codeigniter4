<?php use CodeIgniter\I18n\Time; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
        }
        body { background-color: #f8f9fc; color: #5a5c69; }
        .card { border-radius: .35rem; border: none; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); margin-bottom:1rem; }
        .star-rating { font-size: 1.5rem; direction: rtl; display: inline-flex; }
        .star-rating input { display: none; }
        .star-rating label { cursor: pointer; color: #ccc; margin: 0; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #f5c518; }
        .btn-check + label { margin-right: 4px; }
        .status-group label { min-width: 90px; }
        .preview-img { max-width: 80px; margin-top: 5px; display: block; }
        .needs-attention { border: 1px solid #e67700; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between mb-4">
            <h1><i class="fas fa-clipboard-check text-primary me-2"></i>Devolución</h1>
            <a href="<?= base_url('/reservas') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Cancelar
            </a>
        </div>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $err): ?>
                    <div><?= esc($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Reserva #<?= esc($booking['id']) ?> - Evento: <?= esc($booking['event_name'] ?? 'N/A') ?></h5>
                <p class="mb-1"><i class="fas fa-calendar-day me-1"></i> Fecha del evento: <?= Time::parse($booking['event_date'])->toLocalizedString('dd/MM/yyyy h:mm a') ?></p>
                <p class="mb-0"><i class="fas fa-user me-1"></i> Cliente: <?= esc($booking['customer_name'] ?? 'N/A') ?></p>
            </div>
        </div>

        <!-- Resumen de ítems con daño/pérdida seleccionados -->
        <div id="incidenteResumen" class="mb-4 d-none">
            <div class="alert alert-warning">
                <strong>Atención:</strong> Has marcado algunos ítems con daño o pérdida. Asegúrate de proporcionar foto y descripción para cada uno. 
                <div id="listaIncidentes" class="mt-2"></div>
            </div>
        </div>

        <form id="devolucionForm" action="<?= route_to('devoluciones.cliente_guardar') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="booking_id" value="<?= esc($booking['id']) ?>">
            <input type="hidden" name="event_id" value="<?= esc($booking['event_id']) ?>">

            <!-- Checklist de devolución -->
            <div class="mb-4">
                <h4 class="mb-2">Checklist de Devolución</h4>
                <p>Por cada artículo, selecciona su estado. Si hay daño o pérdida, proporciona evidencia y descripción.</p>

                <?php foreach($itemsReservados as $item): ?>
                    <div class="card" data-item-id="<?= $item['id'] ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-1"><strong><?= esc($item['name']) ?></strong> (<?= esc($item['code']) ?>)</h5>
                                    <p class="mb-0 text-muted"><i class="fas fa-map-marker-alt me-1"></i> Ubicación: <?= esc($item['location'] ?? 'N/A') ?></p>
                                    <p class="mb-0 text-muted"><i class="fas fa-info-circle me-1"></i> Estado previo: <?= esc($item['status']) ?></p>
                                </div>
                            </div>

                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Estado del artículo</label>
                                    <div class="d-flex gap-2 status-group">
                                        <div>
                                            <input type="radio" class="btn-check estado-radio" name="status_<?= $item['id'] ?>" id="devuelto_<?= $item['id'] ?>" value="devuelto" autocomplete="off" checked>
                                            <label class="btn btn-outline-success" for="devuelto_<?= $item['id'] ?>">Devuelto</label>
                                        </div>
                                        <div>
                                            <input type="radio" class="btn-check estado-radio" name="status_<?= $item['id'] ?>" id="danio_<?= $item['id'] ?>" value="danio" autocomplete="off">
                                            <label class="btn btn-outline-warning" for="danio_<?= $item['id'] ?>">Daño</label>
                                        </div>
                                        <div>
                                            <input type="radio" class="btn-check estado-radio" name="status_<?= $item['id'] ?>" id="perdida_<?= $item['id'] ?>" value="perdida" autocomplete="off">
                                            <label class="btn btn-outline-danger" for="perdida_<?= $item['id'] ?>">Pérdida</label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="items[]" value="<?= $item['id'] ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Foto del artículo (evidencia)</label>
                                    <input type="file" name="photo_<?= $item['id'] ?>" accept="image/*" class="form-control form-control-sm photo-input" disabled>
                                    <img src="" alt="Preview" class="preview-img d-none" id="preview_<?= $item['id'] ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Comentarios / Detalle</label>
                                    <textarea name="comment_<?= $item['id'] ?>" class="form-control form-control-sm comment-input" rows="2" placeholder="Describe el daño o pérdida..." disabled></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Firma y reseña general -->
            <div class="mb-4">
                <h4 class="mb-2">Tu reseña</h4>
                <div class="row gy-3">
                    <div class="col-md-4">
                        <label class="form-label">Firmado por</label>
                        <input type="text" name="signed_by" class="form-control" value="<?= old('signed_by', session('username') ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block">Calificación</label>
                        <div class="star-rating">
                            <?php for($i=5;$i>=1;$i--): ?>
                                <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= old('rating') == $i ? 'checked' : '' ?>>
                                <label for="star<?= $i ?>" title="<?= $i ?> estrellas">&#9733;</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Reseña</label>
                        <textarea name="review" class="form-control" rows="3" placeholder="Cuéntanos cómo te fue..."><?= old('review') ?></textarea>
                    </div>
                </div>
            </div>

            <input type="hidden" name="checklist_type" value="devolucion">

            <div class="d-flex justify-content-between">
                <a href="<?= route_to('devoluciones.index') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle me-1"></i> Enviar devolución
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // actualizar controles habilitados, y resumen de incidentes
        function actualizarControles(itemId, estado) {
            const photoInput = document.querySelector(`input[name="photo_${itemId}"]`);
            const commentInput = document.querySelector(`textarea[name="comment_${itemId}"]`);
            const card = document.querySelector(`[data-item-id="${itemId}"]`);

            if (estado === 'devuelto') {
                if (photoInput) {
                    photoInput.disabled = true;
                    photoInput.value = '';
                }
                if (commentInput) {
                    commentInput.disabled = true;
                    commentInput.value = '';
                }
                card.classList.remove('needs-attention');
            } else if (estado === 'danio' || estado === 'perdida') {
                if (photoInput) photoInput.disabled = false;
                if (commentInput) commentInput.disabled = false;
                card.classList.add('needs-attention');
            }

            renderResumen();
        }

        // Preview de imagen
        function handlePreview(itemId, file) {
            const img = document.getElementById(`preview_${itemId}`);
            if (!img) return;
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                img.src = '';
                img.classList.add('d-none');
            }
        }

        // Resumen de incidentes (daño/perdida)
        function renderResumen() {
            const lista = [];
            document.querySelectorAll('input.estado-radio:checked').forEach(radio => {
                const itemId = radio.name.split('_')[1];
                const estado = radio.value;
                if (estado === 'danio' || estado === 'perdida') {
                    const nameEl = document.querySelector(`[data-item-id="${itemId}"] h5`);
                    const label = nameEl ? nameEl.textContent.trim() : `Ítem ${itemId}`;
                    lista.push(`${label} (${estado.toUpperCase()})`);
                }
            });

            const resumenContainer = document.getElementById('incidenteResumen');
            const listaEl = document.getElementById('listaIncidentes');

            if (lista.length > 0) {
                resumenContainer.classList.remove('d-none');
                listaEl.innerHTML = lista.map(i => `<div>• ${i}</div>`).join('');
            } else {
                resumenContainer.classList.add('d-none');
                listaEl.innerHTML = '';
            }
        }

        // Inicializar estados
        document.querySelectorAll('input[type=radio][name^="status_"]:checked').forEach(radio => {
            const itemId = radio.name.split('_')[1];
            actualizarControles(itemId, radio.value);
        });

        // Delegación de evento para radios de estado
        document.body.addEventListener('change', function(e) {
            if (e.target.matches('input.estado-radio')) {
                const itemId = e.target.name.split('_')[1];
                actualizarControles(itemId, e.target.value);
            }

            // manejar cambio de archivo para preview
            if (e.target.matches('input.photo-input')) {
                const name = e.target.name; // photo_#
                const parts = name.split('_');
                const itemId = parts[1];
                handlePreview(itemId, e.target.files[0]);
            }
        });

        // Validación previa al submit
        document.getElementById('devolucionForm').addEventListener('submit', function(e) {
            let invalid = false;
            document.querySelectorAll('input.estado-radio:checked').forEach(radio => {
                const itemId = radio.name.split('_')[1];
                const estado = radio.value;
                if (estado === 'danio' || estado === 'perdida') {
                    const photo = document.querySelector(`input[name="photo_${itemId}"]`);
                    const comment = document.querySelector(`textarea[name="comment_${itemId}"]`);
                    if (photo && photo.files.length === 0) {
                        invalid = true;
                        photo.classList.add('is-invalid');
                    } else if (photo) {
                        photo.classList.remove('is-invalid');
                    }
                    if (comment && comment.value.trim() === '') {
                        invalid = true;
                        comment.classList.add('is-invalid');
                    } else if (comment) {
                        comment.classList.remove('is-invalid');
                    }
                }
            });
            if (invalid) {
                e.preventDefault();
                alert('Por favor completa foto y comentario en los ítems marcados como daño o pérdida.');
                return false;
            }
        });

        // Estrellas visuales
        document.querySelectorAll('.star-rating input').forEach(input => {
            input.addEventListener('change', () => {
                document.querySelectorAll('.star-rating label').forEach(label => {
                    label.style.color = '#ccc';
                });
                const val = input.value;
                for (let i = 5; i >= val; i--) {
                    const lbl = document.querySelector(`label[for="star${i}"]`);
                    if (lbl) lbl.style.color = '#f5c518';
                }
            });
        });
    });
    </script>
</body>
</html>
