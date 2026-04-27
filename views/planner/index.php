<?php
/**
 * views/planner/index.php
 */
$extraScripts = ['dijkstra', 'map'];
include dirname(__DIR__) . '/layout/header.php';
?>

<main class="page">
    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="section-header anim-1">
                <div>
                    <h1 class="section-title">Journey <span class="text-primary">Planner</span></h1>
                    <p class="section-sub">Find the shortest bus path between two points in <?= htmlspecialchars($city['city_name']) ?></p>
                    <?php if (empty($stops)): ?>
                        <div class="alert alert-warning mt-3" style="background: rgba(232,184,75,0.1); color: #e8b84b; border: 1px solid rgba(232,184,75,0.2); padding: 12px; border-radius: 12px; font-size: 13px;">
                            <strong>No stops found:</strong> Please switch to a different city (e.g., Kolkata) or add stops in the Admin panel.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 350px 1fr; gap: 30px;" class="anim-2">
                <!-- Sidebar Planner Panel -->
                <div class="stop-detail-card" style="margin-top:0; height: fit-content;">
                    <div style="margin-bottom:20px; border-bottom: 1px solid var(--border); padding-bottom: 15px;">
                        <span style="font-family:var(--font-display);font-size:15px;font-weight:700">Set Journey</span>
                    </div>

                    <div class="planner-controls-stack">
                        <div>
                            <label class="meta-key" style="margin-bottom:5px; display:block">From Stop</label>
                            <select id="plannerFrom" class="search-type" style="width:100%; background: var(--surface2); border: 1px solid var(--border); padding: 10px; border-radius: 8px; color: var(--text);">
                                <option value="">Select Origin...</option>
                                <?php foreach ($stops as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['stop_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div style="text-align:center;">
                            <button id="swapPlanner" class="btn-ghost" style="padding: 4px 12px; font-size: 11px;">SWAP</button>
                        </div>

                        <div>
                            <label class="meta-key" style="margin-bottom:5px; display:block">To Stop</label>
                            <select id="plannerTo" class="search-type" style="width:100%; background: var(--surface2); border: 1px solid var(--border); padding: 10px; border-radius: 8px; color: var(--text);">
                                <option value="">Select Destination...</option>
                                <?php foreach ($stops as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['stop_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="meta-key" style="margin-bottom:5px; display:block">Passenger Type</label>
                            <select id="plannerType" class="search-type" style="width:100%; background: var(--surface2); border: 1px solid var(--border); padding: 10px; border-radius: 8px; color: var(--text);">
                                <option value="General">General</option>
                                <option value="Student">Student</option>
                                <option value="Senior">Senior</option>
                            </select>
                        </div>

                        <button id="findPathBtn" class="search-btn mt-16" style="width:100%">FIND BEST ROUTE</button>
                    </div>

                    <div id="plannerLoading" class="text-center py-4 d-none">
                        <div class="skeleton" style="height: 100px; width: 100%;"></div>
                        <p class="text-muted small mt-2">Calculating path...</p>
                    </div>

                    <div id="plannerResults" class="d-none mt-24" style="border-top: 1px solid var(--border); padding-top: 20px;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                            <div class="meta-item" style="background: var(--surface2); padding: 10px; border-radius: 8px;">
                                <span class="meta-val" id="resDist" style="font-size: 16px;">--</span>
                                <span class="meta-key">Distance</span>
                            </div>
                            <div class="meta-item" style="background: var(--surface2); padding: 10px; border-radius: 8px;">
                                <span class="meta-val" id="resTime" style="font-size: 16px;">--</span>
                                <span class="meta-key">Est. Time</span>
                            </div>
                            <div class="meta-item" style="background: var(--surface2); padding: 10px; border-radius: 8px;">
                                <span class="meta-val" id="resTrans" style="font-size: 16px;">--</span>
                                <span class="meta-key">Transfers</span>
                            </div>
                            <div class="meta-item" style="background: var(--surface2); padding: 10px; border-radius: 8px; border: 1px solid var(--accent);">
                                <span class="meta-val text-accent" id="resFare" style="font-size: 16px;">--</span>
                                <span class="meta-key">Total Fare</span>
                            </div>
                        </div>

                        <div id="pathLegs" class="stop-timeline">
                            <!-- Leg elements injected via JS -->
                        </div>
                    </div>

                    <div id="plannerError" class="mt-16 text-accent small d-none" style="background: rgba(232,64,37,0.1); padding: 10px; border-radius: 8px; border: 1px solid rgba(232,64,37,0.2);"></div>
                </div>

                <!-- Map Panel -->
                <div class="map-card anim-3">
                    <div id="plannerMap" style="height: 100%; background: #000; border-radius: 20px;"></div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    window.CITY_DATA = <?= json_encode([
        'id' => $city['id'],
        'name' => $city['city_name'],
        'lat' => (float)$city['center_lat'],
        'lng' => (float)$city['center_lng'],
        'zoom' => (int)$city['default_zoom'],
        'currency' => $city['currency']
    ]) ?>;
</script>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
