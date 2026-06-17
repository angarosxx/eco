<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Suppress runtime notices/warnings from leaking into output buffer
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Auth\RegisterHandler;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}

try {
    $handler = new RegisterHandler();
    $result = $handler->register($_POST);

    if (isset($result['success']) && $result['success'] === true) {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        $_SESSION['user_name'] = strip_tags($_POST['first_name'] ?? $_POST['company_name'] ?? 'Usuario');

        echo json_encode(["success" => true, "redirect" => "/dashboard.php"]);
        exit;
    }

    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => $result['message'] ?? "Datos de registro inválidos."
    ]);
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno del servidor de registro."
    ]);
    exit;
}