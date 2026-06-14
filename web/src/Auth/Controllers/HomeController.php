<?php

namespace Eco\Controllers;

use Eco\Core\Database;
use PDO;

class HomeController {

    /**
     * Fetches an balanced mix of Private and Business listings for the landing feed
     */
    public function getHomepageFeed(int $limitPerSide = 12): array {
        $db = Database::getConnection();

        // High-performance query to grab recent active ads from both branches
        $query = "
            (
                SELECT l.*, c.name as comuna_name, img.image_path 
                FROM listings l
                LEFT JOIN chile_comunas c ON l.comuna_id = c.id
                LEFT JOIN listing_images img ON l.id = img.listing_id AND img.is_primary = 1
                WHERE l.status = 'active' AND l.ad_type_origin = 'company'
                ORDER BY l.created_at DESC 
                LIMIT :limit_company
            )
            UNION ALL
            (
                SELECT l.*, c.name as comuna_name, img.image_path 
                FROM listings l
                LEFT JOIN chile_comunas c ON l.comuna_id = c.id
                LEFT JOIN listing_images img ON l.id = img.listing_id AND img.is_primary = 1
                WHERE l.status = 'active' AND l.ad_type_origin = 'private'
                ORDER BY l.created_at DESC 
                LIMIT :limit_private
            )
            ORDER BY created_at DESC
        ";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit_company', $limitPerSide, PDO::PARAM_INT);
        $stmt->bindValue(':limit_private', $limitPerSide, PDO::PARAM_INT);
        $stmt->execute();
        
        $allListings = $stmt->fetchAll();

        // Optional: Interleave them explicitly [Company, Private, Company, Private] 
        // to guarantee perfect layout real estate balance on screen
        $companies = array_filter($allListings, fn($item) => $item['ad_type_origin'] === 'company');
        $privates = array_filter($allListings, fn($item) => $item['ad_type_origin'] === 'private');

        $balancedFeed = [];
        while (!empty($companies) || !empty($privates)) {
            if ($companyItem = array_shift($companies)) {
                $balancedFeed[] = $companyItem;
            }
            if ($privateItem = array_shift($privates)) {
                $balancedFeed[] = $privateItem;
            }
        }

        return $balancedFeed;
    }
}