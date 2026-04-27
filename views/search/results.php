<?php
/**
 * views/search/results.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<main class="page">
    <div class="sec">
        <div class="sec-head reveal">
            <div>
                <div class="sec-title">Search <span>Results</span></div>
                <div class="sec-desc">Showing results for "<?= htmlspecialchars($query) ?>" in <?= htmlspecialchars($city['city_name']) ?></div>
            </div>

            <form action="<?= APP_URL ?>/search" method="GET" class="search-bar" style="max-width: 520px;">
                <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
                <span class="s-icon">⌕</span>
                <input class="s-input" type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Search again..." required>
                <button type="submit" class="s-btn">Search</button>
            </form>
        </div>

        <div style="display:grid; grid-template-columns: 2fr 1fr; gap: 32px;">
            <div>
                <div class="sec-desc" style="margin-bottom:14px;font-weight:600;color:var(--muted2)">Matching Routes (<?= count($results['routes'] ?? []) ?>)</div>

                <?php if (empty($results['routes'])): ?>
                    <p class="sec-desc">No matching routes found.</p>
                <?php else: ?>
                    <div class="r-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                        <?php foreach ($results['routes'] as $r): ?>
                            <?php
                                $rt = strtolower($r['route_type'] ?? '');
                                $badgeClass = match ($rt) {
                                    'ac' => 'rb-ac',
                                    'express' => 'rb-ex',
                                    'night' => 'rb-ni',
                                    default => 'rb-nm',
                                };
                            ?>
                            <a class="rcard reveal" href="<?= APP_URL ?>/routes/<?= $r['id'] ?>" style="--strip-a:var(--accent);--strip-b:var(--accent2)">
                                <div class="rcard-strip"></div>
                                <div class="rcard-inner">
                                    <div class="rcard-top" style="margin-bottom:12px">
                                        <span class="rnum" style="font-size:26px"><?= htmlspecialchars($r['route_number']) ?></span>
                                        <span class="rbadge <?= $badgeClass ?>"><?= htmlspecialchars($r['route_type'] ?? 'Normal') ?></span>
                                    </div>
                                    <div class="rstop" style="font-size:13.5px;font-weight:500;color:var(--muted2)">
                                        <?= htmlspecialchars($r['source']) ?> → <?= htmlspecialchars($r['destination']) ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <div class="sec-desc" style="margin-bottom:14px;font-weight:600;color:var(--muted2)">Matching Stops (<?= count($results['stops'] ?? []) ?>)</div>

                <?php if (empty($results['stops'])): ?>
                    <p class="sec-desc">No matching stops found.</p>
                <?php else: ?>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <?php foreach ($results['stops'] as $s): ?>
                            <div class="rcard reveal" style="--strip-a:var(--green);--strip-b:var(--blue)">
                                <div class="rcard-strip"></div>
                                <div class="rcard-inner" style="padding:16px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px">
                                    <div style="min-width:0">
                                        <div style="font-weight:700;color:var(--accent2);font-size:14px;line-height:1.3"><?= htmlspecialchars($s['stop_name']) ?></div>
                                        <div class="sec-desc" style="margin-top:2px;font-size:11.5px"><?= htmlspecialchars($s['landmark'] ?? 'No landmark') ?></div>
                                    </div>
                                    <?php if ($s['latitude']): ?>
                                        <a href="https://www.openstreetmap.org/?mlat=<?= $s['latitude'] ?>&mlon=<?= $s['longitude'] ?>" target="_blank" class="btn-g" style="padding:6px 10px;font-size:12px">Map</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
