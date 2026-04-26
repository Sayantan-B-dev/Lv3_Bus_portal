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
    <meta name="description" content="Universal City Bus Route Portal — Real-time routes, stops, and journey planning for Kolkata and major cities.">
    <link rel="icon" type="image/svg+xml" href="<?= APP_URL ?>/assets/img/logo.svg">
    <title><?= htmlspecialchars($pageTitle ?? 'Bus Route Portal') ?> — Universal City Bus Portal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Noto+Sans:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/map.css" rel="stylesheet">

    <script>
        const APP_URL = "<?= APP_URL ?>";
        const CSRF_TOKEN = "<?= CsrfMiddleware::generateToken() ?>";
    </script>
</head>
<body>
    <header class="site-header">
        <div class="container-custom header-inner">
            <div class="d-flex align-items-center gap-4">
                <a href="<?= APP_URL ?>/" class="logo">
                    <span class="logo-text">BUS PORTAL</span>
                </a>

                <select class="city-selector" id="globalCitySelector">
                    <?php foreach ($cities ?? [] as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($city['id'] ?? 0) == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['city_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <nav class="nav-links">
                <a href="<?= APP_URL ?>/" class="nav-link <?= ($view ?? '') == 'home/index' ? 'active' : '' ?>">Home</a>
                <a href="<?= APP_URL ?>/routes" class="nav-link <?= ($view ?? '') == 'routes/list' ? 'active' : '' ?>">Routes</a>
                <a href="<?= APP_URL ?>/planner" class="nav-link <?= ($view ?? '') == 'planner/index' ? 'active' : '' ?>">Planner</a>
                <a href="<?= APP_URL ?>/admin" class="nav-link">Admin</a>
            </nav>
        </div>
    </header>
    <main>
