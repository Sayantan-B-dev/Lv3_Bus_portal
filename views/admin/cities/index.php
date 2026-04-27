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
                    <div class="city-actions-container" style="display:grid; grid-template-columns:1fr 1fr; gap:0.6rem; margin-top:1.25rem">
                        <a href="<?= APP_URL ?>/admin/routes?city_id=<?= $c['id'] ?>" class="adm-action adm-action-outline" style="font-size:11px; padding:0.6rem; justify-content:center; color:var(--admin-text)">Routes Hub</a>
                        <a href="<?= APP_URL ?>/admin/stops?city_id=<?= $c['id'] ?>" class="adm-action adm-action-outline" style="font-size:11px; padding:0.6rem; justify-content:center; color:var(--admin-text)">Manage Stops</a>
                        
                        <button 
                            class="adm-action adm-action-outline" 
                            style="font-size:11px; padding:0.6rem; justify-content:center; color:var(--admin-text)"
                            onclick='openEditCityModal(<?= json_encode($c) ?>)'
                        >Edit Profile</button>
                        
                        <form action="<?= APP_URL ?>/admin/cities/<?= $c['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this city? All routes and stops will be lost.')" style="display:contents">
                            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                            <button type="submit" class="adm-action adm-action-outline adm-link-danger" style="font-size:11px; padding:0.6rem; justify-content:center; width:100%">Remove</button>
                        </form>
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
                <div class="modal-header">
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

<!-- Edit City Modal -->
<div class="modal" id="editCityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCityForm" method="POST">
                <input type="hidden" name="_csrf" value="<?= $csrf ?>">
                <div class="modal-header">
                    <h5 class="modal-title">EDIT CITY</h5>
                    <button type="button" class="adm-modal-close" data-modal-close></button>
                </div>
                <div class="modal-body">
                    <div class="adm-field">
                        <label class="adm-label">City Name</label>
                        <input type="text" name="city_name" id="edit_city_name" class="adm-input" required>
                    </div>
                    <div class="adm-form-grid two-col">
                        <div class="adm-field">
                            <label class="adm-label">State/Region</label>
                            <input type="text" name="state_region" id="edit_city_state" class="adm-input">
                        </div>
                        <div class="adm-field">
                            <label class="adm-label">Country Code</label>
                            <input type="text" name="country_code" id="edit_city_country_code" class="adm-input" maxlength="2">
                        </div>
                    </div>
                    <div class="adm-form-grid two-col">
                        <div class="adm-field">
                            <label class="adm-label">Latitude</label>
                            <input type="text" name="center_lat" id="edit_city_lat" class="adm-input">
                        </div>
                        <div class="adm-field">
                            <label class="adm-label">Longitude</label>
                            <input type="text" name="center_lng" id="edit_city_lng" class="adm-input">
                        </div>
                    </div>
                    <div class="adm-form-grid two-col">
                        <div class="adm-field">
                            <label class="adm-label">Currency</label>
                            <input type="text" name="currency" id="edit_city_currency" class="adm-input">
                        </div>
                        <div class="adm-field">
                            <label class="adm-label">Timezone</label>
                            <input type="text" name="timezone" id="edit_city_timezone" class="adm-input">
                        </div>
                    </div>
                    <div class="adm-field">
                        <label class="adm-label">OSM Relation ID</label>
                        <input type="text" name="osm_relation_id" id="edit_city_osm_id" class="adm-input">
                    </div>
                    <div class="adm-field">
                        <label class="adm-label" style="display:flex; align-items:center; gap:0.5rem">
                            <input type="checkbox" name="is_active" id="edit_city_active" value="1"> City is Active?
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="adm-action adm-action-outline" data-modal-close>Cancel</button>
                    <button type="submit" class="adm-action adm-action-primary">Update City</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditCityModal(city) {
    const form = document.getElementById('editCityForm');
    form.action = `<?= APP_URL ?>/admin/cities/${city.id}`;
    
    document.getElementById('edit_city_name').value = city.city_name;
    document.getElementById('edit_city_state').value = city.state_region || '';
    document.getElementById('edit_city_country_code').value = city.country_code || 'IN';
    document.getElementById('edit_city_lat').value = city.center_lat;
    document.getElementById('edit_city_lng').value = city.center_lng;
    document.getElementById('edit_city_currency').value = city.currency || 'INR';
    document.getElementById('edit_city_timezone').value = city.timezone || 'Asia/Kolkata';
    document.getElementById('edit_city_osm_id').value = city.osm_relation_id || '';
    document.getElementById('edit_city_active').checked = city.is_active == 1;
    
    document.getElementById('editCityModal').classList.add('is-open');
}
</script>

<?php
$content = ob_get_clean();
require dirname(dirname(__DIR__)) . '/layout/admin-layout.php';
?>
