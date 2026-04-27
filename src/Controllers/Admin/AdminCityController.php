<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\City;
use App\Services\CityResolverService;
use App\Core\Response;
use App\Core\Session;

class AdminCityController
{
    public function index(array $params = []): void
    {
        AdminMiddleware::handle();
        $cities    = (new City())->allActive();
        $csrf      = CsrfMiddleware::generateToken();
        $pageTitle = 'Manage Cities';
        Response::view('admin/cities/index', compact('cities', 'csrf', 'pageTitle'));
    }

    public function store(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();

        $name    = trim($_POST['city_name'] ?? '');
        $country = trim($_POST['country_code'] ?? 'IN');

        if (!$name) {
            Session::flash('error', 'City name is required.');
            Response::redirect(APP_URL . '/admin/cities');
        }

        // Geocode the city via Nominatim
        $resolver = new CityResolverService();
        $geo      = $resolver->geocodeCity($name, $country);

        $data = [
            'city_name'       => $name,
            'state_region'    => trim($_POST['state_region'] ?? '') ?: null,
            'country'         => trim($_POST['country'] ?? 'India'),
            'country_code'    => $country,
            'currency'        => trim($_POST['currency'] ?? 'INR'),
            'timezone'        => trim($_POST['timezone'] ?? 'Asia/Kolkata'),
            'center_lat'      => $geo ? $geo['lat'] : (float)($_POST['center_lat'] ?? 0),
            'center_lng'      => $geo ? $geo['lng'] : (float)($_POST['center_lng'] ?? 0),
            'default_zoom'    => (int)($_POST['default_zoom'] ?? 12),
            'osm_relation_id' => $geo ? $geo['osm_id'] : null,
        ];

        $id = (new City())->create($data);
        Session::flash('success', "City '{$name}' added (ID: {$id}).");
        Response::redirect(APP_URL . '/admin/cities');
    }

    public function update(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();
        $id = (int)$params['id'];
        $data = [
            'city_name'       => trim($_POST['city_name'] ?? ''),
            'state_region'    => trim($_POST['state_region'] ?? '') ?: null,
            'country'         => trim($_POST['country'] ?? 'India'),
            'country_code'    => trim($_POST['country_code'] ?? 'IN'),
            'currency'        => trim($_POST['currency'] ?? 'INR'),
            'timezone'        => trim($_POST['timezone'] ?? 'Asia/Kolkata'),
            'center_lat'      => (float)($_POST['center_lat'] ?? 0),
            'center_lng'      => (float)($_POST['center_lng'] ?? 0),
            'default_zoom'    => (int)($_POST['default_zoom'] ?? 12),
            'osm_relation_id' => $_POST['osm_relation_id'] ? (int)$_POST['osm_relation_id'] : null,
            'is_active'       => (int)($_POST['is_active'] ?? 1),
        ];

        (new City())->update($id, $data);
        Session::flash('success', "City '{$data['city_name']}' updated.");
        Response::redirect(APP_URL . '/admin/cities');
    }

    public function destroy(array $params = []): void
    {
        AdminMiddleware::handle();
        CsrfMiddleware::validate();
        $id = (int)$params['id'];
        (new City())->delete($id);
        Session::flash('success', "City deleted.");
        Response::redirect(APP_URL . '/admin/cities');
    }
}
