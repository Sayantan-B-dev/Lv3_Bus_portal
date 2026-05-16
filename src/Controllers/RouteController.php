<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Route;
use App\Models\City;
use App\Core\Response;

class RouteController
{
    public function list(array $params = []): void
    {
        $cityId     = (int)($_GET['city_id'] ?? $_SESSION['city_id'] ?? 1);
        $type       = $_GET['type'] ?? '';
        $page       = max(1, (int)($_GET['page'] ?? 1));
        $perPage    = 20;

        $routeModel = new Route();
        $cityModel  = new City();

        $filters    = $type ? ['type' => $type] : [];
        $routes     = $routeModel->allByCity($cityId, $filters, $page, $perPage);
        $total      = $routeModel->countByCity($cityId, $filters);
        $city       = $cityModel->findById($cityId);
        $cities     = $cityModel->allActive();
        $pageTitle  = 'All Routes — ' . ($city['city_name'] ?? 'YatraPath');
        $totalPages = (int)ceil($total / $perPage);

        Response::view('routes/list', compact('routes', 'city', 'cities', 'type', 'page', 'totalPages', 'total', 'pageTitle'));
    }

    public function detail(array $params = []): void
    {
        $id         = (int)($params['id'] ?? 0);
        $routeModel = new Route();
        $route      = $routeModel->findWithStops($id);

        if (!$route) {
            http_response_code(404);
            Response::view('errors/404', ['pageTitle' => '404 — Route Not Found']);
            return;
        }

        $cityModel  = new City();
        $city       = $cityModel->findById((int)$route['city_id']);
        $cities     = $cityModel->allActive();
        $pageTitle  = 'Route ' . $route['route_number'] . ' — ' . $route['source'] . ' → ' . $route['destination'];

        Response::view('routes/detail', compact('route', 'city', 'cities', 'pageTitle'));
    }
}
