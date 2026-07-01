<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Cargar el Autoloader de Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// ==========================================================================
// 1. EXCEPCIÓN INMEDIATA DE LA API
// ==========================================================================
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($requestUri, '/api/') === 0) {
    $apiFile = __DIR__ . $requestUri;
    if (file_exists($apiFile)) {
        require_once $apiFile;
        exit;
    }
}

// ==========================================================================
// 2. MODO MANTENIMIENTO CON BYPASS POR IP
// ==========================================================================
$modo_mantenimiento = true;

$ips_autorizadas = [
    '127.0.0.1',
    '::1',
    '90.129.235.246'
];

$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

if (strpos($user_ip, ',') !== false) {
    $user_ip = trim(explode(',', $user_ip)[0]);
}

if ($modo_mantenimiento && !in_array($user_ip, $ips_autorizadas, true)) {
    http_response_code(503);
    require_once __DIR__ . '/../src/Views/maintenance.php';
    exit;
}

// ==========================================================================
// 3. ENRUTADOR GLOBAL PARA PÁGINAS ESTÁNDAR
// ==========================================================================
switch ($requestUri) {
    case '/':
    case '/index.php':
    case '/home':
    case '/home.php':
        require_once __DIR__ . '/../src/Views/home.php';
        break;

    case '/login':
    case '/login.php':
        require_once __DIR__ . '/login.php';
        break;

    case '/dashboard':
    case '/dashboard.php':
        require_once __DIR__ . '/dashboard.php';
        break;

    case '/listings':
    case '/listings.php':
    case '/advanced_search':
    case '/advanced_search.php':
        require_once __DIR__ . '/advanced_search.php';
        break;

    case '/register':
    case '/register.php':
        require_once __DIR__ . '/register.php';
        break;

    case '/publish_ad':
    case '/publish_ad.php':
        require_once __DIR__ . '/publish_ad.php';
        break;

    case '/ad_details':
    case '/ad_details.php':
        require_once __DIR__ . '/ad_details.php';
        break;

    default:
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Ruta no encontrada en K8s: ' . htmlspecialchars($requestUri)
        ]);
        break;
}
