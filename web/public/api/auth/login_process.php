<?php
// Set explicit session cookie configuration before starting the session
ini_set('session.cookie_path', '/');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Controllers\LoginController;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método de red no admitido.']);
    exit;
}

try {
    $authEngine = new LoginController();
    $result = $authEngine->authenticate($_POST);

    if ($result['success'] === true) {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        
        // FORCE the session to write data right now before sending the network response
        session_write_close();
        
        echo json_encode(['success' => true, 'redirect' => '/dashboard.php']);
        exit;
    }

    http_response_code(400);
    echo json_encode($result);
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Fallo crítico: ' . $e->getMessage()]);
    exit;
}