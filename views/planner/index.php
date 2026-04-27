<?php
/**
 * views/planner/index.php
 * Premium Journey Planner — Supports direct and 1-transfer routes.
 */
include dirname(__DIR__) . '/layout/header.php';
?>

<div class="page">
    <section class="section">
        <div class="container">
            <div class="section-header anim-1" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                <h1 class="section-title">Journey <span class="text-primary">Planner</span></h1>
                <p class="section-sub" style="margin-top: 0;">Find the most efficient way to travel between stops in <?= htmlspecialchars($city['city_name']) ?>.</p>
            </div>

            <div class="planner-wrapper anim-2">
                <!-- Search Section -->
                <div class="planner-card">
                    <div class="card-header">
                        <i class="fas fa-route"></i>
                        <span>Plan Your Trip</span>
                    </div>
                    <form id="journeyPlannerForm" class="planner-form-grid">
                        <div class="form-group">
                            <label>Origin Stop</label>
                            <div class="custom-dropdown" id="dropdownFrom">
                                <div class="dropdown-trigger">
                                    <span class="selected-text">Select Origin...</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="dropdown-menu">
                                    <div class="dropdown-search">
                                        <input type="text" placeholder="Search stop...">
                                    </div>
                                    <div class="dropdown-options">
                                        <?php foreach ($stops as $s): ?>
                                            <div class="dropdown-option" data-value="<?= $s['id'] ?>"><?= htmlspecialchars($s['stop_name']) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" id="plannerFrom" required>
                            </div>
                        </div>

                        <div class="swap-action">
                            <button type="button" id="swapBtn" class="swap-btn" title="Swap Stops">
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><path d="M7 16V4M7 4L3 8M7 4L11 8M17 8v12M17 20l4-4M17 20l-4-4"/></svg>
                            </button>
                        </div>

                        <div class="form-group">
                            <label>Destination Stop</label>
                            <div class="custom-dropdown" id="dropdownTo">
                                <div class="dropdown-trigger">
                                    <span class="selected-text">Select Destination...</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="dropdown-menu">
                                    <div class="dropdown-search">
                                        <input type="text" placeholder="Search stop...">
                                    </div>
                                    <div class="dropdown-options">
                                        <?php foreach ($stops as $s): ?>
                                            <div class="dropdown-option" data-value="<?= $s['id'] ?>"><?= htmlspecialchars($s['stop_name']) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" id="plannerTo" required>
                            </div>
                        </div>

                        <div class="form-action">
                            <button type="submit" class="btn-primary-glow">Search Routes</button>
                        </div>
                    </form>
                </div>

                <!-- Results Section -->
                <div id="resultsWrapper" class="results-container d-none">
                    <div id="resultStatus" class="result-status-badge"></div>
                    <div id="resultsList" class="journey-list">
                        <!-- Journey cards injected here -->
                    </div>
                </div>

                <!-- States -->
                <div id="plannerLoading" class="planner-state d-none">
                    <div class="loader-ring"></div>
                    <p>Optimizing your journey...</p>
                </div>

                <div id="plannerError" class="planner-state error-state d-none">
                    <div class="error-icon">!</div>
                    <p id="errorMessage"></p>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.planner-wrapper { max-width: 1000px; margin: 0 auto; }
.planner-card { 
    background: rgba(255, 255, 255, 0.02); 
    backdrop-filter: blur(24px); 
    border: 1px solid var(--border); 
    border-radius: 32px; 
    padding: 40px; 
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
}

.card-header { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; }
.card-header span { font-family: var(--font-display); font-size: 18px; text-transform: uppercase; letter-spacing: 1px; color: var(--accent); }

.planner-form-grid { display: grid; grid-template-columns: 1fr auto 1fr auto; gap: 25px; align-items: flex-end; }
.form-group { position: relative; }
.form-group label { display: block; font-size: 11px; text-transform: uppercase; color: var(--muted); margin-bottom: 10px; font-weight: 700; letter-spacing: 1px; }

