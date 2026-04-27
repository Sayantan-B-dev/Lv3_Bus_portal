<?php
/**
 * views/admin/fares/index.php
 */
ob_start();
?>

<div class="adm-head-block admin-page-head-simple">
    <a href="<?= APP_URL ?>/admin/routes" class="adm-back-link">← BACK TO ROUTES</a>
    <h3 class="adm-title adm-gap-top">Fares for Route <span style="color:var(--admin-accent)"><?= htmlspecialchars($route['route_number']) ?></span></h3>
    <p class="adm-muted-note"><?= htmlspecialchars($route['source']) ?> → <?= htmlspecialchars($route['destination']) ?></p>
</div>

<div class="admin-fares-grid">
    <div>
        <div class="adm-card">
            <div class="adm-card-head">FARE STRUCTURE</div>
            <div class="adm-card-body adm-pad-none">
                <div class="adm-table-wrap">
                    <table class="adm-table adm-table-middle">
                        <thead>
                            <tr>
                                <th>DISTANCE RANGE</th>
                                <th>PASSENGER TYPE</th>
                                <th>FARE AMOUNT</th>
                                <th class="adm-align-end">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fares)): ?>
                                <tr><td colspan="4" class="adm-align-center adm-empty">No fare slabs defined.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($fares as $f): ?>
                                <tr>
                                    <td><?= $f['min_km'] ?> - <?= $f['max_km'] ?> km</td>
                                    <td><span class="adm-pill"><?= $f['passenger_type'] ?></span></td>
                                    <td class="adm-strong" style="color:var(--admin-accent)"><?= $f['fare_amount'] ?></td>
                                    <td class="adm-align-end">
                                        <form action="<?= APP_URL ?>/admin/fares/<?= $f['id'] ?>/delete" method="POST" onsubmit="return confirm('Remove this slab?')">
                                            <button type="submit" class="action-btn adm-link-danger">Delete</button>
                                        </form>
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
            <div class="adm-card-head">ADD FARE SLAB</div>
            <div class="adm-card-body">
                <form action="<?= APP_URL ?>/admin/fares" method="POST">
                    <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                    <input type="hidden" name="route_id" value="<?= $route['id'] ?>">
                    
                    <div class="adm-field">
                        <label class="adm-label">Passenger Type</label>
                        <select name="passenger_type" class="adm-select">
                            <option value="General">General</option>
                            <option value="Student">Student</option>
                            <option value="Senior">Senior</option>
                        </select>
                    </div>
                    <div class="adm-form-grid two-col">
                        <div class="adm-field">
                            <label class="adm-label">Min KM</label>
                            <input type="number" step="0.1" name="min_km" class="adm-input" required>
                        </div>
                        <div class="adm-field">
                            <label class="adm-label">Max KM</label>
                            <input type="number" step="0.1" name="max_km" class="adm-input" required>
                        </div>
                    </div>
                    <div class="adm-field adm-gap-sm">
                        <label class="adm-label">Fare Amount</label>
                        <input type="number" step="0.01" name="fare_amount" class="adm-input" required>
                    </div>
                    <button type="submit" class="adm-action adm-action-primary adm-action-block">Add Slab</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
