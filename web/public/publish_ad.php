<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecomercio - Publicar Anuncio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-6">Publicar Nuevo Anuncio</h2>

        <div id="alert-banner" class="hidden p-4 mb-6 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600 flex items-center gap-2">
            <span id="alert-message"></span>
        </div>

        <form id="ad-form" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Título del Anuncio</label>
                    <input type="text" name="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de Anuncio</label>
                    <select name="ad_type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        <option value="">Seleccione una opción</option>
                        <option value="vendo">Vendo</option>
                        <option value="compro">Compro</option>
                        <option value="arriendo">Arriendo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Precio (CLP)</label>
                    <input type="number" name="price" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" rows="4" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                </div>

                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Imágenes del Anuncio (Mínimo 1, Máximo 5)</label>
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="images[]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full sm:w-auto py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Publicar Anuncio
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('ad-form');
            const alertBanner = document.getElementById('alert-banner');
            const alertMessage = document.getElementById('alert-message');

            form?.addEventListener('submit', async (e) => {
                e.preventDefault();
                alertBanner.classList.add('hidden');

                const formData = new FormData(form);

                try {
                    const response = await fetch('/api/ads/create.php', {
                        method: 'POST',
                        body: formData // Automatically encodes multipart boundaries for file array matrices
                    });

                    // If backend crashes with PHP error, grab the text directly
                    if (!response.ok) {
                        const errText = await response.text();
                        throw new Error(errText || 'Error en el servidor backend.');
                    }

                    const result = await response.json();
                    if (result.success) {
                        window.location.href = '/dashboard.php';
                    } else {
                        alertMessage.textContent = result.message || 'Error al guardar.';
                        alertBanner.classList.remove('hidden');
                    }
                } catch (err) {
                    alertMessage.innerHTML = `<strong>Error de procesamiento:</strong><br><pre class="text-xs mt-1 overflow-x-auto">${err.message}</pre>`;
                    alertBanner.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>