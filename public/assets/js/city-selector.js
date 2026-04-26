/**
 * public/assets/js/city-selector.js
 * Handles dynamic city switching via AJAX or form post.
 */

document.addEventListener('DOMContentLoaded', () => {
    const selector = document.getElementById('globalCitySelector');
    if (!selector) return;

    selector.addEventListener('change', async (e) => {
        const cityId = e.target.value;
        
        // Show loading state if desired
        document.body.style.opacity = '0.5';

        try {
            // Post to switch endpoint to update session
            const response = await fetch(`${window.APP_URL}/cities/switch`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `city_id=${cityId}`
            });

            // Redirect to home with new city
            window.location.href = `${window.APP_URL}/?city_id=${cityId}`;
        } catch (error) {
            console.error('Failed to switch city:', error);
            document.body.style.opacity = '1';
        }
    });
});
