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
    <title>Admin Login — Bus Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body class="auth-container">
    <div class="auth-card">
        <h1 class="logo-text mb-4">BUS PORTAL</h1>
        <h2 class="h5 font-rajdhani text-white mb-4">ADMIN ACCESS</h2>
        <div class="mb-3"><span class="badge bg-danger bg-opacity-25 text-danger font-rajdhani">AUTHORIZED PERSONNEL ONLY</span></div>

        <?php if ($error = \App\Core\Session::getFlash('error')): ?>
            <div class="alert alert-danger small py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <p class="text-muted small mb-4">Please log in with your authorized Google account to access the management dashboard.</p>
        
        <a href="<?= APP_URL ?>/auth/google" class="btn btn-google">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" width="20" height="20" alt="G">
            Sign in with Google
        </a>

        <div class="mt-5">
            <a href="<?= APP_URL ?>/" class="text-muted small">← Back to Public Portal</a>
        </div>
    </div>
</body>
</html>
