<?php
// Ensure headers are completely clean
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close(); // Release lock
}
session_start();

header('Content-Type: application/json; charset=utf-8');
//error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Absolute safety net against structural warnings leaking into response streams
//error_reporting(E_ERROR | E_PARSE);
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
        // Provision access validation matrices inside secure PHP memory
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        
        echo json_encode(['success' => true, 'redirect' => '/dashboard.php']);
        exit;
    }

    http_response_code(400);
    echo json_encode($result);
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Fallo crítico del servidor de autenticación.']);
    exit;
}