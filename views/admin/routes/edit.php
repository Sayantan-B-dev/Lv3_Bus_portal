<?php
/**
 * views/admin/routes/edit.php
 */
ob_start();
?>

<div class="mb-4 d-flex justify-content-between align-items-end">
    <div>
        <a href="<?= APP_URL ?>/admin/routes" class="text-muted small">← BACK TO LIST</a>
        <h3 class="h4 font-rajdhani mt-2">Edit Route <span class="text-primary">#<?= $route['route_number'] ?></span></h3>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= APP_URL ?>/admin/routes/<?= $route['id'] ?>" method="POST">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Route Number</label>
                    <input type="text" name="route_number" class="form-control" value="<?= htmlspecialchars($route['route_number']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Route Type</label>
                    <select name="route_type" class="form-select">
                        <?php foreach (['Normal', 'AC', 'Express', 'Night', 'Mini'] as $t): ?>
                            <option value="<?= $t ?>" <?= $route['route_type'] == $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Source</label>
                    <input type="text" name="source" class="form-control" value="<?= htmlspecialchars($route['source']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Destination</label>
                    <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($route['destination']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Frequency (mins)</label>
                    <input type="number" name="frequency_mins" class="form-control" value="<?= $route['frequency_mins'] ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">First Bus</label>
                    <input type="time" name="first_bus_time" class="form-control" value="<?= date('H:i', strtotime($route['first_bus_time'])) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Last Bus</label>
                    <input type="time" name="last_bus_time" class="form-control" value="<?= date('H:i', strtotime($route['last_bus_time'])) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($route['description'] ?? '') ?></textarea>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-5">Update Route</button>
                <a href="<?= APP_URL ?>/admin/routes" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div class="mt-4">
    <h4 class="h5 font-rajdhani mb-3">STOP SEQUENCE</h4>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p class="small text-muted">Sequence management coming soon. Use database for now.</p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
