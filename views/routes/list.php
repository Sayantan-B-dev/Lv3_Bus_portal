<?php
/**
 * views/routes/list.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<main class="page">
    <div class="sec">
        <div class="sec-head reveal">
            <div>
                <div class="sec-title">All <span>Routes</span></div>
                <div class="sec-desc"><?= $total ?> routes available in <?= htmlspecialchars($city['city_name']) ?></div>
            </div>

            <div class="chips">
                <a href="?city_id=<?= $city['id'] ?>" class="chip <?= !$type ? 'on' : '' ?>"><span>All</span></a>
                <a href="?city_id=<?= $city['id'] ?>&type=AC" class="chip <?= $type == 'AC' ? 'on' : '' ?>"><span>❄️ AC</span></a>
                <a href="?city_id=<?= $city['id'] ?>&type=Express" class="chip <?= $type == 'Express' ? 'on' : '' ?>"><span>⚡ Express</span></a>
                <a href="?city_id=<?= $city['id'] ?>&type=Normal" class="chip <?= $type == 'Normal' ? 'on' : '' ?>"><span>Normal</span></a>
                <a href="?city_id=<?= $city['id'] ?>&type=Night" class="chip <?= $type == 'Night' ? 'on' : '' ?>"><span>🌙 Night</span></a>
            </div>
        </div>

        <div class="r-grid">
            <?php foreach ($routes as $r): ?>
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
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="reveal" style="display:flex;justify-content:center;margin-top:28px">
                <div style="background: var(--surface); padding: 5px; border-radius: 12px; border: 1px solid var(--border); display:flex; gap:4px; flex-wrap:wrap;">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?city_id=<?= $city['id'] ?>&type=<?= $type ?>&page=<?= $i ?>" class="btn-g" style="padding: 8px 14px; <?= $page == $i ? 'border-color:rgba(255,255,255,.25);color:var(--text)' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
