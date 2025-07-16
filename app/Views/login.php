<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión - EventMobiliario</title>
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

        .login-container {
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
        }

        .success {
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 4px solid var(--success);
            color: var(--success);
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

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }

        button[type="submit"] {
            background-color: var(--primary);
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
            background-color: var(--primary-dark);
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
            .login-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>


<!-- AQUÍ EMPIEZA EL CÓDIGO -->
<body>
    <div class="login-container">
        <h1><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h1>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('/login') ?>" method="POST">
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Usuario:</label>
                <input type="text" id="username" name="username" value="<?= old('username') ?>" required>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>
            </div>
        </form>

        <div class="auth-links">
            <p>¿No tienes cuenta? <a href="<?= base_url('/register') ?>">Regístrate aquí</a></p>
            <p><a href="<?= base_url('/') ?>"><i class="fas fa-arrow-left"></i> Volver al inicio</a></p>
        </div>
    </div>
</body>
</html>