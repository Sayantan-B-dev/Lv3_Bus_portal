<?php
/**
 * views/routes/list.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<main style="padding-top: 100px;">
    <section class="section">
        <div class="container">
            <div class="section-header anim-1">
                <div>
                    <h1 class="section-title">All <span class="text-primary">Routes</span></h1>
                    <p class="section-sub"><?= $total ?> routes available in <?= htmlspecialchars($city['city_name']) ?></p>
                </div>

                <div class="filter-bar">
                    <a href="?city_id=<?= $city['id'] ?>" class="filter-chip <?= !$type ? 'active' : '' ?>">All</a>
                    <a href="?city_id=<?= $city['id'] ?>&type=AC" class="filter-chip <?= $type == 'AC' ? 'active' : '' ?>">AC ❄️</a>
                    <a href="?city_id=<?= $city['id'] ?>&type=Express" class="filter-chip <?= $type == 'Express' ? 'active' : '' ?>">Express ⚡</a>
                    <a href="?city_id=<?= $city['id'] ?>&type=Normal" class="filter-chip <?= $type == 'Normal' ? 'active' : '' ?>">Normal</a>
                    <a href="?city_id=<?= $city['id'] ?>&type=Night" class="filter-chip <?= $type == 'Night' ? 'active' : '' ?>">Night 🌙</a>
                </div>
            </div>

            <div class="routes-grid anim-3">
                <?php foreach ($routes as $r): ?>
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
                                <div class="route-arrow">▶</div>
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
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="d-flex justify-content-center mt-5 anim-5">
                <div class="nav-links" style="background: var(--surface); padding: 5px; border-radius: 12px; border: 1px solid var(--border);">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?city_id=<?= $city['id'] ?>&type=<?= $type ?>&page=<?= $i ?>" class="<?= $page == $i ? 'active' : '' ?>" style="padding: 8px 16px;">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
