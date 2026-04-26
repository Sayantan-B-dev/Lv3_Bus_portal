<?php
/**
 * views/routes/list.php
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="container-custom py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 font-rajdhani">All <span class="text-primary">Routes</span></h1>
            <p class="text-muted small"><?= $total ?> routes available in <?= htmlspecialchars($city['city_name']) ?></p>
        </div>

        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="city-selector dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    TYPE: <?= $type ?: 'ALL' ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="?city_id=<?= $city['id'] ?>">All Types</a></li>
                    <li><a class="dropdown-item" href="?city_id=<?= $city['id'] ?>&type=AC">AC</a></li>
                    <li><a class="dropdown-item" href="?city_id=<?= $city['id'] ?>&type=Express">Express</a></li>
                    <li><a class="dropdown-item" href="?city_id=<?= $city['id'] ?>&type=Normal">Normal</a></li>
                    <li><a class="dropdown-item" href="?city_id=<?= $city['id'] ?>&type=Night">Night</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <?php foreach ($routes as $r): ?>
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
                            <span class="text-muted small"><?= $r['frequency_mins'] ?> mins</span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination pagination-sm justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                    <a class="page-link bg-surface border-secondary text-white" href="?city_id=<?= $city['id'] ?>&type=<?= $type ?>&page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
