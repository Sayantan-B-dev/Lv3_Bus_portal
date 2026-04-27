<?php
/**
 * views/layout/header.php
 */
use App\Middleware\CsrfMiddleware;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Universal City Bus Route Portal — Real-time routes, stops, and journey planning.">
    <link rel="icon" type="image/svg+xml" href="<?= APP_URL ?>/public/assets/img/logo.svg">
    <title><?= htmlspecialchars($pageTitle ?? 'Bus Route Portal') ?> — City Route Information Portal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="<?= APP_URL ?>/public/assets/css/main.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/map.css" rel="stylesheet">

    <script>
        window.APP_URL = "<?= APP_URL ?>";
        window.CSRF_TOKEN = "<?= \App\Middleware\CsrfMiddleware::generateToken() ?>";
    </script>
</head>
<body>
    <!-- CURSOR -->
    <div id="cursor"></div>
    <div id="cursor-ring"></div>

    <!-- MOBILE NAV -->
    <div class="mob-nav" id="mobNav">
        <a href="<?= APP_URL ?>/" onclick="closeMob()">Home</a>
        <a href="<?= APP_URL ?>/routes" onclick="closeMob()">Routes</a>
        <a href="<?= APP_URL ?>/planner" onclick="closeMob()">Planner</a>
        <a href="<?= APP_URL ?>/cities" onclick="closeMob()">Cities</a>
        <?php if (\App\Core\Session::isLoggedIn()): $u = \App\Core\Session::getUser(); ?>
            <?php if (in_array($u['role'] ?? '', ['admin', 'super_admin'], true)): ?>
                <a href="<?= APP_URL ?>/admin" onclick="closeMob()">Admin</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?= APP_URL ?>/auth/login" onclick="closeMob()">Sign In</a>
        <?php endif; ?>
    </div>

    <!-- NAV -->
    <nav>
        <a href="<?= APP_URL ?>/" class="n-logo">
            <div class="n-icon"><?= strtoupper(substr($city['city_name'] ?? 'BUS', 0, 3)) ?></div>
            <span>ROUTES</span>
            <span class="n-badge"><?= htmlspecialchars($city['city_name'] ?? 'City') ?></span>
        </a>

        <div class="n-links">
            <a href="<?= APP_URL ?>/" class="<?= ($view ?? '') == 'home/index' ? 'act' : '' ?>">Home</a>
            <a href="<?= APP_URL ?>/routes" class="<?= ($view ?? '') == 'routes/list' ? 'act' : '' ?>">Routes</a>
            <a href="<?= APP_URL ?>/planner" class="<?= ($view ?? '') == 'planner/index' ? 'act' : '' ?>">Planner</a>
            <a href="<?= APP_URL ?>/cities" class="<?= ($view ?? '') == 'cities/index' ? 'act' : '' ?>">Cities</a>
        </div>

        <div class="n-cta">
            <?php if (\App\Core\Session::isLoggedIn()): $u = \App\Core\Session::getUser(); ?>
                <?php if (in_array($u['role'] ?? '', ['admin', 'super_admin'], true)): ?>
                    <a href="<?= APP_URL ?>/admin" class="btn-g">Dashboard</a>
                <?php endif; ?>
                <form action="<?= APP_URL ?>/auth/logout" method="POST" style="display:inline;">
                    <button type="submit" class="btn-o">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?= APP_URL ?>/auth/login" class="btn-g">Sign In</a>
                <a href="<?= APP_URL ?>/auth/adminLogin" class="btn-o">Admin Panel →</a>
            <?php endif; ?>

            <button class="ham" id="hamBtn" onclick="toggleMob()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main>
