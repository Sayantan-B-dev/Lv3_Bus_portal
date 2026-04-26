<?php
/**
 * views/layout/footer.php
 */
?>
    </main>

    <footer class="site-footer">
        <div class="container-custom">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="text-white mb-3">Bus Route Portal</h4>
                    <p>A universal information system for city bus routes. Seeded with Kolkata data, but city-agnostic by design.</p>
                </div>
                <div class="col-md-2 offset-md-2 mb-4">
                    <h5 class="font-rajdhani text-white mb-3">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="<?= APP_URL ?>/">Home</a></li>
                        <li><a href="<?= APP_URL ?>/routes">Routes</a></li>
                        <li><a href="<?= APP_URL ?>/planner">Planner</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="font-rajdhani text-white mb-3">City Focus</h5>
                    <p>Currently viewing: <strong class="text-primary"><?= htmlspecialchars($city['city_name'] ?? 'None') ?></strong></p>
                    <p class="small"><?= htmlspecialchars($city['state_region'] ?? '') ?>, <?= htmlspecialchars($city['country'] ?? '') ?></p>
                </div>
            </div>
            <div class="border-top border-secondary pt-4 mt-4 text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> Universal Bus Portal. Open Source Project.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="<?= APP_URL ?>/assets/js/main.js"></script>
    <script src="<?= APP_URL ?>/assets/js/city-selector.js"></script>
    <?php if (isset($extraScripts)): foreach ($extraScripts as $script): ?>
        <script src="<?= APP_URL ?>/assets/js/<?= $script ?>.js"></script>
    <?php endforeach; endif; ?>
</body>
</html>
