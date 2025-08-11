<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Servicios - Empresa de Renta de Salones</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
            position: relative;
        }
        .service-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .service-icon {
            font-size: 2.5rem;
            color: #d4af37;
            margin-bottom: 15px;
        }
        .section-title {
            position: relative;
            margin-bottom: 40px;
            padding-bottom: 15px;
        }
        .section-title:after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #d4af37;
        }
        .about-us {
            background-color: #fff;
            padding: 50px 0;
            margin: 50px 0;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
        .img-fluid-rounded {
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            .display-4 {
                font-size: 2.5rem;
            }
            .about-us .row > div {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EventSalones</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container position-relative">
           
            <h1 class="display-4 fw-bold mb-4">Salones para Eventos</h1>
            <p class="lead">Transformamos tus momentos especiales en experiencias inolvidables</p>
            <a href="<?= base_url('inicio') ?>" class="btn btn-primary btn-lg mt-3 d-md-none">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-us">
        <div class="container">
            <h2 class="text-center section-title">¿Quiénes Somos?</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Nuestro equipo" class="img-fluid img-fluid-rounded shadow">
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 bg-transparent">
                        <div class="card-body">
                            <p class="lead">Con más de 15 años de experiencia en la industria de eventos, nos enorgullecemos de ofrecer los espacios más exclusivos y versátiles para cualquier ocasión.</p>
                            <p>Nuestro equipo profesional está comprometido con hacer de tu evento una experiencia única, cuidando cada detalle para superar tus expectativas.</p>
                            
                            <div class="accordion mt-4" id="aboutAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMission">
                                            Nuestra Misión
                                        </button>
                                    </h2>
                                    <div id="collapseMission" class="accordion-collapse collapse show" data-bs-parent="#aboutAccordion">
                                        <div class="accordion-body">
                                            Proporcionar espacios elegantes y funcionales, acompañados de servicios excepcionales que hagan de cada evento una celebración memorable.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdvantage">
                                            Nuestra Ventaja
                                        </button>
                                    </h2>
                                    <div id="collapseAdvantage" class="accordion-collapse collapse" data-bs-parent="#aboutAccordion">
                                        <div class="accordion-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item bg-transparent"><i class="fas fa-check-circle text-success me-2"></i> Salones completamente equipados</li>
                                                <li class="list-group-item bg-transparent"><i class="fas fa-check-circle text-success me-2"></i> Equipo profesional de coordinación</li>
                                                <li class="list-group-item bg-transparent"><i class="fas fa-check-circle text-success me-2"></i> Proveedores certificados</li>
                                                <li class="list-group-item bg-transparent"><i class="fas fa-check-circle text-success me-2"></i> Flexibilidad en diseños y layouts</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="container my-5">
        <h2 class="text-center section-title">Nuestros Servicios</h2>
        <p class="text-center mb-5">Ofrecemos soluciones completas para todo tipo de eventos sociales y corporativos</p>
        
        <div class="row g-4">
            <!-- Ejemplo de servicios - normalmente estos vendrían de tu base de datos -->
            <div class="col-md-6 col-lg-4">
                <div class="card service-card h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-glass-cheers service-icon"></i>
                        <h3 class="card-title h5">Bodas y Recepciones</h3>
                        <p class="card-text">Espacios elegantes y personalizados para hacer de tu boda el evento soñado. Incluye coordinación integral y diseño personalizado.</p>
                        <a href="#" class="btn btn-outline-primary mt-3">Más información</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card service-card h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-briefcase service-icon"></i>
                        <h3 class="card-title h5">Eventos Corporativos</h3>
                        <p class="card-text">Salones equipados con tecnología de punta para conferencias, convenciones y reuniones ejecutivas. Servicio audiovisual incluido.</p>
                        <a href="#" class="btn btn-outline-primary mt-3">Más información</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card service-card h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-birthday-cake service-icon"></i>
                        <h3 class="card-title h5">Fiestas de Cumpleaños</h3>
                        <p class="card-text">Celebra tus momentos especiales en nuestros salones temáticos con opciones de catering y decoración personalizada.</p>
                        <a href="#" class="btn btn-outline-primary mt-3">Más información</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card service-card h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-graduation-cap service-icon"></i>
                        <h3 class="card-title h5">Graduaciones</h3>
                        <p class="card-text">Paquetes completos para graduaciones con áreas para ceremonias, recepciones y fotografía profesional incluida.</p>
                        <a href="#" class="btn btn-outline-primary mt-3">Más información</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card service-card h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-utensils service-icon"></i>
                        <h3 class="card-title h5">Catering y Banquetes</h3>
                        <p class="card-text">Servicio gastronómico de primera calidad con menús personalizados para cada tipo de evento y requerimientos dietéticos.</p>
                        <a href="#" class="btn btn-outline-primary mt-3">Más información</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card service-card h-100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-photo-video service-icon"></i>
                        <h3 class="card-title h5">Producción Audiovisual</h3>
                        <p class="card-text">Equipo técnico profesional para transmisiones en vivo, grabación de eventos y producción multimedia de alta calidad.</p>
                        <a href="#" class="btn btn-outline-primary mt-3">Más información</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center section-title">Lo que dicen nuestros clientes</h2>
            
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card border-0 bg-transparent text-center p-4">
                                    <img src="https://randomuser.me/api/portraits/women/32.jpg" class="rounded-circle mx-auto mb-3" width="80" alt="Cliente">
                                    <div class="card-body">
                                        <p class="card-text lead">"El salón fue perfecto para nuestra boda. El equipo estuvo atento a cada detalle y todo salió impecable. ¡Recomendados 100%!"</p>
                                        <h5 class="card-title mt-3">María González</h5>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card border-0 bg-transparent text-center p-4">
                                    <img src="https://randomuser.me/api/portraits/men/45.jpg" class="rounded-circle mx-auto mb-3" width="80" alt="Cliente">
                                    <div class="card-body">
                                        <p class="card-text lead">"Organizamos nuestra convención anual aquí y fue un éxito total. La tecnología funcionó perfectamente y el servicio fue excelente."</p>
                                        <h5 class="card-title mt-3">Carlos Mendoza</h5>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">¿Listo para planificar tu evento?</h2>
        <p class="lead mb-4">Contáctanos hoy mismo para una consulta gratuita</p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="<?= base_url('contacto') ?>" class="btn btn-light btn-lg px-4 py-3">
                <i class="fas fa-envelope me-2"></i> Contáctanos
            </a>
            <a href="#" class="btn btn-outline-light btn-lg px-4 py-3">
                <i class="fas fa-phone-alt me-2"></i> Llamar ahora
            </a>
            <a href="<?= base_url('/') ?>" class="btn btn-outline-light btn-lg px-4 py-3">
                <i class="fas fa-home me-2"></i> Volver al Inicio
            </a>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4">EventSalones</h5>
                    <p>Transformamos tus momentos especiales en experiencias inolvidables desde 2008.</p>
                    <div class="social-icons mt-4">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
    <h5 class="mb-4">Enlaces rápidos</h5>
    <ul class="list-unstyled">
        <li class="mb-2">
            <a href="<?= base_url('/') ?>" class="text-white text-decoration-none">
                <i class="fas fa-home me-2"></i> Inicio
            </a>
        </li>
        <li class="mb-2">
            <a href="<?= base_url('servicios') ?>" class="text-white text-decoration-none">
                Servicios
            </a>
        </li>
        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Galería</a></li>
        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Testimonios</a></li>
        <li><a href="<?= base_url('contacto') ?>" class="text-white text-decoration-none">Contacto</a></li>
    </ul>
</div>
                <div class="col-md-4">
                    <h5 class="mb-4">Contacto</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Av. Principal 1234, Tlaxcala Centro, Tlaxcala</p>
                    <p><i class="fas fa-phone-alt me-2"></i> +56 2 2345 6789</p>
                    <p><i class="fas fa-envelope me-2"></i> contacto@eventsalones.cl</p>
                    <p><i class="fas fa-clock me-2"></i> Lunes a Viernes: 9:00 - 18:00 hrs</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2025 EventMobiliario. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        <a href="#" class="text-white text-decoration-none me-3">Términos de servicio</a>
                        <a href="#" class="text-white text-decoration-none">Política de privacidad</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>