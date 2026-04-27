<?php
/**
 * views/admin/stops/index.php
 */
ob_start();
?>
<div class="admin-page-head">
    <div>
        <h3 class="adm-title admin-page-title">Bus Stops in <span style="color:var(--admin-accent)"><?= htmlspecialchars($city['city_name']) ?></span></h3>
        <p class="adm-muted-note">Total: <?= count($stops) ?> registered stations</p>
    </div>
    <div class="admin-page-tools">
        <form action="<?= APP_URL ?>/admin/stops/import-osm" method="POST" onsubmit="return confirm('Fetch real bus stops for this city from OpenStreetMap?')">
            <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
            <button type="submit" class="adm-action adm-action-outline adm-action-compact" style="border-color:var(--admin-border); color:var(--admin-text)">Auto-Import from OSM</button>
        </form>
        <button type="button" class="adm-action adm-action-primary" data-modal-target="#addStopModal">+ Add Stop</button>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head">STATION NETWORK</div>
    <div class="adm-card-body adm-pad-none">
        <div class="adm-table-wrap">
            <table class="adm-table adm-table-middle">
                <thead>
                    <tr>
                        <th>STOP NAME</th>
                        <th>COORDINATES</th>
                        <th>LANDMARK</th>
                        <th>ZONE</th>
                        <th class="adm-align-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stops)): ?>
                        <tr><td colspan="5" class="adm-align-center adm-empty">No stops found for this city.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($stops as $s): ?>
                        <tr>
                            <td>
                                <div class="adm-strong"><?= htmlspecialchars($s['stop_name']) ?></div>
                                <?php if ($s['is_terminal']): ?>
                                    <span class="adm-pill" style="font-size: 9px; border-color:var(--admin-accent); color:var(--admin-accent)">TERMINAL</span>
                                <?php endif; ?>
                            </td>
                            <td class="adm-muted-note"><?= $s['latitude'] ?>, <?= $s['longitude'] ?></td>
                            <td><?= htmlspecialchars($s['landmark'] ?? '-') ?></td>
                            <td><span class="adm-pill"><?= htmlspecialchars($s['zone'] ?? 'Standard') ?></span></td>
                            <td class="adm-align-end">
                                <button class="adm-action adm-action-outline adm-action-compact action-btn">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Stop Modal -->
<div class="modal" id="addStopModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= APP_URL ?>/admin/stops" method="POST">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
                <div class="modal-header font-rajdhani">
                    <h5 class="modal-title">ADD NEW STOP</h5>
                    <button type="button" class="adm-modal-close" data-modal-close></button>
                </div>
                <div class="modal-body">
                    <div class="adm-field">
                        <label class="adm-label">Stop Name</label>
                        <input type="text" name="stop_name" class="adm-input" required>
                    </div>
                    <div class="adm-form-grid two-col">
                        <div class="adm-field">
                            <label class="adm-label">Latitude</label>
                            <input type="text" name="latitude" class="adm-input" placeholder="e.g. 22.56">
                        </div>
                        <div class="adm-field">
                            <label class="adm-label">Longitude</label>
                            <input type="text" name="longitude" class="adm-input" placeholder="88.35">
                        </div>
                    </div>
                    <div class="adm-field">
                        <label class="adm-label">Landmark</label>
                        <input type="text" name="landmark" class="adm-input">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="adm-action adm-action-outline" data-modal-close>Cancel</button>
                    <button type="submit" class="adm-action adm-action-primary">Save Stop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
