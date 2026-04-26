<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\City;
use App\Models\Route;
use App\Core\Response;

class HomeController
{
    public function index(array $params = []): void
    {
        $cityModel  = new City();
        $routeModel = new Route();

        // Resolve active city from query param, session, or default
        $cityId = (int)($_GET['city_id'] ?? $_SESSION['city_id'] ?? 0);
        $cities = $cityModel->allActive();

        $city = $cityId > 0 ? $cityModel->findById($cityId) : null;
        if (!$city) {
            $city   = $cityModel->getDefault();
            $cityId = (int)($city['id'] ?? 1);
        }
        $_SESSION['city_id'] = $cityId;

        $routes     = $routeModel->allByCity($cityId, [], 1, 20);
        $stats      = $cityModel->getStats($cityId);
        $globalStats= $cityModel->globalStats();

        $pageTitle  = $city ? $city['city_name'] . ' Bus Routes' : 'Bus Route Portal';

        Response::view('home/index', compact('cities', 'city', 'routes', 'stats', 'globalStats', 'pageTitle'));
    }
}
