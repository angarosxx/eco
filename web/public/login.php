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
            alertBanner.className = 'alert-box'; // Limpiamos clases extras
            if (type === 'error') {
                alertBanner.classList.add('alert-danger');
            } else {
                alertBanner.classList.add('alert-success');
            }
        };

        // 🔄 Activar modo recuperación
        forgotPasswordLink?.addEventListener('click', (e) => {
            e.preventDefault();
            isRecoveryMode = true;
            alertBanner.classList.add('hidden');
            
            formTitle.textContent = "Recuperar Contraseña";
            formSubtitle.textContent = "Introduce tu correo para enviarte un enlace de restauración";
            
            passwordContainer.classList.add('hidden');
            passwordInput.removeAttribute('required');
            
            submitBtn.textContent = "Enviar enlace de recuperación";
            backToLoginContainer.classList.remove('hidden');
        });

        // 🔄 Regresar al Login
        backToLoginLink?.addEventListener('click', (e) => {
            e.preventDefault();
            isRecoveryMode = false;
            alertBanner.classList.add('hidden');
            
            formTitle.textContent = "Ecomercio";
            formSubtitle.textContent = "Ingresa a tu panel de administración";
            
            passwordContainer.classList.remove('hidden');
            passwordInput.setAttribute('required', 'required');
            
            submitBtn.textContent = "Iniciar Sesión";
            backToLoginContainer.classList.add('hidden');
        });

        // 🚀 Envío asíncrono
        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            alertBanner.classList.add('hidden');

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = isRecoveryMode ? 'Procesando...' : 'Verificando...';
            }

            const endpoint = isRecoveryMode ? '/api/auth/reset_request.php' : '/api/auth/login_process.php';

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: new FormData(form)
                });

                if (!response.ok) {
                    const errText = await response.text();
                    throw new Error(errText || 'Error interno en el servidor.');
                }

                const result = await response.json();
                
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
                console.error(err);
                setAlertStyle('error');
                alertMessage.innerHTML = `<strong>Error:</strong><br><small>${err.message}</small>`;
                alertBanner.classList.remove('hidden');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = isRecoveryMode ? 'Enviar enlace de recuperación' : 'Iniciar Sesión';
                }
            }
        });
    });
</script>
</body>
</html>