<?php

namespace Eco\Models;

// 🛠️ CORRECCIÓN 1: Namespace correcto de la Base de Datos
use Eco\Core\Database; 
use PDO;

class Comuna {
    /**
     * Fetch all comunas linked to a specific region ID
     */
    public static function getByRegion(int $regionId): array {
        $db = Database::getConnection();
        
        // 🛠️ CORRECCIÓN 2: Asegurar que los nombres de columna coincidan con tu MariaDB
        // (Revisa si en tu tabla se llama 'region_id' o 'chile_region_id')
        $stmt = $db->prepare("SELECT id, name FROM chile_comunas WHERE region_id = :region_id ORDER BY name ASC");
        $stmt->execute(['region_id' => $regionId]);
        
        return $stmt->fetchAll();
    }
}