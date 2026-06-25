<?php
// web/public/api/vehicles/models.php
header('Content-Type: application/json; charset=utf-8');

// 1. Cargar el autoloader de Composer de forma segura
if (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../../vendor/autoload.php';
}

use Eco\Core\Database;

// 2. Capturar el ID de la marca enviado por el formulario
$marcaId = isset($_GET['marca_id']) ? (int)$_GET['marca_id'] : 0;

if ($marcaId === 0) {
    echo json_encode([]);
    exit;
}

try {
    // 3. Obtener la conexión (Database ya maneja los getenv() internamente)
    $db = Database::getConnection();
    
    // 4. Validar query contra tablas reales de MariaDB
    // 💡 NOTA: Asegúrate de que en tu base de datos la tabla se llame 'vehicle_models' 
    // y que las columnas sean exactamente 'id', 'nombre' y 'marca_id'.
    $stmt = $db->prepare("SELECT id, nombre FROM vehicle_models WHERE marca_id = :marca_id ORDER BY nombre ASC");
    $stmt->execute(['marca_id' => $marcaId]);
    
    $models = $stmt->fetchAll();
    
    // 5. Devolver la respuesta limpia
    echo json_encode($models);
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false, 
        "message" => "Error al cargar modelos: " . $e->getMessage()
    ]);
    exit;
}