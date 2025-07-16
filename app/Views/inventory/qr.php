<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código QR del artículo</title>

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --light-gray: #f3f4f6;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-gray);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            text-align: center;
            margin: 2rem auto;
        }
        
        h1 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .item-info {
            background-color: var(--light-gray);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .item-info strong {
            color: var(--secondary-color);
        }
        
        .qr-container {
            margin: 1.5rem 0;
            padding: 1rem;
            background-color: var(--white);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            display: inline-block;
        }
        
        .qr-container img {
            width: 200px;
            height: 200px;
            display: block;
            margin: 0 auto;
        }
        
        .download-btn {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .download-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        @media print {
            body {
                background-color: var(--white);
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
            
            .download-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h1>QR del artículo: <?= esc($item['name']) ?></h1>
    <p><strong>Código:</strong> <?= esc($item['code']) ?></p>

    <!-- Mostrar QR usando API externa o generado localmente -->
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($item['code']) ?>" alt="QR del artículo">

    <!-- También puedes usar librerías como endroid/qr-code si lo deseas generar localmente -->
</body>
</html>
