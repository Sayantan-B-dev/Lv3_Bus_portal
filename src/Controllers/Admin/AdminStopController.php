<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Stop;
use App\Models\City;
use App\Services\OverpassService;
use App\Services\CityResolverService;
use App\Core\Response;
use App\Core\Session;

class AdminStopController
{
    public function index(array $params = []): void
    {
        AdminMiddleware::handle();
        $cityId    = (int)($_GET['city_id'] ?? Session::get('city_id', 1));
        $stops     = (new Stop())->allByCity($cityId);
        $cities    = (new City())->allActive();
        $city      = (new City())->findById($cityId);
        $csrf      = CsrfMiddleware::generateToken();
        $pageTitle = 'Manage Stops';
        Response::view('admin/stops/index', compact('stops', 'cities', 'city', 'csrf', 'pageTitle'));
    }

    public function store(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();
        $data = [
            'city_id'     => (int)$_POST['city_id'],
            'stop_name'   => trim($_POST['stop_name'] ?? ''),
            'stop_code'   => trim($_POST['stop_code'] ?? '') ?: null,
            'latitude'    => $_POST['latitude']  ? (float)$_POST['latitude']  : null,
            'longitude'   => $_POST['longitude'] ? (float)$_POST['longitude'] : null,
            'landmark'    => trim($_POST['landmark'] ?? '') ?: null,
            'zone'        => trim($_POST['zone'] ?? '') ?: null,
            'is_terminal' => (int)($_POST['is_terminal'] ?? 0),
        ];
        if (!$data['stop_name']) {
            Session::flash('error', 'Stop name is required.');
            Response::redirect(APP_URL . '/admin/stops?city_id=' . $data['city_id']);
        }
        (new Stop())->create($data);
        Session::flash('success', 'Stop "' . $data['stop_name'] . '" added.');
        Response::redirect(APP_URL . '/admin/stops?city_id=' . $data['city_id']);
    }

    public function update(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();
        $id = (int)$params['id'];
        $data = [
            'stop_name'   => trim($_POST['stop_name'] ?? ''),
            'stop_code'   => trim($_POST['stop_code'] ?? '') ?: null,
            'latitude'    => $_POST['latitude']  ? (float)$_POST['latitude']  : null,
            'longitude'   => $_POST['longitude'] ? (float)$_POST['longitude'] : null,
            'landmark'    => trim($_POST['landmark'] ?? '') ?: null,
            'zone'        => trim($_POST['zone'] ?? '') ?: null,
            'is_terminal' => (int)($_POST['is_terminal'] ?? 0),
        ];
        
        $stopModel = new Stop();
        $stop = $stopModel->findById($id);
        if (!$stop) { Response::error('Stop not found', 404); }

        $stopModel->update($id, $data);
        Session::flash('success', 'Stop "' . $data['stop_name'] . '" updated.');
        Response::redirect(APP_URL . '/admin/stops?city_id=' . $stop['city_id']);
    }

    public function destroy(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();
        $id = (int)$params['id'];
        $stopModel = new Stop();
        $stop = $stopModel->findById($id);
        if (!$stop) { Response::error('Stop not found', 404); }

        $stopModel->delete($id);
        Session::flash('success', 'Stop deleted.');
        Response::redirect(APP_URL . '/admin/stops?city_id=' . $stop['city_id']);
    }

    public function importFromOsm(array $params = []): void
    {
        AdminMiddleware::handle();
        $cityId   = (int)($_POST['city_id'] ?? 1);
        $city     = (new City())->findById($cityId);
        if (!$city) { Response::error('City not found', 404); }

        $resolver = new CityResolverService();
        $bbox     = $resolver->getBoundingBox((float)$city['center_lat'], (float)$city['center_lng'], 15);
        $count    = (new OverpassService())->importStopsForCity($cityId, $bbox);

        Session::flash('success', "Imported {$count} stops from OpenStreetMap for {$city['city_name']}.");
        Response::redirect(APP_URL . '/admin/stops?city_id=' . $cityId);
    }
}
