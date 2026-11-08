import test from 'node:test';
import assert from 'node:assert/strict';
import { nextSlideIndex, timerState, isTyping, evidenceShortcuts, initializeVideoPlayers, initializeMediaStages, stopEmbeddedVideos } from '../../resources/js/gtk2026.js';

test('arrow and page navigation respect both ends of the eight-slide deck', () => {
    assert.equal(nextSlideIndex(0, 'ArrowLeft', 8), 0);
    assert.equal(nextSlideIndex(7, 'ArrowRight', 8), 7);
    assert.equal(nextSlideIndex(3, 'ArrowRight', 8), 4);
    assert.equal(nextSlideIndex(3, 'ArrowLeft', 8), 2);
    assert.equal(nextSlideIndex(3, 'PageDown', 8), 4);
    assert.equal(nextSlideIndex(3, 'PageUp', 8), 2);
    assert.equal(nextSlideIndex(3, 'Home', 8), 0);
    assert.equal(nextSlideIndex(3, 'End', 8), 7);
    assert.equal(nextSlideIndex(3, 'b', 8), null);
});

test('timer shows six minutes and warns at the requested elapsed thresholds', () => {
    assert.deepEqual(timerState(0), { text: '06:00', level: 'normal' });
    assert.deepEqual(timerState(1), { text: '05:59', level: 'normal' });
    assert.deepEqual(timerState(299), { text: '01:01', level: 'normal' });
    assert.deepEqual(timerState(300), { text: '01:00', level: 'notice' });
    assert.deepEqual(timerState(330), { text: '00:30', level: 'warning' });
    assert.deepEqual(timerState(360), { text: '00:00', level: 'over' });
    assert.deepEqual(timerState(400), { text: '00:00', level: 'over' });
});

test('typing in form fields and contenteditable regions suppresses shortcuts', () => {
    assert.equal(isTyping({ closest: () => ({ tagName: 'INPUT' }) }), true);
    assert.equal(isTyping({ closest: () => ({ isContentEditable: true }) }), true);
    assert.equal(isTyping({ closest: () => null }), false);
    assert.equal(isTyping(null), false);
    assert.deepEqual(evidenceShortcuts, { b: null, t: 'timeline', d: 'dashboard', k: 'bk', w: 'whatsapp', p: 'pilketos' });
});

function videoPlayer(start) {
    const button = { hidden: false, disabled: true, addEventListener: (_, callback) => { button.click = callback; } };
    const frame = {
        hidden: true, dataset: { embedSrc: `https://www.youtube-nocookie.com/embed/xXtA_eOoEZo?start=${start}` },
        setAttribute: (name, value) => { frame[name] = value; },
        removeAttribute: (name) => { delete frame[name]; },
    };
    return { button, frame, querySelector: (selector) => selector === 'iframe' ? frame : button };
}

test('embedded players load only after a click and stop the previous video when another starts', () => {
    const players = [videoPlayer(16), videoPlayer(8)];
    const document = { querySelectorAll: () => players };
    initializeVideoPlayers(document);
    assert.equal(players[0].frame.src, undefined);
    assert.equal(players[1].frame.src, undefined);
    assert.equal(players[0].button.disabled, false);
    players[0].button.click();
    assert.equal(players[0].frame.src, 'https://www.youtube-nocookie.com/embed/xXtA_eOoEZo?start=16&autoplay=1');
    assert.equal(players[0].frame.hidden, false);
    players[1].button.click();
    assert.equal(players[0].frame.src, undefined);
    assert.equal(players[0].button.hidden, false);
    assert.equal(players[1].frame.hidden, false);
    stopEmbeddedVideos(document);
    assert.equal(players[1].frame.src, undefined);
    assert.equal(players[1].frame.hidden, true);
    assert.equal(players[1].button.hidden, false);
});

test('switching to documentation stops playback and keeps the selected media view accessible', () => {
    const player = videoPlayer(7);
    const buttons = ['video', 'documentation'].map((mode) => ({
        dataset: { mediaTab: mode },
        setAttribute(name, value) { this[name] = value; },
        addEventListener(_, callback) { this.click = callback; },
    }));
    const panels = ['video', 'documentation'].map((mode) => ({ dataset: { mediaPanel: mode }, hidden: false }));
    const stage = { querySelectorAll: (selector) => selector === '[data-media-tab]' ? buttons : selector === '[data-media-panel]' ? panels : [player] };
    let updates = 0;
    initializeMediaStages({ querySelectorAll: () => [stage] }, () => { updates++; });
    assert.equal(panels[0].hidden, false);
    assert.equal(panels[1].hidden, true);
    player.frame.src = 'playing';
    buttons[1].click();
    assert.equal(player.frame.src, undefined);
    assert.equal(panels[0].hidden, true);
    assert.equal(panels[1].hidden, false);
    assert.equal(buttons[0]['aria-pressed'], 'false');
    assert.equal(buttons[1]['aria-pressed'], 'true');
    assert.equal(updates, 2);
});
