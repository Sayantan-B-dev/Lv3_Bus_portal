<?php
/**
 * public/index.php
 * Front controller — all web requests route through here.
 * Apache rewrites any request that isn't a real file to this file.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\RouteController;
use App\Controllers\SearchController;
use App\Controllers\PlannerController;
use App\Controllers\CityController;
use App\Controllers\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\AdminRouteController;
use App\Controllers\Admin\AdminStopController;
use App\Controllers\Admin\AdminFareController;
use App\Controllers\Admin\AdminCityController;

$router = new Router();

// ─── Public Routes ────────────────────────────────────────────────────────────
$router->get('/',                     [HomeController::class,     'index']);
$router->get('/routes',               [RouteController::class,    'list']);
$router->get('/routes/{id}',          [RouteController::class,    'detail']);
$router->get('/search',               [SearchController::class,   'results']);
$router->get('/planner',              [PlannerController::class,  'index']);
$router->post('/planner/find',        [PlannerController::class,  'find']);
$router->get('/cities',               [CityController::class,     'index']);
$router->post('/cities/switch',       [CityController::class,     'switchCity']);

// ─── Auth Routes ──────────────────────────────────────────────────────────────
$router->get('/auth/login',           [AuthController::class, 'login']);
$router->get('/auth/google',          [AuthController::class, 'googleRedirect']);
$router->get('/auth/google/callback', [AuthController::class, 'googleCallback']);
$router->post('/auth/logout',         [AuthController::class, 'logout']);

// ─── Admin Routes ─────────────────────────────────────────────────────────────
$router->get('/admin',                       [DashboardController::class,   'index']);
$router->get('/admin/routes',                [AdminRouteController::class,  'index']);
$router->get('/admin/routes/create',         [AdminRouteController::class,  'create']);
$router->post('/admin/routes',               [AdminRouteController::class,  'store']);
$router->get('/admin/routes/{id}/edit',      [AdminRouteController::class,  'edit']);
$router->post('/admin/routes/{id}',          [AdminRouteController::class,  'update']);
$router->post('/admin/routes/{id}/delete',   [AdminRouteController::class,  'destroy']);
$router->get('/admin/stops',                 [AdminStopController::class,   'index']);
$router->post('/admin/stops',                [AdminStopController::class,   'store']);
$router->post('/admin/stops/import-osm',     [AdminStopController::class,   'importFromOsm']);
$router->get('/admin/fares/{routeId}',       [AdminFareController::class,   'index']);
$router->post('/admin/fares',                [AdminFareController::class,   'store']);
$router->post('/admin/fares/{id}/delete',    [AdminFareController::class,   'destroy']);
$router->get('/admin/cities',                [AdminCityController::class,   'index']);
$router->post('/admin/cities',               [AdminCityController::class,   'store']);

// ─── Dispatch ─────────────────────────────────────────────────────────────────
$router->dispatch();
