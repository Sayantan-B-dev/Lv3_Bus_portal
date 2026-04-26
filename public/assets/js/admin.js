/**
 * public/assets/js/admin.js
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin Panel initialized.');

    // Highlight active sidebar link
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '/admin') {
            link.classList.add('active');
        } else if (href === '/admin' && (currentPath === '/admin' || currentPath === '/admin/')) {
            link.classList.add('active');
        }
    });

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
