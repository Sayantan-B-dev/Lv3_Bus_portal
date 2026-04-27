<?php
/**
 * views/layout/header.php
 */
use App\Middleware\CsrfMiddleware;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Universal City Bus Route Portal — Real-time routes, stops, and journey planning.">
    <link rel="icon" type="image/svg+xml" href="<?= APP_URL ?>/public/assets/img/logo.svg">
    <title><?= htmlspecialchars($pageTitle ?? 'Bus Route Portal') ?> — City Route Information Portal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="<?= APP_URL ?>/public/assets/css/main.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/map.css" rel="stylesheet">

    <script>
        window.APP_URL = "<?= APP_URL ?>";
        window.CSRF_TOKEN = "<?= \App\Middleware\CsrfMiddleware::generateToken() ?>";
    </script>
</head>
<body>
    <!-- CURSOR -->
    <div id="cursor"></div>
    <div id="cursor-ring"></div>

    <!-- MOBILE NAV -->
    <div class="mob-nav" id="mobNav">
        <a href="<?= APP_URL ?>/" onclick="closeMob()">Home</a>
        <a href="<?= APP_URL ?>/routes" onclick="closeMob()">Routes</a>
        <a href="<?= APP_URL ?>/planner" onclick="closeMob()">Planner</a>
        <a href="<?= APP_URL ?>/cities" onclick="closeMob()">Cities</a>
        <?php if (\App\Core\Session::isLoggedIn()): $u = \App\Core\Session::getUser(); ?>
            <?php if (in_array($u['role'] ?? '', ['admin', 'super_admin'], true)): ?>
                <a href="<?= APP_URL ?>/admin" onclick="closeMob()">Admin</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?= APP_URL ?>/auth/login" onclick="closeMob()">Sign In</a>
        <?php endif; ?>
    </div>

    <!-- NAV -->
    <nav>
        <a href="<?= APP_URL ?>/" class="n-logo">
            <div class="n-icon"><?= strtoupper(substr($city['city_name'] ?? 'BUS', 0, 3)) ?></div>
            <span>ROUTES</span>
            <span class="n-badge"><?= htmlspecialchars($city['city_name'] ?? 'City') ?></span>
        </a>

        <div class="n-links">
            <a href="<?= APP_URL ?>/" class="<?= ($view ?? '') == 'home/index' ? 'act' : '' ?>">Home</a>
            <a href="<?= APP_URL ?>/routes" class="<?= ($view ?? '') == 'routes/list' ? 'act' : '' ?>">Routes</a>
            <a href="<?= APP_URL ?>/planner" class="<?= ($view ?? '') == 'planner/index' ? 'act' : '' ?>">Planner</a>
            <a href="<?= APP_URL ?>/cities" class="<?= ($view ?? '') == 'cities/index' ? 'act' : '' ?>">Cities</a>
            <?php if (\App\Core\Session::isLoggedIn() && in_array(\App\Core\Session::getUser()['role'] ?? '', ['admin', 'super_admin'], true)): ?>
                <a href="<?= APP_URL ?>/admin" class="<?= str_starts_with($view ?? '', 'admin/') ? 'act' : '' ?>" target="_blank">Admin</a>
            <?php endif; ?>
        </div>

        <div class="n-cta">
            <?php 
            if (\App\Core\Session::isLoggedIn()): 
                $sessionUser = \App\Core\Session::getUser();
                // Refresh user data from DB to ensure role/permissions are up to date
                $u = (new \App\Models\User())->findById((int)$sessionUser['id']);
                if ($u) {
                    \App\Core\Session::setUser($u); // Update session with fresh data
                } else {
                    $u = $sessionUser; // Fallback
                }
            ?>
                <div class="user-actions">
                    <button class="info-trigger" onclick="toggleUserInfo()" aria-label="User Info">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </button>
                    
                    <div class="profile-dropdown">
                        <button class="profile-btn" onclick="toggleProfileMenu()">
                            <img src="<?= htmlspecialchars($u['avatar_url'] ?? $u['profile_image'] ?? APP_URL . '/public/assets/img/default-avatar.png') ?>" alt="Profile" class="nav-avatar">
                            <span><?= htmlspecialchars($u['name'] ?? 'User') ?></span>
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="dropdown-content" id="profileMenu">
                            <div class="dropdown-header">
                                <strong><?= htmlspecialchars($u['name']) ?></strong>
                                <span><?= htmlspecialchars($u['email']) ?></span>
                            </div>
                            <hr>
                            <a href="<?= APP_URL ?>/profile">View Profile</a>
                            <a href="<?= APP_URL ?>/profile/edit">Edit Profile</a>
                            <?php if (in_array($u['role'] ?? '', ['admin', 'super_admin'], true)): ?>
                                <a href="<?= APP_URL ?>/admin" target="_blank">Dashboard</a>
                            <?php endif; ?>
                            <hr>
                            <form action="<?= APP_URL ?>/auth/logout" method="POST">
                                <button type="submit" class="logout-link">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= APP_URL ?>/auth/login" class="btn-g">Sign In</a>
                <a href="<?= APP_URL ?>/auth/adminLogin" class="btn-o" target="_blank">Admin Panel</a>
            <?php endif; ?>

            <button class="ham" id="hamBtn" onclick="toggleMob()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- USER INFO CARD (Glassy Pop-up) -->
    <?php if (\App\Core\Session::isLoggedIn()): $u = \App\Core\Session::getUser(); ?>
    <div id="userInfoCard" class="user-info-card">
        <div class="card-close" onclick="toggleUserInfo()">Close</div>
        <div class="card-glass"></div>
        <div class="card-content">
            <div class="card-user">
                <img src="<?= htmlspecialchars($u['avatar_url'] ?? $u['profile_image'] ?? APP_URL . '/public/assets/img/default-avatar.png') ?>" alt="" class="card-avatar">
                <h3><?= htmlspecialchars($u['name']) ?></h3>
                <span class="card-role"><?= strtoupper($u['role'] ?? 'Viewer') ?></span>
            </div>
            
            <div class="card-grid">
                <div class="card-item">
                    <label>Email</label>
                    <span><?= htmlspecialchars($u['email']) ?></span>
                </div>
                <div class="card-item">
                    <label>Username</label>
                    <span><?= htmlspecialchars($u['username'] ?? 'N/A') ?></span>
                </div>
                <div class="card-item">
                    <label>Phone</label>
                    <span><?= htmlspecialchars($u['phone'] ?? 'N/A') ?></span>
                </div>
                <div class="card-item">
                    <label>Location</label>
                    <?php if (!empty($u['city'])): ?>
                        <span><?= htmlspecialchars($u['city']) . ($u['state'] ? ', ' . $u['state'] : '') ?></span>
                    <?php elseif (!empty($u['latitude'])): ?>
                        <span>Lat: <?= round($u['latitude'], 4) ?>, Lng: <?= round($u['longitude'], 4) ?></span>
                    <?php else: ?>
                        <span>N/A</span>
                    <?php endif; ?>
                </div>

                <?php if ($u['is_student']): ?>
                <div class="card-item full-width">
                    <label>Student Academic Info</label>
                    <div class="card-sub-grid">
                        <div class="card-item"><label>College</label><span><?= htmlspecialchars($u['college_name'] ?? 'N/A') ?></span></div>
                        <div class="card-item"><label>Reg No</label><span><?= htmlspecialchars($u['college_registration_number'] ?? 'N/A') ?></span></div>
                        <div class="card-item"><label>Roll No</label><span><?= htmlspecialchars($u['roll_number'] ?? 'N/A') ?></span></div>
                        <div class="card-item"><label>Branch</label><span><?= htmlspecialchars($u['branch'] ?? 'N/A') ?></span></div>
                        <div class="card-item"><label>Year</label><span><?= htmlspecialchars($u['year_of_study'] ?? 'N/A') ?></span></div>
                        <div class="card-item"><label>Semester</label><span><?= htmlspecialchars($u['semester'] ?? 'N/A') ?> Semester</span></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card-item full-width">
                    <label>Bio</label>
                    <p class="card-bio"><?= htmlspecialchars($u['bio'] ?? 'No bio provided.') ?></p>
                </div>

                <?php if (!empty($u['skills'])): ?>
                <div class="card-item full-width">
                    <label>Skills</label>
                    <div class="card-skills">
                        <?php foreach (explode(',', $u['skills']) as $skill): ?>
                            <span class="card-skill-tag"><?= trim(htmlspecialchars($skill)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card-item full-width">
                    <label>Social Links</label>
                    <div class="card-links">
                        <?php if ($u['linkedin_url']): ?><a href="<?= $u['linkedin_url'] ?>" target="_blank">LinkedIn</a><?php endif; ?>
                        <?php if ($u['github_url']): ?><a href="<?= $u['github_url'] ?>" target="_blank">GitHub</a><?php endif; ?>
                        <?php if ($u['portfolio_url']): ?><a href="<?= $u['portfolio_url'] ?>" target="_blank">Portfolio</a><?php endif; ?>
                    </div>
                </div>

                <div class="card-item">
                    <label>Joined</label>
                    <span><?= date('M d, Y', strtotime($u['created_at'])) ?></span>
                </div>
            </div>
            
            <div class="card-actions">
                <a href="<?= APP_URL ?>/profile/edit" class="btn-o">Update Profile</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <main>
