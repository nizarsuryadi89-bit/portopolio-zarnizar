const products = ['kulapos', 'jasa-aplikasi'];
const sources = ['home', 'kulapos', 'jasa-aplikasi', 'blog', 'portfolio'];
const positions = ['hero', 'content', 'demo', 'process', 'footer'];
const intents = ['demo', 'consultation'];

export function trackMarketingClick(event, analytics) {
    const link = event.target?.closest?.('a[data-product], a[data-demo-product]');
    if (!link || typeof analytics !== 'function') {
        return;
    }

    if (products.includes(link.dataset.demoProduct)) {
        analytics('event', 'demo_click', { product: link.dataset.demoProduct });
        return;
    }

    if (!products.includes(link.dataset.product)
        || !sources.includes(link.dataset.leadSource)
        || !positions.includes(link.dataset.leadPosition)
        || !intents.includes(link.dataset.leadIntent)) {
        return;
    }

    analytics('event', 'contact_click', {
        product: link.dataset.product,
        source_page: link.dataset.leadSource,
        placement: link.dataset.leadPosition,
        intent: link.dataset.leadIntent,
    });
}

if (typeof document !== 'undefined') {
    document.addEventListener('click', (event) => trackMarketingClick(event, window.gtag));
    document.addEventListener('click', (event) => {
        const link = event.target?.closest?.('.sales-mobile-links a');
        if (link) {
            link.closest('details').open = false;
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.sales-mobile-menu[open]').forEach((menu) => {
                menu.open = false;
                menu.querySelector('summary').focus();
            });
        }
    });
}
