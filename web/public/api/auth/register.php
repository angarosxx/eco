<?php
// web/public/api/auth/register.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔥 FIXED: Dynamically search the exact locations where vendor/autoload could be mounted
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
} else {
    // Ultimate fallback if it's nested deep inside your public workspace
    require_once __DIR__ . '/../../../../vendor/autoload.php'; 
}

use Eco\Auth\RegisterHandler;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

try {
    // 1. Instantiate your existing enterprise grade registration engine
    $handler = new RegisterHandler();
    
    // 2. Pass the entire $_POST array
    $result = $handler->register($_POST);

    if ($result['success'] === true) {
        // Start session and authorize user globally
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        $_SESSION['user_name'] = strip_tags($_POST['first_name'] ?? $_POST['company_name'] ?? 'Usuario');

        // Redirect nicely to your dashboard
        header('Location: /dashboard.php');
        exit;
    } else {
        // Return verification errors gracefully
        http_response_code(400);
        echo json_encode($result);
        exit;
    }

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error crítico: " . $e->getMessage()]);
    exit;
}