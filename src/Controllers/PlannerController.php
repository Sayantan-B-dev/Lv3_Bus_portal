<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\DijkstraService;
use App\Models\City;
use App\Models\Stop;
use App\Core\Response;

class PlannerController
{
    public function index(array $params = []): void
    {
        $cityId    = (int)($_GET['city_id'] ?? $_SESSION['city_id'] ?? 1);
        $cityModel = new City();
        $city      = $cityModel->findById($cityId);
        $cities    = $cityModel->allActive();
        $stops     = (new Stop())->allByCity($cityId);
        $pageTitle = 'Journey Planner — ' . ($city['city_name'] ?? 'Bus Portal');
        Response::view('planner/index', compact('city', 'cities', 'stops', 'pageTitle'));
    }

    public function find(array $params = []): void
    {
        $body     = json_decode(file_get_contents('php://input'), true) ?? [];
        $cityId   = (int)($body['city_id']        ?? $_POST['city_id']        ?? 1);
        $fromStop = (int)($body['from_stop_id']   ?? $_POST['from_stop_id']   ?? 0);
        $toStop   = (int)($body['to_stop_id']     ?? $_POST['to_stop_id']     ?? 0);
        $passType = $body['passenger_type'] ?? $_POST['passenger_type'] ?? 'General';

        if (!$fromStop || !$toStop) {
            Response::error('from_stop_id and to_stop_id are required.', 422);
        }

        $result = (new DijkstraService())->findShortestPath($cityId, $fromStop, $toStop);
        if (!$result) {
            Response::error('No route found between the selected stops.', 404);
        }

        Response::json($result);
    }
}
