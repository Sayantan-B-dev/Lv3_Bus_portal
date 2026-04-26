<?php
/**
 * views/admin/dashboard.php
 */
ob_start();
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-stat p-3 border-start border-4 border-primary">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-light text-primary me-3">🚌</div>
                <div>
                    <h6 class="text-muted small mb-1">TOTAL ROUTES</h6>
                    <span class="h4 font-rajdhani mb-0"><?= $global['total_routes'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3 border-start border-4 border-success">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-light text-success me-3">📍</div>
                <div>
                    <h6 class="text-muted small mb-1">TOTAL STOPS</h6>
                    <span class="h4 font-rajdhani mb-0"><?= $global['total_stops'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3 border-start border-4 border-info">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-light text-info me-3">🏙️</div>
                <div>
                    <h6 class="text-muted small mb-1">CITIES</h6>
                    <span class="h4 font-rajdhani mb-0"><?= $global['total_cities'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3 border-start border-4 border-warning">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-light text-warning me-3">👤</div>
                <div>
                    <h6 class="text-muted small mb-1">ADMINS</h6>
                    <span class="h4 font-rajdhani mb-0"><?= count($users) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white font-rajdhani py-3">CITY OVERVIEW</div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>CITY NAME</th>
                            <th>REGION</th>
                            <th>ROUTES</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cities as $c): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($c['city_name']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($c['state_region']) ?></td>
                                <td><span class="badge bg-secondary">...</span></td>
                                <td>
                                    <a href="<?= APP_URL ?>/admin/routes?city_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary">Manage</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white font-rajdhani py-3">SYSTEM ACTIONS</div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= APP_URL ?>/admin/cities" class="btn btn-primary">Add New City</a>
                    <a href="<?= APP_URL ?>/admin/stops/import-osm" class="btn btn-outline-secondary">Import OSM Data</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/admin-layout.php';
?>
