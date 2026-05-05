<?php
/**
 * views/auth/register.php
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — DTC Route Information Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/main.css?v=<?= filemtime(BASE_PATH . '/public/assets/css/main.css') ?>" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/main_component/auth.css?v=<?= filemtime(BASE_PATH . '/public/assets/css/main_component/auth.css') ?>" rel="stylesheet">
</head>
<body>
    <div id="cursor"></div>
    <div id="cursor-ring"></div>

    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="logo-badge" style="margin: 0 auto 20px;">DTC</div>

            <div class="sec-title auth-heading" style="font-size:42px">Register</div>

            <p class="auth-note auth-note-spacing">Create an account with email and password</p>

            <?php if ($error = \App\Core\Session::getFlash('error')): ?>
                <div class="auth-alert error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <p class="auth-note">Use Google for one-click signup</p>

            <a href="<?= APP_URL ?>/auth/google" class="auth-google-action">
                <img
                    src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png"
                    width="20"
                    height="20"
                    alt=""
                >
                Continue with Google
            </a>

            <div class="auth-divider">or create with email</div>

            <form class="auth-form" method="post" action="<?= APP_URL ?>/auth/register" autocomplete="on">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\App\Middleware\CsrfMiddleware::generateToken()) ?>">

                <div class="auth-form-group">
                    <label for="name">Display name <span style="opacity:.6">(optional)</span></label>
                    <input class="auth-input" type="text" id="name" name="name" maxlength="150"
                           value="<?= htmlspecialchars((string)($_POST['name'] ?? '')) ?>">
                </div>

                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input class="auth-input" type="email" id="email" name="email" required maxlength="150"
                           value="<?= htmlspecialchars((string)($_POST['email'] ?? '')) ?>">
                </div>

                <div class="auth-form-group">
                    <label for="username">Username</label>
                    <input class="auth-input" type="text" id="username" name="username" required
                           pattern="[a-zA-Z0-9_]{3,50}" maxlength="50"
                           title="3–50 characters: letters, numbers, underscore"
                           value="<?= htmlspecialchars((string)($_POST['username'] ?? '')) ?>">
                </div>

                <div class="auth-form-group">
                    <label for="password">Password</label>
                    <input class="auth-input" type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
                </div>

                <div class="auth-form-group">
                    <label for="password_confirm">Confirm password</label>
                    <input class="auth-input" type="password" id="password_confirm" name="password_confirm" required minlength="8" autocomplete="new-password">
                </div>

                <button type="submit" class="auth-submit">Create account</button>
            </form>

            <p class="auth-inline-link">
                Already have an account? <a href="<?= APP_URL ?>/auth/login">Sign in</a>
            </p>

            <div class="auth-footer-link-wrap">
                <a href="<?= APP_URL ?>/" class="auth-back-link">← Back to Home</a>
            </div>

        </div>
    </div>

    <script src="<?= APP_URL ?>/public/assets/js/main.js"></script>
</body>
</html>
