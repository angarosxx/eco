<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Anuncio - Ecomercio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-sm border-b border-gray-200 p-4 mb-8">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900">Ecomercio Workspace</h1>
            <a href="/dashboard.php" class="text-sm text-blue-600 hover:underline">Volver al Panel</a>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 pb-12">
        <div class="bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Crear Nuevo Anuncio</h2>

            <div id="publish-alert" class="hidden mb-6 p-4 rounded-lg text-sm border flex items-center gap-2 bg-red-50 border-red-200 text-red-600">
                <span id="publish-alert-msg"></span>
            </div>

            <form id="publish-form" class="space-y-6" enctype="multipart/form-data">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Título del Anuncio</label>
                    <input type="text" name="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio (CLP)</label>
                        <input type="number" name="price" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Imagen Destacada</label>
                        <input type="file" name="ad_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Región</label>
                        <select id="region-selector" name="region_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Cargando regiones...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Comuna</label>
                        <select id="comuna-selector" name="comuna_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Selecciona una región primero</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" rows="4" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                </div>

                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    Publicar Anuncio Coherente
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const regSel = document.getElementById('region-selector');
            const comSel = document.getElementById('comuna-selector');
            const form = document.getElementById('publish-form');
            const alertBox = document.getElementById('publish-alert');
            const alertMsg = document.getElementById('publish-alert-msg');

            // 1. Fetch Location Data Arrays 
            try {
                const res = await fetch('/api/locations.php?action=regions');
                const regions = await res.json();
                regSel.innerHTML = '<option value="">Selecciona Región</option>';
                regions.forEach(r => {
                    regSel.innerHTML += `<option value="${r.id}">${r.name}</option>`;
                });
            } catch { regSel.innerHTML = '<option value="">Error al cargar regiones</option>'; }

            regSel.addEventListener('change', async () => {
                if(!regSel.value) return;
                comSel.innerHTML = '<option value="">Cargando comunas...</option>';
                const res = await fetch(`/api/locations.php?action=comunas&region_id=${regSel.value}`);
                const comunas = await res.json();
                comSel.innerHTML = '<option value="">Selecciona Comuna</option>';
                comunas.forEach(c => {
                    comSel.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                });
            });

            // 2. Intercept Submission
            form?.addEventListener('submit', async (e) => {
                e.preventDefault();
                alertBox.classList.add('hidden');

                try {
                    const res = await fetch('/api/ads/create.php', {
                        method: 'POST',
                        body: new FormData(form)
                    });
                    const result = await res.json();

                    if(result.success) {
                        window.location.href = '/dashboard.php?published=success';
                    } else {
                        alertMsg.textContent = result.message || 'Error al guardar el anuncio.';
                        alertBox.classList.remove('hidden');
                    }
                } catch {
                    alertMsg.textContent = 'Fallo de red al intentar comunicarse con el microservicio.';
                    alertBox.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>