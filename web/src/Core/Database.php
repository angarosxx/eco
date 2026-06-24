<?php

namespace Eco\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    /**
     * Get the authenticated PDO connection instance (Singleton Pattern)
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            // Updated default fallback host to match your verified live service name
            $host     = getenv('DB_HOST') ?: 'mariadb-service.default.svc.cluster.local';
            $port     = getenv('DB_PORT') ?: '3306';
            $dbName   = getenv('DB_NAME') ?: 'eco_classifieds';
            $username = getenv('DB_USER') ?: 'user_prod_eco';
            $password = getenv('DB_PASSWORD') ?: 'U80wrNQF2r4V8F5109FD';
            
            // FIXED: Only evaluate to true if the environment variable is explicitly 'true'
            $useSsl   = getenv('DB_SSL') === 'true'; 

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // This block will now be correctly skipped when DB_SSL is false
            if ($useSsl) {
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false; 
            }

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                throw new PDOException("Database connection failure: " . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }
}