import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('sidebar', {
    collapsed: (function() {
        try {
            return localStorage.getItem('sidebar_collapsed') === 'true';
        } catch(e) {
            return false;
        }
    })(),
    toggle() {
        document.documentElement.classList.add('sidebar-animated');
        this.collapsed = !this.collapsed;
        try {
            localStorage.setItem('sidebar_collapsed', this.collapsed);
            document.cookie = 'sidebar_collapsed=' + this.collapsed + '; path=/; max-age=31536000; SameSite=Lax';
        } catch(e) {}
        if (this.collapsed) {
            document.documentElement.classList.add('sidebar-collapsed');
        } else {
            document.documentElement.classList.remove('sidebar-collapsed');
        }
    }
});

Alpine.start();
