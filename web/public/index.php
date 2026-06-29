<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🚀 Cargar el Autoloader de Composer para habilitar los Namespaces
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// ==========================================================================
// 🛡️ MODO MANTENIMIENTO CON BYPASS POR IP
// ==========================================================================
$modo_mantenimiento = true; // Cambia a false cuando quieras lanzar la web al público

// Lista de IPs autorizadas para saltarse el mantenimiento y testear (Tu IP pública)
$ips_autorizadas = [
    '127.0.0.1',  // Localhost
    '::1',
    '186.10.245.198'        // Localhost IPv6
    // '200.12.34.56' <-- Quita el comentario y pon aquí tu IP de la casa/oficina
];

// Capturamos la IP real del usuario (incluso detrás de proxies de k8s/Cloudflare)
$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

// Cambia esto en la línea 31:
if ($modo_mantenimiento && !in_array($user_ip, $ips_autorizadas)) {
    // Código correcto con guion bajo: in_array()
    require_once __DIR__ . '/../src/Views/maintenance.php';
    exit;
}
// ==========================================================================

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "La ruta exacta que lee PHP es: [" . $requestUri . "]"; 
exit;
// (El resto de tu código de la API y el switch de rutas sigue exactamente igual abajo...)

if (strpos($requestUri, '/index.php') === 0) {
    $requestUri = substr($requestUri, 10); // Quita "/index.php"
    if (empty($requestUri)) $requestUri = '/';
}



$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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
    // 🏠 HOME: Ahora acepta la raíz, index y home explícito
    case '/':
    case '/index.php':
    case '/home':
    case '/home.php': // 💡 ¡Añadido! Así evitamos que falle si entras directo a /home.php
        require_once __DIR__ . '/../src/Views/home.php';
        break;

    // 🎯 Ruta para el Login
    case '/login':
    case '/login.php':
        require_once __DIR__ . '/login.php';
        break;

    // 🎯 Ruta para el Dashboard
    case '/dashboard':
    case '/dashboard.php':
        require_once __DIR__ . '/dashboard.php';
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
    case '/ad_details.php': // 💡 Invertido el orden para mantener consistencia visual
        require_once __DIR__ . '/ad_details.php';
        break;

    // 🎯 Añadir dentro de tu switch en index.php
    case '/listings':
    case '/listings.php':
    case '/advanced_search':
    case '/advanced_search.php':
        require_once __DIR__ . '/advanced_search.php';
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