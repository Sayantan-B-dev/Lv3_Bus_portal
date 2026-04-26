<?php
/**
 * views/home/index.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-label">v1.0 — Live in <?= htmlspecialchars($city['city_name'] ?? 'Delhi') ?></div>
        <h1>
            Find your <span class="accent">bus route</span><br>
            across <span class="dim"><?= htmlspecialchars($city['city_name'] ?? 'Delhi') ?></span> instantly.
        </h1>
        <p class="hero-desc">
            Search hundreds of bus routes by number, source, or destination.
            Get real-time stop sequences, fare slabs, timings, and map views — all in one place.
        </p>

        <form action="<?= APP_URL ?>/search" method="GET" class="search-wrap">
            <input type="hidden" name="city_id" value="<?= $city['id'] ?? 1 ?>">
            <div class="search-icon"></div>
            <input class="search-input" type="text" name="q" placeholder="Search by route number, stop or area…" id="searchInput" required autocomplete="off">
            <div class="search-divider"></div>
            <select class="search-type" name="type">
                <option value="">All Types</option>
                <option value="AC">AC</option>
                <option value="Express">Express</option>
                <option value="Normal">Normal</option>
                <option value="Night">Night</option>
            </select>
            <button type="submit" class="search-btn">Search Routes</button>
        </form>

        <div class="hero-tags">
            <span class="text-muted small" style="margin-right:8px; margin-top:8px">Popular:</span>
            <?php foreach (array_slice($routes ?? [], 0, 5) as $r): ?>
                <a href="<?= APP_URL ?>/routes/<?= $r['id'] ?>" class="tag"><?= htmlspecialchars($r['route_number']) ?></a>
            <?php endforeach; ?>
            <a href="<?= APP_URL ?>/planner" class="tag">Route Planner</a>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="container">
    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-num" data-target="<?= $stats['route_count'] ?? 0 ?>"><?= $stats['route_count'] ?? 0 ?></span>
            <span class="stat-label">Active Routes</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-target="<?= $stats['stop_count'] ?? 0 ?>"><?= $stats['stop_count'] ?? 0 ?></span>
            <span class="stat-label">Bus Stops</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-target="<?= $globalStats['total_cities'] ?? 1 ?>"><?= $globalStats['total_cities'] ?? 1 ?></span>
            <span class="stat-label">Cities Covered</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-target="<?= $stats['avg_frequency'] ?? 15 ?>m"><?= $stats['avg_frequency'] ?? 15 ?>m</span>
            <span class="stat-label">Avg Frequency</span>
        </div>
    </div>
</div>

<!-- ROUTE CARDS -->
<section class="section">
    <div class="container">
        <div class="section-header anim-1">
            <div>
                <div class="section-title">Popular Routes</div>
                <div class="section-sub">High-frequency routes across <?= htmlspecialchars($city['city_name'] ?? 'the city') ?></div>
            </div>
            <a href="<?= APP_URL ?>/routes" class="see-all">View all routes</a>
        </div>

        <div class="filter-bar anim-2">
            <button class="filter-chip active">All</button>
            <button class="filter-chip">AC</button>
            <button class="filter-chip">Express</button>
            <button class="filter-chip">Normal</button>
            <button class="filter-chip">Night</button>
        </div>

        <div class="routes-grid anim-3">
            <?php if (empty($routes)): ?>
                <div style="grid-column: span 3; text-align: center; padding: 60px 0;">
                    <p class="text-muted">No routes found for this city yet.</p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($routes, 0, 9) as $r): ?>
                    <a class="route-card" href="<?= APP_URL ?>/routes/<?= $r['id'] ?>">
                        <div class="route-card-top">
                            <span class="route-num"><?= htmlspecialchars($r['route_number']) ?></span>
                            <span class="route-badge badge-<?= strtolower($r['route_type']) ?>"><?= $r['route_type'] ?></span>
                        </div>
                        <div class="route-path">
                            <div class="route-from-label">From</div>
                            <div class="route-from"><?= htmlspecialchars($r['source']) ?></div>
                            <div class="route-connector">
                                <div class="route-line"></div>
                                <div class="route-arrow"></div>
                            </div>
                            <div class="route-to-label">To</div>
                            <div class="route-to"><?= htmlspecialchars($r['destination']) ?></div>
                        </div>
                        <div class="route-meta">
                            <div class="meta-item">
                                <span class="meta-val"><?= $r['distance_km'] ?? '??' ?> km</span>
                                <span class="meta-key">Distance</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-val"><?= $r['frequency_mins'] ?? '??' ?> min</span>
                                <span class="meta-key">Freq</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-val flex items-center gap-8"><span class="status-dot dot-green"></span>Active</span>
                                <span class="meta-key">Status</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ADMIN STRIP -->
<div class="container">
    <div class="admin-strip anim-5">
        <div class="admin-strip-text">
            <h3>Admin Dashboard</h3>
            <p>Manage routes, stops, fares, and users. Secure access via Google OAuth.</p>
        </div>
        <div class="admin-strip-btns">
            <a href="<?= APP_URL ?>/api" class="btn-ghost">View API Docs</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= APP_URL ?>/admin" class="btn-primary">Dashboard</a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/auth/login" class="btn-primary">Login with Google</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
