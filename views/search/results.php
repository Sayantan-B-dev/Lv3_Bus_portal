<?php
/**
 * views/search/results.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<main style="padding-top: 100px;">
    <section class="section">
        <div class="container">
            <div class="section-header anim-1">
                <div>
                    <h1 class="section-title">Search <span class="text-primary">Results</span></h1>
                    <p class="section-sub">Showing results for "<?= htmlspecialchars($query) ?>" in <?= htmlspecialchars($city['city_name']) ?></p>
                </div>
                
                <form action="<?= APP_URL ?>/search" method="GET" class="search-wrap" style="margin-bottom:0; max-width: 400px;">
                    <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
                    <div class="search-icon"></div>
                    <input class="search-input" type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Search again..." required>
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>

            <div style="display:grid; grid-template-columns: 2fr 1fr; gap: 40px;" class="mt-24">
                <!-- Routes Column -->
                <div>
                    <h3 class="font-display mb-4" style="font-size:18px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">Matching Routes (<?= count($results['routes'] ?? []) ?>)</h3>
                    
                    <?php if (empty($results['routes'])): ?>
                        <p class="text-muted py-3">No matching routes found.</p>
                    <?php else: ?>
                        <div class="routes-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                            <?php foreach ($results['routes'] as $r): ?>
                                <a class="route-card" href="<?= APP_URL ?>/routes/<?= $r['id'] ?>">
                                    <div class="route-card-top" style="margin-bottom:12px">
                                        <span class="route-num" style="font-size:22px"><?= htmlspecialchars($r['route_number']) ?></span>
                                        <span class="route-badge badge-<?= strtolower($r['route_type']) ?>"><?= $r['route_type'] ?></span>
                                    </div>
                                    <div class="route-path" style="margin-bottom:0">
                                        <div class="route-from" style="font-size:13px"><?= htmlspecialchars($r['source']) ?> → <?= htmlspecialchars($r['destination']) ?></div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Stops Column -->
                <div>
                    <h3 class="font-display mb-4" style="font-size:18px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">Matching Stops (<?= count($results['stops'] ?? []) ?>)</h3>
                    
                    <?php if (empty($results['stops'])): ?>
                        <p class="text-muted py-3">No matching stops found.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <?php foreach ($results['stops'] as $s): ?>
                                <div class="route-card" style="padding: 16px; cursor: default;">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="fw-700" style="color:var(--accent2); font-size:14px;"><?= htmlspecialchars($s['stop_name']) ?></div>
                                            <div class="small text-muted" style="font-size:11px;"><?= htmlspecialchars($s['landmark'] ?? 'No landmark') ?></div>
                                        </div>
                                        <?php if ($s['latitude']): ?>
                                            <a href="https://www.openstreetmap.org/?mlat=<?= $s['latitude'] ?>&mlon=<?= $s['longitude'] ?>" target="_blank" class="tag" style="padding:4px 8px">Map</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
