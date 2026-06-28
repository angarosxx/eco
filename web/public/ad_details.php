<?php
// MOCK DATA: Datos para simular el producto al estilo Gymfit
$ad = [
    'title' => 'Gymfit Core Pulse Trainers',
    'description' => "Engineered for high-intensity training and daily athletic performance. The Core Pulse Trainers deliver maximum stability with our proprietary reactive cushioning technology.\n\n• Breathable engineered mesh upper\n• High-traction rubber outsole for lateral stability\n• Lightweight design optimized for agility\n• Available for immediate delivery in the Antofagasta region.",
    'price' => 129000,
    'negotiable' => 0,
    'published_at' => '2026-06-28 14:30:00',
    'featured_until' => '2026-07-15 00:00:00',
    'condition' => 'new',
    'shipping' => 1,
    'pickup' => 1,
    'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1000',
    'comuna_nombre' => 'San Pedro de Atacama',
    'region_nombre' => 'Antofagasta',
    'latitude' => -22.911,
    'longitude' => -68.200,
    'whatsapp' => 1,
    'contact_phone' => '+56912345678',
    'allow_messages' => 1
];

$vendedor = [
    'nombre' => 'Gymfit Chile Official',
    'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200',
    'account_type' => 'company',
    'verified_identity' => 1,
    'rating' => 4.9,
    'reviews' => 142,
    'member_since' => '2025-01-10'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ad['title']) ?></title>
    <style>
        :root {
            --bg-site: #0b0f19;        /* Fondo ultra oscuro nórdico */
            --bg-surface: #131a26;     /* Superficie de paneles */
            --border-color: #222f43;   /* Bordes finos y elegantes */
            --text-main: #f8fafc;
            --text-muted: #64748b;
            --accent: #ffffff;         /* Gymfit usa mucho blanco puro para destacar botones */
            --accent-hover: #e2e8f0;
            --brand-color: #38bdf8;    /* Un celeste eléctrico sutil para detalles */
            --fuente: 'Inter', system-ui, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg-site);
            color: var(--text-main);
            font-family: var(--fuente);
            -webkit-font-smoothing: antialiased;
            padding: 3rem 1.5rem;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        /* Layout de Dos Columnas Estilo Shopify */
        .product-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 4rem;
        }

        @media (max-width: 992px) {
            .product-grid { grid-template-columns: 1fr; gap: 2rem; }
        }

        /* COLUMNA IZQUIERDA: Flujo Visual */
        .media-stack {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .main-image-container {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            aspect-ratio: 1 / 1; /* Cuadrado perfecto como las tiendas de ropa/zapatillas */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .main-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge-new {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--text-main);
            color: var(--bg-site);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* COLUMNA DERECHA: Detalles Sticky */
        .purchase-panel {
            position: sticky;
            top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .meta-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.5rem;
        }

        .vendor-tag {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: block;
        }

        .product-title {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
        }

        .price-container {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-main);
        }

        /* Bloques de Información Desplegada/Limpia */
        .info-block {
            font-size: 0.95rem;
            color: #cbd5e1;
            line-height: 1.7;
        }

        .info-block h4 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-main);
            margin-bottom: 0.75rem;
        }

        /* Botones de Acción Minimalistas */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin: 1.5rem 0;
        }

        .btn-gymfit {
            width: 100%;
            padding: 1.1rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary-gym {
            background-color: var(--accent);
            color: var(--bg-site);
        }

        .btn-primary-gym:hover {
            background-color: var(--accent-hover);
        }

        .btn-secondary-gym {
            background-color: transparent;
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-secondary-gym:hover {
            border-color: var(--text-main);
        }

        /* Caja de especificaciones discretas */
        .spec-list {
            border-top: 1px solid var(--border-color);
            padding-top: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            font-size: 0.85rem;
        }

        .spec-item span {
            display: block;
        }
        .spec-label { color: var(--text-muted); }
        .spec-value { color: var(--text-main); font-weight: 500; margin-top: 0.15rem; }

        /* Tarjeta Vendedor Integrada */
        .seller-mini-card {
            border: 1px solid var(--border-color);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255,255,255,0.02);
        }
        .seller-mini-avatar {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 2px;
        }

        /* Sección Inferior de Similares */
        .related-section {
            margin-top: 6rem;
            border-top: 1px solid var(--border-color);
            padding-top: 3rem;
        }

        .related-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .related-card {
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .related-img-holder {
            aspect-ratio: 1 / 1;
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .related-img-holder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .related-card:hover .related-img-holder img {
            transform: scale(1.03);
        }

        .related-meta h5 { font-size: 0.95rem; font-weight: 500; color: var(--text-main); }
        .related-meta span { font-size: 0.9rem; color: var(--text-muted); }
    </style>
</head>
<body>

<div class="container">
    <div class="product-grid">
        
        <div class="media-stack">
            <div class="main-image-container">
                <img src="<?= htmlspecialchars($ad['image_url']) ?>" alt="<?= htmlspecialchars($ad['title']) ?>">
                <?php if ($ad['condition'] === 'new'): ?>
                    <span class="badge-new">Nuevo</span>
                <?php endif; ?>
            </div>
            
            <div class="info-block" style="border: 1px solid var(--border-color); padding: 1.5rem;">
                <h4>Ubicación de disponibilidad</h4>
                <p style="font-size:0.9rem; color: var(--text-main);"><?= htmlspecialchars($ad['comuna_nombre']) ?>, Región de <?= htmlspecialchars($ad['region_nombre']) ?></p>
                <div style="width:100%; height:120px; background:var(--bg-site); margin-top:1rem; border:1px solid var(--border-color); display:flex; align-items:center; justify-content:center; font-size:0.8rem; color:var(--text-muted);">
                    [Mapa Base de Datos Activo]
                </div>
            </div>
        </div>

        <div class="purchase-panel">
            
            <div class="meta-header">
                <span class="vendor-tag"><?= htmlspecialchars($vendedor['nombre']) ?></span>
                <h1 class="product-title"><?= htmlspecialchars($ad['title']) ?></h1>
                <div class="price-container">
                    $<?= number_format($ad['price'], 0, ',', '.') ?> CLP
                </div>
            </div>

            <div class="info-block">
                <h4>Descripción del producto</h4>
                <p><?= nl2br(htmlspecialchars($ad['description'])) ?></p>
            </div>

            <div class="action-buttons">
                <?php if ($ad['whatsapp'] && !empty($ad['contact_phone'])): ?>
                    <a href="https://wa.me/<?= preg_replace('/\D/', '', $ad['contact_phone']) ?>" target="_blank" class="btn-gymfit btn-primary-gym">
                        Contactar vía WhatsApp
                    </a>
                <?php endif; ?>

                <?php if ($ad['allow_messages']): ?>
                    <button class="btn-gymfit btn-secondary-gym">Enviar Mensaje Interno</button>
                <?php endif; ?>
            </div>

            <div class="seller-mini-card">
                <img src="<?= htmlspecialchars($vendedor['avatar']) ?>" class="seller-mini-avatar">
                <div>
                    <span style="font-size:0.85rem; font-weight:600; display:block;"><?= htmlspecialchars($vendedor['nombre']) ?></span>
                    <span style="font-size:0.75rem; color:var(--text-muted);">⭐ <?= number_format($vendedor['rating'], 1) ?> (<?= $vendedor['reviews'] ?> reviews)</span>
                </div>
            </div>

            <div class="spec-list">
                <div class="spec-item">
                    <span class="spec-label">Condición</span>
                    <span class="spec-value" style="text-transform: capitalize;"><?= $ad['condition'] ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Envío a Región</span>
                    <span class="spec-value"><?= $ad['shipping'] ? 'Disponible' : 'Solo Retiro' ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Tipo de Cuenta</span>
                    <span class="spec-value" style="text-transform: uppercase; font-size:0.75rem; color:var(--brand-color);"><?= $vendedor['account_type'] ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Precio</span>
                    <span class="spec-value"><?= $ad['negotiable'] ? 'Conversable' : 'Fijo' ?></span>
                </div>
            </div>

        </div>
    </div>

    <section class="related-section">
        <h3 class="related-title">Te puede interesar</h3>
        <div class="related-grid">
            <?php for($i=1; $i<=4; $i++): ?>
            <a href="#" class="related-card">
                <div class="related-img-holder">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400">
                </div>
                <div class="related-meta">
                    <h5>Core Pulse Pro Trainer Variant v<?= $i ?></h5>
                    <span>$115.000 CLP</span>
                </div>
            </a>
            <?php endfor; ?>
        </div>
    </section>
</div>

</body>
</html>