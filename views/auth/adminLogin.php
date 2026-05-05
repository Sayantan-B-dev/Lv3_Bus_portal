<?php
/**
 * views/auth/login.php
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — DTC Route Information Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/main.css?v=<?= filemtime(BASE_PATH . '/public/assets/css/main.css') ?>" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/main_component/auth.css?v=<?= filemtime(BASE_PATH . '/public/assets/css/main_component/auth.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- CURSOR -->
    <div id="cursor"></div>
    <div id="cursor-ring"></div>

    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="logo-badge" style="margin: 0 auto 20px;">DTC</div>

            <div class="sec-title" style="font-size:42px">Admin Login</div>

            <p class="auth-note auth-note-spacing">Restricted Access Only</p>

            <?php if ($error = \App\Core\Session::getFlash('error')): ?>
                <div class="alert" style="margin-bottom:20px;border-color:rgba(255,69,0,.25);color:var(--accent2);background:rgba(255,69,0,.08)">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <p class="auth-note">
                This portal is reserved for authorized administrators only. 
                Sign in using your official Google account to access route management tools,
                analytics dashboards, and system controls.
            </p>

            <a href="<?= APP_URL ?>/auth/google" class="auth-google-action">
                <img 
                    src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" 
                    width="20" 
                    height="20" 
                    alt="G"
                >
                Sign in as Administrator
            </a>

            <div class="auth-footer-link-wrap">
                <a 
                    href="<?= APP_URL ?>/" 
                    class="auth-back-link" 
                >
                    ← Back to Public Portal
                </a>
            </div>

        </div>
    </div>

    <script src="<?= APP_URL ?>/public/assets/js/main.js"></script>
</body>
</html>