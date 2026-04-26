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
    <link rel="icon" type="image/svg+xml" href="<?= APP_URL ?>/assets/img/logo.svg">
    <title><?= htmlspecialchars($pageTitle ?? 'Bus Route Portal') ?> — DTC Route Information Portal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/map.css" rel="stylesheet">

    <script>
        const APP_URL = "<?= APP_URL ?>";
        const CSRF_TOKEN = "<?= CsrfMiddleware::generateToken() ?>";
    </script>
</head>
<body>
    <!-- Ambient Glow -->
    <div class="ambient amb1"></div>
    <div class="ambient amb2"></div>
    <div class="ambient amb3"></div>

    <!-- NAV -->
    <nav>
        <a href="<?= APP_URL ?>/" class="nav-logo">
            <div class="logo-badge"><?= strtoupper(substr($city['city_name'] ?? 'DTC', 0, 3)) ?></div>
            <div>
                <span class="logo-text">Route Portal</span>
                <span class="logo-sub"><?= htmlspecialchars($city['city_name'] ?? 'Delhi') ?> Transport System</span>
            </div>
        </a>

        <div class="nav-links">
            <a href="<?= APP_URL ?>/" class="<?= ($view ?? '') == 'home/index' ? 'active' : '' ?>">Home</a>
            <a href="<?= APP_URL ?>/routes" class="<?= ($view ?? '') == 'routes/list' ? 'active' : '' ?>">Routes</a>
            <a href="<?= APP_URL ?>/planner" class="<?= ($view ?? '') == 'planner/index' ? 'active' : '' ?>">Planner</a>
            <a href="<?= APP_URL ?>/cities" class="<?= ($view ?? '') == 'cities/index' ? 'active' : '' ?>">Cities</a>
        </div>

        <div class="nav-right">
            <?php if (\App\Core\Session::isLoggedIn()): $u = \App\Core\Session::getUser(); ?>
                <?php if (in_array($u['role'] ?? '', ['admin', 'super_admin'], true)): ?>
                    <a href="<?= APP_URL ?>/admin" class="btn-ghost">Dashboard</a>
                <?php endif; ?>
                <div class="user-pill d-none d-md-flex" style="display:flex; align-items:center; gap:8px; margin-right:10px; background:rgba(255,255,255,0.08); padding:5px 14px; border-radius:20px; border:1px solid var(--border); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <img src="<?= $u['avatar_url'] ?? '' ?>" width="22" height="22" style="border-radius:50%; border: 1px solid rgba(255,255,255,0.2);">
                    <span style="font-size:12px; font-weight: 500; color:var(--text)"><?= htmlspecialchars($u['name'] ?? 'Admin') ?></span>
                </div>
                <form action="<?= APP_URL ?>/auth/logout" method="POST" style="display:inline;">
                    <button type="submit" class="btn-primary" style="padding:7px 18px; font-size:12px">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?= APP_URL ?>/auth/login" class="btn-ghost">Sign In</a>
                <a href="<?= APP_URL ?>/auth/adminLogin" class="btn-primary">Admin Access</a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
