<?php
// Mock instance injection for structural preview
$controller = new \Eco\Controllers\HomeController();
$listings = $controller->getHomepageFeed(12);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Marketplace — B2C2B Chile</title>
    <link href="../css/app.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">eco<span class="text-gray-800">.cl</span></span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="/login" class="text-sm font-medium text-gray-600 hover:text-gray-900">Iniciar Sesión</a>
                <a href="/publish" class="inline-flex items-center justify-center px-4 h-9 font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 transition rounded-lg shadow-sm">Publicar Gratis</a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-12">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Descubre avisos destacados</h1>
            <p class="mt-2 text-sm text-gray-500 max-w-xl">Plataforma unificada para particulares y empresas a lo largo de todo Chile.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (empty($listings)): ?>
                <div class="col-span-full py-12 text-center text-gray-500">No hay avisos activos en este momento.</div>
            <?php else: ?>
                <?php foreach ($listings as $ad): ?>
                    <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                        
                        <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                            <img src="<?= $ad['image_path'] ?? 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?w=500' ?>" 
                                 alt="<?= htmlspecialchars($ad['title'] ?? 'Aviso sin título') ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            
                            <div class="absolute top-3 left-3">
                                <?php if ($ad['ad_type_origin'] === 'company'): ?>
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-blue-600 text-white rounded-md shadow-sm">
                                        🏢 Empresa
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold bg-gray-800 text-white rounded-md shadow-sm">
                                        👤 Particular
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center text-xs text-gray-400 mb-1 space-x-1">
                                    <span>📍 <?= htmlspecialchars($ad['comuna_name'] ?? 'Chile') ?></span>
                                </div>
                                <h3 class="font-semibold text-gray-800 text-base line-clamp-2 group-hover:text-emerald-600 transition">
                                    <a href="/ad/<?= $ad['slug'] ?? $ad['id'] ?>">
                                        <?= htmlspecialchars($ad['title'] ?? 'Aviso sin título') ?>
                                    </a>
                                </h3>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-baseline justify-between">
                                <span class="text-xl font-bold text-gray-900">
                                    <?= $ad['currency'] === 'CLP' ? '$' . number_format($ad['price'], 0, ',', '.') : '$' . number_format($ad['price'], 2) ?>
                                </span>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 mt-20 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-400">
            &copy; 2026 Eco Classifieds Marketplace. Todos los derechos reservados.
        </div>
    </footer>

</body>
</html>