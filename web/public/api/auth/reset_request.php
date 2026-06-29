<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar clases core mediante el autoloader o rutas relativas según tu estructura
require_once __DIR__ . '/../../../src/Core/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Por favor, ingresa un correo electrónico válido.']);
    exit;
}

try {
    // Instanciamos tu clase de conexión nativa a MariaDB
    $db = \Core\Database::getInstance()->getConnection(); 

    // 1. Verificar si el usuario existe en MariaDB
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($user) {
        // 2. Generar un token seguro y una expiración (Ej: 1 hora)
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // 3. Guardar el token en la base de datos
        // NOTA: Ajusta los nombres de las columnas según tu migración de MariaDB
        $updateStmt = $db->prepare("
            UPDATE users 
            SET reset_token = :token, reset_expires_at = :expires_at 
            WHERE id = :id
        ");
        $updateStmt->execute([
            'token' => $token,
            'expires_at' => $expiresAt,
            'id' => $user['id']
        ]);

        // 4. Enviar el correo electrónico
        // TODO: Aquí integrarás PHPMailer o tu servicio smtp de K8s.
        //$resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $token;
        $resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/public/reset_password.php?token=" . $token;
        
        // Log temporal en el contenedor para que puedas capturar el link mientras pruebas
        error_log("[RESET PASSWORD] Enlace generado para {$email}: {$resetLink}");
    }

    // 🛡️ RESPUESTA DE SEGURIDAD GENERAL (Evita enumeración de cuentas)
    echo json_encode([
        'success' => true,
        'message' => 'Si el correo está registrado, recibirás un enlace de recuperación en los próximos minutos.'
    ]);

} catch (\PDOException $e) {
    // Si truena MariaDB en K8s, capturamos el error
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos en el clúster: ' . $e->getMessage()
    ]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
}