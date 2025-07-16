<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - EventMobiliario</title>
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
            --danger: #dc3545;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .register-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
        }

        h1 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        .error {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid var(--danger);
            color: var(--danger);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus, textarea:focus, select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }

        button[type="submit"] {
            background-color: var(--success);
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer; 
            transition: var(--transition);
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray);
        }

        .auth-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1><i class="fas fa-user-plus"></i> Registro de Usuario</h1>

        <?php if(session()->getFlashdata('errors')): ?>
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form action="<?= base_url('/createUser') ?>" method="POST">
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Usuario:</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Contraseña (mínimo 8 caracteres):</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Correo electrónico:</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i> Teléfono:</label>
                <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>">
            </div>

            <input type="hidden" name="role" value="cliente">

            <div class="form-group">
                <button type="submit"><i class="fas fa-user-plus"></i> Registrar</button>
            </div>
        </form>

        <div class="auth-links">
            <p>¿Ya tienes cuenta? <a href="<?= base_url('/login') ?>">Inicia sesión aquí</a></p>
            <p><a href="<?= base_url('/') ?>"><i class="fas fa-arrow-left"></i> Volver al inicio</a></p>
        </div>
    </div>
</body>
</html>