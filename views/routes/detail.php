<?php
/**
 * views/routes/detail.php
 */
$extraScripts = ['map'];
include dirname(__DIR__) . '/layout/header.php';
?>

<main class="page">
    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="section-header anim-1">
                <div>
                    <a href="<?= APP_URL ?>/routes" class="text-muted small text-decoration-none mb-2 d-inline-block">← BACK TO LIST</a>
                    <div class="section-title">Route <?= htmlspecialchars($route['route_number']) ?> — Detail View</div>
                    <div class="section-sub">
                        <?= htmlspecialchars($route['source']) ?> → <?= htmlspecialchars($route['destination']) ?> 
                        · <?= $route['total_distance_km'] ?> km 
                        · <?= $route['route_type'] ?>
                    </div>
                </div>
                <span class="route-badge badge-<?= strtolower($route['route_type']) ?>" style="font-size:12px;padding:5px 14px">● Operational</span>
            </div>

            <!-- MAP SECTION -->
            <div class="map-card anim-2">
                <div id="routeMap" style="height: 100%; width: 100%; border-radius: 20px;"></div>
                
                <div class="map-badge-overlay d-none d-md-flex">
                    <div class="map-overlay-item">
                        <span class="map-overlay-val"><?= $route['total_distance_km'] ?> km</span>
                        <span class="map-overlay-key">Total Distance</span>
                    </div>
                    <div class="map-overlay-divider"></div>
                    <div class="map-overlay-item">
                        <span class="map-overlay-val">~<?= round($route['total_distance_km'] * 2.5) ?> min</span>
                        <span class="map-overlay-key">Est. Duration</span>
                    </div>
                    <div class="map-overlay-divider"></div>
                    <div class="map-overlay-item">
                        <span class="map-overlay-val"><?= count($route['stops']) ?> stops</span>
                        <span class="map-overlay-key">Total Stops</span>
                    </div>
                </div>
            </div>

            <!-- TABS -->
            <div class="tabs anim-3 mt-24">
                <button class="tab active" data-target="tab-stops">Stop Sequence</button>
                <button class="tab" data-target="tab-fares">Fare Table</button>
                <button class="tab">Schedule</button>
                <button class="tab">API Reference</button>
            </div>

            <!-- STOPS + FARE SIDE BY SIDE -->
            <div class="route-details-grid anim-4">

                <!-- STOP TIMELINE -->
                <div class="stop-detail-card tab-content" id="tab-stops">
                    <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between">
                        <span style="font-family:var(--font-display);font-size:15px;font-weight:700">Stop Sequence</span>
                        <span style="color:var(--muted);font-size:12px"><?= count($route['stops']) ?> stops total</span>
                    </div>
                    <div class="stop-timeline">
                        <?php foreach ($route['stops'] as $index => $s): ?>
                            <?php 
                                $class = '';
                                if ($index === 0 || $index === count($route['stops']) - 1) $class = 'terminal';
                                elseif ($s['is_major_stop']) $class = 'major';
                            ?>
                            <div class="stop-item <?= $class ?>">
                                <div class="stop-name">
                                    <?= htmlspecialchars($s['stop_name']) ?>
                                    <small>
                                        <?= $index === 0 ? 'Start Terminal' : ($index === count($route['stops']) - 1 ? 'End Terminal' : $s['distance_from_start_km'] . ' km from start') ?>
                                        <?= $s['landmark'] ? ' · ' . htmlspecialchars($s['landmark']) : '' ?>
                                    </small>
                                </div>
                                <span class="stop-time"><?= $index === 0 ? date('H:i', strtotime($route['first_bus_time'])) : '+' . $s['arrival_time_offset_mins'] . 'm' ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- FARE TABLE -->
                <div class="fare-table-wrap tab-content" id="tab-fares">
                    <div style="padding:20px 20px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                        <span style="font-family:var(--font-display);font-size:15px;font-weight:700">Fare Slabs</span>
                        <span style="color:var(--muted);font-size:12px">Route <?= htmlspecialchars($route['route_number']) ?> · <?= $route['route_type'] ?></span>
                    </div>
                    <div class="adm-table-wrap" style="border:none">
                        <table>
                            <thead>
                                <tr>
                                    <th>Distance Slab</th>
                                    <th>Passenger Type</th>
                                    <th>Fare</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($route['fares'] as $f): ?>
                                    <tr>
                                        <td><?= $f['min_km'] ?> – <?= $f['max_km'] ?> km</td>
                                        <td><?= htmlspecialchars($f['passenger_type']) ?></td>
                                        <td class="fare-price"><?= $city['currency'] ?> <?= $f['fare_amount'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;gap:20px; flex-wrap: wrap;">
                        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted)">
                            <span style="width:10px;height:10px;border-radius:50%;background:#60a5fa;display:inline-block"></span>
                            Concessional rates apply
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted)">
                            <span style="width:10px;height:10px;border-radius:50%;background:var(--accent);display:inline-block"></span>
                            Exact fare may vary
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

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
