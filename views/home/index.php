<?php
/**
 * views/home/index.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<section class="hero-section">
    <div class="container-custom">
        <h1 class="display-3 mb-2 font-rajdhani">Find Your <span class="text-primary">Route</span></h1>
        <p class="text-muted mb-4 fs-5">Universal Information Portal for <?= htmlspecialchars($city['city_name'] ?? 'City') ?> Bus Services</p>

        <form action="<?= APP_URL ?>/search" method="GET" class="search-container">
            <input type="hidden" name="city_id" value="<?= $city['id'] ?? 1 ?>">
            <input type="text" name="q" class="search-input" placeholder="Search by route number, stop, or landmark..." required autocomplete="off">
            <button type="submit" class="search-btn">SEARCH</button>
        </form>

        <div class="d-flex justify-content-center gap-3 mt-3">
            <span class="text-muted small">Popular:</span>
            <?php foreach (array_slice($routes ?? [], 0, 4) as $r): ?>
                <a href="<?= APP_URL ?>/routes/<?= $r['id'] ?>" class="badge badge-type badge-mini text-decoration-none">
                    <?= htmlspecialchars($r['route_number']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="stats-bar bg-surface py-4 mb-5">
    <div class="container-custom">
        <div class="row text-center">
            <div class="col-6 col-md-3 border-end border-dark">
                <div class="info-value text-primary fs-2"><?= $stats['route_count'] ?? 0 ?></div>
                <div class="info-label">Active Routes</div>
            </div>
            <div class="col-6 col-md-3 border-end border-dark">
                <div class="info-value text-primary fs-2"><?= $stats['stop_count'] ?? 0 ?></div>
                <div class="info-label">Bus Stops</div>
            </div>
            <div class="col-6 col-md-3 border-end border-dark">
                <div class="info-value text-primary fs-2"><?= $globalStats['total_cities'] ?? 1 ?></div>
                <div class="info-label">Cities Covered</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-value text-primary fs-2"><?= $stats['avg_frequency'] ?? 0 ?>m</div>
                <div class="info-label">Avg Frequency</div>
            </div>
        </div>
    </div>
</section>

<section class="routes-preview pb-5">
    <div class="container-custom">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="h3 font-rajdhani mb-0">Browse <span class="text-primary">Routes</span></h2>
                <p class="text-muted small mb-0">Most searched bus routes in <?= htmlspecialchars($city['city_name'] ?? 'City') ?></p>
            </div>
            <a href="<?= APP_URL ?>/routes" class="btn btn-outline-warning btn-sm font-rajdhani">VIEW ALL ROUTES</a>
        </div>

        <div class="row">
            <?php if (empty($routes)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No routes found for this city yet.</p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($routes, 0, 8) as $r): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="<?= APP_URL ?>/routes/<?= $r['id'] ?>" class="route-card text-decoration-none">
                            <div class="route-number"><?= htmlspecialchars($r['route_number']) ?></div>
                            <div class="route-info">
                                <div class="route-endpoints">
                                    <?= htmlspecialchars($r['source']) ?> 
                                    <span class="text-primary mx-1">→</span> 
                                    <?= htmlspecialchars($r['destination']) ?>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge badge-type badge-<?= strtolower($r['route_type']) ?>"><?= $r['route_type'] ?></span>
                                    <span class="text-muted small"><?= $r['frequency_mins'] ?> mins freq.</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