/* Custom Searchable Dropdown */
.custom-dropdown { position: relative; width: 100%; }
.dropdown-trigger { 
    background: var(--surface2); 
    border: 1px solid var(--border); 
    padding: 16px 20px; 
    border-radius: 18px; 
    color: var(--text); 
    font-size: 15px; 
    cursor: pointer; 
    display: flex; 
    justify-content: space-between; 
    align-items: center;
    transition: all 0.3s var(--ease);
}
.dropdown-trigger:hover { border-color: var(--border-hover); background: var(--surface3); }
.dropdown-trigger.active { border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-dim); }
.dropdown-trigger .arrow { font-size: 10px; color: var(--muted); transition: transform 0.3s; }
.dropdown-trigger.active .arrow { transform: rotate(180deg); color: var(--accent); }

.dropdown-menu {
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    right: 0;
    background: var(--surface3);
    border: 1px solid var(--border);
    border-radius: 20px;
    z-index: 2000;
    overflow: hidden;
    display: none;
    backdrop-filter: blur(30px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.5);
    animation: dropDownIn 0.3s var(--ease);
}
@keyframes dropDownIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-menu.show { display: block; }
.dropdown-search {
    padding: 12px;
    border-bottom: 1px solid var(--border);
}
.dropdown-search input {
    width: 100%;
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 10px 15px;
    color: var(--text);
    font-size: 14px;
    outline: none;
}
.dropdown-options { 
    max-height: 350px; 
    overflow-y: auto; 
    padding: 8px 0;
}

/* Stylish Dropdown Scrollbar */
.dropdown-options::-webkit-scrollbar { width: 5px; }
.dropdown-options::-webkit-scrollbar-track { background: transparent; }
.dropdown-options::-webkit-scrollbar-thumb { 
    background: var(--border); 
    border-radius: 10px; 
    border: 1px solid transparent;
    background-clip: padding-box;
}
.dropdown-options::-webkit-scrollbar-thumb:hover { background: var(--accent); }

.dropdown-option {
    padding: 12px 20px;
    font-size: 14px;
    color: var(--muted2);
    cursor: pointer;
    transition: all 0.2s;
}
.dropdown-option:hover { background: var(--accent-dim); color: var(--text); }
.dropdown-option.selected { background: var(--accent); color: white; }
.dropdown-option.hidden { display: none; }

.swap-btn { background: var(--surface3); border: 1px solid var(--border); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent); cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); margin-bottom: 3px; }
.swap-btn:hover { transform: rotate(180deg); background: var(--accent); color: white; border-color: var(--accent); box-shadow: 0 0 15px var(--accent-dim); }

.btn-primary-glow { 
    background: var(--accent); 
    color: white; 
    border: none; 
    padding: 15px 35px; 
    border-radius: 18px; 
    font-weight: 800; 
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer; 
    transition: all 0.3s;
    box-shadow: 0 10px 20px var(--accent-dim);
    height: 55px;
}
.btn-primary-glow:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(232, 64, 37, 0.4); }

