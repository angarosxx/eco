<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Eco\Database;

// Validar que el parámetro brand_id venga como entero limpio
$marcaId = filter_var($_GET['brand_id'] ?? 0, FILTER_VALIDATE_INT);

if (!$marcaId) {
    echo json_encode([]);
    exit;
}

try {
    $db = Database::getConnection();
    
    // Consulta apuntando a la nueva tabla unificada en español
    $stmt = $db->prepare("SELECT id, nombre FROM modelos WHERE marca_id = :marca_id AND activo = TRUE ORDER BY nombre ASC");
    $stmt->execute([':marca_id' => $marcaId]);
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($modelos);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}