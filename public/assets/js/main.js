document.addEventListener('DOMContentLoaded', () => {
    // Filter chips
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
        });
    });

    // Tabs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            // Handle content switching if needed
            const target = tab.getAttribute('data-target');
            if (target) {
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.style.display = 'none';
                });
                document.getElementById(target).style.display = 'block';
            }
        });
    });

    // Route card hover accent (optional enhancement)
    document.querySelectorAll('.route-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.setProperty('--local-accent', 'rgba(232,64,37,0.08)');
        });
    });

    // Search input interaction
    const input = document.getElementById('searchInput');
    if (input) {
        input.addEventListener('focus', () => {
            input.parentElement.style.boxShadow = '0 0 0 4px rgba(232,64,37,.1), 0 8px 40px rgba(0,0,0,.5)';
        });
        input.addEventListener('blur', () => {
            input.parentElement.style.boxShadow = '';
        });
    }

    // Animate stat numbers on load
    function animateNumber(el, target, duration = 1200) {
        if (!el) return;
        const isFloat = target.includes('.');
        const isPercent = target.includes('%');
        const isPlus = target.includes('+');
        const num = parseFloat(target.replace(/[^0-9.]/g, ''));
        let start = null;
        const step = (ts) => {
            if (!start) start = ts;
            const progress = Math.min((ts - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = num * eased;
            const formatted = isFloat ? current.toFixed(1) : Math.floor(current).toLocaleString();
            el.textContent = formatted + (isPercent ? '%' : '') + (isPlus ? '+' : '');
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    const statNums = document.querySelectorAll('.stat-num');
    if (statNums.length > 0) {
        setTimeout(() => {
            statNums.forEach((el) => {
                const original = el.getAttribute('data-target') || el.textContent;
                el.textContent = '0';
                animateNumber(el, original);
            });
        }, 600);
    }

    // Stagger route cards on load
    document.querySelectorAll('.route-card').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(24px)';
        card.style.transition = 'opacity .5s ease, transform .5s ease';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 400 + i * 90);
    });
});
