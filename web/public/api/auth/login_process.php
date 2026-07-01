<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Controllers\LoginController;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}

try {
    $controller = new LoginController();
    $result = $controller->authenticate($_POST);

    if ($result['success']) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int) $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];

        if (isset($result['user_name'])) {
            $_SESSION['user_name'] = $result['user_name'];
        }

        session_write_close();

        echo json_encode([
            'success' => true,
            'redirect' => '/dashboard.php'
        ]);
        exit;
    }

    echo json_encode($result);
    exit;

} catch (\Exception $e) {
    // 🕵️‍♂️ DEBUG TOTAL: Guardamos el error en los logs de Apache del Pod
    error_log('LOGIN CRITICAL EXCEPTION: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        // 🔥 Reemplazamos el string genérico por el error real para cazarlo ya mismo
        'message' => 'Fallo interno: ' . $e->getMessage() . ' (En línea ' . $e->getLine() . ' de ' . basename($e->getFile()) . ')'
    ]);
    exit;
}