<?php
/**
 * views/errors/404.php
 */
$pageTitle = '404 Page Not Found';
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="container-custom py-5 text-center">
    <div class="py-5">
        <h1 class="display-1 font-rajdhani text-primary">404</h1>
        <h2 class="h3 font-rajdhani text-white mb-4">THE BUS HAS ALREADY LEFT.</h2>
        <p class="text-muted mb-5">The route or page you are looking for does not exist or has been moved.</p>
        <a href="<?= APP_URL ?>/" class="btn btn-outline-warning px-5 font-rajdhani">RETURN TO HOME</a>
    </div>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
