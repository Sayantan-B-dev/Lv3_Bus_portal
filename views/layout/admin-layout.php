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
    <title><?= $pageTitle ?? 'Admin' ?> — DTC Route Information Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="p-4 mb-3">
                <div style="background: var(--admin-accent); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-family: 'Syne'; margin-bottom: 16px;">ADM</div>
                <h1 class="h5 font-display fw-bold mb-0" style="letter-spacing: -0.5px;">DTC PORTAL</h1>
                <p class="small text-muted mb-0">Management Hub</p>
            </div>
            
            <nav class="nav flex-column">
                <a class="nav-link <?= ($view ?? '') == 'admin/dashboard' ? 'active' : '' ?>" href="<?= APP_URL ?>/admin">Dashboard</a>
                <a class="nav-link <?= str_starts_with($view ?? '', 'admin/routes') ? 'active' : '' ?>" href="<?= APP_URL ?>/admin/routes">Routes</a>
                <a class="nav-link <?= str_starts_with($view ?? '', 'admin/stops') ? 'active' : '' ?>" href="<?= APP_URL ?>/admin/stops">Stops</a>
                <a class="nav-link <?= str_starts_with($view ?? '', 'admin/cities') ? 'active' : '' ?>" href="<?= APP_URL ?>/admin/cities">Cities</a>
                <a class="nav-link" href="<?= APP_URL ?>/" target="_blank">View Portal</a>
                <div class="mt-auto p-4">
                    <hr class="border-secondary opacity-25">
                    <a class="nav-link text-danger m-0 p-2" href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit()">Logout</a>
                </div>
            </nav>
            
            <form id="logoutForm" action="<?= APP_URL ?>/auth/logout" method="POST" class="d-none"></form>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow-1" style="overflow-y: auto;">
            <header class="admin-header">
                <h2 class="h6 font-display fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;"><?= $pageTitle ?? 'Dashboard' ?></h2>
                <div class="user-info d-flex align-items-center gap-3">
                    <div class="text-end d-none d-md-block">
                        <div class="small fw-bold" style="line-height: 1;"><?= htmlspecialchars($user['name'] ?? 'Admin') ?></div>
                        <span class="text-muted" style="font-size: 10px;"><?= htmlspecialchars($user['role'] ?? 'Staff') ?></span>
                    </div>
                    <?php if ($user['avatar_url'] ?? false): ?>
                        <img src="<?= $user['avatar_url'] ?>" class="rounded-circle border border-secondary" width="32" height="32" alt="p">
                    <?php else: ?>
                        <div class="rounded-circle bg-secondary" style="width: 32px; height: 32px;"></div>
                    <?php endif; ?>
                </div>
            </header>

            <main class="admin-content">
                <?php if ($success = Session::getFlash('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm" style="background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.2) !important;"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error = Session::getFlash('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm" style="background: rgba(232,64,37,0.1); color: var(--admin-accent); border: 1px solid rgba(232,64,37,0.2) !important;"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/admin.js"></script>
</body>
</html>
