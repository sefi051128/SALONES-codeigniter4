<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px 0;
        }
        
        .contact-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .contact-container h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 15px;
        }
        
        .info-icon {
            color: #3498db;
            font-size: 1.5rem;
        }
        
        .info-content h3 {
            color: #2c3e50;
        }
        
        .info-content p {
            color: #555;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }
        
        .map-container {
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contact-container p-4 p-md-5 my-4">
            <h1 class="text-center mb-4">Contáctenos</h1>
            
            <div class="row g-4">
                <!-- Información de Contacto -->
                <div class="col-lg-6">
                    <div class="contact-info p-3 p-md-4">
                        <h2 class="mb-4 text-primary">Información de Contacto</h2>
                        
                        <div class="d-flex mb-4">
                            <div class="info-icon me-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>Dirección</h3>
                                <p class="mb-0"><?= esc($contact_info['direccion']) ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="info-icon me-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Teléfono</h3>
                                <p class="mb-0"><?= esc($contact_info['telefono']) ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="info-icon me-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h3>Email</h3>
                                <p class="mb-0"><?= esc($contact_info['email']) ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div class="info-icon me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>Horario de Atención</h3>
                                <p class="mb-0"><?= esc($contact_info['horario']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de Contacto -->
                <div class="col-lg-6">
                    <div class="contact-form p-3 p-md-4">
                        <h2 class="mb-4 text-primary">Envíanos un Mensaje</h2>
                        
                        <form action="<?= base_url('contacto/enviar') ?>" method="post">
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">Nombre Completo</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Correo Electrónico</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="asunto" class="form-label fw-bold">Asunto</label>
                                <input type="text" name="asunto" id="asunto" class="form-control" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="mensaje" class="form-label fw-bold">Mensaje</label>
                                <textarea name="mensaje" id="mensaje" class="form-control" rows="6" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Mapa -->
            <div class="map-container mt-5">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3329.511216758862!2d-70.6488549242253!3d-33.4378889951345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9662c5a3a0c7027d%3A0x5f3a9f8a6d6b8b0!2sPlaza%20de%20Armas%20de%20Santiago!5e0!3m2!1ses!2scl!4v1712345678901!5m2!1ses!2scl" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            
            <!-- Botón Volver -->
            <div class="text-center mt-4">
                <a href="<?= base_url('inicio') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>