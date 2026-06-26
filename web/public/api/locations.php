<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../../vendor/autoload.php';

use Eco\Models\Region;
use Eco\Models\Comuna;

try {
    $type = $_GET['type'] ?? '';

    if ($type === 'regions') {
        $regions = array_map(function ($region) {
            return [
                'id' => $region['id'],
                'name' => $region['name'] ?? $region['nombre'] ?? ''
            ];
        }, Region::getAll());

        echo json_encode($regions);
        exit;
    }

    if ($type === 'comunas') {
        $regionId = (int) ($_GET['region_id'] ?? 0);

        if ($regionId <= 0) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'Debe indicar una región válida.'
            ]);
            exit;
        }

        $comunas = array_map(function ($comuna) {
            return [
                'id' => $comuna['id'],
                'name' => $comuna['name'] ?? $comuna['nombre'] ?? ''
            ];
        }, Comuna::getByRegion($regionId));

        echo json_encode($comunas);
        exit;
    }

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Parámetro type no válido.'
    ]);
    exit;

} catch (\Throwable $e) {
    error_log('locations.php error: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno al cargar ubicaciones.'
    ]);
    exit;
}
