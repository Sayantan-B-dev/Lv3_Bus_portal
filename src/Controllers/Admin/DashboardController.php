<?php
declare(strict_types=1);
namespace App\Controllers\Admin;
use App\Middleware\AdminMiddleware;
use App\Models\City;
use App\Models\Route;
use App\Models\User;
use App\Core\Response;
use App\Core\Session;

class DashboardController
{
    public function index(array $params = []): void
    {
        $user      = AdminMiddleware::handle();
        $cityModel = new City();
        $global    = $cityModel->globalStats();
        $cities    = $cityModel->allActive();
        $users     = (new User())->all();
        $pageTitle = 'Admin Dashboard';
        Response::view('admin/dashboard', compact('user', 'global', 'cities', 'users', 'pageTitle'));
    }
}
