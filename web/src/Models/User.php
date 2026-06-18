<?php

namespace Eco\Models;

use Eco\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Locate a user record by their unique email identifier
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: null;
    }
}