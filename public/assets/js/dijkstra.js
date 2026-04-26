/**
 * public/assets/js/dijkstra.js
 * Handles Journey Planner UI logic and path rendering.
 */

document.addEventListener('DOMContentLoaded', () => {
    const findBtn = document.getElementById('findPathBtn');
    if (!findBtn) return;

    const fromSelect = document.getElementById('plannerFrom');
    const toSelect = document.getElementById('plannerTo');
    const typeSelect = document.getElementById('plannerType');
    const resultsDiv = document.getElementById('plannerResults');
    const loadingDiv = document.getElementById('plannerLoading');
    const errorDiv = document.getElementById('plannerError');
    const legsDiv = document.getElementById('pathLegs');

    const swapBtn = document.getElementById('swapPlanner');
    if (swapBtn) {
        swapBtn.addEventListener('click', () => {
            const temp = fromSelect.value;
            fromSelect.value = toSelect.value;
            toSelect.value = temp;
        });
    }

    findBtn.addEventListener('click', async () => {
        const from = fromSelect.value;
        const to = toSelect.value;
        const type = typeSelect.value;

        if (!from || !to) {
            showError('Please select both origin and destination stops.');
            return;
        }

        if (from === to) {
            showError('Origin and destination cannot be the same.');
            return;
        }

        // Reset UI
        errorDiv.classList.add('d-none');
        resultsDiv.classList.add('d-none');
        loadingDiv.classList.remove('d-none');
        legsDiv.innerHTML = '';

        try {
            const response = await fetch(`${window.APP_URL}/api/planner`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    city_id: window.CITY_DATA.id,
                    from_stop_id: from,
                    to_stop_id: to,
                    passenger_type: type
                })
            });

            const result = await response.json();
            
            if (result.status === 'error') {
                throw new Error(result.message);
            }

            renderResults(result.data);
        } catch (err) {
            showError(err.message || 'An error occurred while planning your journey.');
        } finally {
            loadingDiv.classList.add('d-none');
        }
    });

    function renderResults(data) {
        resultsDiv.classList.remove('d-none');
        
        document.getElementById('resDist').textContent = data.total_distance_km + ' km';
        document.getElementById('resTime').textContent = data.estimated_time_mins + ' mins';
        document.getElementById('resTrans').textContent = data.transfers;
        document.getElementById('resFare').textContent = window.CITY_DATA.currency + ' ' + (data.fare || '--');

        // Draw on map if available
        if (window.plannerMap) {
            // Since API returns legs, we need to gather all stop coordinates for the map
            // For now, let's just use the leg data
            // To be really precise, we'd need stop coords in the API response or fetch them
            console.log('Path found:', data);
        }

        data.legs.forEach((leg, idx) => {
            const item = document.createElement('div');
            item.className = 'timeline-item';
            item.innerHTML = `
                <div class="timeline-dot"></div>
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small text-muted font-rajdhani fw-bold">LEG ${idx + 1}</div>
                        <div class="stop-name text-white">Board: ${leg.board_stop}</div>
                        <div class="small text-muted">Alight: ${leg.alight_stop}</div>
                    </div>
                    <div class="text-end">
                        <div class="badge badge-type badge-${leg.route_type.toLowerCase()}">${leg.route_number}</div>
                        <div class="small text-muted mt-1">${leg.stops_count} stops</div>
                    </div>
                </div>
            `;
            legsDiv.appendChild(item);
        });
    }

    function showError(msg) {
        errorDiv.textContent = msg;
        errorDiv.classList.remove('d-none');
        loadingDiv.classList.add('d-none');
    }
});
