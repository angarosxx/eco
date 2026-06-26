<?php

namespace Eco\Models;

use Eco\Core\Database;
use PDO;
use Throwable;

class Listing
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Create a new listing and return its ID.
     */
    public function create(array $data): int|false
    {
        $stmt = $this->db->prepare("
            INSERT INTO listings (
                user_id,
                category_id,
                region_id,
                comuna_id,
                title,
                slug,
                description,
                price,
                currency,
                ad_type_origin,
                status,
                created_at
            ) VALUES (
                :user_id,
                :category_id,
                :region_id,
                :comuna_id,
                :title,
                :slug,
                :description,
                :price,
                :currency,
                :ad_type_origin,
                :status,
                NOW()
            )
        ");

        $ok = $stmt->execute([
            ':user_id'        => (int) $data['user_id'],
            ':category_id'    => (int) $data['category_id'],
            ':region_id'      => (int) $data['region_id'],
            ':comuna_id'      => (int) $data['comuna_id'],
            ':title'          => trim(strip_tags($data['title'])),
            ':slug'           => trim($data['slug']),
            ':description'    => trim(strip_tags($data['description'])),
            ':price'          => (float) $data['price'],
            ':currency'       => strtoupper(trim($data['currency'] ?? 'CLP')),
            ':ad_type_origin' => $data['ad_type_origin'] ?? 'private',
            ':status'         => $data['status'] ?? 'active',
        ]);

        if (!$ok) {
            return false;
        }

        return (int) $this->db->lastInsertId();
    }

    /**
     * Save one image for a listing.
     */
    public function addImage(int $listingId, string $imagePath, bool $isPrimary = false): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO listing_images (
                listing_id,
                image_path,
                is_primary
            ) VALUES (
                :listing_id,
                :image_path,
                :is_primary
            )
        ");

        return $stmt->execute([
            ':listing_id' => $listingId,
            ':image_path' => $imagePath,
            ':is_primary' => $isPrimary ? 1 : 0,
        ]);
    }

    /**
     * Create listing + images in one transaction.
     */
    public function createWithImages(array $data, array $imagePaths = []): int|false
    {
        try {
            $this->db->beginTransaction();

            $listingId = $this->create($data);
            if (!$listingId) {
                $this->db->rollBack();
                return false;
            }

            foreach ($imagePaths as $index => $path) {
                $saved = $this->addImage($listingId, $path, $index === 0);
                if (!$saved) {
                    $this->db->rollBack();
                    return false;
                }
            }

            $this->db->commit();
            return $listingId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Fetch all advertisements belonging to a specific user.
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                l.*,
                r.name AS region_name,
                c.name AS comuna_name,
                (
                    SELECT li.image_path
                    FROM listing_images li
                    WHERE li.listing_id = l.id
                    ORDER BY li.is_primary DESC, li.id ASC
                    LIMIT 1
                ) AS primary_image
            FROM listings l
            LEFT JOIN regions r ON l.region_id = r.id
            LEFT JOIN comunas c ON l.comuna_id = c.id
            WHERE l.user_id = :user_id
            ORDER BY l.created_at DESC
        ");

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
