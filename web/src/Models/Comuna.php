<?php

namespace Eco\Models;

use Eco\Database;
use PDO;

class Comuna {
    /**
     * Fetch comunas matching a specific parent region
     */
    public static function getByRegion(int $regionId): array {
        $db = Database::getConnection();
        // 🎯 FIXED: Query against 'chile_comunas'
        $stmt = $db->prepare("SELECT id, name FROM chile_comunas WHERE region_id = :region_id ORDER BY name ASC");
        $stmt->execute(['region_id' => $regionId]);
        return $stmt->fetchAll();
    }
}