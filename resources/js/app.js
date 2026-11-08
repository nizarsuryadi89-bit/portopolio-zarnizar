import Offcanvas from 'bootstrap/js/dist/offcanvas';
import './pwa';
import './marketing';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebarMenu');
    const menuButtons = document.querySelectorAll('[data-bs-toggle="offcanvas"][data-bs-target="#sidebarMenu"]');

    if (!sidebar) {
        return;
    }

    sidebar.addEventListener('shown.bs.offcanvas', () => {
        menuButtons.forEach((button) => button.setAttribute('aria-expanded', 'true'));
    });
    sidebar.addEventListener('hidden.bs.offcanvas', () => {
        menuButtons.forEach((button) => button.setAttribute('aria-expanded', 'false'));
    });

    window.matchMedia('(min-width: 992px)').addEventListener('change', (event) => {
        if (event.matches) {
            Offcanvas.getInstance(sidebar)?.hide();
        }
    });
});
