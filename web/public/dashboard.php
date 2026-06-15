<?php
// web/public/dashboard.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect back to login if session doesn't exist
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$userName = htmlspecialchars($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Ecomercio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
    <div class="min-full flex">
        
        <div class="w-64 bg-slate-900 min-h-screen p-4 text-white flex flex-col justify-between">
            <div>
                <div class="text-2xl font-bold tracking-wider mb-8 text-indigo-400">ecomercio<span class="text-white text-xs">.cl</span></div>
                <nav class="space-y-2">
                    <a href="/dashboard.php" class="block py-2.5 px-4 rounded bg-slate-800 text-white font-medium transition duration-200">
                        📦 Mis Anuncios
                    </a>
                    <a href="/publish.php" class="block py-2.5 px-4 rounded text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200">
                        ➕ Publicar Anuncio
                    </a>
                    <a href="/profile.php" class="block py-2.5 px-4 rounded text-slate-400 hover:bg-slate-800 hover:text-white transition duration-200">
                        ⚙️ Mi Perfil
                    </a>
                </nav>
            </div>
            
            <div class="border-t border-slate-800 pt-4">
                <div class="text-sm text-slate-400 mb-2">Conectado como:</div>
                <div class="font-semibold text-indigo-300 mb-4"><?php echo $userName; ?></div>
                <a href="/api/auth/logout.php" class="block text-center bg-rose-600 hover:bg-rose-700 py-2 rounded text-sm font-semibold transition duration-200">
                    Cerrar Sesión
                </a>
            </div>
        </div>

        <div class="flex-1 p-10">
            <header class="flex justify-between items-center mb-8 border-b pb-5">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Bienvenido de vuelta, <?php echo $userName; ?></h1>
                    <p class="text-sm text-gray-500 mt-1">Administra tus anuncios activos en la región de Antofagasta y el resto de Chile.</p>
                </div>
                <a href="/publish.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center space-x-2">
                    <span>+ Crear Nuevo Anuncio</span>
                </a>
            </header>

            <main>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Tus Publicaciones Activas</h2>
                
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No tienes ningún anuncio publicado</h3>
                    <p class="mt-1 text-sm text-gray-500">Comienza a publicar tus servicios de hospedaje, transporte o productos mineros hoy mismo.</p>
                    <div class="mt-6">
                        <a href="/publish.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                            Publicar mi primer anuncio
                        </a>
                    </div>
                </div>
            </main>
        </div>

    </div>
</body>
</html>