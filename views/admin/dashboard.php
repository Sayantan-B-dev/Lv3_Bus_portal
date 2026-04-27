<?php
/**
 * views/admin/dashboard.php
 */
ob_start();
?>


<div class="admin-stats-grid">
    <div class="admin-stats-grid-item">
        <div class="adm-card adm-stat-card">
            <div class="stat-label">Total Routes</div>
            <div class="stat-row">
                <div class="stat-value"><?= $global['total_routes'] ?></div>
                <div class="stat-icon">R</div>
            </div>
        </div>
    </div>
    <div class="admin-stats-grid-item">
        <div class="adm-card adm-stat-card">
            <div class="stat-label">Total Stops</div>
            <div class="stat-row">
                <div class="stat-value"><?= $global['total_stops'] ?></div>
                <div class="stat-icon">S</div>
            </div>
        </div>
    </div>
    <div class="admin-stats-grid-item">
        <div class="adm-card adm-stat-card">
            <div class="stat-label">Cities Covered</div>
            <div class="stat-row">
                <div class="stat-value"><?= $global['total_cities'] ?></div>
                <div class="stat-icon">C</div>
            </div>
        </div>
    </div>
    <div class="admin-stats-grid-item">
        <div class="adm-card adm-stat-card">
            <div class="stat-label">System Admins</div>
            <div class="stat-row">
                <div class="stat-value"><?= count($users) ?></div>
                <div class="stat-icon">A</div>
            </div>
        </div>
    </div>
</div>

<div class="admin-panels-grid">
    <div>
        <div class="adm-card adm-gap-bottom">
            <div class="adm-card-head">CITY OVERVIEW</div>
            <div class="adm-card-body adm-pad-none">
                <div class="adm-table-wrap">
                    <table class="adm-table">
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
                                        <div class="adm-strong" style="color:inherit"><?= htmlspecialchars($c['city_name']) ?></div>
                                        <div class="adm-muted-note"><?= htmlspecialchars($c['country'] ?? 'India') ?></div>
                                    </td>
                                    <td><span class="adm-pill"><?= htmlspecialchars($c['state_region']) ?></span></td>
                                    <td>
                                        <span style="color:var(--admin-accent); font-weight:700">Active</span>
                                    </td>
                                    <td>
                                        <a href="<?= APP_URL ?>/admin/routes?city_id=<?= $c['id'] ?>" class="adm-action adm-action-outline action-btn">Manage</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="adm-card">
            <div class="adm-card-head">SYSTEM ACTIONS</div>
            <div class="adm-card-body">
                <p class="adm-muted-note adm-gap-sm">Quickly manage global entities and data imports.</p>
                <div class="admin-actions-stack">
                    <a href="<?= APP_URL ?>/admin/cities" class="adm-action adm-action-primary">
                        <span class="adm-gap-inline">+</span> Add New City
                    </a>
                    <a href="<?= APP_URL ?>/admin/stops/import-osm" class="adm-action adm-action-outline" style="border-color:var(--admin-border); color:var(--admin-text)">
                        Import OSM Data
                    </a>
                    <div class="adm-divider-block">
                        <h6 class="adm-subhead">Recent Users</h6>
                        <?php foreach (array_slice($users, 0, 3) as $u): ?>
                            <div class="recent-user-row">
                                <div class="recent-user-avatar">
                                    <?= substr($u['name'], 0, 1) ?>
                                </div>
                                <div class="adm-note"><?= htmlspecialchars($u['name']) ?></div>
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
