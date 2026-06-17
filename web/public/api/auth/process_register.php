<?php
// 1. Initialize session storage before any output occurs
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// 2. Load the autoloader map
require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Auth\RegisterHandler;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

try {
    $handler = new RegisterHandler();
    $result = $handler->register($_POST);

    if ($result['success'] === true) {
        // 3. Populate global user tracking arrays cleanly
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        $_SESSION['user_name'] = strip_tags($_POST['first_name'] ?? $_POST['company_name'] ?? 'Usuario');

        echo json_encode(["success" => true, "redirect" => "/dashboard.php"]);
        exit;
    } else {
        http_response_code(400);
        echo json_encode($result);
        exit;
    }
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error crítico: " . $e->getMessage()]);
    exit;
}