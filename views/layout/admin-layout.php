<?php
/**
 * views/layout/admin-layout.php
 */
use App\Core\Session;
$user = $user ?? Session::getUser() ?? [];
$isRoutesView = strpos(($view ?? ''), 'admin/routes') === 0;
$isStopsView = strpos(($view ?? ''), 'admin/stops') === 0;
$isCitiesView = strpos(($view ?? ''), 'admin/cities') === 0;
?>
<!DOCTYPE html>
<html lang="en">
<!-- views/layout/admin-layout.php -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> — YatraPath</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300&display=swap" rel="stylesheet">
    
    <?php 
    $baseUrl = defined('APP_URL') ? APP_URL : ''; // fallback
    $cssFile = $baseUrl . '/public/assets/css/admin.css?v=' . filemtime(__DIR__ . '/../../public/assets/css/admin.css');
    ?>
    <link href="<?= $cssFile ?>" rel="stylesheet">
    
    <!-- Admin Tab Icon (Favicon) -->
    <link rel="icon" type="image/svg+xml" href="<?= APP_URL ?>/public/assets/img/admin-favicon.svg">
</head>
<body>
    <!-- CURSOR -->
    <div id="cursor"></div>
    <div id="cursor-ring"></div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-badge">ADM</div>
                <h1 class="sidebar-brand-title">ROUTE PORTAL</h1>
                <p class="sidebar-brand-subtitle">Management Hub</p>
            </div>
            
            <nav class="nav admin-sidebar-nav">
                <a class="nav-link <?= ($view ?? '') == 'admin/dashboard' ? 'active' : '' ?>" href="<?= APP_URL ?>/admin">Dashboard</a>
                <a class="nav-link <?= $isRoutesView ? 'active' : '' ?>" href="<?= APP_URL ?>/admin/routes">Routes</a>
                <a class="nav-link <?= $isStopsView ? 'active' : '' ?>" href="<?= APP_URL ?>/admin/stops">Stops</a>
                <a class="nav-link <?= $isCitiesView ? 'active' : '' ?>" href="<?= APP_URL ?>/admin/cities">Cities</a>
                <a class="nav-link" href="<?= APP_URL ?>/" target="_blank">View Portal</a>
                <div class="sidebar-logout-wrap">
                    <hr class="admin-divider">
                    <a class="nav-link sidebar-logout-link" href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit()">Logout</a>
                </div>
            </nav>
            
            <form id="logoutForm" action="<?= APP_URL ?>/auth/logout" method="POST" class="is-hidden"></form>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <header class="admin-header">
                <h2 class="admin-header-title"><?= $pageTitle ?? 'Dashboard' ?></h2>
                <div class="user-info admin-user-info">
                    <div class="admin-user-text">
                        <div class="admin-user-name"><?= htmlspecialchars($user['name'] ?? 'Administrator') ?></div>
                        <span class="admin-user-role"><?= ucwords(str_replace('_', ' ', $user['role'] ?? 'Admin')) ?></span>
                    </div>
                    <img src="<?= htmlspecialchars($user['avatar_url'] ?? $user['profile_image'] ?? APP_URL . '/public/assets/img/default-avatar.png') ?>" 
                         class="admin-user-avatar" 
                         width="40" 
                         height="40" 
                         alt="Avatar">
                </div>
            </header>

            <main class="admin-content">
                <?php if ($success = Session::getFlash('success')): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error = Session::getFlash('error')): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="<?= APP_URL ?>/public/assets/js/admin.js"></script>
</body>
</html>
