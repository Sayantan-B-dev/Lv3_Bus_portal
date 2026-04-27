    </main>

    <!-- FOOTER -->
    <footer class="fixed-footer">
        <div class="footer-container">
            <div class="f-left">
                <div class="f-logo-small"><?= strtoupper($city['city_name'] ?? 'CITY') ?> TRANSIT</div>
                <div class="f-status">
                    <span class="status-dot"></span> System Live
                </div>
            </div>
            
            <div class="f-links-compact">
                <a href="<?= APP_URL ?>/routes">Routes</a>
                <a href="<?= APP_URL ?>/planner">Planner</a>
                <a href="<?= APP_URL ?>/cities">Cities</a>
            </div>

            <div class="f-right">
                <div class="f-emergency">Support: 1800-BUS-HELP</div>
                <div class="f-copy-small">© <?= date('Y') ?> · v1.2</div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="<?= APP_URL ?>/public/assets/js/main.js"></script>
    <script src="<?= APP_URL ?>/public/assets/js/city-selector.js"></script>
    <?php if (isset($extraScripts)): foreach ($extraScripts as $script): ?>
        <script src="<?= APP_URL ?>/public/assets/js/<?= $script ?>.js"></script>
    <?php endforeach; endif; ?>
</body>
</html>
