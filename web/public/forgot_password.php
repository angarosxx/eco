<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Ecomercio</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="site-title">¿Olvidaste tu contraseña?</h2>
            <p class="site-subtitle">Ingresa tu correo y te enviaremos las instrucciones de recuperación.</p>
        </div>

        <div id="alert" class="alert-box hidden">
            <span id="alert-text"></span>
        </div>

        <form id="forgot-form" class="form-group-container">
            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" name="email" type="email" required class="form-input">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-full">
                    Enviar enlace de recuperación
                </button>
            </div>
            
            <div class="form-footer">
                <a href="/login.php" class="theme-link-secondary">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('forgot-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('alert');
            const alertText = document.getElementById('alert-text');
            
            try {
                const res = await fetch('/public/api/auth/reset_request.php', {
                    method: 'POST',
                    body: new FormData(e.target)
                });
                const data = await res.json();
                
                // Limpiamos estilos previos del alert-box nativo
                alertBox.className = 'alert-box';
                if (data.success) {
                    alertBox.classList.add('alert-success');
                } else {
                    alertBox.classList.add('alert-danger');
                }
                
                alertText.textContent = data.message;
                alertBox.classList.remove('hidden');
            } catch (err) {
                alertBox.className = 'alert-box alert-danger';
                alertText.textContent = 'Error al procesar la solicitud.';
                alertBox.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>