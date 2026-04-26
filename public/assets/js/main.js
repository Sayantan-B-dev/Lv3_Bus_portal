/**
 * public/assets/js/main.js
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Bus Portal initialized.');
    
    // Global event listeners
    initSearchAutocomplete();
});

/**
 * Handle debounced search autocomplete (stub for now, can be expanded)
 */
function initSearchAutocomplete() {
    const searchInput = document.querySelector('.search-input');
    if (!searchInput) return;

    let timeout = null;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(timeout);
        const query = e.target.value.trim();
        if (query.length < 2) return;

        timeout = setTimeout(() => {
            // Future: Fetch and show live dropdown results
            console.log('Searching for:', query);
        }, 300);
    });
}

/**
 * Utility: Fetch wrapper with CSRF
 */
async function apiFetch(url, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.CSRF_TOKEN || ''
    };
    
    const response = await fetch(url, {
        ...options,
        headers: { ...headers, ...options.headers }
    });

    if (!response.ok) {
        const error = await response.json().catch(() => ({ message: 'Request failed' }));
        throw new Error(error.message || 'Server error');
    }

    return response.json();
}
