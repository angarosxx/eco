<?php
header('Content-Type: application/json; charset=utf-8');

if (!class_exists('Eco\Core\Database')) {
    if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
        require_once __DIR__ . '/../../vendor/autoload.php';
    }
}

$action = $_GET['action'] ?? '';

try {
    $db = \Eco\Core\Database::getConnection();

    if ($action === 'regions') {
        // 🇨🇱 Ajustado a tus nuevas tablas
        $stmt = $db->query("SELECT id, name, roman_numeral FROM chile_regions ORDER BY id ASC");
        $regions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($regions);
        exit;
    } 
    
    if ($action === 'comunas') {
        $regionId = intval($_GET['region_id'] ?? 0);
        
        // 🇨🇱 Ajustado a tus nuevas tablas
        $stmt = $db->prepare("SELECT id, name FROM chile_comunas WHERE region_id = :region_id ORDER BY name ASC");
        $stmt->execute(['region_id' => $regionId]);
        $comunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($comunas);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Acción no válida.']);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
}