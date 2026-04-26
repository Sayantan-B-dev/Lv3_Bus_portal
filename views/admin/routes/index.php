<?php
/**
 * views/admin/routes/index.php
 */
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="font-display fw-bold mb-0">Routes in <span class="text-accent"><?= htmlspecialchars($city['city_name']) ?></span></h3>
        <p class="text-muted small">Manage bus lines, segments, and operational details.</p>
    </div>
    <div class="d-flex gap-3 align-items-center">
        <form action="" method="GET">
            <select name="city_id" class="form-select form-select-sm" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text); width: 180px;" onchange="this.form.submit()">
                <?php foreach ($cities as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $city['id'] ? 'selected' : '' ?>><?= $c['city_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="<?= APP_URL ?>/admin/routes/create" class="btn btn-primary">+ Add Route</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="ps-4">NUMBER</th>
                        <th>PATH</th>
                        <th>TYPE</th>
                        <th>FREQ</th>
                        <th class="text-end pe-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($routes)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No routes found for this city.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($routes as $r): ?>
                        <tr>
                            <td class="ps-4">
                                <span style="color:var(--admin-accent); font-weight:700; font-family:var(--font-display); font-size:16px;"><?= $r['route_number'] ?></span>
                            </td>
                            <td>
                                <div style="font-size:14px;"><?= $r['source'] ?></div>
                                <div class="text-muted" style="font-size:11px;">→ <?= $r['destination'] ?></div>
                            </td>
                            <td><span class="badge-outline"><?= $r['route_type'] ?></span></td>
                            <td><span style="color:var(--admin-muted)"><?= $r['frequency_mins'] ?>m</span></td>
                            <td class="text-end pe-4">
                                <a href="<?= APP_URL ?>/admin/fares/<?= $r['id'] ?>" class="btn btn-sm btn-link action-btn text-success p-0 me-3">Fares</a>
                                <a href="<?= APP_URL ?>/admin/routes/<?= $r['id'] ?>/edit" class="btn btn-sm btn-outline-primary action-btn me-2">Edit</a>
                                <form action="<?= APP_URL ?>/admin/routes/<?= $r['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this route?')">
                                    <button type="submit" class="btn btn-sm btn-link text-danger action-btn p-0">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
