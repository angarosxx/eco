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
        // 🎯 FIXED: Query against 'chile_regions' and select roman_numeral explicitly
        $stmt = $db->query("SELECT id, name, roman_numeral FROM chile_regions ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}