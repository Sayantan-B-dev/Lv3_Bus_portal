<?php
/**
 * views/admin/dashboard.php
 */
ob_start();
?>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card card-stat">
            <div class="stat-label">Total Routes</div>
            <div class="d-flex align-items-end justify-content-between">
                <div class="stat-value"><?= $global['total_routes'] ?></div>
                <div class="stat-icon">R</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat">
            <div class="stat-label">Total Stops</div>
            <div class="d-flex align-items-end justify-content-between">
                <div class="stat-value"><?= $global['total_stops'] ?></div>
                <div class="stat-icon">S</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat">
            <div class="stat-label">Cities Covered</div>
            <div class="d-flex align-items-end justify-content-between">
                <div class="stat-value"><?= $global['total_cities'] ?></div>
                <div class="stat-icon">C</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat">
            <div class="stat-label">System Admins</div>
            <div class="d-flex align-items-end justify-content-between">
                <div class="stat-value"><?= count($users) ?></div>
                <div class="stat-icon">A</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">CITY OVERVIEW</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>CITY NAME</th>
                                <th>REGION</th>
                                <th>ROUTES</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cities as $c): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold" style="color:black"><?= htmlspecialchars($c['city_name']) ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($c['country'] ?? 'India') ?></div>
                                    </td>
                                    <td><span class="badge-outline"><?= htmlspecialchars($c['state_region']) ?></span></td>
                                    <td>
                                        <span style="color:var(--admin-accent); font-weight:700">Active</span>
                                    </td>
                                    <td>
                                        <a href="<?= APP_URL ?>/admin/routes?city_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary action-btn">Manage</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">SYSTEM ACTIONS</div>
            <div class="card-body">
                <p class=" small mb-4">Quickly manage global entities and data imports.</p>
                <div class="d-grid gap-3">
                    <a href="<?= APP_URL ?>/admin/cities" class="btn btn-primary">
                        <span class="me-2">+</span> Add New City
                    </a>
                    <a href="<?= APP_URL ?>/admin/stops/import-osm" class="btn btn-outline-secondary" style="border-color:var(--admin-border); color:var(--admin-text)">
                        Import OSM Data
                    </a>
                    <div class="mt-4 pt-4 border-top border-secondary opacity-25">
                        <h6 class="font-display fw-bold mb-3">Recent Users</h6>
                        <?php foreach (array_slice($users, 0, 3) as $u): ?>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:24px; height:24px; background:var(--admin-surface); border-radius:50%; font-size:10px; display:flex; align-items:center; justify-content:center; border:1px solid var(--admin-border)">
                                    <?= substr($u['name'], 0, 1) ?>
                                </div>
                                <div class="small"><?= htmlspecialchars($u['name']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout/admin-layout.php';
?>
