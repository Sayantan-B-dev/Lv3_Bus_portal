<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Route;
use App\Models\City;
use App\Core\Response;
use App\Core\Session;

class AdminRouteController
{
    public function index(array $params = []): void
    {
        $user      = AdminMiddleware::handle();
        $cityId    = (int)($_GET['city_id'] ?? Session::get('city_id', 1));
        $routes    = (new Route())->allByCity($cityId, [], 1, 100);
        $cities    = (new City())->allActive();
        $city      = (new City())->findById($cityId);
        $pageTitle = 'Manage Routes';
        Response::view('admin/routes/index', compact('routes', 'cities', 'city', 'user', 'pageTitle'));
    }

    public function create(array $params = []): void
    {
        AdminMiddleware::handle();
        $cities    = (new City())->allActive();
        $pageTitle = 'Add Route';
        $csrf      = CsrfMiddleware::generateToken();
        Response::view('admin/routes/create', compact('cities', 'pageTitle', 'csrf'));
    }

    public function store(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();

        $data = [
            'city_id'          => (int)$_POST['city_id'],
            'route_number'     => trim($_POST['route_number'] ?? ''),
            'source'           => trim($_POST['source'] ?? ''),
            'destination'      => trim($_POST['destination'] ?? ''),
            'route_type'       => $_POST['route_type'] ?? 'Normal',
            'frequency_mins'   => (int)($_POST['frequency_mins'] ?? 20),
            'first_bus_time'   => $_POST['first_bus_time'] ?? '06:00:00',
            'last_bus_time'    => $_POST['last_bus_time'] ?? '22:00:00',
            'total_distance_km'=> (float)($_POST['total_distance_km'] ?? 0),
            'description'      => trim($_POST['description'] ?? ''),
        ];

        if (!$data['route_number'] || !$data['source'] || !$data['destination']) {
            Session::flash('error', 'Route number, source, and destination are required.');
            Response::redirect(APP_URL . '/admin/routes/create');
        }

        $id = (new Route())->create($data);
        Session::flash('success', "Route #{$data['route_number']} created successfully.");
        Response::redirect(APP_URL . '/admin/routes');
    }

    public function edit(array $params = []): void
    {
        AdminMiddleware::handle();
        $id    = (int)($params['id'] ?? 0);
        $route = (new Route())->findWithStops($id);
        if (!$route) { http_response_code(404); echo 'Route not found'; exit; }
        $cities    = (new City())->allActive();
        $csrf      = CsrfMiddleware::generateToken();
        $pageTitle = 'Edit Route ' . $route['route_number'];
        Response::view('admin/routes/edit', compact('route', 'cities', 'csrf', 'pageTitle'));
    }

    public function update(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();
        $id   = (int)($params['id'] ?? 0);
        $data = [
            'route_number'      => trim($_POST['route_number'] ?? ''),
            'source'            => trim($_POST['source'] ?? ''),
            'destination'       => trim($_POST['destination'] ?? ''),
            'route_type'        => $_POST['route_type'] ?? 'Normal',
            'frequency_mins'    => (int)($_POST['frequency_mins'] ?? 20),
            'first_bus_time'    => $_POST['first_bus_time'] ?? '06:00:00',
            'last_bus_time'     => $_POST['last_bus_time'] ?? '22:00:00',
            'total_distance_km' => (float)($_POST['total_distance_km'] ?? 0),
            'description'       => trim($_POST['description'] ?? ''),
        ];
        (new Route())->update($id, $data);
        Session::flash('success', 'Route updated successfully.');
        Response::redirect(APP_URL . '/admin/routes');
    }

    public function destroy(array $params = []): void
    {
        AdminMiddleware::handle();
        $id = (int)($params['id'] ?? 0);
        (new Route())->softDelete($id);
        Session::flash('success', 'Route deleted.');
        Response::redirect(APP_URL . '/admin/routes');
    }
}
