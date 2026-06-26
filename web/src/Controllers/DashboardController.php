<?php

namespace Eco\Controllers;

use Eco\Models\Listing;

class DashboardController
{
    public function getUserListings(int $userId): array
    {
        $listingModel = new Listing();
        return $listingModel->getByUser($userId);
    }
}
