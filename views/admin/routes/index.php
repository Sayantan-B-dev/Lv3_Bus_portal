<?php
/**
 * views/admin/routes/index.php
 */
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="h4 font-rajdhani mb-0">Routes in <span class="text-primary"><?= htmlspecialchars($city['city_name']) ?></span></h3>
        <p class="text-muted small">Manage bus lines and schedules</p>
    </div>
    <div class="d-flex gap-2">
        <form class="d-flex gap-2 align-items-center me-3">
            <select name="city_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php foreach ($cities as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $city['id'] ? 'selected' : '' ?>><?= $c['city_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="<?= APP_URL ?>/admin/routes/create" class="btn btn-primary btn-sm">+ Add Route</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">NUMBER</th>
                    <th>PATH</th>
                    <th>TYPE</th>
                    <th>FREQ</th>
                    <th class="text-end pe-4">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($routes as $r): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-primary"><?= $r['route_number'] ?></td>
                        <td><?= $r['source'] ?> → <?= $r['destination'] ?></td>
                        <td><span class="badge bg-secondary opacity-75"><?= $r['route_type'] ?></span></td>
                        <td><?= $r['frequency_mins'] ?>m</td>
                        <td class="text-end pe-4">
                            <a href="<?= APP_URL ?>/admin/fares/<?= $r['id'] ?>" class="btn btn-sm btn-link text-success">Fares</a>
                            <a href="<?= APP_URL ?>/admin/routes/<?= $r['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="<?= APP_URL ?>/admin/routes/<?= $r['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this route?')">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
