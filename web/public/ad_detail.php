<?php
// MOCK DATA: Datos ficticios para probar el diseño sin caerse
$ad = [
    'title' => 'iPhone 15 Pro Max 256GB - Como Nuevo',
    'description' => "Excelente estado, único dueño. Siempre usado con carcasa y lámina de vidrio.\n\nSe entrega en caja original con su cable de carga intacto. Batería al 94% de condición. Solo venta, no permuto.",
    'price' => 850000,
    'negotiable' => 1,
    'published_at' => '2026-06-28 14:30:00',
    'featured_until' => '2026-07-15 00:00:00',
    'condition' => 'used',
    'shipping' => 1,
    'pickup' => 1,
    'image_url' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?q=80&w=1000',
    'video_url' => 'https://youtube.com',
    'comuna_nombre' => 'Calama',
    'region_nombre' => 'Antofagasta',
    'latitude' => -22.454392,
    'longitude' => -68.929504,
    'whatsapp' => 1,
    'contact_phone' => '+56912345678',
    'allow_messages' => 1
];

$vendedor = [
    'nombre' => 'Complejo Turístico San Pedro',
    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200',
    'account_type' => 'company',
    'verified_identity' => 1,
    'rating' => 4.8,
    'reviews' => 24,
    'member_since' => '2024-03-15'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ad['title']) ?> - Ecomercio</title>
    <style>
        :root {
            --bg-principal: #0f172a; /* Slate 900 */
            --bg-tarjeta: #1e293b;    /* Slate 800 */
            --bg-oscuro: #020617;     /* Slate 950 */
            --texto-principal: #f8fafc;
            --texto-secundario: #94a3b8;
            --color-primario: #f59e0b; /* Ámbar */
            --color-exito: #10b981;    /* Esmeralda */
            --color-peligro: #f43f5e;  /* Rosa/Rojo */
            --borde: #334155;
            --fuente: 'Inter', system-ui, -apple-system, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg-principal);
            color: var(--texto-principal);
            font-family: var(--fuente);
            line-height: 1.6;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Grid Principal */
        .grid-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 992px) {
            .grid-layout { grid-template-columns: 1fr; }
        }

        /* Tarjetas */
        .card {
            background-color: var(--bg-tarjeta);
            border: 1px solid var(--borde);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3);
        }

        /* Galería */
        .main-image-wrapper {
            position: relative;
            aspect-ratio: 16 / 9;
            background-color: var(--bg-oscuro);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .badge-destacado {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background-color: var(--color-primario);
            color: var(--bg-oscuro);
            font-weight: bold;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            text-transform: uppercase;
        }

        .thumbnails {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .thumb {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            border: 2px solid var(--color-primario);
            cursor: pointer;
            object-fit: cover;
            background-color: var(--bg-oscuro);
        }

        /* Detalles de Cabecera */
        .ad-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid var(--borde);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 576px) {
            .ad-header { flex-direction: column; gap: 1rem; }
        }

        .ad-title h1 { font-size: 1.8rem; font-weight: 800; color: #fff; }
        .ad-title p { color: var(--texto-secundario); font-size: 0.85rem; margin-top: 0.25rem; }
        
        .ad-price { text-align: right; }
        @media (max-width: 576px) { .ad-price { text-align: left; } }
        .price-tag { font-size: 2rem; font-weight: 900; color: var(--color-primario); display: block; }
        .badge-negotiable { display: inline-block; background: rgba(16, 185, 129, 0.1); color: var(--color-exito); font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px; margin-top: 0.25rem; }

        .description-text { color: #cbd5e1; white-space: pre-line; }

        /* Características */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .feature-item {
            background: rgba(2, 6, 23, 0.3);
            border: 1px solid rgba(51, 65, 85, 0.5);
            padding: 0.75rem;
            border-radius: 8px;
        }

        .feature-item span { display: block; }
        .feature-label { font-size: 0.7rem; color: var(--texto-secundario); text-transform: uppercase; font-weight: 600; }
        .feature-value { font-size: 0.9rem; font-weight: 500; color: #e2e8f0; margin-top: 0.25rem; }

        /* Sidebar Vendedor */
        .seller-info { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .seller-avatar { width: 60px; height: 60px; border-radius: 50%; border: 2px solid var(--color-primario); object-fit: cover; }
        .seller-name h4 { font-size: 1.1rem; color: #fff; }
        .seller-badge { display: inline-block; background: var(--bg-oscuro); font-size: 0.7rem; color: var(--color-primario); padding: 0.1rem 0.4rem; border-radius: 4px; border: 1px solid var(--borde); text-transform: capitalize; }

        .reputation-box {
            display: flex;
            justify-content: space-between;
            background: rgba(2, 6, 23, 0.4);
            border: 1px solid var(--borde);
            border-radius: 8px;
            padding: 0.75rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .rep-item span { display: block; }
        .rep-title { font-size: 0.7rem; color: var(--texto-secundario); }
        .rep-val { font-size: 0.9rem; font-weight: bold; color: #f1f5f9; }

        /* Botones de Acción */
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s;
            border: none;
            margin-bottom: 0.75rem;
        }

        .btn:hover { opacity: 0.9; }
        .btn-whatsapp { background-color: #16a34a; color: white; }
        .btn-message { background-color: #475569; color: white; border: 1px solid #64748b; }
        .btn-fav { background-color: var(--bg-oscuro); color: var(--texto-secundario); border: 1px solid var(--borde); }
        .btn-report { background: none; color: var(--texto-secundario); font-size: 0.75rem; border: none; margin: 1rem auto 0; display: block; }
        .btn-report:hover { color: var(--color-peligro); }

        /* Similares */
        .similar-title { font-size: 1.3rem; margin: 2rem 0 1rem; color: #fff; }
        .similar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .similar-card {
            background-color: var(--bg-tarjeta);
            border: 1px solid var(--borde);
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
        }

        .similar-img { width: 100%; aspect-ratio: 16/9; background: var(--bg-oscuro); object-fit: cover; }
        .similar-body { padding: 1rem; }
        .similar-body h5 { font-size: 0.9rem; color: #e2e8f0; margin-bottom: 0.25rem; }
        .similar-price { font-weight: 800; color: #fff; }

        /* Mapa placeholder */
        .map-box { width: 100%; height: 200px; background: var(--bg-oscuro); border: 1px solid var(--borde); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--texto-secundario); font-size: 0.8rem; margin-top: 0.5rem;}
    </style>
</head>
<body>

<div class="container">
    <div class="grid-layout">
        
        <main>
            <div class="card">
                <div class="main-image-wrapper">
                    <img id="main-gallery" src="<?= htmlspecialchars($ad['image_url']) ?>" alt="<?= htmlspecialchars($ad['title']) ?>">
                    <?php if ($ad['featured_until'] && strtotime($ad['featured_until']) > time()): ?>
                        <span class="badge-destacado">Destacado</span>
                    <?php endif; ?>
                </div>
                <div class="thumbnails">
                    <img src="<?= htmlspecialchars($ad['image_url']) ?>" class="thumb" onclick="document.getElementById('main-gallery').src = this.src">
                </div>
            </div>

            <div class="card">
                <div class="ad-header">
                    <div class="ad-title">
                        <h1><?= htmlspecialchars($ad['title']) ?></h1>
                        <p>Publicado el <?= date('d/m/Y', strtotime($ad['published_at'])) ?></p>
                    </div>
                    <div class="ad-price">
                        <span class="price-tag">$<?= number_format($ad['price'], 0, ',', '.') ?></span>
                        <?php if ($ad['negotiable']): ?>
                            <span class="badge-negotiable">Precio Conversable</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <h3 style="color:#fff; margin-bottom:0.5rem;">Descripción</h3>
                    <p class="description-text"><?= htmlspecialchars($ad['description']) ?></p>
                </div>
            </div>

            <div class="card">
                <h3 style="color:#fff; border-bottom: 1px solid var(--borde); padding-bottom: 0.5rem;">Características</h3>
                <div class="features-grid">
                    <div class="feature-item">
                        <span class="feature-label">Condición</span>
                        <span class="feature-value"><?= $ad['condition'] === 'new' ? 'Nuevo' : ($ad['condition'] === 'used' ? 'Usado' : 'Reacondicionado') ?></span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-label">Envío</span>
                        <span class="feature-value" style="color:<?= $ad['shipping'] ? 'var(--color-exito)' : 'var(--texto-secundario)' ?>"><?= $ad['shipping'] ? 'Disponible' : 'No' ?></span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-label">Retiro</span>
                        <span class="feature-value" style="color:<?= $ad['pickup'] ? 'var(--color-exito)' : 'var(--texto-secundario)' ?>"><?= $ad['pickup'] ? 'En domicilio' : 'No' ?></span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="color:#fff;">Ubicación</h3>
                <p style="font-size:0.95rem; margin-top:0.5rem; color: #cbd5e1;"><?= htmlspecialchars($ad['comuna_nombre']) ?>, Región de <?= htmlspecialchars($ad['region_nombre']) ?></p>
                <div class="map-box">
                    [Coordenadas: <?= $ad['latitude'] ?>, <?= $ad['longitude'] ?>]
                </div>
            </div>
        </main>

        <aside>
            <div class="card">
                <div class="seller-info">
                    <img src="<?= htmlspecialchars($vendedor['avatar']) ?>" class="seller-avatar">
                    <div class="seller-name">
                        <h4><?= htmlspecialchars($vendedor['nombre']) ?></h4>
                        <span class="seller-badge"><?= $vendedor['account_type'] ?></span>
                    </div>
                </div>

                <div class="reputation-box">
                    <div class="rep-item">
                        <span class="rep-title">Rating</span>
                        <span class="rep-val" style="color:var(--color-primario)">⭐ <?= var_export($vendedor['rating'], true) ?></span>
                    </div>
                    <div class="rep-item">
                        <span class="rep-title">Reviews</span>
                        <span class="rep-val"><?= $vendedor['reviews'] ?></span>
                    </div>
                    <div class="rep-item">
                        <span class="rep-title">Año</span>
                        <span class="rep-val"><?= date('Y', strtotime($vendedor['member_since'])) ?></span>
                    </div>
                </div>

                <div class="actions-wrapper">
                    <?php if ($ad['whatsapp'] && !empty($ad['contact_phone'])): ?>
                        <a href="https://wa.me/<?= preg_replace('/\D/', '', $ad['contact_phone']) ?>" target="_blank" class="btn btn-whatsapp">
                            Contactar por WhatsApp
                        </a>
                    <?php endif; ?>

                    <?php if ($ad['allow_messages']): ?>
                        <button class="btn btn-message">Enviar Mensaje</button>
                    <?php endif; ?>

                    <button class="btn btn-fav">❤ Guardar en Favoritos</button>
                </div>

                <button class="btn-report">⚠ Reportar comportamiento o anuncio falaz</button>
            </div>
        </aside>

    </div>

    <section>
        <h3 class="similar-title">Anuncios sugeridos</h3>
        <div class="similar-grid">
            <?php for($i=1; $i<=4; $i++): ?>
            <a href="#" class="similar-card">
                <div class="similar-img"></div>
                <div class="similar-body">
                    <h5>Anuncio relacionado de muestra <?= $i ?></h5>
                    <span class="similar-price">$120.000</span>
                </div>
            </a>
            <?php endfor; ?>
        </div>
    </section>
</div>

</body>
</html>