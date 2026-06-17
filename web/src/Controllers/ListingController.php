<?php

namespace Eco\Controllers;

use Eco\Core\Database;
use Exception;

class ListingController {

    /**
     * Publish a new classified advertisement (Free Tier initialization)
     */
    public function createListing(array $data, int $userId, string $accountType): array {
        $db = Database::getConnection();

        // 1. Sanitize input strings
        $title = trim(strip_tags($data['title'] ?? ''));
        $description = trim(strip_tags($data['description'] ?? ''));
        $price = filter_var($data['price'] ?? 0, FILTER_VALIDATE_FLOAT) ?: 0.00;
        $currency = strip_tags($data['currency'] ?? 'CLP'); // Defaulting to Chilean Pesos
        $categoryId = (int)($data['category_id'] ?? 0);
        $regionId = (int)($data['region_id'] ?? 0);
        $comunaId = (int)($data['comuna_id'] ?? 0);

        // 2. Fundamental Validations
        if (empty($title) || empty($description) || !$categoryId || !$regionId || !$comunaId) {
            return ['success' => false, 'message' => 'Por favor, complete todos los campos obligatorios y seleccione la ubicación exacta.'];
        }

        // 3. Generate a URL-friendly Slug (e.g., "Notebook Pro i7" -> "notebook-pro-i7")
        $slug = $this->generateSlug($title);

        // 4. Set the expiration date logic (Free for 30 days)
        $daysActive = 30;
        $expiresAt = date('Y-m-d H:i:s', strtotime("+$daysActive days"));

        try {
            $stmt = $db->prepare("
                INSERT INTO listings (
                    user_id, category_id, region_id, comuna_id, 
                    title, slug, description, price, currency, 
                    ad_type_origin, status, is_featured, expires_at
                ) VALUES (
                    :user_id, :category_id, :region_id, :comuna_id, 
                    :title, :slug, :description, :price, :currency, 
                    :ad_type_origin, 'active', 0, :expires_at
                )
            ");

            $stmt->execute([
                ':user_id'        => $userId,
                ':category_id'    => $categoryId,
                ':region_id'      => $regionId,
                ':comuna_id'      => $comunaId,
                ':title'          => $title,
                ':slug'           => $slug,
                ':description'    => $description,
                ':price'          => $price,
                ':currency'       => $currency,
                ':ad_type_origin' => $accountType, // Automatically tracked from the logged-in user profile ('private' / 'company')
                ':expires_at'     => $expiresAt
            ]);

            $listingId = $db->lastInsertId();

            return [
                'success' => true, 
                'listing_id' => $listingId, 
                'message' => '¡Tu aviso ha sido publicado con éxito de forma gratuita!'
            ];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al guardar la publicación: ' . $e->getMessage()];
        }
    }

    /**
     * Helper method to convert text titles into clean database slugs
     */
    private function generateSlug(string $text): string {
        // Replace non-letter or digits by hyphen
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Trim hyphens
        $text = trim($text, '-');
        // Remove duplicate hyphens
        $text = preg_replace('~-+~', '-', $text);
        // Lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'aviso-' . time();
        }

        // Append short unique token to prevent namespace collisions in URLs
        return $text . '-' . bin2hex(random_bytes(3));
    }
}