<?php
header('Content-Type: application/json; charset=utf-8');

// Cargar el autoloader de Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// 🛠️ CORRECCIÓN 1: Asegurar que use la ruta correcta de Database si invoca SQL directo,
// o que el modelo de Vehículos tenga el namespace bien configurado.
use Eco\Core\Database;

// Capturar el ID de la marca enviado por el formulario
$marcaId = isset($_GET['marca_id']) ? (int)$_GET['marca_id'] : 0;

if ($marcaId === 0) {
    echo json_encode([]);
    exit;
}

// Variables de entorno nativas inyectadas por tu clúster
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_name = $_ENV['DB_DATABASE'] ?? ''; // Mapeado correcto de tu Secret/ConfigMap
$db_user = $_ENV['DB_USER'] ?? '';
$db_pass = $_ENV['DB_PASSWORD'] ?? ''; // Mapeado correcto de tu Secret/ConfigMap

try {
    $db = Database::getConnection();
    
    // 🛠️ CORRECCIÓN 2: Validar query contra tablas reales (ej: 'vehicle_models' o 'modelos')
    // Cambia los nombres de tabla/columna si difieren en tu estructura de MariaDB
    $stmt = $db->prepare("SELECT id, nombre FROM vehicle_models WHERE marca_id = :marca_id ORDER BY nombre ASC");
    $stmt->execute(['marca_id' => $marcaId]);
    
    $models = $stmt->fetchAll();
    echo json_encode($models);
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}