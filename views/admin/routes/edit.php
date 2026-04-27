<?php
/**
 * views/admin/routes/edit.php
 */
ob_start();
?>

<div class="adm-head-block admin-page-head">
    <div>
        <a href="<?= APP_URL ?>/admin/routes" class="adm-back-link">← BACK TO LIST</a>
        <h3 class="adm-title adm-gap-top admin-page-title">Edit Route <span style="color:var(--admin-accent)">#<?= $route['route_number'] ?></span></h3>
    </div>
</div>

<div class="adm-card adm-gap-bottom">
    <div class="adm-card-head">CORE CONFIGURATION</div>
    <div class="adm-card-body">
        <form action="<?= APP_URL ?>/admin/routes/<?= $route['id'] ?>" method="POST">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            
            <div class="adm-form-grid two-col">
                <div class="adm-field">
                    <label class="adm-label">Route Number</label>
                    <input type="text" name="route_number" class="adm-input" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)" value="<?= htmlspecialchars($route['route_number']) ?>" required>
                </div>
                <div class="adm-field">
                    <label class="adm-label">Route Type</label>
                    <select name="route_type" class="adm-select" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)">
                        <?php foreach (['Normal', 'AC', 'Express', 'Night', 'Mini'] as $t): ?>
                            <option value="<?= $t ?>" <?= $route['route_type'] == $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="adm-form-grid two-col">
                <div class="adm-field">
                    <label class="adm-label">Source</label>
                    <input type="text" name="source" class="adm-input" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)" value="<?= htmlspecialchars($route['source']) ?>" required>
                </div>
                <div class="adm-field">
                    <label class="adm-label">Destination</label>
                    <input type="text" name="destination" class="adm-input" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)" value="<?= htmlspecialchars($route['destination']) ?>" required>
                </div>
            </div>

            <div class="adm-form-grid three-col">
                <div class="adm-field">
                    <label class="adm-label">Frequency (mins)</label>
                    <input type="number" name="frequency_mins" class="adm-input" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)" value="<?= $route['frequency_mins'] ?>">
                </div>
                <div class="adm-field">
                    <label class="adm-label">First Bus</label>
                    <input type="time" name="first_bus_time" class="adm-input" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)" value="<?= date('H:i', strtotime($route['first_bus_time'])) ?>">
                </div>
                <div class="adm-field">
                    <label class="adm-label">Last Bus</label>
                    <input type="time" name="last_bus_time" class="adm-input" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)" value="<?= date('H:i', strtotime($route['last_bus_time'])) ?>">
                </div>
            </div>

            <div class="adm-field">
                <label class="adm-label">Description</label>
                <textarea name="description" class="adm-input" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text)" rows="2"><?= htmlspecialchars($route['description'] ?? '') ?></textarea>
            </div>

            <hr class="adm-divider-space">

            <div class="admin-form-actions">
                <button type="submit" class="adm-action adm-action-primary">Update Route</button>
                <a href="<?= APP_URL ?>/admin/routes" class="adm-action adm-action-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div class="adm-gap-top">
    <h4 class="adm-subhead">STOP SEQUENCE</h4>
    <div class="adm-card">
        <div class="adm-card-body">
            <p class="adm-muted-note">Sequence management coming soon. Use database for now.</p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
