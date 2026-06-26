<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TEMP DEBUG LOGS: right after session starts
error_log('LOGIN START host=' . gethostname());
error_log('LOGIN START session_id=' . session_id());
error_log('LOGIN START cookie=' . ($_COOKIE['PHPSESSID'] ?? 'none'));
error_log('LOGIN START user_id=' . ($_SESSION['user_id'] ?? 'none'));

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '0');

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
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];

        // TEMP DEBUG LOGS: right after session values are written
        error_log('LOGIN SUCCESS host=' . gethostname());
        error_log('LOGIN SUCCESS session_id=' . session_id());
        error_log('LOGIN SUCCESS cookie=' . ($_COOKIE['PHPSESSID'] ?? 'none'));
        error_log('LOGIN SUCCESS user_id=' . ($_SESSION['user_id'] ?? 'none'));

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
