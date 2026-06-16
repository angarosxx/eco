<?php
header('Content-Type: application/json');

// Pull down Composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use Eco\Models\Region;
use Eco\Models\Comuna;

try {
    // If a specific region ID parameter is supplied, query sub-level comunas
    if (isset($_GET['region_id'])) {
        $regionId = (int)$_GET['region_id'];
        echo json_encode(Comuna::getByRegion($regionId));
        exit;
    }

    // Default payload: Provide top-level regional directories
    echo json_encode(Region::getAll());
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error de locación: " . $e->getMessage()]);
    exit;
}