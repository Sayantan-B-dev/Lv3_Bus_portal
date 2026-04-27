<?php
require_once __DIR__ . '/../config/config.php';
use App\Controllers\Admin\DashboardController;

try {
    // Mock Session
    $_SESSION['admin_user'] = ['id' => 12, 'role' => 'admin'];
    $_SESSION['admin_token'] = 'dummy'; // This will fail JWT but we fixed requireAdmin to check DB

    // Note: Since JWT validation will fail, it will call findById(12)
    // We need to make sure User model works.

    $ctrl = new DashboardController();
    echo "Running DashboardController::index...\n";
    // We can't really run it because it calls Response::view which requires files and exits.
    // But we can check the data part.
    
    $cityModel = new \App\Models\City();
    $global    = $cityModel->globalStats();
    print_r($global);
    
    $cities    = $cityModel->allActive();
    echo "Cities count: " . count($cities) . "\n";
    
    $users     = (new \App\Models\User())->all();
    echo "Users count: " . count($users) . "\n";

    echo "All data fetched successfully.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
