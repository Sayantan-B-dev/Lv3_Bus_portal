<?php
/**
 * views/admin/cities/index.php
 */
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="h4 font-rajdhani mb-0">Manage <span class="text-primary">Cities</span></h3>
        <p class="text-muted small">Add and configure supported regions</p>
    </div>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCityModal">+ Add City</button>
</div>

<div class="row">
    <?php foreach ($cities as $c): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="font-rajdhani mb-0 text-primary"><?= htmlspecialchars($c['city_name']) ?></h5>
                        <span class="badge bg-light text-dark border"><?= $c['country_code'] ?></span>
                    </div>
                    <p class="small text-muted mb-4">
                        <?= htmlspecialchars($c['state_region']) ?>, <?= htmlspecialchars($c['country']) ?><br>
                        Currency: <?= $c['currency'] ?> | Timezone: <?= $c['timezone'] ?>
                    </p>
                    <div class="d-flex gap-2">
                        <a href="<?= APP_URL ?>/admin/routes?city_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary flex-grow-1">Routes</a>
                        <a href="<?= APP_URL ?>/admin/stops?city_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-secondary flex-grow-1">Stops</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Add City Modal -->
<div class="modal fade" id="addCityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <form action="<?= APP_URL ?>/admin/cities" method="POST">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <div class="modal-header font-rajdhani">
                    <h5 class="modal-title">ADD NEW CITY</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">The system will automatically attempt to find the city's coordinates and OSM Relation ID via Nominatim.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">City Name</label>
                        <input type="text" name="city_name" class="form-control" placeholder="e.g. New York" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Country Code</label>
                            <input type="text" name="country_code" class="form-control" value="IN" maxlength="2">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Currency</label>
                            <input type="text" name="currency" class="form-control" value="INR">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Create City</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
