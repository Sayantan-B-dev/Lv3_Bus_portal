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
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/main.css" rel="stylesheet">
    
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 48px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            box-shadow: 0 24px 80px rgba(0,0,0,0.5);
            animation: fadeSlideUp 0.6s ease both;
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px;
            background: white;
            color: #1f1f1f;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-family: var(--font-body);
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 24px;
        }

        .btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255,255,255,0.1);
            background: #f7f7f7;
        }
    </style>
</head>
<body>

    <!-- Ambient Glow -->
    <div class="ambient amb1"></div>
    <div class="ambient amb2"></div>
    <div class="ambient amb3"></div>

    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="logo-badge" style="margin: 0 auto 20px;">DTC</div>

            <h1 style="font-size: 28px; margin-bottom: 8px;">Admin Login</h1>

            <p class="text-muted small mb-4">Restricted Access Only</p>

            <?php if ($error = \App\Core\Session::getFlash('error')): ?>
                <div style="background: rgba(232,64,37,0.1); color: var(--accent2); padding: 12px; border-radius: 12px; border: 1px solid rgba(232,64,37,0.2); font-size: 13px; margin-bottom: 20px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <p class="text-muted" style="font-size: 14px; line-height: 1.6;">
                This portal is reserved for authorized administrators only. 
                Sign in using your official Google account to access route management tools,
                analytics dashboards, and system controls.
            </p>

            <a href="<?= APP_URL ?>/auth/google" class="btn-google">
                <img 
                    src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" 
                    width="20" 
                    height="20" 
                    alt="G"
                >
                Sign in as Administrator
            </a>

            <div class="mt-24">
                <a 
                    href="<?= APP_URL ?>/" 
                    class="see-all justify-content-center" 
                    style="justify-content: center;"
                >
                    ← Back to Public Portal
                </a>
            </div>

        </div>
    </div>

</body>
</html>