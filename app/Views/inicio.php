<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Mobiliario para Eventos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #4a6fa5;
            --primary-dark: #3a5a8a;
            --secondary: #ff7e5f;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --success: #28a745;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: #f5f5f5;
        }

        .user-dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 4px;
}

.dropdown-content a {
    color: #333;
    padding: 12px 16px;
    display: block;
    text-align: left;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.user-dropdown:hover .dropdown-content {
    display: block;
}

.role-badge {
    background: var(--primary);
    color: white;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 0.8em;
    margin-left: 5px;
}

        /* Navbar */
        .navbar {
            background-color: white;
            box-shadow: var(--box-shadow);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
            color: var(--secondary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .login-btn {
            background-color: var(--primary);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
        }

        .login-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 6rem 2rem;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .search-bar {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
        }

        .search-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-btn:hover {
            background-color: #e06b4d;
            transform: translateY(-2px);
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            color: var(--primary);
        }

        /* Furniture Grid */
        .furniture-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .furniture-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .furniture-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .card-text {
            color: var(--gray);
            margin-bottom: 1rem;
        }

        .card-features {
            margin-bottom: 1.5rem;
        }

        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .feature i {
            color: var(--secondary);
            margin-right: 0.5rem;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .price {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary);
        }

        .reserve-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .reserve-btn:hover {
            background-color: var(--primary-dark);
        }

        /* User Panel (visible al iniciar sesión) */
        .user-panel {
            display: none;
            background-color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 3rem;
        }

        .user-panel.active {
            display: block;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .user-greeting {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .logout-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .logout-btn:hover {
            background-color: #e06b4d;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            margin-top: 4rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--secondary);
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .social-icons a {
            color: white;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .social-icons a:hover {
            color: var(--secondary);
        }

        .copyright {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 1rem;
            }

            .nav-links {
                margin-top: 1rem;
                width: 100%;
                justify-content: space-between;
            }

            .hero {
                padding: 4rem 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .search-bar {
                flex-direction: column;
            }

            .search-input, .search-btn {
                width: 100%;
            }

            .container {
                padding: 0 1rem;
            }

            .furniture-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .card-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }

            /* Agregar esto a los estilos existentes */
.register-btn {
    background-color: var(--success);
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    transition: var(--transition);
}

.register-btn:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

/* Asegurar espacio entre botones */
.login-btn {
    margin-right: 10px;
}
        }
    </style>
</head>

<!-- AQUÍ EMPIEZA EL CÓDIGO -->
 
<body>
    <!-- Navbar -->
    <nav class="navbar">
    <a href="#" class="logo">
        <i class="fas fa-chair"></i>
        EventMobiliario
    </a>
    <div class="nav-links">
        <a href="#mobiliario">Ver sedes</a>
        <a href="#servicios">Servicios</a>
        <a href="#contacto">Contacto</a>
        
        <?php if (session('isLoggedIn')): ?>
            <!-- Usuario Logueado -->
            <div class="user-dropdown">
                <button class="user-btn">
                    <i class="fas fa-user"></i> <?= esc(session('username')) ?>
                    <span class="role-badge"><?= esc(session('role')) ?></span>
                </button>
                <div class="dropdown-content">
                    <a href="<?= base_url('profile') ?>">Mi Perfil</a>
                    <?php if (session('role') === 'administrador'): ?>
                        <a href="<?= base_url('admin') ?>">Panel Admin</a>
                    <?php elseif (session('role') !== 'cliente'): ?>
                        <a href="<?= base_url(session('role') . '/dashboard') ?>">Mi Panel</a>
                    <?php endif; ?>
                    <a href="<?= base_url('logout') ?>">Cerrar Sesión</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Usuario No Logueado -->
            <a href="<?= base_url('login') ?>" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
            </a>
            <a href="<?= base_url('register') ?>" class="register-btn">
                <i class="fas fa-user-plus"></i> Registrarse
            </a>
        <?php endif; ?>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Reserva el mobiliario perfecto para tu evento</h1>
        <p>Encuentra y reserva sillas, mesas y decoración para hacer de tu evento una ocasión inolvidable</p>
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Buscar mobiliario...">
            <select class="search-input">
                <option value="">Tipo de mobiliario</option>
                <option value="sillas">Sillas</option>
                <option value="mesas">Mesas</option>
                <option value="decoracion">Decoración</option>
            </select>
            <button class="search-btn">Buscar</button>
        </div>
    </section>

    <!-- User Panel (hidden by default) -->
    <div class="container">
        <div class="user-panel" id="userPanel">
            <div class="user-info">
                <h2 class="user-greeting">Bienvenido, <span id="username"></span></h2>
                <button class="logout-btn" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </button>
            </div>
            <p>Aquí puedes gestionar tus reservas y consultar el estado de tus pedidos.</p>
        </div>
    </div>

    <!-- Furniture Section -->
    <div class="container">
        <h2 class="section-title" id="mobiliario">Nuestro Mobiliario</h2>
        <div class="furniture-grid">
            <!-- Furniture Card 1 -->
            <div class="furniture-card">
                <img src="https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Sillas elegantes" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Sillas Elegantes</h3>
                    <p class="card-text">Sillas de alta calidad para eventos formales y bodas.</p>
                    <div class="card-features">
                        <div class="feature">
                            <i class="fas fa-chair"></i>
                            <span>Disponibles: 120</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-palette"></i>
                            <span>Colores: Blanco, Negro, Oro</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="price">$15/unidad</span>
                        <button class="reserve-btn">Reservar</button>
                    </div>
                </div>
            </div>

            <!-- Furniture Card 2 -->
            <div class="furniture-card">
                <img src="https://images.unsplash.com/photo-1550583724-b2692b85b150?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Mesas redondas" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Mesas Redondas</h3>
                    <p class="card-text">Mesas para 8-10 personas, ideales para banquetes.</p>
                    <div class="card-features">
                        <div class="feature">
                            <i class="fas fa-table"></i>
                            <span>Disponibles: 45</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-ruler-combined"></i>
                            <span>Diámetro: 1.5m</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="price">$80/unidad</span>
                        <button class="reserve-btn">Reservar</button>
                    </div>
                </div>
            </div>

            <!-- Furniture Card 3 -->
            <div class="furniture-card">
                <img src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Sillas plegables" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Sillas Plegables</h3>
                    <p class="card-text">Prácticas y cómodas para cualquier tipo de evento.</p>
                    <div class="card-features">
                        <div class="feature">
                            <i class="fas fa-chair"></i>
                            <span>Disponibles: 300</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-weight"></i>
                            <span>Peso: 2.5kg</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="price">$8/unidad</span>
                        <button class="reserve-btn">Reservar</button>
                    </div>
                </div>
            </div>

            <!-- Furniture Card 4 -->
            <div class="furniture-card">
                <img src="https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Barras de cóctel" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">Barra de Cóctel</h3>
                    <p class="card-text">Elegante barra para servir bebidas en tu evento.</p>
                    <div class="card-features">
                        <div class="feature">
                            <i class="fas fa-wine-glass-alt"></i>
                            <span>Disponibles: 15</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-ruler-combined"></i>
                            <span>Dimensiones: 2m x 0.6m</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="price">$120/unidad</span>
                        <button class="reserve-btn">Reservar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="contacto">
        <div class="footer-links">
            <a href="#">Inicio</a>
            <a href="#mobiliario">Mobiliario</a>
            <a href="#servicios">Servicios</a>
            <a href="#">Términos y condiciones</a>
            <a href="#">Política de privacidad</a>
        </div>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
        <p class="copyright">© 2023 EventMobiliario. Todos los derechos reservados.</p>
    </footer>

    <script>
        // Simulación de inicio de sesión
        document.getElementById('loginBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Simulamos un usuario logueado
            const username = "Usuario Ejemplo";
            document.getElementById('username').textContent = username;
            document.getElementById('userPanel').classList.add('active');
            document.getElementById('loginBtn').style.display = 'none';
            
            // Cambiar el botón de login por el nombre de usuario
            this.innerHTML = `<i class="fas fa-user"></i> ${username}`;
            this.classList.remove('login-btn');
            this.style.backgroundColor = 'transparent';
            this.style.color = 'var(--dark)';
            this.style.padding = '0';
            this.id = 'userProfileBtn';
            
            // Podrías agregar aquí la lógica real de autenticación
            alert(`Bienvenido ${username}! Ahora puedes reservar mobiliario.`);
        });

        // Cerrar sesión
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'logoutBtn') {
                document.getElementById('userPanel').classList.remove('active');
                document.getElementById('loginBtn').style.display = 'block';
                
                const userProfileBtn = document.getElementById('userProfileBtn');
                if (userProfileBtn) {
                    userProfileBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Iniciar sesión';
                    userProfileBtn.classList.add('login-btn');
                    userProfileBtn.style.backgroundColor = 'var(--primary)';
                    userProfileBtn.style.color = 'white';
                    userProfileBtn.style.padding = '0.6rem 1.2rem';
                    userProfileBtn.id = 'loginBtn';
                }
                
                alert('Has cerrado sesión correctamente.');
            }
        });

        // Interacción con botones de reserva
        const reserveButtons = document.querySelectorAll('.reserve-btn');
        reserveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.furniture-card');
                const itemName = card.querySelector('.card-title').textContent;
                
                if (document.getElementById('userPanel').classList.contains('active')) {
                    alert(`Has reservado: ${itemName}\nSerás redirigido al proceso de pago.`);
                    // Aquí iría la lógica real de reserva
                } else {
                    alert('Por favor inicia sesión para reservar mobiliario.');
                    document.getElementById('loginBtn').click();
                }
            });
        });
    </script>
</body>
</html>