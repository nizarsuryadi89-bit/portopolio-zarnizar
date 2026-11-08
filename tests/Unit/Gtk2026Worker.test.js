import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync(new URL('../../resources/js/gtk2026-worker.js', import.meta.url), 'utf8');
const origin = 'https://portfolio.example';
const shell = `${origin}/gtk2026`;
const assets = [`${origin}/build/assets/gtk-style.css`, `${origin}/build/assets/gtk-script.js`];

function createWorker(fetchImplementation = async () => new Response('presentation', { headers: { 'X-GTK-Public-Shell': '1' } })) {
    const listeners = new Map();
    const entries = new Map();
    const writes = [];
    const deleted = [];
    const precached = [];
    const key = (request) => typeof request === 'string' ? request : request.url;
    const cache = {
        match: async (request) => entries.get(key(request))?.clone(),
        put: async (request, response) => { writes.push(key(request)); entries.set(key(request), response); },
        addAll: async (urls) => { precached.push(...urls); urls.forEach((url) => entries.set(url, new Response('asset'))); },
    };
    vm.runInNewContext(source, {
        GTK_CORE: ['/gtk2026', ...assets],
        URL, Response, AbortController, setTimeout, clearTimeout,
        self: { location: { origin }, addEventListener: (name, handler) => listeners.set(name, handler), skipWaiting: async () => {}, clients: { claim: async () => {} } },
        caches: { open: async () => cache, keys: async () => ['gtk2026-presentation-old', 'portfolio-admin-pwa-v1'], delete: async (name) => { deleted.push(name); } },
        fetch: fetchImplementation,
    });
    return {
        entries, writes, deleted, precached,
        async lifecycle(name) { let work; listeners.get(name)({ waitUntil: (promise) => { work = promise; } }); await work; },
        async request(path, overrides = {}) {
            let work;
            listeners.get('fetch')({ request: { method: 'GET', url: new URL(path, origin).href, ...overrides }, respondWith: (promise) => { work = promise; } });
            return work === undefined ? undefined : await work;
        },
    };
}

test('worker precaches the public shell and only the declared core assets', async () => {
    const worker = createWorker();
    await worker.lifecycle('install');
    assert.deepEqual(worker.writes, [shell]);
    assert.deepEqual(worker.precached, assets);
});

test('worker refuses to cache a redirect, login page or unmarked HTML response', async () => {
    const worker = createWorker(async () => new Response('login'));
    await assert.rejects(worker.lifecycle('install'), /Public presentation unavailable/);
    assert.deepEqual(worker.writes, []);
});

test('offline presentation reload preserves the full shell for presenter and notes query parameters', async () => {
    const worker = createWorker(async () => { throw new TypeError('offline'); });
    worker.entries.set(shell, new Response('eight slides and notes'));
    const response = await worker.request('/gtk2026?present=1&notes=1');
    assert.equal(await response.text(), 'eight slides and notes');
    assert.deepEqual(worker.writes, []);
});

test('offline style and script requests use their cached core assets', async () => {
    const worker = createWorker(async () => { throw new TypeError('offline'); });
    worker.entries.set(assets[0], new Response('cached css'));
    const response = await worker.request(assets[0]);
    assert.equal(await response.text(), 'cached css');
});

test('worker never intercepts evidence, authentication, other sites or POST requests', async () => {
    const worker = createWorker();
    for (const path of ['/gtk2026/presenter?present=1', '/gtk2026/evidence', '/gtk2026/evidence/bk/media', '/gtk2026/media/dashboard', '/login', '/admin/dashboard', '/', 'https://external.example/gtk2026']) {
        assert.equal(await worker.request(path), undefined);
    }
    assert.equal(await worker.request('/gtk2026', { method: 'POST' }), undefined);
    assert.deepEqual(worker.writes, []);
});

test('server errors fall back to the last public presentation without poisoning its cache', async () => {
    const worker = createWorker(async () => new Response('server error', { status: 500 }));
    worker.entries.set(shell, new Response('last presentation'));
    assert.equal(await (await worker.request('/gtk2026')).text(), 'last presentation');
    assert.deepEqual(worker.writes, []);
});

test('activation deletes only obsolete GTK caches and preserves the existing admin PWA', async () => {
    const worker = createWorker();
    await worker.lifecycle('activate');
    assert.deepEqual(worker.deleted, ['gtk2026-presentation-old']);
});
