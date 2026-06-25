<?php
// web/public/api/vehicles/models.php
header('Content-Type: application/json; charset=utf-8');

if (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../../vendor/autoload.php';
}

use Eco\Core\Database;

$marcaId = isset($_GET['marca_id']) ? (int)$_GET['marca_id'] : 0;

if ($marcaId === 0) {
    echo json_encode([]);
    exit;
}

try {
    $db = Database::getConnection();
    
    // 🔥 CORRECCIÓN: Apuntar a la tabla 'modelos' que nos mostraste en el dump
    $stmt = $db->prepare("SELECT id, nombre FROM modelos WHERE marca_id = :marca_id AND activo = 1 ORDER BY nombre ASC");
    $stmt->execute(['marca_id' => $marcaId]);
    
    $models = $stmt->fetchAll();
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