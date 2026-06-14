<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../../src/Database.php';

use Eco\Core\Database;

$action = $_GET['action'] ?? '';
$db = Database::getConnection();

try {
    // Action 1: Fetch all 16 regions of Chile
    if ($action === 'regions') {
        $stmt = $db->query("SELECT id, name, roman_numeral FROM chile_regions ORDER BY id ASC");
        $regions = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $regions], JSON_UNESCAPED_UNICODE);
        exit;
    } 
    
    // Action 2: Fetch specific Comunas filtered by parent Region ID
    unset($db);
    $db = Database::getConnection();
    if ($action === 'comunas') {
        $regionId = (int)($_GET['region_id'] ?? 0);
        
        if (!$regionId) {
            echo json_encode(['success' => false, 'message' => 'Falta el parámetro region_id.']);
            exit;
        }

        $stmt = $db->prepare("SELECT id, name FROM chile_comunas WHERE region_id = :region_id ORDER BY name ASC");
        $stmt->execute([':region_id' => $regionId]);
        $comunas = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'data' => $comunas], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Default response if action doesn't match
    echo json_encode(['success' => false, 'message' => 'Acción inválida. Utilice ?action=regions o ?action=comunas&region_id=X']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}