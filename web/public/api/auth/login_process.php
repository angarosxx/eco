<?php
// 1. Validar estado de sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cabeceras limpias antes de cualquier salida
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 3. Cargar dependencias
require_once __DIR__ . '/../../../vendor/autoload.php';
use Eco\Controllers\LoginController;

// 4. Solo procesar si la petición es estrictamente POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

try {
    $controller = new LoginController();
    $result = $controller->authenticate($_POST);

    if ($result['success']) {
        // Guardar variables de sesión
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        
        // 🔥 FORZAR guardado inmediato antes de cortar con exit
        session_write_close(); 
        
        echo json_encode(['success' => true, 'redirect' => '/dashboard.php']);
        exit;
    } else {
        // Credenciales inválidas o campos vacíos
        http_response_code(200); // Se responde 200 porque la API procesó bien el rechazo
        echo json_encode($result);
        exit;
    }

} catch (\Exception $e) {
    // Captura cualquier fallo de conexión a base de datos o sintaxis interna
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Fallo crítico en el servidor: ' . $e->getMessage()]);
    exit;
}