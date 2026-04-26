<?php
/**
 * views/admin/fares/index.php
 */
ob_start();
?>

<div class="mb-4">
    <a href="<?= APP_URL ?>/admin/routes" class="text-muted small">← BACK TO ROUTES</a>
    <h3 class="h4 font-rajdhani mt-2">Fares for Route <span class="text-primary"><?= htmlspecialchars($route['route_number']) ?></span></h3>
    <p class="text-muted small"><?= htmlspecialchars($route['source']) ?> → <?= htmlspecialchars($route['destination']) ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr>
                            <th class="ps-4">DISTANCE RANGE</th>
                            <th>PASSENGER TYPE</th>
                            <th>FARE AMOUNT</th>
                            <th class="text-end pe-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fares)): ?>
                            <tr><td colspan="4" class="text-center py-4 text-muted">No fare slabs defined.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($fares as $f): ?>
                            <tr>
                                <td class="ps-4"><?= $f['min_km'] ?> - <?= $f['max_km'] ?> km</td>
                                <td><span class="badge border text-dark"><?= $f['passenger_type'] ?></span></td>
                                <td class="fw-bold text-success"><?= $f['fare_amount'] ?></td>
                                <td class="text-end pe-4">
                                    <form action="<?= APP_URL ?>/admin/fares/<?= $f['id'] ?>/delete" method="POST" onsubmit="return confirm('Remove this slab?')">
                                        <button type="submit" class="btn btn-sm btn-link text-danger">Delete</button>
                                    </form>
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
            <div class="card-header bg-white font-rajdhani py-3">ADD FARE SLAB</div>
            <div class="card-body">
                <form action="<?= APP_URL ?>/admin/fares" method="POST">
                    <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                    <input type="hidden" name="route_id" value="<?= $route['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Passenger Type</label>
                        <select name="passenger_type" class="form-select">
                            <option value="General">General</option>
                            <option value="Student">Student</option>
                            <option value="Senior">Senior</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Min KM</label>
                            <input type="number" step="0.1" name="min_km" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Max KM</label>
                            <input type="number" step="0.1" name="max_km" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Fare Amount</label>
                        <input type="number" step="0.01" name="fare_amount" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Slab</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
