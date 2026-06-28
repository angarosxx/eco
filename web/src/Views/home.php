<?php
// 1. Configuración del título de la página principal
$page_title = "Ecomercio Chile — El Marketplace de la Región";

// 2. Inyectamos las cabeceras globales del Tema
require __DIR__ . '/layout/header.php';
require __DIR__ . '/layout/navbar.php';

// MOCK DATA: Simulación de listados para poblar tu grilla al cargar el home
$featured_ads = [
    [
        'title' => 'Toyota RAV4 2.5 XLE AWD Automática',
        'price' => 18490000,
        'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=500',
        'tags' => ['2022', '45.000 km', 'Bencina'],
        'comuna' => 'Calama',
        'tiempo' => 'Hace 2 días'
    ],
    [
        'title' => 'Gymfit Core Pulse Trainers Pro Edition',
        'price' => 129000,
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500',
        'tags' => ['Nuevo', 'Calzado', 'Envío Gratis'],
        'comuna' => 'San Pedro',
        'tiempo' => 'Hoy'
    ],
    [
        'title' => 'Extractor de Aire Industrial para Minería',
        'price' => 450000,
        'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=500',
        'tags' => ['Usado', 'Equipamiento', 'Retiro'],
        'comuna' => 'Antofagasta',
        'tiempo' => 'Ayer'
    ],
    [
        'title' => 'Notebook Corporativo ThinkPad 16GB RAM',
        'price' => 680000,
        'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?q=80&w=500',
        'tags' => ['Reacondicionado', 'Intel i7', 'Garantía'],
        'comuna' => 'Calama',
        'tiempo' => 'Hace 3 horas'
    ]
];
?>

<section class="hero-section" style="text-align: center; padding: 3rem 1rem 2rem 1rem;">
    <span style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary); padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.05em; display: inline-block; margin-bottom: 1rem;">
        📍 PLATAFORMA COMERCIAL ANTOFAGASTA
    </span>
    <h1 style="font-size: 2.5rem; font-weight: 900; color: var(--text-main); letter-spacing: -0.03em; margin-bottom: 0.5rem;">
        Encuentra lo que necesitas en la región
    </h1>
    <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2.5rem auto;">
        Conectando el comercio local, servicios e infraestructura desde los hubs de tránsito hasta el corazón de la minería.
    </p>
</section>

<div class="search-container" style="max-width: 1000px; margin: 0 auto;">
    <?php require __DIR__ . '/layout/searchbar.php'; ?>
</div>

<section class="listings-section" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-main);">
            Anuncios destacados en la zona
        </h2>
        <a href="/advanced_search.php" style="color: var(--primary); font-size: 0.9rem; font-weight: 600; text-decoration: none;">Ver todos →</a>
    </div>

    <div class="ads-grid">
        <?php foreach ($featured_ads as $item): ?>
            <a href="/ad_details" class="card-ad">
                <div class="card-ad-media">
                    <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                </div>
                <div class="card-ad-body">
                    <h3 class="card-ad-title"><?= htmlspecialchars($item['title']) ?></h3>
                    <div class="card-ad-price">$<?= number_format($item['price'], 0, ',', '.') ?></div>
                    
                    <div class="card-ad-specs">
                        <?php foreach ($item['tags'] as $tag): ?>
                            <span class="spec-tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="card-ad-footer">
                        <span class="card-ad-location">📍 <?= htmlspecialchars($item['comuna']) ?></span>
                        <span><?= $item['tiempo'] ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php
// 6. Inyectamos el pie de página del Tema
require __DIR__ . '/layout/footer.php';
?>