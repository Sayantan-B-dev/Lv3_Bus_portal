<?php
/**
 * views/admin/routes/create.php
 */
ob_start();
?>

<div class="adm-head-block admin-page-head-simple">
    <a href="<?= APP_URL ?>/admin/routes" class="adm-back-link">← BACK TO LIST</a>
    <h3 class="adm-title adm-gap-top">Add New <span style="color:var(--admin-accent)">Bus Route</span></h3>
</div>

<div class="adm-card">
    <div class="adm-card-head">ROUTE DETAILS</div>
    <div class="adm-card-body">
        <form action="<?= APP_URL ?>/admin/routes" method="POST">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            
            <div class="adm-form-grid two-col">
                <div class="adm-field">
                    <label class="adm-label">City</label>
                    <select name="city_id" class="adm-select">
                        <?php foreach ($cities as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['city_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="adm-field">
                    <label class="adm-label">Route Number</label>
                    <input type="text" name="route_number" class="adm-input" placeholder="e.g. S-12" required>
                </div>
            </div>

            <div class="adm-form-grid two-col">
                <div class="adm-field">
                    <label class="adm-label">Source Stop Name</label>
                    <input type="text" name="source" class="adm-input" placeholder="Origin" required>
                </div>
                <div class="adm-field">
                    <label class="adm-label">Destination Stop Name</label>
                    <input type="text" name="destination" class="adm-input" placeholder="Terminal" required>
                </div>
            </div>

            <div class="adm-form-grid three-col">
                <div class="adm-field">
                    <label class="adm-label">Route Type</label>
                    <select name="route_type" class="adm-select">
                        <option value="Normal">Normal</option>
                        <option value="AC">AC</option>
                        <option value="Express">Express</option>
                        <option value="Night">Night</option>
                        <option value="Mini">Mini</option>
                    </select>
                </div>
                <div class="adm-field">
                    <label class="adm-label">Frequency (mins)</label>
                    <input type="number" name="frequency_mins" class="adm-input" value="20">
                </div>
                <div class="adm-field">
                    <label class="adm-label">Total Distance (km)</label>
                    <input type="number" step="0.1" name="total_distance_km" class="adm-input" value="0">
                </div>
            </div>

            <div class="adm-form-grid two-col">
                <div class="adm-field">
                    <label class="adm-label">First Bus Time</label>
                    <input type="time" name="first_bus_time" class="adm-input" value="06:00">
                </div>
                <div class="adm-field">
                    <label class="adm-label">Last Bus Time</label>
                    <input type="time" name="last_bus_time" class="adm-input" value="22:00">
                </div>
            </div>

            <div class="adm-field">
                <label class="adm-label">Description (Optional)</label>
                <textarea name="description" class="adm-input" rows="3"></textarea>
            </div>

            <hr class="adm-divider-space">
            
            <div class="admin-form-actions">
                <button type="submit" class="adm-action adm-action-primary">Save Route</button>
                <a href="<?= APP_URL ?>/admin/routes" class="adm-action adm-action-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
