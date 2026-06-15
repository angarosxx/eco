<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../../src/Database.php';

use Eco\Core\Database;

$action = $_GET['action'] ?? '';

try {
    $db = Database::getConnection();

    // Acción 1: Obtener las 16 regiones de Chile
    if ($action === 'regions') {
        $stmt = $db->query("SELECT id, name, roman_numeral FROM chile_regions ORDER BY id ASC");
        $regions = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $regions], JSON_UNESCAPED_UNICODE);
        exit;
    } 
    
    // Acción 2: Obtener comunas filtradas por ID de Región
    if ($action === 'comunas') {
        $regionId = (int)($_GET['region_id'] ?? 0);
        
        if (!$regionId) {
            http_response_code(400); // Bad Request formal
            echo json_encode(['success' => false, 'message' => 'Falta el parámetro region_id.']);
            exit;
        }

        $stmt = $db->prepare("SELECT id, name FROM chile_comunas WHERE region_id = :region_id ORDER BY name ASC");
        $stmt->execute([':region_id' => $regionId]);
        $comunas = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'data' => $comunas], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Respuesta por defecto si no coincide la acción
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Acción inválida. Utilice ?action=regions o ?action=comunas&region_id=X']);

} catch (Exception $e) {
    http_response_code(500);
    // CHANGE THIS LINE to output the detailed system message:
    echo json_encode(['success' => false, 'message' => 'CRASH LOG: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine()]);
    exit;
}