/* Results Styling */
.results-container { margin-top: 50px; }
.result-status-badge { display: inline-block; padding: 6px 16px; border-radius: 100px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; }
.status-direct { background: rgba(39, 174, 96, 0.1); color: #2ecc71; border: 1px solid rgba(39, 174, 96, 0.2); }
.status-transfer { background: rgba(232, 184, 75, 0.1); color: #f1c40f; border: 1px solid rgba(232, 184, 75, 0.2); }

.journey-list { display: grid; gap: 40px; }
.journey-result-wrapper { margin-bottom: 20px; }

/* Journey Results (Matches Route List) */
.transfer-journey-container { display: grid; grid-template-columns: 1fr auto 1fr; gap: 20px; align-items: center; width: 100%; }
.transfer-divider { display: flex; flex-direction: column; align-items: center; gap: 10px; }
.t-line { width: 2px; height: 30px; background: var(--border); }
.t-icon { width: 45px; height: 45px; background: var(--surface3); border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent); box-shadow: 0 0 15px rgba(0,0,0,0.2); }

@media (max-width: 900px) {
    .transfer-journey-container { grid-template-columns: 1fr; }
    .transfer-divider { flex-direction: row; margin: 15px 0; width: 100%; justify-content: center; }
    .t-line { height: 2px; width: 50px; }
}

.journey-meta { display: flex; gap: 30px; border-top: 1px solid var(--border); padding-top: 20px; font-size: 13px; color: var(--muted); }
.meta-item strong { color: var(--text); margin-right: 5px; }

/* States */
.planner-state { text-align: center; padding: 60px 0; }
.loader-ring { width: 50px; height: 50px; border: 3px solid rgba(232, 64, 37, 0.1); border-top-color: var(--accent); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px; }
@keyframes spin { to { transform: rotate(360deg); } }

.error-state .error-icon { width: 60px; height: 60px; background: rgba(232, 64, 37, 0.1); color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; font-weight: 800; margin: 0 auto 20px; }

@media (max-width: 850px) {
    .planner-form-grid { grid-template-columns: 1fr; gap: 15px; text-align: center; }
    .swap-action { transform: rotate(90deg); margin: 10px 0; }
    .journey-path { flex-direction: column; align-items: flex-start; }
    .transfer-point { margin: 10px 0; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Custom Dropdown Logic
    function setupDropdown(id) {
        const dropdown = document.getElementById(id);
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');
        const search = dropdown.querySelector('.dropdown-search input');
        const options = dropdown.querySelectorAll('.dropdown-option');
        const hiddenInput = dropdown.querySelector('input[type="hidden"]');
        const selectedText = dropdown.querySelector('.selected-text');

        trigger.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(m => { if(m !== menu) m.classList.remove('show'); });
            document.querySelectorAll('.dropdown-trigger').forEach(t => { if(t !== trigger) t.classList.remove('active'); });
            menu.classList.toggle('show');
            trigger.classList.toggle('active');
            if (menu.classList.contains('show')) search.focus();
        });

        search.addEventListener('input', (e) => {
            const val = e.target.value.toLowerCase();
            options.forEach(opt => {
                const text = opt.textContent.toLowerCase();
                opt.classList.toggle('hidden', !text.includes(val));
            });
        });

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                options.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                hiddenInput.value = opt.dataset.value;
                selectedText.textContent = opt.textContent;
                menu.classList.remove('show');
                trigger.classList.remove('active');
            });
        });

        // Close on click outside
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) {
                menu.classList.remove('show');
                trigger.classList.remove('active');
            }
        });
    }

    setupDropdown('dropdownFrom');
    setupDropdown('dropdownTo');

    const form = document.getElementById('journeyPlannerForm');
    const resultsWrapper = document.getElementById('resultsWrapper');
    const resultsList = document.getElementById('resultsList');
    const resultStatus = document.getElementById('resultStatus');
    const loadingDiv = document.getElementById('plannerLoading');
    const errorDiv = document.getElementById('plannerError');
    const errorMessage = document.getElementById('errorMessage');
    const swapBtn = document.getElementById('swapBtn');

    swapBtn.addEventListener('click', () => {
        const fromInput = document.querySelector('#dropdownFrom input[type="hidden"]');
        const toInput = document.querySelector('#dropdownTo input[type="hidden"]');
        const fromText = document.querySelector('#dropdownFrom .selected-text');
        const toText = document.querySelector('#dropdownTo .selected-text');

        const tempVal = fromInput.value;
        const tempText = fromText.textContent;

        fromInput.value = toInput.value;
        fromText.textContent = toText.textContent;
        toInput.value = tempVal;
        toText.textContent = tempText;
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fromId = document.getElementById('plannerFrom').value;
        const toId = document.getElementById('plannerTo').value;

        if (fromId === toId) {
            alert('Origin and destination cannot be the same.');
            return;
        }

        // Reset UI
        resultsWrapper.classList.add('d-none');
        errorDiv.classList.add('d-none');
        loadingDiv.classList.remove('d-none');
        resultsList.innerHTML = '';

        try {
            const res = await fetch('<?= APP_URL ?>/api/planner', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    city_id: <?= (int)$city['id'] ?>,
                    from_stop_id: fromId,
                    to_stop_id: toId
                })
            });

            const json = await res.json();
            if (json.status === 'error') throw new Error(json.message);

            const { type, results } = json.data;
            
            resultStatus.textContent = type === 'direct' ? 'Direct Journeys' : '1-Transfer Connections';
            resultStatus.className = 'result-status-badge ' + (type === 'direct' ? 'status-direct' : 'status-transfer');

            results.forEach(j => {
                const wrapper = document.createElement('div');
                wrapper.className = 'journey-result-wrapper anim-3';
                
                if (type === 'direct') {
                    wrapper.innerHTML = `
                        <a href="${window.APP_URL}/routes/${j.route_id}" class="rcard" style="--strip-a:var(--accent);--strip-b:var(--accent2); margin-bottom:0; width:100%; display:block">
                            <div class="rcard-strip"></div>
                            <div class="rcard-inner">
                                <div class="rcard-top">
                                    <span class="rnum">${j.route_number}</span>
                                    <span class="rbadge rb-nm">${j.route_type}</span>
                                </div>
                                <div class="rroute">
                                    <div class="rstop"><small>From</small>${j.from_stop}</div>
                                    <div class="rconn">
                                        <div class="rline"></div>
                                        <span class="rdist">${j.distance_km} km</span>
                                        <div class="rline" style="background:linear-gradient(90deg,rgba(255,255,255,.04),rgba(255,255,255,.2))"></div>
                                    </div>
                                    <div class="rstop"><small>To</small>${j.to_stop}</div>
                                </div>
                                <div class="rmeta">
                                    <div class="rmeta-item"><span class="rmv">${Math.round(j.distance_km * 3)} min</span><span class="rmk">Est. Time</span></div>
                                    <div class="rmeta-item"><span class="rmv">DIRECT</span><span class="rmk">Journey Type</span></div>
                                    <div class="rmeta-item"><span class="rmv rdot"><span class="dot d-g"></span>Active</span><span class="rmk">Status</span></div>
                                </div>
                            </div>
                        </a>
                    `;
                } else {
                    wrapper.innerHTML = `
                        <div class="transfer-journey-container">
                            <a href="${window.APP_URL}/routes/${j.r1_id}" class="rcard" style="--strip-a:var(--accent);--strip-b:var(--accent2); margin-bottom:0;">
                                <div class="rcard-strip"></div>
                                <div class="rcard-inner">
                                    <div class="rcard-top">
                                        <span class="rnum">${j.r1_num}</span>
                                        <span class="rbadge rb-ex">LEG 1</span>
                                    </div>
                                    <div class="rroute">
                                        <div class="rstop"><small>From</small>${j.from_stop_name}</div>
                                        <div class="rconn">
                                            <div class="rline"></div>
                                            <span class="rdist">${round(j.dist_leg1, 1)} km</span>
                                            <div class="rline" style="background:linear-gradient(90deg,rgba(255,255,255,.04),rgba(255,255,255,.2))"></div>
                                        </div>
                                        <div class="rstop"><small>Transfer at</small>${j.transfer_stop_name}</div>
                                    </div>
                                </div>
                            </a>

                            <div class="transfer-divider">
                                <div class="t-line"></div>
                                <div class="t-icon">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="3" fill="none"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </div>
                                <div class="t-line"></div>
                            </div>

                            <a href="${window.APP_URL}/routes/${j.r2_id}" class="rcard" style="--strip-a:var(--accent2);--strip-b:var(--accent); margin-bottom:0;">
                                <div class="rcard-strip"></div>
                                <div class="rcard-inner">
                                    <div class="rcard-top">
                                        <span class="rnum">${j.r2_num}</span>
                                        <span class="rbadge rb-ac">LEG 2</span>
                                    </div>
                                    <div class="rroute">
                                        <div class="rstop"><small>From</small>${j.transfer_stop_name}</div>
                                        <div class="rconn">
                                            <div class="rline"></div>
                                            <span class="rdist">${round(j.dist_leg2, 1)} km</span>
                                            <div class="rline" style="background:linear-gradient(90deg,rgba(255,255,255,.04),rgba(255,255,255,.2))"></div>
                                        </div>
                                        <div class="rstop"><small>To</small>${j.to_stop_name}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="journey-meta mt-15" style="border:none; padding:0; justify-content:center">
                            <div class="meta-item"><strong>Total Dist:</strong> ${round(j.total_dist, 2)} km</div>
                            <div class="meta-item"><strong>Transfer Stop:</strong> ${j.transfer_stop_name}</div>
                        </div>
                    `;
                }
                resultsList.appendChild(wrapper);
            });

            loadingDiv.classList.add('d-none');
            resultsWrapper.classList.remove('d-none');

        } catch (err) {
            loadingDiv.classList.add('d-none');
            errorMessage.textContent = err.message;
            errorDiv.classList.remove('d-none');
        }
    });

    function round(num, precision) {
        var base = Math.pow(10, precision);
        return Math.round(num * base) / base;
    }
});
</script>
</div>

<?php include dirname(__DIR__) . '/layout/footer.php'; ?>
