<?php

namespace Eco\Models;

use Eco\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Locate a unique system account record by its email address
     * * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("
            SELECT id, password_hash, account_type, is_active 
            FROM users 
            WHERE email = :email 
            LIMIT 1
        ");
        
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ? $user : null;
    }
}