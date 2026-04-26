<?php
/**
 * api/index.php
 * REST API entry point — all /api/* requests route here.
 * Returns JSON in the SRS 6.4 format.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

use App\Core\Response;
use App\Models\City;
use App\Models\Route;
use App\Models\Stop;
use App\Services\SearchService;
use App\Services\DijkstraService;
use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimitMiddleware;

// ─── Parse URI ───────────────────────────────────────────────────────────────
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base   = parse_url(APP_URL, PHP_URL_PATH) ?? '';
$uri    = '/' . ltrim(substr($uri, strlen($base)), '/');
$method = strtoupper($_SERVER['REQUEST_METHOD']);

// Strip /api prefix
$uri = preg_replace('#^/api#', '', $uri);
$uri = rtrim($uri, '/') ?: '/';

// ─── Rate limit public endpoints ─────────────────────────────────────────────
RateLimitMiddleware::check('api', 120, 60);

// ─── Route matching ──────────────────────────────────────────────────────────
$cityModel  = new City();
$routeModel = new Route();
$stopModel  = new Stop();

// GET /cities
if ($method === 'GET' && $uri === '/cities') {
    $cities = $cityModel->allActive();
    Response::json($cities);
}

// GET /routes?city_id=&type=&page=
if ($method === 'GET' && $uri === '/routes') {
    $cityId  = (int)($_GET['city_id'] ?? 1);
    $filters = [];
    if (!empty($_GET['type'])) $filters['type'] = $_GET['type'];
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = (int)($_GET['per_page'] ?? 20);
    $routes  = $routeModel->allByCity($cityId, $filters, $page, $perPage);
    $total   = $routeModel->countByCity($cityId, $filters);
    $city    = $cityModel->findById($cityId);
    Response::json($routes, 200, [
        'page' => $page, 'per_page' => $perPage,
        'total' => $total, 'city' => $city['city_name'] ?? '',
    ]);
}

// GET /routes/{id}
if ($method === 'GET' && preg_match('#^/routes/(\d+)$#', $uri, $m)) {
    $route = $routeModel->findWithStops((int)$m[1]);
    if (!$route) Response::error('Route not found', 404);
    Response::json($route);
}

// GET /search?q=&city_id=
if ($method === 'GET' && $uri === '/search') {
    $q      = trim($_GET['q'] ?? '');
    $cityId = (int)($_GET['city_id'] ?? 1);
    if (strlen($q) < 2) Response::error('Query must be at least 2 characters.', 422);
    $results = (new SearchService())->search($q, $cityId);
    Response::json($results);
}

// GET /stops?city_id=
if ($method === 'GET' && $uri === '/stops') {
    $cityId = (int)($_GET['city_id'] ?? 1);
    $stops  = $stopModel->allByCity($cityId);
    Response::json($stops);
}

// GET /stops/nearby?lat=&lng=&radius=&city_id=
if ($method === 'GET' && $uri === '/stops/nearby') {
    $lat    = (float)($_GET['lat']    ?? 0);
    $lng    = (float)($_GET['lng']    ?? 0);
    $radius = (float)($_GET['radius'] ?? 1.0);
    $cityId = (int)($_GET['city_id']  ?? 0);
    if (!$lat || !$lng) Response::error('lat and lng are required.', 422);
    $stops = $stopModel->nearby($lat, $lng, $radius, $cityId);
    Response::json($stops);
}

// GET /stops/{id}
if ($method === 'GET' && preg_match('#^/stops/(\d+)$#', $uri, $m)) {
    $stop = $stopModel->findWithRoutes((int)$m[1]);
    if (!$stop) Response::error('Stop not found', 404);
    Response::json($stop);
}

// POST /planner
if ($method === 'POST' && $uri === '/planner') {
    $body     = json_decode(file_get_contents('php://input'), true) ?? [];
    $cityId   = (int)($body['city_id']      ?? 1);
    $fromStop = (int)($body['from_stop_id'] ?? 0);
    $toStop   = (int)($body['to_stop_id']   ?? 0);

    if (!$fromStop || !$toStop) Response::error('from_stop_id and to_stop_id required.', 422);

    $result = (new DijkstraService())->findShortestPath($cityId, $fromStop, $toStop);
    if (!$result) Response::error('No route found between these stops.', 404);
    Response::json($result);
}

// POST /routes (JWT protected)
if ($method === 'POST' && $uri === '/routes') {
    $user  = AuthMiddleware::handle();
    $body  = json_decode(file_get_contents('php://input'), true) ?? [];
    $id    = $routeModel->create($body);
    $route = $routeModel->findById($id);
    Response::json($route, 201);
}

// PUT /routes/{id} (JWT protected)
if ($method === 'PUT' && preg_match('#^/routes/(\d+)$#', $uri, $m)) {
    AuthMiddleware::handle();
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $routeModel->update((int)$m[1], $body);
    Response::json($routeModel->findById((int)$m[1]));
}

// DELETE /routes/{id} (JWT protected)
if ($method === 'DELETE' && preg_match('#^/routes/(\d+)$#', $uri, $m)) {
    AuthMiddleware::handle();
    $routeModel->softDelete((int)$m[1]);
    Response::json(['deleted' => true]);
}

// ─── 404 fallback ────────────────────────────────────────────────────────────
Response::error('API endpoint not found.', 404);
