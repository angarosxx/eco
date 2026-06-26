<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Eco\Controllers\DashboardController;

$controller = new DashboardController();
$userAds = $controller->getUserListings((int) $_SESSION['user_id']);

function statusBadgeClasses(string $status): string
{
    return match ($status) {
        'active' => 'bg-green-100 text-green-800 border-green-200',
        'paused' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'expired' => 'bg-gray-100 text-gray-700 border-gray-200',
        'deleted' => 'bg-red-100 text-red-700 border-red-200',
        default => 'bg-blue-100 text-blue-700 border-blue-200',
    };
}

function statusLabel(string $status): string
{
    return match ($status) {
        'active' => 'Activo',
        'paused' => 'Pausado',
        'expired' => 'Expirado',
        'deleted' => 'Eliminado',
        default => ucfirst($status),
    };
}

function formatPrice(float $price): string
{
    if ($price <= 0) {
        return 'A convenir';
    }

    return '$' . number_format($price, 0, ',', '.') . ' CLP';
}
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Ecomercio</title>
    <link rel="stylesheet" href="[rsms.me](https://rsms.me/inter/inter.css)">
    <script src="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4)"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen text-gray-900">

    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/index.php" class="text-xl font-bold tracking-tight text-gray-900">Ecomercio</a>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                    Mi Panel
                </span>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    Hola, <strong class="text-gray-900"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></strong>
                </span>
                <a href="/api/auth/logout.php" class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <section class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Mis Anuncios</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Administra tus publicaciones, revisa su estado y publica nuevos anuncios.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="/publish_ad.php"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors"
                >
                    Publicar Nuevo Anuncio
                </a>
            </div>
        </section>

        <?php if (isset($_GET['published']) && $_GET['published'] === 'success'): ?>
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                ¡Tu anuncio fue publicado correctamente!
            </div>
        <?php endif; ?>

        <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Total anuncios</div>
                <div class="mt-2 text-3xl font-bold text-gray-900"><?= count($userAds) ?></div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Activos</div>
                <div class="mt-2 text-3xl font-bold text-green-600">
                    <?= count(array_filter($userAds, fn($ad) => ($ad['status'] ?? '') === 'active')) ?>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-sm text-gray-500">Pausados / expirados</div>
                <div class="mt-2 text-3xl font-bold text-yellow-600">
                    <?= count(array_filter($userAds, fn($ad) => in_array(($ad['status'] ?? ''), ['paused', 'expired'], true))) ?>
                </div>
            </div>
        </section>

        <?php if (empty($userAds)): ?>
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-6 4h.01M9 16h.01"></path>
                    </svg>
                </div>

                <h2 class="text-lg font-semibold text-gray-900">Aún no tienes anuncios publicados</h2>
                <p class="mt-2 text-sm text-gray-500">
                    Publica tu primer producto o servicio para empezar a aparecer en Ecomercio.
                </p>

                <div class="mt-6">
                    <a
                        href="/publish_ad.php"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                    >
                        Crear mi primer anuncio
                    </a>
                </div>
            </section>
        <?php else: ?>
            <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($userAds as $ad): ?>
                    <?php
                    $image = !empty($ad['primary_image'])
                        ? $ad['primary_image']
                        : '[images.unsplash.com](https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=900&q=80)';

                    $status = $ad['status'] ?? 'active';
                    ?>
                    <article class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                            <img
                                src="<?= htmlspecialchars($image) ?>"
                                alt="<?= htmlspecialchars($ad['title']) ?>"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 leading-snug line-clamp-2">
                                    <?= htmlspecialchars($ad['title']) ?>
                                </h3>

                                <span class="shrink-0 inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold <?= statusBadgeClasses($status) ?>">
                                    <?= htmlspecialchars(statusLabel($status)) ?>
                                </span>
                            </div>

                            <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                                <?= htmlspecialchars($ad['description']) ?>
                            </p>

                            <div class="mt-4 space-y-2 text-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-gray-500">Ubicación</span>
                                    <span class="text-right text-gray-900 font-medium">
                                        <?= htmlspecialchars(trim(($ad['comuna_name'] ?? '') . ' - ' . ($ad['region_name'] ?? ''), ' -')) ?>
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-gray-500">Precio</span>
                                    <span class="text-right text-gray-900 font-bold">
                                        <?= htmlspecialchars(formatPrice((float) ($ad['price'] ?? 0))) ?>
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-gray-500">Visitas</span>
                                    <span class="text-right text-gray-900 font-medium">
                                        <?= (int) ($ad['views_count'] ?? 0) ?>
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-gray-500">Publicado</span>
                                    <span class="text-right text-gray-900 font-medium">
                                        <?= !empty($ad['created_at']) ? date('d/m/Y', strtotime($ad['created_at'])) : '-' ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-5 flex items-center gap-3">
                                <a
                                    href="/edit_ad.php?id=<?= (int) $ad['id'] ?>"
                                    class="inline-flex flex-1 items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                                >
                                    Editar
                                </a>

                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition-colors"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
