<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\City;
use App\Services\CityResolverService;
use App\Core\Response;

class CityController
{
    public function index(array $params = []): void
    {
        $cities    = (new City())->allActive();
        $pageTitle = 'Browse Cities';
        Response::view('cities/index', compact('cities', 'pageTitle'));
    }

    public function switchCity(array $params = []): void
    {
        $cityId = (int)($_POST['city_id'] ?? $_GET['city_id'] ?? 0);
        if ($cityId > 0) {
            $_SESSION['city_id'] = $cityId;
        }
        // JSON response for AJAX city switch
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            $city = (new City())->findById($cityId);
            Response::json($city ?: []);
        }
        Response::redirect(APP_URL . '/?city_id=' . $cityId);
    }
}
