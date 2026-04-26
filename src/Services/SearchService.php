<?php
declare(strict_types=1);
namespace App\Services;
use App\Models\Route;
use App\Models\Stop;

class SearchService
{
    private Route $routeModel;
    private Stop $stopModel;

    public function __construct()
    {
        $this->routeModel = new Route();
        $this->stopModel  = new Stop();
    }

    /**
     * Unified search: returns routes and stops matching query for a city.
     */
    public function search(string $query, int $cityId): array
    {
        $query = trim($query);
        if (strlen($query) < 2) return ['routes' => [], 'stops' => []];

        $routes = $this->routeModel->search($query, $cityId);
        $stops  = $this->stopModel->autocomplete($query, $cityId, 10);

        return ['routes' => $routes, 'stops' => $stops, 'query' => $query, 'city_id' => $cityId];
    }
}
