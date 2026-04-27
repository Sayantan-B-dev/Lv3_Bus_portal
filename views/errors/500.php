<?php
/**
 * views/errors/500.php
 * Stylish 500 Server Error Page
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="page error-page">
    <div class="container">
        <div class="error-container reveal">
            <div class="error-glitch-wrap">
                <h1 class="error-code code-500">500</h1>
                <div class="error-glitch-overlay overlay-500">500</div>
            </div>
            
            <div class="error-text-content">
                <h2 class="error-title">Engine <span class="text-primary">Failure!</span></h2>
                <p class="error-desc">Our servers are experiencing some turbulence. We are working on fixing the issue and getting you back on the road.</p>
                
                <div class="error-actions">
                    <a href="<?= APP_URL ?>" class="btn-primary">
                        <i class="fas fa-redo"></i> Try Refreshing
                    </a>
                    <a href="<?= APP_URL ?>/routes" class="btn-o">
                        <i class="fas fa-bus"></i> View All Routes
                    </a>
                </div>
            </div>
        </div>

        <!-- Decorative Elements -->
        <div class="error-decor">
            <div class="decor-circle circle-500-1"></div>
            <div class="decor-circle circle-500-2"></div>
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
    opacity: 0.1;
    background: linear-gradient(180deg, var(--text) 0%, transparent 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.code-500 { color: #ff3e3e; }

.error-glitch-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    font-family: var(--font-display);
    font-size: clamp(120px, 20vw, 240px);
    line-height: 1;
    color: #ff3e3e;
    letter-spacing: -5px;
    clip-path: polygon(0 0, 100% 0, 100% 45%, 0 45%);
    transform: translate(-4px, -2px);
    animation: glitch 3s infinite linear alternate-reverse;
}

.overlay-500 {
    text-shadow: 0 0 30px rgba(255, 62, 62, 0.5);
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
    filter: blur(100px);
    z-index: 1;
    opacity: 0.15;
}

.circle-500-1 {
    width: 500px;
    height: 500px;
    background: #ff3e3e;
    top: -150px;
    right: -100px;
}

.circle-500-2 {
    width: 400px;
    height: 400px;
    background: var(--purple);
    bottom: -100px;
    left: -100px;
}

@media (max-width: 768px) {
    .error-actions { flex-direction: column; align-items: stretch; padding: 0 40px; }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
