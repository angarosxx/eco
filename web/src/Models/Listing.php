<?php

namespace Eco\Models;

use Eco\Core\Database;
use PDO;

class Listing {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Create a new advertisement in the database
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO listings (user_id, title, description, price, region_id, comuna_id, image_url, created_at)
            VALUES (:user_id, :title, :description, :price, :region_id, :comuna_id, :image_url, NOW())
        ");

        return $stmt->execute([
            ':user_id'     => (int)$data['user_id'],
            ':title'       => strip_tags(trim($data['title'])),
            ':description' => strip_tags(trim($data['description'])),
            ':price'       => (float)$data['price'],
            ':region_id'   => (int)$data['region_id'],
            ':comuna_id'   => (int)$data['comuna_id'],
            ':image_url'   => $data['image_url'] ?? null
        ]);
    }

    /**
     * Fetch all advertisements belonging to a specific user for the dashboard view
     */
    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT l.*, r.name as region_name, c.name as comuna_name 
            FROM listings l
            LEFT JOIN regions r ON l.region_id = r.id
            LEFT JOIN comunas c ON l.comuna_id = c.id
            WHERE l.user_id = :user_id 
            ORDER BY l.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}