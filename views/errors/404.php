<?php
/**
 * views/errors/404.php
 */
$pageTitle = '404 Page Not Found';
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="error-shell">
    <div class="error-content">
        <h1 class="error-code">404</h1>
        <h2 class="error-title">THE BUS HAS ALREADY LEFT.</h2>
        <p class="error-note">The route or page you are looking for does not exist or has been moved.</p>
        <a href="<?= APP_URL ?>/" class="error-home-link">RETURN TO HOME</a>
    </div>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
