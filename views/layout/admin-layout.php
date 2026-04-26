<?php
/**
 * views/layout/admin-layout.php
 */
use App\Core\Session;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> — Bus Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="p-4">
                <h1 class="h4 font-rajdhani text-warning mb-0">BUS PORTAL</h1>
                <p class="small text-muted mb-4">Management Dashboard</p>
                
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?= APP_URL ?>/admin">Dashboard</a>
                    <a class="nav-link" href="<?= APP_URL ?>/admin/routes">Routes</a>
                    <a class="nav-link" href="<?= APP_URL ?>/admin/stops">Stops</a>
                    <a class="nav-link" href="<?= APP_URL ?>/admin/cities">Cities</a>
                    <hr class="border-secondary">
                    <a class="nav-link text-danger" href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit()">Logout</a>
                </nav>
            </div>
            
            <form id="logoutForm" action="<?= APP_URL ?>/auth/logout" method="POST" class="d-none"></form>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <header class="admin-header">
                <h2 class="h5 font-rajdhani mb-0"><?= $pageTitle ?? 'Dashboard' ?></h2>
                <div class="user-info d-flex align-items-center gap-2">
                    <span class="small fw-bold"><?= htmlspecialchars($user['name'] ?? 'Admin') ?></span>
                    <?php if ($user['avatar_url'] ?? false): ?>
                        <img src="<?= $user['avatar_url'] ?>" class="rounded-circle" width="32" height="32" alt="p">
                    <?php endif; ?>
                </div>
            </header>

            <main class="admin-content">
                <?php if ($success = Session::getFlash('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error = Session::getFlash('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/admin.js"></script>
</body>
</html>
