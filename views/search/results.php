<?php
/**
 * views/search/results.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="container-custom py-5">
    <div class="mb-5">
        <h1 class="h2 font-rajdhani">Search <span class="text-primary">Results</span></h1>
        <p class="text-muted">Showing results for "<?= htmlspecialchars($query) ?>" in <?= htmlspecialchars($city['city_name']) ?></p>
        
        <form action="<?= APP_URL ?>/search" method="GET" class="search-container mt-4" style="margin: 0; max-width: 500px;">
            <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
            <input type="text" name="q" class="search-input" value="<?= htmlspecialchars($query) ?>" placeholder="Search again..." required>
            <button type="submit" class="search-btn">SEARCH</button>
        </form>
    </div>

    <div class="row">
        <!-- Routes Column -->
        <div class="col-lg-8">
            <h3 class="h5 font-rajdhani text-white mb-4 border-bottom border-dark pb-2">Matching Routes (<?= count($results['routes'] ?? []) ?>)</h3>
            
            <?php if (empty($results['routes'])): ?>
                <p class="text-muted py-3">No matching routes found.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($results['routes'] as $r): ?>
                        <div class="col-md-6">
                            <a href="<?= APP_URL ?>/routes/<?= $r['id'] ?>" class="route-card text-decoration-none">
                                <div class="route-number"><?= htmlspecialchars($r['route_number']) ?></div>
                                <div class="route-info">
                                    <div class="route-endpoints small">
                                        <?= htmlspecialchars($r['source']) ?> → <?= htmlspecialchars($r['destination']) ?>
                                    </div>
                                    <span class="badge badge-type badge-<?= strtolower($r['route_type']) ?> mt-1"><?= $r['route_type'] ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Stops Column -->
        <div class="col-lg-4">
            <h3 class="h5 font-rajdhani text-white mb-4 border-bottom border-dark pb-2">Matching Stops (<?= count($results['stops'] ?? []) ?>)</h3>
            
            <?php if (empty($results['stops'])): ?>
                <p class="text-muted py-3">No matching stops found.</p>
            <?php else: ?>
                <div class="list-group list-group-flush bg-surface border border-dark">
                    <?php foreach ($results['stops'] as $s): ?>
                        <div class="list-group-item bg-transparent border-dark p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="stop-name text-primary"><?= htmlspecialchars($s['stop_name']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($s['landmark'] ?? 'No landmark') ?></div>
                                </div>
                                <?php if ($s['latitude']): ?>
                                    <a href="https://www.openstreetmap.org/?mlat=<?= $s['latitude'] ?>&mlon=<?= $s['longitude'] ?>" target="_blank" class="text-muted">📍</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
