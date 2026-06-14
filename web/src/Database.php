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
            // Read values natively from the environment variables configured via Docker
            $host     = getenv('DB_HOST') ?: 'b2c2b-db.tracexit.com';
            $port     = getenv('DB_PORT') ?: '3306';
            $dbName   = getenv('DB_NAME') ?: 'eco_classifieds';
            $username = getenv('DB_USER') ?: '';
            $password = getenv('DB_PASSWORD') ?: '';
            $useSsl   = getenv('DB_SSL') === 'true';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // If your remote K8s cluster enforces secure encrypted traffic paths
            if ($useSsl) {
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false; 
                // You can expand this to point to a specific CA cert file if needed later
            }

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                // Log error safely or handle gracefully depending on environmental setup
                throw new PDOException("Database connection failure: " . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }
}