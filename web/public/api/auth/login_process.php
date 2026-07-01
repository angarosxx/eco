<?php
// 1. Configuración de logs y reporte de errores internos
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// 2. Inicio de sesión limpio (las directivas seguras ya las inyecta el Dockerfile)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TEMP DEBUG LOGS: Logs iniciales seguros
error_log('LOGIN START host=' . gethostname());
error_log('LOGIN START session_id=' . session_id());
error_log('LOGIN START user_id=' . ($_SESSION['user_id'] ?? 'none'));

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../vendor/autoload.php';
use Eco\Controllers\LoginController;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

try {
    $controller = new LoginController();
    $result = $controller->authenticate($_POST);

    if ($result['success']) {
        // Guardamos los datos de autenticación esenciales en la sesión global
        $_SESSION['user_id'] = (int)$result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        
        // Si el controlador te devuelve el nombre del usuario, lo persistimos de una vez
        if (isset($result['user_name'])) {
            $_SESSION['user_name'] = $result['user_name'];
        }

        // TEMP DEBUG LOGS: Log tras escribir en la sesión exitosamente
        error_log('LOGIN SUCCESS host=' . gethostname() . ' | SESSID=' . session_id() . ' | UID=' . $_SESSION['user_id']);

        // 🔒 Forzamos la escritura física en disco/memoria antes de responder el JSON
        session_write_close();

        echo json_encode([
            'success' => true,
            'redirect' => '/dashboard.php'
        ]);
        exit;
    } else {
        echo json_encode($result);
        exit;
    }

} catch (\Exception $e) {
    error_log('LOGIN ERROR: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fallo crítico en el servidor.'
    ]);
    exit;
}