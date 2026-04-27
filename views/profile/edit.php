<?php include dirname(__DIR__) . '/layout/header.php'; ?>

<section class="page profile-edit-page">
    <div class="container">
        <div class="sec-head reveal">
            <div>
                <div class="sec-title">Edit <span>Profile</span></div>
                <div class="sec-desc">Update your personal and academic information</div>
            </div>
            <a href="<?= APP_URL ?>/profile" class="btn-g back-btn">Back to Profile</a>
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
                            <div class="file-upload-wrapper">
                                <button type="button" class="btn-g file-btn">Choose Image</button>
                                <input type="file" name="profile_image" accept="image/*" class="file-input-hidden">
                                <span class="file-name">No file chosen</span>
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Cover Image</label>
                            <div class="file-upload-wrapper">
                                <button type="button" class="btn-g file-btn">Choose Image</button>
                                <input type="file" name="cover_image" accept="image/*" class="file-input-hidden">
                                <span class="file-name">No file chosen</span>
                            </div>
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

                <!-- Location Picker -->
                <div class="form-section full-width location-picker-section">
                    <h3>Set Your Location</h3>
                    <div class="map-search-wrapper">
                        <input type="text" id="mapSearchInput" placeholder="Search for your area..." class="map-search-input">
                        <button type="button" id="mapSearchBtn" class="btn-g search-map-btn">Search</button>
                    </div>
                    <div id="userLocationMap" class="stylish-map-container"></div>
                    <p class="small-text">Drag the marker or click to refine your location</p>
                    <input type="hidden" name="latitude" id="latInput" value="<?= htmlspecialchars($user['latitude'] ?? '') ?>">
                    <input type="hidden" name="longitude" id="lngInput" value="<?= htmlspecialchars($user['longitude'] ?? '') ?>">
                </div>

                <!-- Student Info Toggle -->
                <div class="form-section full-width student-toggle-wrap">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="is_student" id="isStudentCheckbox" <?= ($user['is_student'] ?? 0) ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
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
                        <div class="input-group">
                            <label>Semester</label>
                            <select name="semester">
                                <option value="">Select Semester</option>
                                <?php for($i=1; $i<=8; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($user['semester'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?> Semester</option>
                                <?php endfor; ?>
                            </select>
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

// Location Picker Map
document.addEventListener('DOMContentLoaded', () => {
    const defaultLat = <?= $user['latitude'] ?: 22.5726 ?>;
    const defaultLng = <?= $user['longitude'] ?: 88.3639 ?>;
    
    const map = L.map('userLocationMap', { zoomControl: false }).setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://api.maptiler.com/maps/dataviz-dark/256/{z}/{x}/{y}.png?key=<?= $_ENV['MAPTILER_API_KEY'] ?? 'get_your_key_at_maptiler_com' ?>', {
        attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        marker.setLatLng([lat, lng]);
        document.getElementById('latInput').value = lat;
        document.getElementById('lngInput').value = lng;
    });

    marker.on('dragend', function(e) {
        const { lat, lng } = marker.getLatLng();
        document.getElementById('latInput').value = lat;
        document.getElementById('lngInput').value = lng;
    });

    // Map Search Handler
    const searchInput = document.getElementById('mapSearchInput');
    const searchBtn = document.getElementById('mapSearchBtn');

    const performSearch = async () => {
        const query = searchInput.value;
        if (!query) return;
        
        searchBtn.textContent = '...';
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (data && data.length > 0) {
                const { lat, lon } = data[0];
                const newLat = parseFloat(lat);
                const newLon = parseFloat(lon);
                map.setView([newLat, newLon], 15);
                marker.setLatLng([newLat, newLon]);
                document.getElementById('latInput').value = newLat;
                document.getElementById('lngInput').value = newLon;
            }
        } catch (err) {
            console.error('Search failed:', err);
        } finally {
            searchBtn.textContent = 'Search';
        }
    };

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') { e.preventDefault(); performSearch(); } });
});

// File input handler
document.querySelectorAll('.file-input-hidden').forEach(input => {
    input.addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        this.parentElement.querySelector('.file-name').textContent = fileName;
    });
});
document.querySelectorAll('.file-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.parentElement.querySelector('input[type="file"]').click();
    });
});
</script>

