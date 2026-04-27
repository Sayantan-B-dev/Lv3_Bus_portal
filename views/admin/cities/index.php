<?php
/**
 * views/admin/cities/index.php
 */
ob_start();
?>

<div class="admin-page-head">
    <div>
        <h3 class="adm-title admin-page-title">Manage <span style="color:var(--admin-accent)">Cities</span></h3>
        <p class="adm-muted-note">Add and configure supported operational regions</p>
    </div>
    <button type="button" class="adm-action adm-action-primary" data-modal-target="#addCityModal">+ Add City</button>
</div>

<div class="adm-cities-grid">
    <?php foreach ($cities as $c): ?>
        <div>
            <div class="adm-card adm-full-height">
                <div class="adm-card-head adm-head-row">
                    <span class="adm-head-label"><?= htmlspecialchars($c['city_name']) ?></span>
                    <span class="adm-pill" style="font-size: 9px;"><?= $c['country_code'] ?></span>
                </div>
                <div class="adm-card-body">
                    <p class="adm-muted-note adm-gap-sm">
                        <?= htmlspecialchars($c['state_region']) ?>, <?= htmlspecialchars($c['country']) ?><br>
                        <span class="adm-inline-note" style="font-size: 11px;">
                            Currency: <span style="color:var(--admin-accent)"><?= $c['currency'] ?></span> | 
                            Timezone: <?= $c['timezone'] ?>
                        </span>
                    </p>
                    <div class="admin-card-actions">
                        <a href="<?= APP_URL ?>/admin/routes?city_id=<?= $c['id'] ?>" class="adm-action adm-action-outline adm-grow action-btn">Routes</a>
                        <a href="<?= APP_URL ?>/admin/stops?city_id=<?= $c['id'] ?>" class="adm-action adm-action-outline adm-grow action-btn" style="border-color:var(--admin-border); color:var(--admin-text)">Stops</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Add City Modal -->
<div class="modal" id="addCityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= APP_URL ?>/admin/cities" method="POST">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <div class="modal-header font-rajdhani">
                    <h5 class="modal-title">ADD NEW CITY</h5>
                    <button type="button" class="adm-modal-close" data-modal-close></button>
                </div>
                <div class="modal-body">
                    <p class="adm-muted-note adm-gap-sm">The system will automatically attempt to find the city's coordinates and OSM Relation ID via Nominatim.</p>
                    <div class="adm-field">
                        <label class="adm-label">City Name</label>
                        <input type="text" name="city_name" class="adm-input" placeholder="e.g. New York" required>
                    </div>
                    <div class="adm-form-grid two-col">
                        <div class="adm-field">
                            <label class="adm-label">Country Code</label>
                            <input type="text" name="country_code" class="adm-input" value="IN" maxlength="2">
                        </div>
                        <div class="adm-field">
                            <label class="adm-label">Currency</label>
                            <input type="text" name="currency" class="adm-input" value="INR">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="adm-action adm-action-outline" data-modal-close>Cancel</button>
                    <button type="submit" class="adm-action adm-action-primary">Create City</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
