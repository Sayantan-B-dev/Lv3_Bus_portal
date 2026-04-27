<?php
/**
 * views/cities/index.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<main class="page">
    <div class="sec">
        <div class="sec-head reveal">
            <div>
                <div class="sec-title">Browse <span>Cities</span></div>
                <div class="sec-desc">Select a city to view its routes and transit information.</div>
            </div>
        </div>

        <div class="r-grid">
            <?php if (empty($cities)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 0;">
                    <p class="sec-desc">No cities available at the moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($cities as $c): ?>
                    <div class="rcard reveal" style="--strip-a:var(--blue);--strip-b:var(--accent)">
                        <div class="rcard-strip"></div>
                        <div class="rcard-inner" style="display:flex;flex-direction:column;gap:18px;height:100%">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px">
                                <div style="min-width:0">
                                    <div class="rnum" style="font-size:28px;letter-spacing:0"><?= htmlspecialchars($c['city_name']) ?></div>
                                    <div class="sec-desc" style="margin-top:4px"><?= htmlspecialchars($c['state_region']) ?> · <?= htmlspecialchars($c['country'] ?? 'India') ?></div>
                                </div>
                                <span class="rbadge rb-ac"><?= htmlspecialchars($c['country_code'] ?? 'IN') ?></span>
                            </div>

                            <form action="<?= APP_URL ?>/cities/switch" method="POST" style="margin-top:auto">
                                <input type="hidden" name="city_id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn-o" style="width:100%;padding:12px 16px;font-size:14px">Switch to <?= htmlspecialchars($c['city_name']) ?> →</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