<style>
.profile-edit-page { padding-bottom: 100px; }
.back-btn { font-size: 13px; padding: 10px 20px; border-radius: 12px; }
.edit-form { background: var(--surface); border: 1px solid var(--border); border-radius: 32px; padding: 50px; margin-top: 40px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: stretch; }
.form-section { display: flex; flex-direction: column; gap: 20px; }
.full-width { grid-column: span 2; }
.form-section h3 { font-family: var(--font-display); font-size: 20px; letter-spacing: 1px; color: var(--accent); margin-bottom: 10px; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
.input-group { display: flex; flex-direction: column; gap: 8px; }
.input-group label { font-size: 12px; font-weight: 600; color: var(--muted); }
.input-group input, .input-group select, .input-group textarea { 
    background: rgba(255,255,255,.05); 
    border: 1px solid var(--border); 
    border-radius: 12px; 
    padding: 12px 16px; 
    color: var(--text); 
    font-family: var(--font-body); 
    font-size: 14px; 
    transition: all .2s;
    appearance: none;
}
.input-group select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%238888a0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 45px;
}
.input-group select option {
    background-color: var(--surface2);
    color: var(--text);
    padding: 10px;
}
.input-group input:focus, .input-group select:focus { border-color: var(--accent); outline: none; background: rgba(255,255,255,.08); }
.academic-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.student-toggle-wrap { background: var(--surface2); padding: 25px; border-radius: 20px; border: 1px solid var(--border); }

/* Custom Checkbox */
.custom-checkbox {
    display: flex;
    align-items: center;
    gap: 15px;
    cursor: pointer;
    user-select: none;
    position: relative;
}
.custom-checkbox input { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
.checkmark {
    height: 24px;
    width: 24px;
    background-color: rgba(255,255,255,.05);
    border: 1px solid var(--border);
    border-radius: 6px;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.custom-checkbox:hover input ~ .checkmark { background-color: rgba(255,255,255,.1); }
.custom-checkbox input:checked ~ .checkmark { background-color: var(--accent); border-color: var(--accent); }
.checkmark:after {
    content: "";
    display: none;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
    margin-bottom: 2px;
}
.custom-checkbox input:checked ~ .checkmark:after { display: block; }
.toggle-text { font-weight: 700; font-size: 16px; color: var(--text); }

/* File Upload */
.media-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.file-upload-wrapper { display: flex; align-items: center; gap: 15px; background: rgba(255,255,255,.03); padding: 8px; border-radius: 14px; border: 1px dashed var(--border); }
.file-input-hidden { display: none; }
.file-name { font-size: 12px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }

.small-text { font-size: 11px; color: var(--muted); margin-top: 5px; }

/* Stylish Map */
.location-picker-section { background: var(--surface2); padding: 30px; border-radius: 24px; border: 1px solid var(--border); margin-top: 20px; }
.stylish-map-container { height: 350px; border-radius: 18px; border: 1px solid var(--border); box-shadow: inset 0 0 20px rgba(0,0,0,.3); margin-top: 15px; }
.map-search-wrapper { display: flex; gap: 10px; margin-bottom: 5px; }
.map-search-input { flex: 1; background: rgba(255,255,255,.03); border: 1px solid var(--border); border-radius: 12px; padding: 10px 15px; color: var(--text); font-size: 13px; }
.search-map-btn { padding: 0 20px; font-size: 12px; border-radius: 10px; }

.btn-large { padding: 16px 40px; font-size: 16px; }
.form-actions { margin-top: 50px; text-align: center; }
@media (max-width: 992px) {
    .edit-form { padding: 30px; border-radius: 24px; }
    .form-grid { grid-template-columns: 1fr; gap: 30px; }
    .academic-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 600px) {
    .edit-form { padding: 20px; }
    .academic-grid { grid-template-columns: 1fr; }
    .media-inputs { grid-template-columns: 1fr; }
    .form-actions .btn-g { width: 100%; padding: 16px; }
    .stylish-map-container { height: 300px; }
    .map-search-wrapper { flex-direction: column; }
    .search-map-btn { padding: 12px; }
}
</style>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
