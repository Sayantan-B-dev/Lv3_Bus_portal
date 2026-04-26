<?php
/**
 * views/routes/detail.php
 */
$extraScripts = ['map'];
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar Detail Panel -->
        <div class="col-lg-4 col-xl-3 bg-surface border-end border-dark" style="height: calc(100vh - 64px); overflow-y: auto;">
            <div class="p-4">
                <a href="<?= APP_URL ?>/routes" class="text-muted small mb-3 d-inline-block">← BACK TO LIST</a>
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="route-number fs-1"><?= htmlspecialchars($route['route_number']) ?></div>
                    <div>
                        <span class="badge badge-type badge-<?= strtolower($route['route_type']) ?> mb-1"><?= $route['route_type'] ?></span>
                        <h1 class="h4 font-rajdhani mb-0"><?= htmlspecialchars($route['source']) ?> <br><span class="text-primary">TO</span> <?= htmlspecialchars($route['destination']) ?></h1>
                    </div>
                </div>

                <div class="info-grid bg-bg border border-dark mb-4">
                    <div class="info-item col-6">
                        <span class="info-label">Freq</span>
                        <span class="info-value"><?= $route['frequency_mins'] ?>m</span>
                    </div>
                    <div class="info-item col-6">
                        <span class="info-label">Dist</span>
                        <span class="info-value"><?= $route['total_distance_km'] ?>k</span>
                    </div>
                    <div class="info-item col-6">
                        <span class="info-label">Start</span>
                        <span class="info-value val-green"><?= date('H:i', strtotime($route['first_bus_time'])) ?></span>
                    </div>
                    <div class="info-item col-6">
                        <span class="info-label">Last</span>
                        <span class="info-value val-amber"><?= date('H:i', strtotime($route['last_bus_time'])) ?></span>
                    </div>
                </div>

                <div class="tabs-container">
                    <ul class="nav nav-pills nav-fill bg-bg border border-dark p-1 mb-4">
                        <li class="nav-item">
                            <button class="nav-link active font-rajdhani py-1" data-bs-toggle="pill" data-bs-target="#tab-stops">STOPS</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link font-rajdhani py-1" data-bs-toggle="pill" data-bs-target="#tab-fares">FARES</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Stops Tab -->
                        <div class="tab-pane fade show active" id="tab-stops">
                            <div class="timeline">
                                <?php foreach ($route['stops'] as $s): ?>
                                    <div class="timeline-item <?= $s['is_major_stop'] ? 'major' : '' ?>">
                                        <div class="timeline-dot"></div>
                                        <div class="stop-name"><?= htmlspecialchars($s['stop_name']) ?></div>
                                        <div class="stop-meta">
                                            <?= $s['distance_from_start_km'] ?> km • <?= $s['arrival_time_offset_mins'] ?> mins
                                            <?php if ($s['landmark']): ?>
                                                <br><span class="text-muted fs-xs font-noto fw-light">Near: <?= htmlspecialchars($s['landmark']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Fares Tab -->
                        <div class="tab-pane fade" id="tab-fares">
                            <table class="table table-dark table-sm small">
                                <thead class="text-muted font-rajdhani">
                                    <tr>
                                        <th>DISTANCE (KM)</th>
                                        <th>TYPE</th>
                                        <th class="text-end">FARE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($route['fares'] as $f): ?>
                                        <tr>
                                            <td><?= $f['min_km'] ?> - <?= $f['max_km'] ?></td>
                                            <td><?= $f['passenger_type'] ?></td>
                                            <td class="text-end text-primary fw-bold"><?= $city['currency'] ?> <?= $f['fare_amount'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <p class="text-muted xsmall mt-3">Fares are subject to change by transit authority.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Panel -->
        <div class="col-lg-8 col-xl-9">
            <div id="routeMap" style="height: calc(100vh - 64px); background: #000;"></div>
        </div>
    </div>
</div>

<!-- Map Data Injection -->
<script>
    window.MAP_DATA = <?= json_encode([
        'city' => [
            'lat' => (float)$city['center_lat'],
            'lng' => (float)$city['center_lng'],
            'zoom' => (int)$city['default_zoom']
        ],
        'route' => [
            'id' => $route['id'],
            'number' => $route['route_number'],
            'type' => $route['route_type'],
            'stops' => array_map(function($s) {
                return [
                    'lat' => (float)$s['latitude'],
                    'lng' => (float)$s['longitude'],
                    'name' => $s['stop_name'],
                    'is_major' => (bool)$s['is_major_stop']
                ];
            }, $route['stops'])
        ]
    ]) ?>;
</script>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
