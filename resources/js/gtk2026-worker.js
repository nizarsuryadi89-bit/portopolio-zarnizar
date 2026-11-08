const GTK_PREFIX = 'gtk2026-presentation-';
const GTK_CACHE = `${GTK_PREFIX}${GTK_CORE.join('|')}`;
const GTK_SHELL = new URL(GTK_CORE[0], self.location.origin).href;
const GTK_ASSETS = GTK_CORE.slice(1).map((path) => new URL(path, self.location.origin).href);

self.addEventListener('install', (event) => {
    event.waitUntil((async () => {
        const cache = await caches.open(GTK_CACHE);
        const response = await fetch(GTK_SHELL, { credentials: 'omit', cache: 'reload' });
        if (!response.ok || response.headers.get('X-GTK-Public-Shell') !== '1') throw new Error('Public presentation unavailable');
        await cache.put(GTK_SHELL, response);
        await cache.addAll(GTK_ASSETS);
        await self.skipWaiting();
    })());
});
self.addEventListener('activate', (event) => {
    event.waitUntil((async () => {
        const names = await caches.keys();
        await Promise.all(names.filter((name) => name.startsWith(GTK_PREFIX) && name !== GTK_CACHE).map((name) => caches.delete(name)));
        await self.clients.claim();
    })());
});
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);
    if (request.method !== 'GET' || url.origin !== self.location.origin) return;
    const isShell = url.pathname === new URL(GTK_SHELL).pathname;
    const isAsset = GTK_ASSETS.includes(url.href);
    if (!isShell && !isAsset) return;
    event.respondWith((async () => {
        const cache = await caches.open(GTK_CACHE);
        if (isAsset) return await cache.match(request) || fetch(request);
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 2500);
        try {
            const response = await fetch(request, { signal: controller.signal });
            if (!response.ok || response.headers.get('X-GTK-Public-Shell') !== '1') throw new Error('Presentation unavailable');
            await cache.put(GTK_SHELL, response.clone());
            return response;
        } catch {
            return await cache.match(GTK_SHELL) || Response.error();
        } finally { clearTimeout(timeout); }
    })());
});
