<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Conexión nativa a MariaDB
require_once __DIR__ . '/../src/Core/Database.php';

$token = $_GET['token'] ?? '';
$tokenValido = false;
$userId = null;
$mensajeError = '';

if (!empty($token)) {
    try {
        $db = \Core\Database::getInstance()->getConnection();
        
        // Buscamos al usuario por el token y verificamos que no haya expirado
        $stmt = $db->prepare("
            SELECT id, email 
            FROM users 
            WHERE reset_token = :token 
              AND reset_expires_at > NOW() 
            LIMIT 1
        ");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $tokenValido = true;
            $userId = $user['id'];
        } else {
            $mensajeError = 'El enlace de recuperación es inválido o ha expirado. Por favor, solicita uno nuevo.';
        }
    } catch (\PDOException $e) {
        $mensajeError = 'Error de conexión con el clúster de base de datos.';
    }
} else {
    $mensajeError = 'Acceso no válido. Falta el token de recuperación.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecer Nueva Contraseña - Ecomercio</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="site-title">Nueva Contraseña</h2>
            <p class="site-subtitle">Escribe tu nueva clave de acceso a continuación.</p>
        </div>

        <div id="alert-banner" class="alert-box <?php echo !$tokenValido ? 'alert-danger' : 'hidden'; ?>">
            <span id="alert-message"><?php echo htmlspecialchars($mensajeError); ?></span>
        </div>

        <?php if ($tokenValido): ?>
            <form id="resetForm" method="POST" action="">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group-container">
                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input id="password" name="password" type="password" required minlength="6" class="form-input" placeholder="Mínimo 6 caracteres">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                        <input id="confirm_password" name="confirm_password" type="password" required minlength="6" class="form-input" placeholder="Repite tu contraseña">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" id="submit-btn" class="btn btn-primary btn-full">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="form-footer" style="margin-top: 20px;">
                <a href="/forgot_password.php" class="btn btn-secondary btn-full" style="text-decoration:none; display:block; text-align:center;">
                    Solicitar nuevo enlace
                </a>
            </div>
        <?php endif; ?>
        
        <div class="form-footer">
            <a href="/login.php" class="theme-link-secondary">Volver al inicio de sesión</a>
        </div>
    </div>

    <?php if ($tokenValido): ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('resetForm');
        const alertBanner = document.getElementById('alert-banner');
        const alertMessage = document.getElementById('alert-message');
        const submitBtn = document.getElementById('submit-btn');

        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            alertBanner.classList.add('hidden');

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Validación rápida en cliente
            if (password !== confirmPassword) {
                alertBanner.className = 'alert-box alert-danger';
                alertMessage.textContent = 'Las contraseñas introducidas no coinciden.';
                alertBanner.classList.remove('hidden');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Guardando cambios...';
            }

            try {
                // Endpoint que procesará el cambio definitivo en MariaDB
                const response = await fetch('/api/auth/reset_password_process.php', {
                    method: 'POST',
                    body: new FormData(form)
                });

                if (!response.ok) {
                    throw new Error('Error interno al actualizar la contraseña.');
                }

                const result = await response.json();

                if (result.success) {
                    alertBanner.className = 'alert-box alert-success';
                    alertMessage.textContent = result.message || 'Contraseña actualizada con éxito.';
                    alertBanner.classList.remove('hidden');
                    
                    // Ocultamos el botón para evitar re-envíos y redirigimos
                    form.reset();
                    if (submitBtn) submitBtn.remove();
                    
                    setTimeout(() => {
                        window.location.href = '/login.php';
                    }, 3000);
                } else {
                    alertBanner.className = 'alert-box alert-danger';
                    alertMessage.textContent = result.message || 'No se pudo actualizar la contraseña.';
                    alertBanner.classList.remove('hidden');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Actualizar Contraseña';
                    }
                }
            } catch (err) {
                alertBanner.className = 'alert-box alert-danger';
                alertMessage.innerHTML = `<strong>Error:</strong> <small>${err.message}</small>`;
                alertBanner.classList.remove('hidden');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Actualizar Contraseña';
                }
            }
        });
    });
    </script>
    <?php endif; ?>
</body>
</html>