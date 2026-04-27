<?php include dirname(__DIR__) . '/layout/header.php'; ?>

<section class="page profile-edit-page">
    <div class="container">
        <div class="sec-head reveal">
            <div>
                <div class="sec-title">Edit <span>Profile</span></div>
                <div class="sec-desc">Update your personal and academic information</div>
            </div>
            <a href="<?= APP_URL ?>/profile" class="sec-link">Back to Profile ↗</a>
        </div>

        <form action="<?= APP_URL ?>/profile/update" method="POST" enctype="multipart/form-data" class="edit-form reveal">
            <input type="hidden" name="csrf_token" value="<?= \App\Middleware\CsrfMiddleware::generateToken() ?>">
            
            <div class="form-grid">
                <!-- Avatar & Cover -->
                <div class="form-section full-width">
                    <h3>Media</h3>
                    <div class="media-inputs">
                        <div class="input-group">
                            <label>Profile Image</label>
                            <input type="file" name="profile_image" accept="image/*">
                        </div>
                        <div class="input-group">
                            <label>Cover Image</label>
                            <input type="file" name="cover_image" accept="image/*">
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="form-section">
                    <h3>Personal Details</h3>
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                    </div>
                    <div class="input-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                    <div class="input-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?= $user['dob'] ?>">
                    </div>
                    <div class="input-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male" <?= ($user['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($user['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= ($user['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Bio & Skills -->
                <div class="form-section">
                    <h3>Professional Info</h3>
                    <div class="input-group">
                        <label>Bio</label>
                        <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>
                    <div class="input-group">
                        <label>Skills (comma separated)</label>
                        <input type="text" name="skills" value="<?= htmlspecialchars($user['skills'] ?? '') ?>" placeholder="e.g. Design, PHP, UI/UX">
                    </div>
                    <div class="input-group">
                        <label>Occupation</label>
                        <input type="text" name="occupation" value="<?= htmlspecialchars($user['occupation'] ?? '') ?>">
                    </div>
                </div>

                <!-- Student Info Toggle -->
                <div class="form-section full-width student-toggle-wrap">
                    <label class="toggle-label">
                        <input type="checkbox" name="is_student" id="isStudentCheckbox" <?= ($user['is_student'] ?? 0) ? 'checked' : '' ?>>
                        <span class="toggle-text">Are you a student?</span>
                    </label>
                </div>

                <!-- Academic Info (Conditional) -->
                <div id="studentInfoFields" class="form-section full-width" style="<?= ($user['is_student'] ?? 0) ? '' : 'display:none;' ?>">
                    <h3>Academic Information</h3>
                    <div class="academic-grid">
                        <div class="input-group">
                            <label>College Name</label>
                            <input type="text" name="college_name" value="<?= htmlspecialchars($user['college_name'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label>Registration Number</label>
                            <input type="text" name="college_registration_number" value="<?= htmlspecialchars($user['college_registration_number'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label>Roll Number</label>
                            <input type="text" name="roll_number" value="<?= htmlspecialchars($user['roll_number'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label>Branch / Course</label>
                            <input type="text" name="branch" value="<?= htmlspecialchars($user['branch'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label>Year of Study</label>
                            <input type="text" name="year_of_study" value="<?= htmlspecialchars($user['year_of_study'] ?? '') ?>" placeholder="e.g. 3rd Year">
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="form-section full-width">
                    <h3>Social & Links</h3>
                    <div class="academic-grid">
                        <div class="input-group">
                            <label>LinkedIn URL</label>
                            <input type="url" name="linkedin_url" value="<?= htmlspecialchars($user['linkedin_url'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label>GitHub URL</label>
                            <input type="url" name="github_url" value="<?= htmlspecialchars($user['github_url'] ?? '') ?>">
                        </div>
                        <div class="input-group">
                            <label>Portfolio URL</label>
                            <input type="url" name="portfolio_url" value="<?= htmlspecialchars($user['portfolio_url'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-o btn-large">Save Changes</button>
            </div>
        </form>
    </div>
</section>

<script>
document.getElementById('isStudentCheckbox')?.addEventListener('change', function() {
    document.getElementById('studentInfoFields').style.display = this.checked ? 'block' : 'none';
});
</script>

<style>
.profile-edit-page { padding-bottom: 100px; }
.edit-form { background: var(--surface); border: 1px solid var(--border); border-radius: 32px; padding: 50px; margin-top: 40px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
.form-section { display: grid; gap: 20px; align-content: start; }
.full-width { grid-column: span 2; }
.form-section h3 { font-family: var(--font-display); font-size: 20px; letter-spacing: 1px; color: var(--accent); margin-bottom: 10px; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
.input-group { display: flex; flex-direction: column; gap: 8px; }
.input-group label { font-size: 12px; font-weight: 600; color: var(--muted); }
.input-group input, .input-group select, .input-group textarea { background: rgba(255,255,255,.05); border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; color: var(--text); font-family: var(--font-body); font-size: 14px; transition: all .2s; }
.input-group input:focus { border-color: var(--accent); outline: none; background: rgba(255,255,255,.08); }
.academic-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.student-toggle-wrap { background: var(--accent-dim); padding: 20px; border-radius: 16px; }
.toggle-label { display: flex; align-items: center; gap: 15px; cursor: pointer; }
.toggle-text { font-weight: 700; font-size: 16px; color: var(--accent2); }
.btn-large { padding: 16px 40px; font-size: 16px; }
.form-actions { margin-top: 50px; text-align: center; }
@media (max-width: 768px) {
    .form-grid, .academic-grid { grid-template-columns: 1fr; }
    .full-width { grid-column: span 1; }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
