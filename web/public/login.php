<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecomercio - Iniciar Sesión</title>
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/forms.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h2 id="form-title" class="site-title">Ecomercio</h2>
            <p id="form-subtitle" class="site-subtitle">Ingresa a tu panel de administración</p>
        </div>

        <div id="alert-banner" class="alert-box hidden">
            <span id="alert-message"></span>
        </div>

        <form id="loginForm" method="POST" action="">
            <div class="form-group-container">
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input id="email" name="email" type="email" required class="form-input">
                </div>

                <div id="password-container" class="form-group">
                    <div class="flex-between">
                        <label for="password" class="form-label">Contraseña</label>
                        <a href="/forgot_password.php" id="forgot-password-link" class="theme-link">¿Olvidaste tu contraseña?</a>
                    </div>
                    <input id="password" name="password" type="password" required class="form-input">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="submit-btn" class="btn btn-primary btn-full">
                    Iniciar Sesión
                </button>
            </div>

            <div id="back-to-login-container" class="form-footer hidden">
                <a href="#" id="back-to-login-link" class="theme-link-secondary">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('loginForm');
        const alertBanner = document.getElementById('alert-banner');
        const alertMessage = document.getElementById('alert-message');
        const submitBtn = document.getElementById('submit-btn');

        const passwordContainer = document.getElementById('password-container');
        const passwordInput = document.getElementById('password');
        const formTitle = document.getElementById('form-title');
        const formSubtitle = document.getElementById('form-subtitle');
        const forgotPasswordLink = document.getElementById('forgot-password-link');
        const backToLoginContainer = document.getElementById('back-to-login-container');
        const backToLoginLink = document.getElementById('back-to-login-link');

        let isRecoveryMode = false;

        const setAlertStyle = (type) => {
            alertBanner.className = 'alert-box';

            if (type === 'error') {
                alertBanner.classList.add('alert-danger');
            } else {
                alertBanner.classList.add('alert-success');
            }
        };

        forgotPasswordLink?.addEventListener('click', (e) => {
            e.preventDefault();
            isRecoveryMode = true;
            alertBanner.classList.add('hidden');
            alertMessage.textContent = '';

            formTitle.textContent = 'Recuperar Contraseña';
            formSubtitle.textContent = 'Introduce tu correo para enviarte un enlace de restauración';

            passwordContainer.classList.add('hidden');
            passwordInput.removeAttribute('required');
            passwordInput.value = '';

            submitBtn.textContent = 'Enviar enlace de recuperación';
            backToLoginContainer.classList.remove('hidden');
        });

        backToLoginLink?.addEventListener('click', (e) => {
            e.preventDefault();
            isRecoveryMode = false;
            alertBanner.classList.add('hidden');
            alertMessage.textContent = '';

            formTitle.textContent = 'Ecomercio';
            formSubtitle.textContent = 'Ingresa a tu panel de administración';

            passwordContainer.classList.remove('hidden');
            passwordInput.setAttribute('required', 'required');

            submitBtn.textContent = 'Iniciar Sesión';
            backToLoginContainer.classList.add('hidden');
        });

        form?.addEventListener('submit', async (e) => {
            e.preventDefault();

            alertBanner.classList.add('hidden');
            alertMessage.textContent = '';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = isRecoveryMode ? 'Procesando...' : 'Verificando...';
            }

            const endpoint = isRecoveryMode
                ? '/api/auth/reset_request.php'
                : '/api/auth/login_process.php';

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    credentials: 'include',
                    body: new FormData(form)
                });

                let result = null;
                const contentType = response.headers.get('content-type') || '';

                if (contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    const rawText = await response.text();

                    if (rawText.includes('<!DOCTYPE html>') || rawText.includes('Fatal error')) {
                        console.error('Respuesta inesperada del servidor:', rawText);
                        throw new Error('El servidor devolvió un error interno. Revisa los logs.');
                    }

                    throw new Error(rawText || `Error en el servidor (Status: ${response.status})`);
                }

                if (!response.ok) {
                    throw new Error(result.message || `Error en el servidor (Status: ${response.status})`);
                }

                if (result.success) {
                    if (isRecoveryMode) {
                        setAlertStyle('success');
                        alertMessage.textContent = result.message || 'Se ha enviado el correo de recuperación.';
                        alertBanner.classList.remove('hidden');
                        form.reset();
                    } else {
                        window.location.href = result.redirect || '/dashboard.php';
                    }
                } else {
                    setAlertStyle('error');
                    alertMessage.textContent = result.message || 'Ocurrió un error en la solicitud.';
                    alertBanner.classList.remove('hidden');
                }
            } catch (err) {
                console.error('Error atrapado en el submit:', err);
                setAlertStyle('error');
                alertMessage.textContent = err.message || 'Ocurrió un error inesperado.';
                alertBanner.classList.remove('hidden');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = isRecoveryMode
                        ? 'Enviar enlace de recuperación'
                        : 'Iniciar Sesión';
                }
            }
        });
    });
    </script>
</body>
</html>
