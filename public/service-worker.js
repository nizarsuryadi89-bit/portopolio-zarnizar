const CACHE_PREFIX = 'portfolio-admin-pwa-';
const CACHE_NAME = `${CACHE_PREFIX}v1`;
const BASE_URL = new URL('./', self.location.href);
const OFFLINE_URL = new URL('offline.html', BASE_URL).href;
const STATIC_FILES = ['offline.html', 'pwa-icon-192.png', 'pwa-icon-512.png', 'pwa-icon-maskable.png', 'apple-touch-icon.png']
    .map((path) => new URL(path, BASE_URL).href);

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_FILES)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    event.waitUntil((async () => {
        const cacheNames = await caches.keys();
        await Promise.all(cacheNames.filter((name) => name.startsWith(CACHE_PREFIX) && name !== CACHE_NAME).map((name) => caches.delete(name)));
        await self.clients.claim();
    })());
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    if (request.method !== 'GET' || url.origin !== BASE_URL.origin || !url.pathname.startsWith(BASE_URL.pathname)) {
        return;
    }

    const path = url.pathname.slice(BASE_URL.pathname.length);
    const isAdminNavigation = request.mode === 'navigate' && (path === 'login' || path === 'home' || path === 'admin' || path.startsWith('admin/'));

    if (isAdminNavigation) {
        event.respondWith(fetch(request, { cache: 'no-store' }).catch(async () => {
            const cache = await caches.open(CACHE_NAME);
            return await cache.match(OFFLINE_URL) || Response.error();
        }));
        return;
    }

    if (path.startsWith('build/assets/') && ['style', 'script'].includes(request.destination)) {
        event.respondWith((async () => {
            const cache = await caches.open(CACHE_NAME);
            const cachedResponse = await cache.match(request);
            if (cachedResponse) {
                return cachedResponse;
            }
            const response = await fetch(request);
            const contentType = response.headers.get('Content-Type') || '';
            const isAssetResponse = /^(text\/css|text\/javascript|application\/javascript)/i.test(contentType);
            if (response.ok && isAssetResponse && !/no-store/i.test(response.headers.get('Cache-Control') || '')) {
                await cache.put(request, response.clone());
            }
            return response;
        })());
    }
});
