<?php

namespace Eco\Models;

use Eco\Core\Database;
use PDO;

class Comuna {
    /**
     * Fetch comunas matching a specific parent region
     */
    public static function getByRegion(int $regionId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, name FROM comunas WHERE region_id = :region_id ORDER BY name ASC");
        $stmt->execute(['region_id' => $regionId]);
        return $stmt->fetchAll();
    }
}