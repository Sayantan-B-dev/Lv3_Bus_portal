<?php
/**
 * views/planner/index.php
 */
$extraScripts = ['dijkstra', 'map'];
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar Planner Panel -->
        <div class="col-lg-4 col-xl-3 bg-surface border-end border-dark" style="height: calc(100vh - 64px); overflow-y: auto;">
            <div class="p-4">
                <h1 class="h3 font-rajdhani mb-1">Journey <span class="text-primary">Planner</span></h1>
                <p class="text-muted small mb-4">Find the shortest bus path between two points.</p>

                <div class="bg-bg border border-dark p-3 mb-4">
                    <div class="mb-3">
                        <label class="info-label">From Stop</label>
                        <select id="plannerFrom" class="city-selector w-100 p-2">
                            <option value="">Select Origin...</option>
                            <?php foreach ($stops as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['stop_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 text-center">
                        <button id="swapPlanner" class="btn btn-outline-secondary btn-sm font-rajdhani py-0 px-2">⇅ SWAP</button>
                    </div>
                    <div class="mb-3">
                        <label class="info-label">To Stop</label>
                        <select id="plannerTo" class="city-selector w-100 p-2">
                            <option value="">Select Destination...</option>
                            <?php foreach ($stops as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['stop_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="info-label">Passenger Type</label>
                        <select id="plannerType" class="city-selector w-100 p-2">
                            <option value="General">General</option>
                            <option value="Student">Student</option>
                            <option value="Senior">Senior</option>
                        </select>
                    </div>
                    <button id="findPathBtn" class="search-btn w-100 py-2 font-rajdhani mt-2">FIND BEST ROUTE</button>
                </div>

                <div id="plannerLoading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted small mt-2">Calculating path...</p>
                </div>

                <div id="plannerResults" class="d-none">
                    <div class="info-grid bg-bg border border-dark mb-4">
                        <div class="info-item col-6">
                            <span class="info-label">Dist</span>
                            <span class="info-value" id="resDist">--</span>
                        </div>
                        <div class="info-item col-6">
                            <span class="info-label">Time</span>
                            <span class="info-value" id="resTime">--</span>
                        </div>
                        <div class="info-item col-6">
                            <span class="info-label">Transfers</span>
                            <span class="info-value" id="resTrans">--</span>
                        </div>
                        <div class="info-item col-6">
                            <span class="info-label">Fare</span>
                            <span class="info-value text-primary" id="resFare">--</span>
                        </div>
                    </div>

                    <div id="pathLegs" class="timeline">
                        <!-- Leg elements injected via JS -->
                    </div>
                </div>

                <div id="plannerError" class="alert alert-danger d-none small"></div>
            </div>
        </div>

        <!-- Map Panel -->
        <div class="col-lg-8 col-xl-9">
            <div id="plannerMap" style="height: calc(100vh - 64px); background: #000;"></div>
        </div>
    </div>
</div>

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
