<?php
/**
 * views/admin/routes/index.php
 */
ob_start();
?>

<div class="admin-page-head">
    <div>
        <h3 class="adm-title admin-page-title">Routes in <span style="color:var(--admin-accent)"><?= htmlspecialchars($city['city_name']) ?></span></h3>
        <p class="adm-muted-note">Manage bus lines, segments, and operational details.</p>
    </div>
    <div class="admin-page-tools">
        <form action="" method="GET">
            <select name="city_id" class="adm-select adm-select-compact" style="background:var(--admin-surface2); border:1px solid var(--admin-border); color:var(--admin-text); width: 180px;" onchange="this.form.submit()">
                <?php foreach ($cities as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $city['id'] ? 'selected' : '' ?>><?= $c['city_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="<?= APP_URL ?>/admin/routes/create" class="adm-action adm-action-primary">+ Add Route</a>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head">ACTIVE ROUTES</div>
    <div class="adm-card-body adm-pad-none">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>NUMBER</th>
                        <th>PATH</th>
                        <th>TYPE</th>
                        <th>FREQ</th>
                        <th class="adm-align-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($routes)): ?>
                        <tr><td colspan="5" class="adm-align-center adm-empty">No routes found for this city.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($routes as $r): ?>
                        <tr>
                            <td>
                                <span class="adm-code"><?= $r['route_number'] ?></span>
                            </td>
                            <td>
                                <div style="font-size:14px; font-weight: 600;"><?= $r['source'] ?></div>
                                <div class="adm-muted-note">→ <?= $r['destination'] ?></div>
                            </td>
                            <td><span class="adm-pill"><?= $r['route_type'] ?></span></td>
                            <td><span style="color:var(--admin-accent); font-weight: 500;"><?= $r['frequency_mins'] ?>m</span></td>
                             <td class="adm-align-end">
                                <div style="display:flex; gap:0.6rem; justify-content:flex-end; align-items:center">
                                    <a href="<?= APP_URL ?>/admin/fares/<?= $r['id'] ?>" class="adm-action adm-action-outline adm-action-compact adm-link-success" style="font-size:11px">Fares</a>
                                    <a href="<?= APP_URL ?>/admin/routes/<?= $r['id'] ?>/edit" class="adm-action adm-action-outline adm-action-compact" style="font-size:11px">Edit</a>
                                    <form action="<?= APP_URL ?>/admin/routes/<?= $r['id'] ?>/delete" method="POST" class="adm-inline-form" onsubmit="return confirm('Delete this route?')">
                                        <input type="hidden" name="_csrf" value="<?= $csrf ?? '' ?>">
                                        <button type="submit" class="adm-action adm-action-outline adm-action-compact adm-link-danger" style="font-size:11px">Delete</button>
                                    </form>
                                </div>
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
