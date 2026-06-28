<?php
// 1. Configuración de la página antes de cargar el layout
$page_title = "Gymfit Core Pulse Trainers - Ecomercio";

// 2. Carga de los componentes estructurales superiores
require "/../src/Views/layout/header.php";
require "/../src/Views/layout/navbar.php";

// 3. MOCK DATA: Tus datos simulados (Esto luego vendrá de tu Base de Datos)
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

<!-- 4. CONTENIDO PURO DE LA VISTA (Estilo Chileautos/Shopify Claro) -->
<div class="product-grid">
    
    <!-- COLUMNA IZQUIERDA: Multimedia y Mapa -->
    <div class="media-stack">
        <div class="main-image-container" style="background-color: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);">
            <img src="<?= htmlspecialchars($ad['image_url']) ?>" alt="<?= htmlspecialchars($ad['title']) ?>">
            <?php if ($ad['condition'] === 'new'): ?>
                <span class="badge-new" style="background: var(--primary); color: white; border-radius: 4px;">Nuevo</span>
            <?php endif; ?>
        </div>
        
        <div class="info-block" style="background: var(--surface); border: 1px solid var(--border); padding: 1.5rem; border-radius: var(--radius);">
            <h4 style="color: var(--text-main);">Ubicación de disponibilidad</h4>
            <p style="font-size:0.9rem; color: var(--text-muted); margin-top: 0.5rem;">
                📍 <?= htmlspecialchars($ad['comuna_nombre']) ?>, Región de <?= htmlspecialchars($ad['region_nombre']) ?>
            </p>
            <div style="width:100%; height:160px; background: var(--background); margin-top:1rem; border:1px solid var(--border); border-radius: var(--radius); display:flex; align-items:center; justify-content:center; font-size:0.8rem; color:var(--text-muted); font-weight: 500;">
                [ Mapa Interactivo Activo ]
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: Panel de Compra/Contacto Sticky -->
    <div class="purchase-panel" style="background: var(--surface); border: 1px solid var(--border); padding: 2rem; border-radius: var(--radius);">
        
        <div class="meta-header" style="border-bottom: 1px solid var(--border); padding-bottom: 1.5rem;">
            <span class="vendor-tag" style="color: var(--primary); font-weight: 600; font-size: 0.85rem;"><?= htmlspecialchars($vendedor['nombre']) ?></span>
            <h1 class="product-title" style="color: var(--text-main); font-size: 1.8rem; margin-top: 0.5rem;"><?= htmlspecialchars($ad['title']) ?></h1>
            <div class="price-container" style="color: var(--text-main); font-size: 2rem; font-weight: 900; margin-top: 1rem;">
                $<?= number_format($ad['price'], 0, ',', '.') ?> <span style="font-size: 1rem; color: var(--text-muted); font-weight: 400;">CLP</span>
            </div>
        </div>

        <div class="info-block" style="border-bottom: 1px solid var(--border); padding-bottom: 1.5rem;">
            <h4 style="color: var(--text-main); font-size: 0.9rem; margin-bottom: 0.5rem;">Descripción</h4>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;"><?= nl2br(htmlspecialchars($ad['description'])) ?></p>
        </div>

        <!-- Botones de Acción usando variables del tema claro -->
        <div class="action-buttons">
            <?php if ($ad['whatsapp'] && !empty($ad['contact_phone'])): ?>
                <a href="https://wa.me/<?= preg_replace('/\D/', '', $ad['contact_phone']) ?>" target="_blank" class="btn-gymfit" style="background-color: var(--success); color: white; border-radius: var(--radius); font-weight: 700; text-decoration: none; display: block; padding: 1rem; text-align: center;">
                    Contactar vía WhatsApp
                </a>
            <?php endif; ?>

            <?php if ($ad['allow_messages']): ?>
                <button class="btn-gymfit" style="background-color: var(--primary); color: white; border-radius: var(--radius); font-weight: 700; border: none; padding: 1rem; cursor: pointer; margin-top: 0.5rem; width: 100%;">
                    Enviar Mensaje Interno
                </button>
            <?php endif; ?>
        </div>

        <!-- Mini Tarjeta del Vendedor -->
        <div class="seller-mini-card" style="border: 1px solid var(--border); background: var(--background); border-radius: var(--radius); padding: 1rem; display: flex; align-items: center; gap: 1rem;">
            <img src="<?= htmlspecialchars($vendedor['avatar']) ?>" class="seller-mini-avatar" style="border-radius: 50%; width: 48px; height: 48px;">
            <div>
                <span style="font-size:0.9rem; font-weight:700; display:block; color: var(--text-main);"><?= htmlspecialchars($vendedor['nombre']) ?></span>
                <span style="font-size:0.8rem; color:var(--text-muted);">⭐ <?= number_format($vendedor['rating'], 1) ?> (<?= $vendedor['reviews'] ?> opiniones)</span>
            </div>
        </div>

        <!-- Especificaciones Técnicas como Metadatos -->
        <div class="spec-list" style="border-top: 1px solid var(--border); padding-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="spec-item">
                <span class="spec-label" style="color: var(--text-muted); font-size: 0.8rem;">Condición</span>
                <span class="spec-value" style="text-transform: capitalize; color: var(--text-main); font-weight: 600;"><?= $ad['condition'] ?></span>
            </div>
            <div class="spec-item">
                <span class="spec-label" style="color: var(--text-muted); font-size: 0.8rem;">Envío</span>
                <span class="spec-value" style="color: var(--text-main); font-weight: 600;"><?= $ad['shipping'] ? 'Disponible' : 'Solo Retiro' ?></span>
            </div>
        </div>

    </div>
</div>

<!-- SECCIÓN: Productos Similares usando el nuevo CSS de cards.css -->
<section class="related-section" style="margin-top: 4rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
    <h3 class="related-title" style="color: var(--text-main); font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Anuncios Recomendados</h3>
    
    <div class="ads-grid">
        <?php for($i=1; $i<=4; $i++): ?>
        <a href="#" class="card-ad">
            <div class="card-ad-media">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400" alt="Producto similar">
            </div>
            <div class="card-ad-body">
                <h2 class="card-ad-title">Core Pulse Pro Variant v<?= $i ?></h2>
                <div class="card-ad-price">$115.000</div>
                <div class="card-ad-footer">
                    <span class="card-ad-location">📍 Antofagasta</span>
                    <span>Hoy</span>
                </div>
            </div>
        </a>
        <?php endfor; ?>
    </div>
</section>

<?php
// 5. Carga de los componentes estructurales inferiores
require "/../src/Views/layout/footer.php";
?>