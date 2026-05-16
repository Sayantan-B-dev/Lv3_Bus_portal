<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\SearchService;
use App\Models\City;
use App\Core\Response;

class SearchController
{
    public function results(array $params = []): void
    {
        $query      = trim($_GET['q'] ?? '');
        $cityId     = (int)($_GET['city_id'] ?? $_SESSION['city_id'] ?? 1);

        $cityModel  = new City();
        $city       = $cityModel->findById($cityId);
        $cities     = $cityModel->allActive();

        $results    = [];
        if (strlen($query) >= 2) {
            $results = (new SearchService())->search($query, $cityId);
        }

        $pageTitle = 'Search: ' . htmlspecialchars($query) . ' — YatraPath';
        Response::view('search/results', compact('query', 'results', 'city', 'cities', 'pageTitle'));
    }
}
