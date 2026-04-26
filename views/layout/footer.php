    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-inner">
                <div class="footer-logo">DTC Route Portal v1.0</div>
                <div class="footer-copy">© <?= date('Y') ?> <?= htmlspecialchars($city['city_name'] ?? 'Delhi') ?> Transport System. Open Source — Internal.</div>
                <div class="footer-links">
                    <a href="<?= APP_URL ?>/api">API</a>
                    <a href="<?= APP_URL ?>/planner">Planner</a>
                    <a href="<?= APP_URL ?>/routes">Routes</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="<?= APP_URL ?>/assets/js/main.js"></script>
    <script src="<?= APP_URL ?>/assets/js/city-selector.js"></script>
    <?php if (isset($extraScripts)): foreach ($extraScripts as $script): ?>
        <script src="<?= APP_URL ?>/assets/js/<?= $script ?>.js"></script>
    <?php endforeach; endif; ?>
</body>
</html>
