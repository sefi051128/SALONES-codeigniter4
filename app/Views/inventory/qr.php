<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código QR del artículo</title>
</head>
<body>
    <h1>QR del artículo: <?= esc($item['name']) ?></h1>
    <p><strong>Código:</strong> <?= esc($item['code']) ?></p>

    <!-- Mostrar QR usando API externa o generado localmente -->
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($item['code']) ?>" alt="QR del artículo">

    <!-- También puedes usar librerías como endroid/qr-code si lo deseas generar localmente -->
</body>
</html>
