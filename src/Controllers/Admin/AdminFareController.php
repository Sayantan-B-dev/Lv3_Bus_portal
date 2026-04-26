<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Fare;
use App\Models\Route;
use App\Core\Response;
use App\Core\Session;

class AdminFareController
{
    public function index(array $params = []): void
    {
        AdminMiddleware::handle();
        $routeId   = (int)($params['routeId'] ?? 0);
        $route     = (new Route())->findById($routeId);
        if (!$route) { Response::error('Route not found', 404); }
        $fares     = (new Fare())->forRoute($routeId);
        $csrf      = CsrfMiddleware::generateToken();
        $pageTitle = 'Fares — Route ' . $route['route_number'];
        Response::view('admin/fares/index', compact('route', 'fares', 'csrf', 'pageTitle'));
    }

    public function store(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();
        $data = [
            'route_id'       => (int)$_POST['route_id'],
            'min_km'         => (float)$_POST['min_km'],
            'max_km'         => (float)$_POST['max_km'],
            'fare_amount'    => (float)$_POST['fare_amount'],
            'passenger_type' => $_POST['passenger_type'] ?? 'General',
        ];
        (new Fare())->create($data);
        Session::flash('success', 'Fare slab added.');
        Response::redirect(APP_URL . '/admin/fares/' . $data['route_id']);
    }

    public function destroy(array $params = []): void
    {
        AdminMiddleware::handle();
        $id = (int)($params['id'] ?? 0);
        (new Fare())->delete($id);
        Session::flash('success', 'Fare deleted.');
        $ref = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/admin/routes';
        Response::redirect($ref);
    }
}
