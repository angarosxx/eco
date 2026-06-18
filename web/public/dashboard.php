<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🛡️ Secure Session Guard Layer
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Eco\Models\Listing;

$listingModel = new Listing();
$userAds = $listingModel->getByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Ecomercio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">

    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold text-gray-900 tracking-tight">Ecomercio</span>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-medium">Workspace</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Hola, <strong class="text-gray-900"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></strong></span>
                    <a href="/api/auth/logout.php" class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Mis Anuncios Clasificados</h2>
                <p class="mt-1 text-sm text-gray-500">Administra tus publicaciones activas, revisa métricas y sube nuevos anuncios.</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/publish_ad.php" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    ➕ Publicar Nuevo Anuncio
                </a>
            </div>
        </div>

        <?php if (isset($_GET['published']) && $_GET['published'] === 'success'): ?>
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700 flex items-center gap-2 shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>¡Anuncio publicado e indexado exitosamente en el clúster de producción!</span>
            </div>
        <?php endif; ?>

        <?php if (empty($userAds)): ?>
            <div class="text-center bg-white rounded-xl border border-gray-200 p-12 shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes anuncios publicados</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza a publicar tus productos o servicios para que aparezcan en la región.</p>
                <div class="mt-6">
                    <a href="/publish_ad.php" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow-sm">
                        Crear mi primer anuncio
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen / Detalles</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($userAds as $ad): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                                <img src="<?= $ad['image_url'] ? htmlspecialchars($ad['image_url']) : 'https://images.unsplash.com/photo-1594322436404-5a0526db4d13?auto=format&fit=crop&w=100&h=100&q=80' ?>" class="h-full w-full object-cover" alt="Ad image">
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($ad['title']) ?></div>
                                                <div class="text-xs text-gray-400 max-w-xs truncate"><?= htmlspecialchars($ad['description']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= htmlspecialchars($ad['comuna_name']) ?></div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($ad['region_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">$<?= number_format($ad['price'], 0, ',', '.') ?> CLP</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Activo</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="/edit_ad.php?id=<?= $ad['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-4 font-semibold">Editar</a>
                                        <button class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>