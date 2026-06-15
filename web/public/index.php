<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 🔥 FIX: If the request is for a real file in the API folder, execution passes straight through
if (strpos($requestUri, '/api/') === 0) {
    $apiFile = __DIR__ . $requestUri;
    if (file_exists($apiFile)) {
        require_once $apiFile;
        exit;
    }
}

// Global Layout Router for standard pages
switch ($requestUri) {
    case '/':
    case '/index.php':
        require_once __DIR__ . '/../src/Views/home.php';
        break;

    case '/register':
    case '/register.php':
        require_once __DIR__ . '/register.php';
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "success" => false, 
            "message" => "Ruta no encontrada: " . htmlspecialchars($requestUri)
        ]);
        break;
}