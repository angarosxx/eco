<?php

namespace Eco\Models;

use Eco\Core\Database;
use PDO;

class Region {
    /**
     * Fetch all administrative regions from the database
     */
    public static function getAll(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, name FROM regions ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}