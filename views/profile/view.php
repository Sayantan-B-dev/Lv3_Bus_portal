<?php include dirname(__DIR__) . '/layout/header.php'; ?>

<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/profile.css">

<section class="page profile-page">
    <div class="profile-header">
        <div class="profile-cover" style="background-image: url('<?= htmlspecialchars($user['cover_image'] ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069') ?>');">
            <div class="cover-overlay"></div>
        </div>
        <div class="container profile-nav-container">
            <div class="profile-info-main">
                <div class="profile-avatar-wrap">
                    <img src="<?= htmlspecialchars(($user['profile_image'] ?: $user['avatar_url']) ?: APP_URL . '/public/assets/img/default-avatar.png') ?>" alt="" class="profile-avatar-lg">
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
            <?php 
            $showMapTop = !$user['is_student'] && $user['latitude']; 
            $showMapWithLinks = $user['is_student'] && $user['latitude'];
            ?>
            <div class="glass-card info-card <?= (!$user['is_student'] && !$showMapTop) ? 'full-width' : '' ?>">
                <h3>Contact Information</h3>
                <div class="info-list">
                    <div class="info-row"><label>Email</label><span><?= htmlspecialchars($user['email']) ?></span></div>
                    <div class="info-row"><label>Phone</label><span><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></span></div>
                    <?php if (!empty($user['alternate_phone'])): ?>
                        <div class="info-row"><label>Alt Phone</label><span><?= htmlspecialchars($user['alternate_phone']) ?></span></div>
                    <?php endif; ?>
                    <div class="info-row"><label>DOB</label><span><?= $user['dob'] ? date('d M, Y', strtotime($user['dob'])) : 'N/A' ?></span></div>
                    <div class="info-row"><label>Gender</label><span><?= htmlspecialchars($user['gender'] ?? 'N/A') ?></span></div>
                    <div class="info-row"><label>Address</label><span><?= htmlspecialchars($user['address'] ?? 'N/A') ?></span></div>
                    <div class="info-row"><label>Location</label><span><?= htmlspecialchars(($user['city'] ?? '') . ($user['state'] ? ', ' . $user['state'] : '')) ?: 'N/A' ?></span></div>
                    <div class="info-row"><label>Occupation</label><span><?= htmlspecialchars($user['occupation'] ?? 'N/A') ?></span></div>
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
                    <div class="info-row"><label>Semester</label><span><?= htmlspecialchars($user['semester'] ?? 'N/A') ?> Semester</span></div>
                    <div class="info-row"><label>Graduation</label><span><?= htmlspecialchars($user['graduation_year'] ?? 'N/A') ?></span></div>
                </div>
            </div>
            <?php elseif ($showMapTop): ?>
            <div class="glass-card map-card">
                <h3>Pinned Location</h3>
                <div id="viewLocationMap" class="view-map-container"></div>
            </div>
            <?php endif; ?>

            <!-- Emergency Contact -->
            <div class="glass-card info-card">
                <h3>Emergency Contact</h3>
                <div class="info-list">
                    <div class="info-row"><label>Contact</label><span><?= htmlspecialchars($user['emergency_contact_name'] ?? 'N/A') ?></span></div>
                    <div class="info-row"><label>Phone</label><span><?= htmlspecialchars($user['emergency_contact_phone'] ?? 'N/A') ?></span></div>
                </div>
            </div>

            <!-- Row 3: Bio & Links -->
            <div class="glass-card bio-card">
                <h3>About Me</h3>
                <div class="bio-content-wrap">
                    <div class="bio-section">
                        <label class="section-sublabel">Biography</label>
                        <p class="bio-text"><?= nl2br(htmlspecialchars($user['bio'] ?? 'This user hasn\'t written a bio yet.')) ?></p>
                    </div>
                    
                    <?php if (!empty($user['skills'])): ?>
                    <div class="skills-section">
                        <label class="section-sublabel">Expertise & Skills</label>
                        <div class="skills-list">
                            <?php foreach (explode(',', $user['skills']) as $skill): ?>
                                <span class="skill-tag"><?= trim(htmlspecialchars($skill)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
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
            
            <?php if ($showMapWithLinks): ?>
            <div class="glass-card map-card">
                <h3>Pinned Location</h3>
                <div id="viewLocationMap" class="view-map-container"></div>
            </div>
            <?php endif; ?>



            <!-- Row 4: Location (Bottom) -->
            <?php if ($user['latitude'] && !$showMapTop && !$showMapWithLinks): ?>
            <div class="glass-card map-card full-width">
                <h3>Pinned Location</h3>
                <div id="viewLocationMap" class="view-map-container"></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', () => {
    <?php if ($user['latitude']): ?>
    const lat = <?= $user['latitude'] ?>;
    const lng = <?= $user['longitude'] ?>;

    maptilersdk.config.apiKey = '<?= $_ENV['MAPTILER_API_KEY'] ?? 'get_your_key_at_maptiler_com' ?>';
    const map = new maptilersdk.Map({
        container: 'viewLocationMap',
        style: maptilersdk.MapStyle.HYBRID,
        center: [lng, lat],
        zoom: 16,
        pitch: 60,
        bearing: -30,
        terrain: true,
        attributionControl: false
    });

    // Custom Pulse Marker for MapTiler SDK
    const el = document.createElement('div');
    el.className = 'custom-pulse-marker';
    el.innerHTML = '<div class="pulse-ring"></div><div class="pulse-dot"></div>';
    
    new maptilersdk.Marker({ element: el }).setLngLat([lng, lat]).addTo(map);
    <?php endif; ?>

});
</script>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
