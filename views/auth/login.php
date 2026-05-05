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
    <title>Login — DTC Route Information Portal</title>
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

            <div class="sec-title auth-heading" style="font-size:42px">Login</div>

            <p class="auth-note auth-note-spacing">Sign in to continue</p>

            <?php if ($error = \App\Core\Session::getFlash('error')): ?>
                <div class="auth-alert error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success = \App\Core\Session::getFlash('success')): ?>
                <div class="auth-alert success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if ($info = \App\Core\Session::getFlash('info')): ?>
                <div class="auth-alert info">
                    <?= htmlspecialchars($info) ?>
                </div>
            <?php endif; ?>

            <p class="auth-note">Quick sign-in with Google</p>

            <a href="<?= APP_URL ?>/auth/google" class="auth-google-action">
                <img 
                    src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" 
                    width="20" 
                    height="20" 
                    alt="G"
                >
                Continue with Google
            </a>

            <div class="auth-divider">or continue with email</div>

            <form class="auth-form" method="post" action="<?= APP_URL ?>/auth/login" autocomplete="on">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\App\Middleware\CsrfMiddleware::generateToken()) ?>">

                <div class="auth-form-group">
                    <label for="identifier">Email or username</label>
                    <input class="auth-input" type="text" id="identifier" name="identifier" required
                           value="<?= htmlspecialchars((string)(\App\Core\Session::getFlash('identifier_prefill') ?: ($_POST['identifier'] ?? ''))) ?>"
                           autocomplete="username">
                </div>

                <div class="auth-form-group">
                    <label for="password">Password</label>
                    <input class="auth-input" type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <button type="submit" class="auth-submit">Sign in</button>
            </form>

            <p class="auth-inline-link">
                New here? <a href="<?= APP_URL ?>/auth/register">Create an account</a>
            </p>

            <div class="auth-footer-link-wrap">
                <a 
                    href="<?= APP_URL ?>/" 
                    class="auth-back-link" 
                >
                    ← Back to Home
                </a>
            </div>

        </div>
    </div>

    <script src="<?= APP_URL ?>/public/assets/js/main.js"></script>
</body>
</html>