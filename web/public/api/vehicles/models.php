<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Eco\Database;

$brandId = filter_var($_GET['brand_id'] ?? 0, FILTER_VALIDATE_INT);

if (!$brandId) {
    echo json_encode([]);
    exit;
}

try {
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT id, name FROM vehicle_models WHERE brand_id = :brand_id ORDER BY name ASC");
    $stmt->execute([':brand_id' => $brandId]);
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($models);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}