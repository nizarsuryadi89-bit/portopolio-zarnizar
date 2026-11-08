import test from 'node:test';
import assert from 'node:assert/strict';
import { trackMarketingClick } from '../../resources/js/marketing.js';

const eventFor = (dataset) => ({ target: { closest: () => ({ dataset }) } });

test('WhatsApp tracking sends only allowlisted attribution without link or visitor data', () => {
    const events = [];
    trackMarketingClick(eventFor({
        product: 'kulapos', leadSource: 'kulapos', leadPosition: 'hero', leadIntent: 'demo',
        phone: 'private phone', name: 'private name', text: 'private chat',
    }), (...args) => events.push(args));
    assert.deepEqual(events, [['event', 'contact_click', {
        product: 'kulapos', source_page: 'kulapos', placement: 'hero', intent: 'demo',
    }]]);
});

test('demo navigation is measured separately from contact clicks', () => {
    const events = [];
    trackMarketingClick(eventFor({ demoProduct: 'kulapos' }), (...args) => events.push(args));
    assert.deepEqual(events, [['event', 'demo_click', { product: 'kulapos' }]]);
});

test('missing analytics or attribution cannot block a contact link', () => {
    assert.doesNotThrow(() => trackMarketingClick(eventFor({ product: 'kulapos' }), undefined));
    const events = [];
    trackMarketingClick(eventFor({ product: 'kulapos', leadSource: 'private name' }), (...args) => events.push(args));
    trackMarketingClick({ target: null }, (...args) => events.push(args));
    assert.deepEqual(events, []);
});
