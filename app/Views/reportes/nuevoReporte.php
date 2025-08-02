<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Reporte de Incidente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card-form {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 850px;
            margin: auto;
        }
        .card-header-form {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 1.5rem;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .img-preview-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-top: 1rem;
            background: #f8f9fa;
            display: none;
        }
        .img-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 6px;
        }
        .form-icon {
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column-reverse;
                gap: 10px;
            }
            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column" style="min-height: 100vh;">
        <div class="my-auto">
            <div class="card card-form">
                <div class="card-header card-header-form text-center">
                    <h2 class="h4 mb-2">
                        <i class="fas fa-file-circle-plus text-primary me-2"></i>Nuevo Reporte de Incidente
                    </h2>
                    <p class="text-muted mb-0 small">Complete todos los campos requeridos</p>
                </div>
                
                <div class="card-body">
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('reportes/guardar') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold required-field">Evento</label>
                                <select name="event_id" class="form-select py-2" required>
                                    <option value="" disabled selected>Seleccione un evento</option>
                                    <?php foreach($eventos as $e): ?>
                                        <option value="<?= $e['id'] ?>" <?= old('event_id') == $e['id'] ? 'selected' : '' ?>>
                                            <?= esc($e['name']) ?><?= isset($e['venue_name']) ? ' - '.esc($e['venue_name']) : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione un evento
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold required-field">Artículo</label>
                                <select name="item_id" class="form-select py-2" required>
                                    <option value="" disabled selected>Seleccione un artículo</option>
                                    <?php foreach($articulos as $a): ?>
                                        <option value="<?= $a['id'] ?>" <?= old('item_id') == $a['id'] ? 'selected' : '' ?>>
                                            <?= esc($a['name']) ?><?= isset($a['status']) ? ' ('.esc($a['status']).')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione un artículo
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold required-field">Tipo de incidente</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text form-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <input type="text" name="incident_type" class="form-control py-2" required 
                                       value="<?= esc(old('incident_type') ?? '') ?>" 
                                       placeholder="Ej: Daño, Pérdida, Mal funcionamiento">
                                <div class="invalid-feedback">
                                    Por favor ingrese el tipo de incidente
                                </div>
                            </div>
                            <small class="text-muted">Describa brevemente la naturaleza del incidente</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold required-field">Descripción</label>
                            <textarea name="description" class="form-control py-2" rows="5" required
                                      placeholder="Describa el incidente con todo detalle"><?= esc(old('description') ?? '') ?></textarea>
                            <div class="invalid-feedback">
                                Por favor proporcione una descripción detallada
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto (opcional)</label>
                            <input type="file" name="photo" id="photoInput" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">Formatos aceptados: JPG, PNG (Máx. 2MB)</small>
                            
                            <div class="img-preview-container mt-3" id="imagePreviewContainer">
                                <img id="imagePreview" src="#" alt="Vista previa de imagen" class="img-preview mb-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeImageBtn">
                                    <i class="fas fa-trash me-1"></i> Eliminar imagen
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 form-actions">
                            <a href="<?= base_url('reportes') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Registrar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script para vista previa y validación -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vista previa de imagen
            const photoInput = document.getElementById('photoInput');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const removeImageBtn = document.getElementById('removeImageBtn');
            
            photoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSize = file.size / 1024 / 1024; // MB
                    
                    if (fileSize > 2) {
                        alert('El archivo excede el tamaño máximo de 2MB');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreviewContainer.style.display = 'block';
                        imagePreview.src = e.target.result;
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
            
            removeImageBtn.addEventListener('click', function() {
                photoInput.value = '';
                imagePreviewContainer.style.display = 'none';
                imagePreview.src = '#';
            });

            // Validación del formulario
            const forms = document.querySelectorAll('.needs-validation');
            
            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
        });
    </script>
</body>
</html>