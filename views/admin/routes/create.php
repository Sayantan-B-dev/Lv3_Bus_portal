<?php
/**
 * views/admin/routes/create.php
 */
ob_start();
?>

<div class="mb-4">
    <a href="<?= APP_URL ?>/admin/routes" class="text-muted small">← BACK TO LIST</a>
    <h3 class="h4 font-rajdhani mt-2">Add New <span class="text-primary">Bus Route</span></h3>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= APP_URL ?>/admin/routes" method="POST">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">City</label>
                    <select name="city_id" class="form-select">
                        <?php foreach ($cities as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['city_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Route Number</label>
                    <input type="text" name="route_number" class="form-control" placeholder="e.g. S-12" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Source Stop Name</label>
                    <input type="text" name="source" class="form-control" placeholder="Origin" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Destination Stop Name</label>
                    <input type="text" name="destination" class="form-control" placeholder="Terminal" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Route Type</label>
                    <select name="route_type" class="form-select">
                        <option value="Normal">Normal</option>
                        <option value="AC">AC</option>
                        <option value="Express">Express</option>
                        <option value="Night">Night</option>
                        <option value="Mini">Mini</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Frequency (mins)</label>
                    <input type="number" name="frequency_mins" class="form-control" value="20">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small fw-bold">Total Distance (km)</label>
                    <input type="number" step="0.1" name="total_distance_km" class="form-control" value="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">First Bus Time</label>
                    <input type="time" name="first_bus_time" class="form-control" value="06:00">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Last Bus Time</label>
                    <input type="time" name="last_bus_time" class="form-control" value="22:00">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Description (Optional)</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <hr class="my-4">
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-5">Save Route</button>
                <a href="<?= APP_URL ?>/admin/routes" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
