document.addEventListener('DOMContentLoaded', () => {
    // Filter chips
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
        });
    });
    document.querySelectorAll('.chip').forEach(chip => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('on'));
            chip.classList.add('on');
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

    // Reveal on scroll (dtc reference)
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add('in'); });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

    // // 3D card tilt (dtc reference)
    // document.querySelectorAll('.rcard').forEach(c => {
    //     c.addEventListener('mousemove', (e) => {
    //         const r = c.getBoundingClientRect();
    //         const x = (e.clientX - r.left) / r.width - 0.5;
    //         const y = (e.clientY - r.top) / r.height - 0.5;
    //         c.style.transform = `perspective(800px) rotateY(${x * 14}deg) rotateX(${ -y * 14}deg) translateZ(8px)`;
    //         c.style.setProperty('--mx', (e.clientX - r.left) + 'px');
    //         c.style.setProperty('--my', (e.clientY - r.top) + 'px');
    //     });
    //     c.addEventListener('mouseleave', () => {
    //         c.style.transform = 'perspective(800px) rotateY(0) rotateX(0) translateZ(0)';
    //     });
    // });

let ticking = false;
document.querySelectorAll('.rcard').forEach(c => {
    c.addEventListener('mousemove', (e) => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            const r = c.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - 0.5;
            const y = (e.clientY - r.top) / r.height - 0.5;
            c.style.transform = `perspective(800px) rotateY(${x * 8}deg) rotateX(${-y * 6}deg)`;
            ticking = false;
        });
    });
    c.addEventListener('mouseleave', () => {
        c.style.transform = '';
    });
});

document.querySelectorAll('.rcard').forEach(card => {
    let parent = card;
    while (parent) {
        const style = getComputedStyle(parent);
        if (style.pointerEvents === 'none') {
            console.warn('Blocked by', parent, style.pointerEvents);
        }
        parent = parent.parentElement;
    }
});
    // Animate stat numbers on view (dtc reference-ish)
    function animateNumber(el, target, duration = 1600) {
        if (!el) return;
        const isFloat = target.includes('.');
        const isPercent = target.includes('%');
        const isPlus = target.includes('+');
        const num = parseFloat(target.replace(/[^0-9.]/g, ''));
        let start = null;
        const step = (ts) => {
            if (!start) start = ts;
            const progress = Math.min((ts - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 4);
            const current = num * eased;
            const formatted = isFloat ? current.toFixed(1) : Math.floor(current).toLocaleString();
            el.textContent = formatted + (isPercent ? '%' : '') + (isPlus ? '+' : '');
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = target;
        };
        requestAnimationFrame(step);
    }

    const statWrap = document.querySelector('.stats, .stats-bar');
    if (statWrap) {
        const statObs = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (!e.isIntersecting) return;
                e.target.querySelectorAll('[data-target]').forEach((n) => {
                    const original = n.getAttribute('data-target') || n.textContent;
                    n.textContent = '0';
                    animateNumber(n, original);
                });
                statObs.unobserve(e.target);
            });
        }, { threshold: 0.3 });
        statObs.observe(statWrap);
    }

    // Mobile nav toggle
    window.toggleMob = () => document.getElementById('mobNav')?.classList.toggle('open');
    window.closeMob = () => document.getElementById('mobNav')?.classList.remove('open');

    /*── CURSOR ──*/
    const cur = document.getElementById('cursor');
    const ring = document.getElementById('cursor-ring');
    if (cur && ring) {
        let mx=0,my=0,rx=0,ry=0;
        document.addEventListener('mousemove', e => {
            mx = e.clientX; my = e.clientY;
            cur.style.left = mx + 'px'; cur.style.top = my + 'px';
        });
        function animRing() {
            rx += (mx - rx) * 0.12; ry += (my - ry) * 0.12;
            ring.style.left = rx + 'px'; ring.style.top = ry + 'px';
            requestAnimationFrame(animRing);
        }
        animRing();
    }
});
