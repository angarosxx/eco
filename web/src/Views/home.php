<?php
// web/src/Views/home.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Marketplace — Próximamente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a; /* Fondo oscuro elegante */
            color: #f8fafc;
            font-family: system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .construction-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 40px;
            backdrop-filter: blur(10px);
            max-width: 550px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .badge-chile {
            background-color: #2563eb;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .spinner-custom {
            color: #38bdf8;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <div class="construction-card mx-auto">
        <span class="badge-chile">📍 ECOMERCIO CHILE</span>
        
        <h1 class="fw-bold mb-3">Estamos construyendo algo grande</h1>
        
        <p class="text-secondary mb-4">
            Nuestra plataforma de soluciones de mercado B2C2B está en camino. Estamos preparando la infraestructura óptima para conectar comercios en todo el país.
        </p>

        <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
            <div class="spinner-border spinner-border-sm spinner-custom" role="status"></div>
            <span class="text-muted small">Configurando servidores y pasarelas de pago...</span>
        </div>
    </div>
</div>

</body>
</html>