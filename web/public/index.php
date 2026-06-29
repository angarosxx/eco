<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar el Autoloader de Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// ==========================================================================
// 🛡️ MODO MANTENIMIENTO CON BYPASS POR IP
// ==========================================================================
$modo_mantenimiento = true; 

$ips_autorizadas = [
    '127.0.0.1',       
    '::1',             
    '186.10.245.198'   // 🏡 Tu IP Pública autorizada
];

// Captura la IP real que Nginx le pasa a K8s a través de X-Forwarded-For
$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

if (strpos($user_ip, ',') !== false) {
    $user_ip = trim(explode(',', $user_ip)[0]);
}

if ($modo_mantenimiento && !in_array($user_ip, $ips_autorizadas)) {
    require_once __DIR__ . '/../src/Views/maintenance.php';
    exit;
}
// ==========================================================================

// Capturamos la ruta limpia
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// 🩺 MÉDICO DE GUARDIA: Añade esto justo aquí
echo "<pre>";
echo "IP Detectada: " . htmlspecialchars($user_ip) . "\n";
echo "URI que lee PHP: [" . htmlspecialchars($requestUri) . "]\n";
echo "URI Original completa: [" . htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') . "]\n";
echo "</pre>";
exit;

// 🔥 Si es una petición a la API, se ejecuta directo el archivo físico
if (strpos($requestUri, '/api/') === 0) {
    $apiFile = __DIR__ . $requestUri;
    if (file_exists($apiFile)) {
        require_once $apiFile;
        exit;
    }
}

// Enrutador global para páginas estándar
switch ($requestUri) {
    // 🏠 HOME: Soporta todas las variantes que Nginx o el navegador puedan enviar
    case '/':
    case '/index.php':
    case '/home':
    case '/home.php': 
        require_once __DIR__ . '/../src/Views/home.php';
        break;

    // 🎯 Login
    case '/login':
    case '/login.php':
        require_once __DIR__ . '/login.php';
        break;

    // 🎯 Dashboard
    case '/dashboard':
    case '/dashboard.php':
        require_once __DIR__ . '/dashboard.php';
        break;

    // 🎯 Lista de anuncios (Mapeamos listings para resolver tu error anterior)
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

    // 🎯 EL DEFAULT SIEMPRE VA AL FINAL
    default:
        http_response_code(404);
        echo json_encode([
            "success" => false, 
            "message" => "Ruta no encontrada en K8s: " . htmlspecialchars($requestUri)
        ]);
        break;
}