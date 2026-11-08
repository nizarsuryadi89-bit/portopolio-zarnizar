import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync(new URL('../../resources/js/pwa.js', import.meta.url), 'utf8');

function createClient({ secure = true, apple = false, standalone = false, online = true } = {}) {
    const documentEvents = new Map();
    const windowEvents = new Map();
    const buttonEvents = new Map();
    const registrations = [];
    const button = { hidden: true, disabled: false, addEventListener: (name, callback) => buttonEvents.set(name, callback) };
    const status = { hidden: true, textContent: '' };
    let guideOpened = false;
    class Form { constructor(method = 'post') { this.method = method; } }
    const navigator = {
        onLine: online,
        userAgent: apple ? 'iPhone' : 'Android', platform: apple ? 'iPhone' : 'Linux', maxTouchPoints: 1,
        serviceWorker: { register: async (url) => { registrations.push(url); } },
    };
    const window = {
        isSecureContext: secure,
        matchMedia: () => ({ matches: standalone }),
        addEventListener: (name, callback) => windowEvents.set(name, callback),
    };
    const document = {
        querySelector: (selector) => selector.startsWith('meta') ? { content: 'https://portfolio.example/service-worker.js' } : status,
        querySelectorAll: () => [button],
        getElementById: () => ({ showModal: () => { guideOpened = true; } }),
        addEventListener: (name, callback) => documentEvents.set(name, callback),
    };
    vm.runInNewContext(source, { document, window, navigator, HTMLFormElement: Form, console });
    documentEvents.get('DOMContentLoaded')();
    return {
        button, status, registrations, Form, navigator,
        get guideOpened() { return guideOpened; },
        documentEvent: (name, event) => documentEvents.get(name)(event),
        windowEvent: (name, event) => windowEvents.get(name)(event),
        clickInstall: () => buttonEvents.get('click')(),
    };
}

test('HTTP pages stay usable without attempting service-worker registration or installation', () => {
    const client = createClient({ secure: false, apple: true });
    assert.deepEqual(client.registrations, []);
    assert.equal(client.button.hidden, true);
});

test('Android installation is offered when the browser is ready and prompted only after a click', async () => {
    const client = createClient();
    let prompted = 0;
    let defaultPrevented = false;
    client.windowEvent('beforeinstallprompt', {
        preventDefault: () => { defaultPrevented = true; },
        prompt: async () => { prompted++; },
        userChoice: Promise.resolve({ outcome: 'accepted' }),
    });
    assert.equal(defaultPrevented, true);
    assert.equal(client.button.hidden, false);
    assert.equal(prompted, 0);
    await client.clickInstall();
    assert.equal(prompted, 1);
    assert.equal(client.button.hidden, true);
});

test('iPhone browsers show installation guidance and installed apps hide it', async () => {
    const client = createClient({ apple: true });
    assert.equal(client.button.hidden, false);
    await client.clickInstall();
    assert.equal(client.guideOpened, true);
    assert.equal(createClient({ apple: true, standalone: true }).button.hidden, true);
});

test('offline forms are not submitted and the status clears when the connection returns', () => {
    const client = createClient({ online: false });
    const form = new client.Form();
    let prevented = false;
    client.documentEvent('submit', { target: form, preventDefault: () => { prevented = true; } });
    assert.equal(prevented, true);
    assert.equal(client.status.hidden, false);
    assert.match(client.status.textContent, /Form belum dikirim/);
    client.navigator.onLine = true;
    client.windowEvent('online');
    assert.equal(client.status.hidden, true);
});
