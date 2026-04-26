<?php
/**
 * views/cities/index.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<main style="padding-top: 100px;">
    <section class="section">
        <div class="container">
            <div class="section-header anim-1">
                <div>
                    <h1 class="section-title">Browse <span class="text-primary">Cities</span></h1>
                    <p class="section-sub">Select a city to view its routes and transit information.</p>
                </div>
            </div>

            <div class="routes-grid anim-3" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                <?php if (empty($cities)): ?>
                    <div style="grid-column: span 3; text-align: center; padding: 60px 0;">
                        <p class="text-muted">No cities available at the moment.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($cities as $c): ?>
                        <div class="route-card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                            <div>
                                <div class="route-card-top" style="margin-bottom: 12px;">
                                    <span class="route-num" style="font-size: 24px;"><?= htmlspecialchars($c['city_name']) ?></span>
                                    <span class="badge-outline" style="font-size: 10px;"><?= htmlspecialchars($c['state_region']) ?></span>
                                </div>
                                <p class="text-muted small" style="margin-bottom: 20px;">
                                    Reliable transit data for <?= htmlspecialchars($c['city_name']) ?>, <?= htmlspecialchars($c['country'] ?? 'India') ?>.
                                </p>
                            </div>
                            
                            <form action="<?= APP_URL ?>/cities/switch" method="POST">
                                <input type="hidden" name="city_id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                                    Switch to <?= htmlspecialchars($c['city_name']) ?>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
