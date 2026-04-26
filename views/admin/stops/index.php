<?php
/**
 * views/admin/stops/index.php
 */
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="h4 font-rajdhani mb-0">Bus Stops in <span class="text-primary"><?= htmlspecialchars($city['city_name']) ?></span></h3>
        <p class="text-muted small">Total: <?= count($stops) ?> stops</p>
    </div>
    <div class="d-flex gap-2">
        <form action="<?= APP_URL ?>/admin/stops/import-osm" method="POST" onsubmit="return confirm('Fetch real bus stops for this city from OpenStreetMap?')">
            <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
            <button type="submit" class="btn btn-outline-info btn-sm">Auto-Import from OSM</button>
        </form>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStopModal">+ Add Stop</button>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">STOP NAME</th>
                    <th>COORDINATES</th>
                    <th>LANDMARK</th>
                    <th>ZONE</th>
                    <th class="text-end pe-4">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stops as $s): ?>
                    <tr>
                        <td class="ps-4">
                            <strong><?= htmlspecialchars($s['stop_name']) ?></strong>
                            <?php if ($s['is_terminal']): ?>
                                <span class="badge bg-warning text-dark ms-1" style="font-size: 10px;">TERMINAL</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= $s['latitude'] ?>, <?= $s['longitude'] ?></td>
                        <td><?= htmlspecialchars($s['landmark'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($s['zone'] ?? '-') ?></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-secondary">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Stop Modal -->
<div class="modal fade" id="addStopModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <form action="<?= APP_URL ?>/admin/stops" method="POST">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
                <div class="modal-header font-rajdhani">
                    <h5 class="modal-title">ADD NEW STOP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Stop Name</label>
                        <input type="text" name="stop_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Latitude</label>
                            <input type="text" name="latitude" class="form-control" placeholder="e.g. 22.56">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Longitude</label>
                            <input type="text" name="longitude" class="form-control" placeholder="88.35">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Landmark</label>
                        <input type="text" name="landmark" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Stop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
