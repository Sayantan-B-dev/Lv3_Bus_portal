<?php include dirname(__DIR__) . '/layout/header.php'; ?>

<section class="page profile-page">
    <div class="profile-header">
        <div class="profile-cover" style="background-image: url('<?= htmlspecialchars($user['cover_image'] ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069') ?>');">
            <div class="cover-overlay"></div>
        </div>
        <div class="container profile-nav-container">
            <div class="profile-info-main">
                <div class="profile-avatar-wrap">
                    <img src="<?= htmlspecialchars($user['avatar_url'] ?? $user['profile_image'] ?? APP_URL . '/public/assets/img/default-avatar.png') ?>" alt="" class="profile-avatar-lg">
                    <?php if ($user['is_active']): ?>
                        <div class="status-badge online"></div>
                    <?php endif; ?>
                </div>
                <div class="profile-text-main">
                    <h1><?= htmlspecialchars($user['name']) ?> <span>(@<?= htmlspecialchars($user['username'] ?? 'user') ?>)</span></h1>
                    <p class="profile-bio"><?= htmlspecialchars($user['bio'] ?? 'No bio yet.') ?></p>
                    <div class="profile-meta-badges">
                        <span class="badge-item"><i class="icon">City:</i> <?= htmlspecialchars($user['city'] ?? 'Earth') ?></span>
                        <span class="badge-item"><i class="icon">Joined:</i> <?= date('M Y', strtotime($user['created_at'])) ?></span>
                        <?php if ($user['is_student']): ?>
                            <span class="badge-item student-badge"><i class="icon">Status:</i> Student</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="profile-actions-top">
                <a href="<?= APP_URL ?>/profile/edit" class="btn-o">Edit Profile</a>
            </div>
        </div>
    </div>

    <div class="container profile-content">
        <div class="profile-grid">
            <!-- Sidebar -->
            <div class="profile-side">
                <div class="glass-card info-card">
                    <h3>Contact Information</h3>
                    <div class="info-list">
                        <div class="info-row"><label>Email</label><span><?= htmlspecialchars($user['email']) ?></span></div>
                        <div class="info-row"><label>Phone</label><span><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></span></div>
                        <div class="info-row"><label>Address</label><span><?= htmlspecialchars($user['address'] ?? 'N/A') ?></span></div>
                    </div>
                </div>

                <?php if ($user['is_student']): ?>
                <div class="glass-card info-card student-info">
                    <h3>Academic Info</h3>
                    <div class="info-list">
                        <div class="info-row"><label>College</label><span><?= htmlspecialchars($user['college_name'] ?? 'N/A') ?></span></div>
                        <div class="info-row"><label>Reg No</label><span><?= htmlspecialchars($user['college_registration_number'] ?? 'N/A') ?></span></div>
                        <div class="info-row"><label>Roll No</label><span><?= htmlspecialchars($user['roll_number'] ?? 'N/A') ?></span></div>
                        <div class="info-row"><label>Branch</label><span><?= htmlspecialchars($user['branch'] ?? 'N/A') ?></span></div>
                        <div class="info-row"><label>Year</label><span><?= htmlspecialchars($user['year_of_study'] ?? 'N/A') ?></span></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="profile-main">
                <div class="glass-card bio-card">
                    <h3>About Me</h3>
                    <p><?= nl2br(htmlspecialchars($user['bio'] ?? 'This user hasn\'t written a bio yet.')) ?></p>
                    
                    <?php if (!empty($user['skills'])): ?>
                    <div class="skills-wrap">
                        <h4>Skills</h4>
                        <div class="skills-list">
                            <?php foreach (explode(',', $user['skills']) as $skill): ?>
                                <span class="skill-tag"><?= trim(htmlspecialchars($skill)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="glass-card social-card">
                    <h3>Professional Links</h3>
                    <div class="social-links-grid">
                        <?php if ($user['linkedin_url']): ?>
                            <a href="<?= $user['linkedin_url'] ?>" target="_blank" class="social-link-item">LinkedIn</a>
                        <?php endif; ?>
                        <?php if ($user['github_url']): ?>
                            <a href="<?= $user['github_url'] ?>" target="_blank" class="social-link-item">GitHub</a>
                        <?php endif; ?>
                        <?php if ($user['portfolio_url']): ?>
                            <a href="<?= $user['portfolio_url'] ?>" target="_blank" class="social-link-item">Portfolio</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.profile-page { padding-top: 62px; background: var(--bg); min-height: 100vh; }
.profile-header { position: relative; margin-bottom: 40px; }
.profile-cover { height: 320px; background-size: cover; background-position: center; position: relative; }
.cover-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent, var(--bg)); }
.profile-nav-container { display: flex; align-items: flex-end; justify-content: space-between; margin-top: -100px; position: relative; z-index: 5; }
.profile-info-main { display: flex; align-items: flex-end; gap: 30px; }
.profile-avatar-wrap { position: relative; }
.profile-avatar-lg { width: 180px; height: 180px; border-radius: 40px; border: 6px solid var(--bg); object-fit: cover; box-shadow: 0 20px 40px rgba(0,0,0,.4); }
.status-badge { position: absolute; bottom: 15px; right: 15px; width: 24px; height: 24px; border-radius: 50%; border: 4px solid var(--bg); background: var(--green); }
.profile-text-main h1 { font-family: var(--font-display); font-size: 48px; letter-spacing: -1px; margin-bottom: 5px; }
.profile-text-main h1 span { font-size: 20px; color: var(--muted); font-weight: 300; }
.profile-bio { color: var(--muted2); max-width: 500px; margin-bottom: 15px; }
.profile-meta-badges { display: flex; gap: 15px; }
.badge-item { font-size: 13px; color: var(--muted); display: flex; align-items: center; gap: 5px; }
.student-badge { color: var(--accent2); background: var(--accent-dim); padding: 2px 10px; border-radius: 100px; }
.profile-content { margin-bottom: 100px; }
.profile-grid { display: grid; grid-template-columns: 350px 1fr; gap: 40px; }
.glass-card { background: rgba(255,255,255,.03); border: 1px solid var(--border); border-radius: 24px; padding: 30px; margin-bottom: 30px; backdrop-filter: blur(20px); }
.glass-card h3 { font-family: var(--font-display); font-size: 24px; letter-spacing: 1px; margin-bottom: 20px; color: var(--accent); }
.info-list { display: grid; gap: 15px; }
.info-row { display: flex; flex-direction: column; }
.info-row label { font-size: 10px; text-transform: uppercase; color: var(--muted); letter-spacing: 1px; margin-bottom: 3px; }
.info-row span { font-size: 14px; color: var(--text); }
.skill-tag { display: inline-block; padding: 5px 12px; background: rgba(255,255,255,.05); border-radius: 8px; font-size: 12px; margin: 0 8px 8px 0; }
.social-links-grid { display: flex; gap: 15px; }
.social-link-item { padding: 10px 20px; background: var(--accent-dim); color: var(--accent2); border-radius: 12px; font-size: 14px; font-weight: 600; }
@media (max-width: 992px) {
    .profile-grid { grid-template-columns: 1fr; }
    .profile-nav-container { flex-direction: column; align-items: center; text-align: center; }
    .profile-info-main { flex-direction: column; align-items: center; }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
