    </main>

    <!-- FOOTER -->
    <footer>
        <div class="f-logo">DTC · ROUTE PORTAL</div>
        <div class="f-links">
            <a href="<?= APP_URL ?>/api">API</a>
            <a href="<?= APP_URL ?>/planner">Planner</a>
            <a href="<?= APP_URL ?>/routes">Routes</a>
            <a href="<?= APP_URL ?>/cities">Cities</a>
        </div>
        <div class="f-copy">© <?= date('Y') ?> <?= htmlspecialchars($city['city_name'] ?? 'Delhi') ?> Transport System · v1.0</div>
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
