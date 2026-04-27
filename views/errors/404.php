<?php
/**
 * views/errors/404.php
 * Stylish 404 Error Page
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="page error-page">
    <div class="container">
        <div class="error-container reveal">
            <div class="error-glitch-wrap">
                <h1 class="error-code">404</h1>
                <div class="error-glitch-overlay">404</div>
            </div>
            
            <div class="error-text-content">
                <h2 class="error-title">Lost in <span class="text-primary">Transit?</span></h2>
                <p class="error-desc">The route you are looking for doesn't exist or has been relocated to a different city.</p>
                
                <div class="error-actions">
                    <a href="<?= APP_URL ?>" class="btn-primary">
                        <i class="fas fa-home"></i> Back to Safety
                    </a>
                </div>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="error-decor">
            <div class="decor-circle circle-1"></div>
            <div class="decor-circle circle-2"></div>
        </div>
    </div>
</div>

<style>
.error-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.error-container {
    text-align: center;
    position: relative;
    z-index: 2;
}

.error-glitch-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 20px;
}

.error-code {
    font-family: var(--font-display);
    font-size: clamp(120px, 20vw, 240px);
    line-height: 1;
    color: var(--text);
    margin: 0;
    letter-spacing: -5px;
    opacity: 0.15;
    background: linear-gradient(180deg, var(--text) 0%, transparent 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.error-glitch-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    font-family: var(--font-display);
    font-size: clamp(120px, 20vw, 240px);
    line-height: 1;
    color: var(--accent);
    letter-spacing: -5px;
    clip-path: polygon(0 0, 100% 0, 100% 45%, 0 45%);
    transform: translate(-4px, -2px);
    animation: glitch 4s infinite linear alternate-reverse;
}

@keyframes glitch {
    0% { clip-path: polygon(0 0, 100% 0, 100% 45%, 0 45%); transform: translate(-4px, -2px); }
    20% { clip-path: polygon(0 15%, 100% 15%, 100% 55%, 0 55%); transform: translate(4px, 2px); }
    40% { clip-path: polygon(0 40%, 100% 40%, 100% 85%, 0 85%); transform: translate(-2px, 4px); }
    60% { clip-path: polygon(0 60%, 100% 60%, 100% 100%, 0 100%); transform: translate(2px, -4px); }
    80% { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); transform: translate(0); }
    100% { clip-path: polygon(0 20%, 100% 20%, 100% 60%, 0 60%); transform: translate(-4px, 2px); }
}

.error-title {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 56px);
    margin-bottom: 15px;
    letter-spacing: 1px;
}

.error-desc {
    color: var(--muted);
    font-size: 18px;
    max-width: 500px;
    margin: 0 auto 40px;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.error-decor .decor-circle {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 1;
    opacity: 0.2;
}

.circle-1 {
    width: 400px;
    height: 400px;
    background: var(--accent);
    top: -100px;
    left: -100px;
}

.circle-2 {
    width: 300px;
    height: 300px;
    background: var(--blue);
    bottom: -50px;
    right: -50px;
}

@media (max-width: 768px) {
    .error-actions { flex-direction: column; align-items: stretch; padding: 0 40px; }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
