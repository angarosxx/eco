<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión nativa a MariaDB
require_once __DIR__ . '/../../../src/Core/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($token) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos para procesar la solicitud.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres.']);
    exit;
}

try {
    $db = \Core\Database::getInstance()->getConnection();

    // 1. Validar por última vez que el token sea correcto y esté vigente
    $stmt = $db->prepare("
        SELECT id 
        FROM users 
        WHERE reset_token = :token 
          AND reset_expires_at > NOW() 
        LIMIT 1
    ");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'El token es inválido o ya ha expirado.']);
        exit;
    }

    // 2. Hashear la nueva contraseña de forma segura
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 3. Actualizar la contraseña del usuario y limpiar los campos de recuperación
    $updateStmt = $db->prepare("
        UPDATE users 
        SET password = :password,
            reset_token = NULL,
            reset_expires_at = NULL 
        WHERE id = :id
    ");
    
    $success = $updateStmt->execute([
        'password' => $hashedPassword,
        'id' => $user['id']
    ]);

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Tu contraseña ha sido actualizada con éxito. Redirigiendo al inicio de sesión...'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el registro en la base de datos.']);
    }

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos en el clúster: ' . $e->getMessage()
    ]);
}