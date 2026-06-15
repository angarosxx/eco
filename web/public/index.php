<?php

// 1. Enable error reporting temporarily for troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Simple clean URL routing layout
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($requestUri) {
    case '/':
    case '/index.php':
        // Route to your home template view
        require_once __DIR__ . '/../src/Views/home.php';
        break;

    case '/register':
    case '/register.php':
        // Route natively straight to your registration interface
        require_once __DIR__ . '/register.php';
        break;

    default:
        // Graceful fallback for missing assets or pages
        http_response_code(404);
        echo json_encode([
            "success" => false, 
            "message" => "Ruta no encontrada en el framework eco: " . htmlspecialchars($requestUri)
        ]);
        break;
}