<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Ecomercio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex items-center justify-center px-4">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <div>
            <h2 class="text-center text-2xl font-bold text-gray-900">¿Olvidaste tu contraseña?</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Ingresa tu correo y te enviaremos las instrucciones de recuperación.</p>
        </div>

        <div id="alert" class="hidden p-4 rounded-lg text-sm border flex items-center gap-2">
            <span id="alert-text"></span>
        </div>

        <form id="forgot-form" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input id="email" name="email" type="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit" class="w-full py-2 px-4 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                Enviar enlace de recuperación
            </button>
            <div class="text-center text-sm">
                <a href="/login.php" class="text-blue-600 hover:underline">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('forgot-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('alert');
            const alertText = document.getElementById('alert-text');
            
            try {
                const res = await fetch('/api/auth/reset_request.php', {
                    method: 'POST',
                    body: new FormData(e.target)
                });
                const data = await res.json();
                
                alertBox.className = `p-4 rounded-lg text-sm border flex items-center gap-2 ${data.success ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'}`;
                alertText.textContent = data.message;
                alertBox.classList.remove('hidden');
            } catch {
                alertBox.className = 'p-4 rounded-lg text-sm border bg-red-50 border-red-200 text-red-700 hidden';
                alertText.textContent = 'Error al procesar la solicitud.';
            }
        });
    </script>
</body>
</html>