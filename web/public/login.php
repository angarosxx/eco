<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecomercio - Iniciar Sesión</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">Ecomercio</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Ingresa a tu panel de administración</p>
        </div>

        <div id="alert-banner" class="hidden p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600 flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span id="alert-message"></span>
        </div>

        <form id="loginForm" method="POST" action="" class="mt-4 space-y-6"> 
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input id="email" name="email" type="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input id="password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        //const loginForm = document.getElementById('login-form'); // Asegúrate de que tu formulario tenga id="login-form"
        const form = document.getElementById('loginForm') || document.querySelector('form');
        const alertBanner = document.getElementById('alert-banner');
        const alertMessage = document.getElementById('alert-message');

        const showAlert = (msg) => {
            if (alertMessage && alertBanner) {
                alertMessage.textContent = msg;
                alertBanner.classList.remove('hidden');
            } else {
                alert(msg);
            }
        };

        form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    alertBanner.classList.add('hidden');

    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerText = 'Verificando...';
    }

    try {
        const response = await fetch('/api/auth/login_process.php', {
            method: 'POST',
            body: new FormData(form)
        });

        // 🎯 CAPTURADOR AVANZADO: Si el backend falla (Error 500/404/etc), leemos el error real
        if (!response.ok) {
            const errText = await response.text();
            throw new Error(errText || 'Error interno en el servidor de autenticación.');
        }

        const result = await response.json();
        if (result.success) {
            window.location.href = result.redirect || '/dashboard.php';
        } else {
            alertMessage.textContent = result.message || 'Credenciales incorrectas.';
            alertBanner.classList.remove('hidden');
        }
    } catch (err) {
        console.error(err);
        // Pinta el error formateado directamente en tu banner
        alertMessage.innerHTML = `<strong>Error en el inicio de sesión:</strong><br><pre class="text-xs mt-1 overflow-x-auto">${err.message}</pre>`;
        alertBanner.classList.remove('hidden');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Iniciar Sesión';
        }
    }
});
</script>
</body>
</html>