<?php
/**
 * views/home/index.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-mesh"></div>
    <div class="hero-grid"></div>

    <div class="hero-inner">
        <div class="hero-pill"><span></span><?= htmlspecialchars($city['city_name'] ?? 'Delhi') ?> Transport — v1.0</div>
        <h1 class="hero-title">
            <span class="line1">EXPLORE</span>
            <span class="line2">ALL <span class="accent-word">BUS</span></span>
            <span class="line1">ROUTES.</span>
        </h1>
        <p class="hero-sub">
            Search routes by number, source, or destination. Stop sequences, fare slabs, timings and maps — built for
            <strong><?= htmlspecialchars($city['city_name'] ?? 'the city') ?></strong>.
        </p>

        <form action="<?= APP_URL ?>/search" method="GET" class="search-bar">
            <input type="hidden" name="city_id" value="<?= $city['id'] ?? 1 ?>">
            <span class="s-icon">⌕</span>
            <input class="s-input" type="text" name="q" placeholder="Route 401, Dwarka, Kashmere Gate…" id="searchInput" required autocomplete="off">
            <div class="s-sep"></div>
            <select class="s-select" name="type">
                <option value="">All Types</option>
                <option value="AC">AC</option>
                <option value="Express">Express</option>
                <option value="Normal">Normal</option>
                <option value="Night">Night</option>
            </select>
            <button type="submit" class="s-btn">Search</button>
        </form>

        <div class="hero-tags">
            <?php foreach (array_slice($routes ?? [], 0, 5) as $r): ?>
                <a href="<?= APP_URL ?>/routes/<?= $r['id'] ?>" class="tag">Route <?= htmlspecialchars($r['route_number']) ?></a>
            <?php endforeach; ?>
            <a href="<?= APP_URL ?>/planner" class="tag">Planner</a>
            <a href="<?= APP_URL ?>/cities" class="tag">Cities</a>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="container">
    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-num" data-target="<?= (int)($stats['route_count'] ?? 0) ?>">0</span>
            <span class="stat-label">Active Routes</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-target="<?= (int)($stats['stop_count'] ?? 0) ?>">0</span>
            <span class="stat-label">Bus Stops</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-target="<?= (int)($globalStats['total_cities'] ?? 1) ?>">0</span>
            <span class="stat-label">Cities Covered</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-target="<?= (int)($stats['avg_frequency'] ?? 15) ?>">0</span>
            <span class="stat-label">Avg Frequency (mins)</span>
        </div>
    </div>
</div>

<!-- ROUTE CARDS -->
<div class="sec" style="padding-top:80px">
        <div class="sec-head reveal">
            <div>
                <div class="sec-title">Popular <span>Routes</span></div>
                <div class="sec-desc">High-frequency routes across <?= htmlspecialchars($city['city_name'] ?? 'the city') ?></div>
            </div>
            <a href="<?= APP_URL ?>/routes" class="sec-link">View all routes ↗</a>
        </div>

        <div class="chips reveal reveal-d1">
            <button class="chip on"><span>All Routes</span></button>
            <button class="chip"><span>AC</span></button>
            <button class="chip"><span>Express</span></button>
            <button class="chip"><span>Normal</span></button>
            <button class="chip"><span>Night</span></button>
        </div>

        <div class="r-grid" id="rGrid">
            <?php if (empty($routes)): ?>
                <div style="grid-column: span 3; text-align: center; padding: 60px 0;">
                    <p class="text-muted">No routes found for this city yet.</p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($routes, 0, 9) as $r): ?>
                    <?php
                        $type = strtolower($r['route_type'] ?? '');
                        $badgeClass = match ($type) {
                            'ac' => 'rb-ac',
                            'express' => 'rb-ex',
                            'night' => 'rb-ni',
                            default => 'rb-nm',
                        };
                    ?>
                    <a class="rcard reveal reveal-d2" href="<?= APP_URL ?>/routes/<?= $r['id'] ?>" style="--strip-a:var(--accent);--strip-b:var(--accent2)">
                        <div class="rcard-strip"></div>
                        <div class="rcard-inner">
                            <div class="rcard-top">
                                <span class="rnum"><?= htmlspecialchars($r['route_number']) ?></span>
                                <span class="rbadge <?= $badgeClass ?>"><?= htmlspecialchars($r['route_type'] ?? 'Normal') ?></span>
                            </div>
                            <div class="rroute">
                                <div class="rstop"><small>From</small><?= htmlspecialchars($r['source']) ?></div>
                                <div class="rconn">
                                    <div class="rline"></div>
                                    <span class="rdist"><?= $r['distance_km'] ?? '??' ?> km</span>
                                    <div class="rline" style="background:linear-gradient(90deg,rgba(255,255,255,.04),rgba(255,255,255,.2))"></div>
                                </div>
                                <div class="rstop"><small>To</small><?= htmlspecialchars($r['destination']) ?></div>
                            </div>
                            <div class="rmeta">
                                <div class="rmeta-item"><span class="rmv"><?= $r['frequency_mins'] ?? '??' ?> min</span><span class="rmk">Frequency</span></div>
                                <div class="rmeta-item"><span class="rmv">06:00</span><span class="rmk">First Bus</span></div>
                                <div class="rmeta-item"><span class="rmv rdot"><span class="dot d-g"></span>Active</span><span class="rmk">Status</span></div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

<!-- ADMIN STRIP -->
<div class="container">
    <div class="admin-strip anim-5">
        <div class="admin-strip-text">
            <h3>Admin Dashboard</h3>
            <p>Manage routes, stops, fares, and users. Secure access via Google OAuth.</p>
        </div>
        <div class="admin-strip-btns">
            <a href="<?= APP_URL ?>/api" class="btn-g">View API Docs</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= APP_URL ?>/admin" class="btn-o">Dashboard</a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/auth/login" class="btn-o">Login with Google →</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
