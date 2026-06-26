<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🚀 LA PIEZA FALTANTE: Cargar el Autoloader de Composer para habilitar los Namespaces
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

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

    case '/publish_ad':
    case '/publish_ad.php':
        require_once __DIR__ . '/publish_ad.php';
        break;

    // 🎯 EL DEFAULT SIEMPRE VA AL FINAL
    default:
        http_response_code(404);
        echo json_encode([
            "success" => false, 
            "message" => "Ruta no encontrada: " . htmlspecialchars($requestUri)
        ]);
        break;
}