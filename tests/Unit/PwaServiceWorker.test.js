import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync(new URL('../../public/service-worker.js', import.meta.url), 'utf8');

function createWorker({ fetchImplementation = async () => new Response('network'), workerUrl = 'https://portfolio.example/service-worker.js' } = {}) {
    const listeners = new Map();
    const entries = new Map();
    const writes = [];
    const fetchCalls = [];
    const deletedCaches = [];
    const precachedUrls = [];
    const key = (request) => typeof request === 'string' ? request : request.url;
    const cache = {
        addAll: async (urls) => {
            precachedUrls.push(...urls);
            urls.forEach((url) => entries.set(url, new Response(url.endsWith('offline.html') ? 'offline-notice' : 'icon')));
        },
        match: async (request) => entries.get(key(request))?.clone(),
        put: async (request, response) => {
            writes.push(key(request));
            entries.set(key(request), response);
        },
    };
    const context = {
        URL, Response,
        self: {
            location: { href: workerUrl },
            addEventListener: (name, listener) => listeners.set(name, listener),
            skipWaiting: async () => {},
            clients: { claim: async () => {} },
        },
        caches: {
            open: async () => cache,
            keys: async () => ['portfolio-admin-pwa-v0', 'portfolio-admin-pwa-v1', 'other-app-cache'],
            delete: async (name) => { deletedCaches.push(name); return true; },
        },
        fetch: async (...args) => { fetchCalls.push(args); return fetchImplementation(...args); },
    };
    vm.runInNewContext(source, context);
    return {
        entries, writes, fetchCalls, deletedCaches, precachedUrls,
        async lifecycle(name) {
            let work;
            listeners.get(name)({ waitUntil: (promise) => { work = promise; } });
            await work;
        },
        async fetch(path, overrides = {}) {
            let response;
            listeners.get('fetch')({
                request: { url: new URL(path, workerUrl).href, method: 'GET', mode: 'navigate', destination: 'document', ...overrides },
                respondWith: (promise) => { response = promise; },
            });
            return response === undefined ? undefined : await response;
        },
    };
}

test('installation caches a public offline page and app icons', async () => {
    const worker = createWorker();
    await worker.lifecycle('install');
    assert.ok(worker.precachedUrls.includes('https://portfolio.example/offline.html'));
    assert.ok(worker.precachedUrls.includes('https://portfolio.example/pwa-icon-192.png'));
    assert.ok(worker.precachedUrls.every((url) => !url.includes('/admin/') && !url.endsWith('/login')));
});

test('activation removes only obsolete caches belonging to this PWA', async () => {
    const worker = createWorker();
    await worker.lifecycle('activate');
    assert.deepEqual(worker.deletedCaches, ['portfolio-admin-pwa-v0']);
});

test('online admin pages always use the network and are never cached', async () => {
    const worker = createWorker({ fetchImplementation: async () => new Response('private-dashboard') });
    const response = await worker.fetch('/admin/dashboard');
    assert.equal(await response.text(), 'private-dashboard');
    assert.equal(worker.fetchCalls[0][1].cache, 'no-store');
    assert.deepEqual(worker.writes, []);
});

test('offline admin navigation shows a public notice instead of previously cached private content', async () => {
    const worker = createWorker({ fetchImplementation: async () => { throw new TypeError('offline'); } });
    await worker.lifecycle('install');
    worker.entries.set('https://portfolio.example/admin/dashboard', new Response('private-dashboard'));
    const response = await worker.fetch('/admin/dashboard');
    assert.equal(await response.text(), 'offline-notice');
    assert.deepEqual(worker.writes, []);
});

test('login and home navigation also show the offline notice without caching credentials', async () => {
    const worker = createWorker({ fetchImplementation: async () => { throw new TypeError('offline'); } });
    await worker.lifecycle('install');
    for (const path of ['/login', '/home']) {
        assert.equal(await (await worker.fetch(path)).text(), 'offline-notice');
    }
    assert.deepEqual(worker.writes, []);
});

test('writes, cross-origin assets, and non-navigation admin requests are not intercepted', async () => {
    const worker = createWorker();
    assert.equal(await worker.fetch('/admin/posts', { method: 'POST' }), undefined);
    assert.equal(await worker.fetch('https://other.example/build/assets/app.js', { destination: 'script', mode: 'cors' }), undefined);
    assert.equal(await worker.fetch('/admin/profile', { destination: '', mode: 'cors' }), undefined);
    assert.deepEqual(worker.fetchCalls, []);
    assert.deepEqual(worker.writes, []);
});

test('hashed public build assets remain available after a connection failure', async () => {
    let online = true;
    const worker = createWorker({ fetchImplementation: async () => {
        if (!online) { throw new TypeError('offline'); }
        return new Response('app-css', { headers: { 'Content-Type': 'text/css' } });
    } });
    const request = { destination: 'style', mode: 'cors' };
    assert.equal(await (await worker.fetch('/build/assets/app-hash.css', request)).text(), 'app-css');
    online = false;
    assert.equal(await (await worker.fetch('/build/assets/app-hash.css', request)).text(), 'app-css');
    assert.equal(worker.fetchCalls.length, 1);
});

test('HTML responses and no-store assets are not persisted in the asset cache', async () => {
    for (const headers of [{ 'Content-Type': 'text/html' }, { 'Content-Type': 'text/css', 'Cache-Control': 'no-store' }]) {
        const worker = createWorker({ fetchImplementation: async () => new Response('private-or-uncacheable', { headers }) });
        await worker.fetch('/build/assets/app-hash.css', { destination: 'style', mode: 'cors' });
        assert.deepEqual(worker.writes, []);
    }
});

test('PWA resources resolve within the deployment path when hosted in a subdirectory', async () => {
    const worker = createWorker({ workerUrl: 'https://online.example/cms/service-worker.js', fetchImplementation: async () => { throw new TypeError('offline'); } });
    await worker.lifecycle('install');
    assert.ok(worker.precachedUrls.includes('https://online.example/cms/offline.html'));
    assert.equal(await (await worker.fetch('/cms/admin/dashboard')).text(), 'offline-notice');
    assert.equal(await worker.fetch('/admin/dashboard'), undefined);
});
