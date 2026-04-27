<?php
/**
 * views/errors/500.php
 */
// Fallback layout since regular header might fail
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Server Error — Bus Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300&display=swap" rel="stylesheet">
    <link href="<?= defined('APP_URL') ? APP_URL : '' ?>/public/assets/css/main.css" rel="stylesheet">
</head>
<body>
    <!-- CURSOR -->
    <div id="cursor"></div>
    <div id="cursor-ring"></div>

    <div class="error-shell">
        <div class="error-content">
            <div class="error-code">500</div>
            <div class="error-title">ENGINE BREAKDOWN.</div>
            <div class="error-note">Something went wrong on our end. We’re working to get the portal back on track.</div>
            <a href="<?= defined('APP_URL') ? APP_URL : '/' ?>" class="error-home-link">RETURN TO HOME</a>
        </div>
    </div>

    <script src="<?= defined('APP_URL') ? APP_URL : '' ?>/public/assets/js/main.js"></script>
</body>
</html>
