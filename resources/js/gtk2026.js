export const evidenceShortcuts = { b: null, t: 'timeline', d: 'dashboard', k: 'bk', w: 'whatsapp', p: 'pilketos' };

export function nextSlideIndex(current, key, total) {
    if (['ArrowRight', 'PageDown'].includes(key)) return Math.min(current + 1, total - 1);
    if (['ArrowLeft', 'PageUp'].includes(key)) return Math.max(current - 1, 0);
    if (key === 'Home') return 0;
    if (key === 'End') return total - 1;
    return null;
}

export function timerState(elapsed) {
    const remaining = Math.max(0, 360 - Math.floor(elapsed));
    return {
        text: `${String(Math.floor(remaining / 60)).padStart(2, '0')}:${String(remaining % 60).padStart(2, '0')}`,
        level: elapsed >= 360 ? 'over' : elapsed >= 330 ? 'warning' : elapsed >= 300 ? 'notice' : 'normal',
    };
}

export function isTyping(target) {
    return Boolean(target?.closest('input, textarea, select, [contenteditable]:not([contenteditable="false"]), [role="textbox"]'));
}

export function stopEmbeddedVideos(root) {
    root.querySelectorAll('[data-gtk-video]').forEach((player) => {
        const frame = player.querySelector('iframe');
        frame.removeAttribute('src');
        frame.hidden = true;
        player.querySelector('[data-video-play]').hidden = false;
    });
}

export async function ensureDeckImagesLoaded(root) {
    const images = Array.from(root.querySelectorAll('.gtk-deck img'));
    await Promise.all(images.map((img) => {
        img.loading = 'eager';
        if (img.complete && img.naturalWidth > 0) return Promise.resolve();
        return new Promise((resolve) => {
            img.addEventListener('load', resolve, { once: true });
            img.addEventListener('error', resolve, { once: true });
        });
    }));
    await Promise.all(images.map(async (img) => {
        if (typeof img.decode === 'function') {
            try { await img.decode(); } catch { /* ignore */ }
        }
    }));
}

export function initializeVideoPlayers(document) {
    document.querySelectorAll('[data-gtk-video]').forEach((player) => {
        const button = player.querySelector('[data-video-play]');
        const frame = player.querySelector('iframe');
        button.disabled = false;
        button.addEventListener('click', () => {
            stopEmbeddedVideos(document);
            frame.setAttribute('src', `${frame.dataset.embedSrc}&autoplay=1`);
            frame.hidden = false;
            button.hidden = true;
        });
    });
}

export function initializeMediaStages(document, onChange) {
    document.querySelectorAll('[data-media-stage]').forEach((stage) => {
        const buttons = [...stage.querySelectorAll('[data-media-tab]')];
        const panels = [...stage.querySelectorAll('[data-media-panel]')];
        const select = (mode) => {
            stopEmbeddedVideos(stage);
            buttons.forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.mediaTab === mode)));
            panels.forEach((panel) => { panel.hidden = panel.dataset.mediaPanel !== mode; });
            onChange();
        };
        buttons.forEach((button) => button.addEventListener('click', () => select(button.dataset.mediaTab)));
        select('video');
    });
}

export function initializePresentation(document, window) {
    const page = document.querySelector('[data-gtk]');
    if (!page) return;
    const slides = [...document.querySelectorAll('[data-slide]')];
    const dialog = document.querySelector('[data-evidence-dialog]');
    const search = document.querySelector('[data-evidence-search]');
    const items = [...document.querySelectorAll('[data-evidence-item]')];
    const details = [...document.querySelectorAll('[data-evidence-detail]')];
    const timer = document.querySelector('[data-timer]');
    const parameters = new URLSearchParams(window.location.search);
    let current = 0;
    let focusBeforeDialog = null;
    let selectedEvidence = 'source';
    let touch = null;
    let toastTimeout;
    let elapsed = 0;
    let timerStartedAt = null;
    let timerInterval;

    const announce = (message) => { document.querySelector('[data-announcement]').textContent = message; };
    const fitActiveSlide = () => {
        const inner = slides[current].querySelector('.gtk-slide-inner');
        inner.style.scale = '';
        if (window.innerWidth < 700 || window.innerHeight <= 540 || !page.classList.contains('gtk-enhanced')) return;
        const style = window.getComputedStyle(inner);
        const contentHeight = inner.scrollHeight - parseFloat(style.paddingTop) - parseFloat(style.paddingBottom);
        const available = window.innerHeight - document.querySelector('.gtk-header').offsetHeight - document.querySelector('.gtk-controls').offsetHeight - 40;
        inner.style.scale = String(Math.min(1, available / contentHeight));
    };
    const toast = (message) => {
        const element = document.querySelector('[data-toast]');
        window.clearTimeout(toastTimeout);
        element.textContent = message;
        element.hidden = false;
        toastTimeout = window.setTimeout(() => { element.hidden = true; }, 4500);
    };
    const renderSlide = (index, focus = false) => {
        if (index === null) return;
        current = Math.max(0, Math.min(index, slides.length - 1));
        slides.forEach((slide, i) => {
            if (i !== current) stopEmbeddedVideos(slide);
            slide.hidden = i !== current;
            slide.setAttribute('aria-hidden', String(i !== current));
            if (i === current) slide.scrollTop = 0;
        });
        page.classList.toggle('gtk-is-light', slides[current].classList.contains('gtk-light'));
        document.querySelector('[data-slide-current]').textContent = String(current + 1);
        document.querySelector('[data-slide-label]').textContent = slides[current].dataset.label;
        document.querySelector('[data-previous]').disabled = current === 0;
        document.querySelector('[data-next]').disabled = current === slides.length - 1;
        document.querySelector('[data-progress]').style.width = `${(current + 1) / slides.length * 100}%`;
        document.querySelector('[role="progressbar"]').setAttribute('aria-valuenow', String(current + 1));
        announce(`Slide ${current + 1} dari ${slides.length}: ${slides[current].dataset.label}`);
        try { window.history.replaceState(null, '', `#slide-${current + 1}`); } catch { /* Sandboxed previews may disable history. */ }
        if (focus) slides[current].focus({ preventScroll: true });
        fitActiveSlide();
    };
    const selectEvidence = (id, focus = false) => {
        if (!details.some((detail) => detail.dataset.evidenceDetail === id)) return;
        selectedEvidence = id;
        items.forEach((item) => item.setAttribute('aria-pressed', String(item.dataset.evidenceItem === id)));
        details.forEach((detail) => {
            const active = detail.dataset.evidenceDetail === id;
            detail.hidden = !active;
            if (!active) stopEmbeddedVideos(detail);
            detail.querySelectorAll('video').forEach((video) => { if (!active) video.pause(); });
            if (active) {
                detail.querySelectorAll('img[data-src]').forEach((img) => { img.src = img.dataset.src; img.removeAttribute('data-src'); });
                detail.querySelectorAll('video[data-video-src]').forEach((video) => { video.src = video.dataset.videoSrc; video.removeAttribute('data-video-src'); });
                if (focus) detail.focus({ preventScroll: true });
            }
        });
    };
    const filterEvidence = () => {
        const query = search.value.toLocaleLowerCase('id').trim();
        items.forEach((item) => { item.hidden = !item.dataset.search.includes(query); });
        const visible = items.filter((item) => !item.hidden);
        document.querySelector('[data-no-results]').hidden = visible.length > 0;
        if (visible.length) {
            selectEvidence(visible.some((item) => item.dataset.evidenceItem === selectedEvidence) ? selectedEvidence : visible[0].dataset.evidenceItem);
        }
        details.forEach((detail) => {
            if (!visible.length) {
                detail.hidden = true;
                stopEmbeddedVideos(detail);
            }
        });
    };
    const openEvidence = (id = null) => {
        stopEmbeddedVideos(slides[current]);
        if (typeof dialog.showModal !== 'function') {
            toast('Panel bukti memerlukan browser modern. Gunakan Chrome, Edge, Safari, atau Firefox terbaru.');
            return;
        }
        if (!dialog.open) focusBeforeDialog = document.activeElement;
        search.value = '';
        filterEvidence();
        selectEvidence(id || selectedEvidence);
        if (!dialog.open) dialog.showModal();
        search.focus();
    };
    const closeEvidence = () => {
        if (dialog.open) dialog.close();
    };
    dialog.addEventListener('close', () => {
        stopEmbeddedVideos(dialog);
        details.forEach((detail) => detail.querySelectorAll('video').forEach((video) => video.pause()));
        if (focusBeforeDialog?.isConnected && !focusBeforeDialog.closest('[hidden]')) focusBeforeDialog.focus({ preventScroll: true });
        else slides[current].focus({ preventScroll: true });
    });
    dialog.addEventListener('click', (event) => { if (event.target === dialog) { const box = dialog.getBoundingClientRect(); if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) closeEvidence(); } });
    document.querySelectorAll('[data-evidence-open]').forEach((button) => button.addEventListener('click', () => openEvidence()));
    document.querySelectorAll('[data-evidence-target]').forEach((button) => button.addEventListener('click', () => openEvidence(button.dataset.evidenceTarget)));
    document.querySelector('[data-evidence-close]').addEventListener('click', closeEvidence);
    items.forEach((item) => item.addEventListener('click', () => selectEvidence(item.dataset.evidenceItem, true)));
    search.addEventListener('input', filterEvidence);
    search.addEventListener('keydown', (event) => {
        if (['ArrowDown', 'Enter'].includes(event.key)) {
            const first = items.find((item) => !item.hidden);
            if (first) { event.preventDefault(); first.focus(); }
        }
    });
    dialog.addEventListener('keydown', (event) => {
        if (event.key !== 'Tab') return;
        const focusable = [...dialog.querySelectorAll('button, input, a[href], video[controls], iframe')].filter((element) => !element.closest('[hidden]') && !element.disabled);
        const first = focusable[0];
        const last = focusable.at(-1);
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });
    document.addEventListener('keydown', (event) => {
        if (event.defaultPrevented || event.isComposing) return;
        if (!isTyping(event.target) && (event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault(); openEvidence(); return;
        }
        if (event.key === 'Escape' && dialog.open) { event.preventDefault(); closeEvidence(); return; }
        if (isTyping(event.target) || event.ctrlKey || event.metaKey || event.altKey || event.shiftKey) return;
        const key = event.key.toLowerCase();
        if (Object.hasOwn(evidenceShortcuts, key)) { event.preventDefault(); openEvidence(evidenceShortcuts[key]); return; }
        if (dialog.open) return;
        const index = nextSlideIndex(current, event.key, slides.length);
        if (index !== null) { event.preventDefault(); renderSlide(index, true); }
    });
    document.querySelector('[data-previous]').addEventListener('click', () => renderSlide(current - 1, true));
    document.querySelector('[data-next]').addEventListener('click', () => renderSlide(current + 1, true));
    document.querySelector('[data-start]').addEventListener('click', () => renderSlide(1, true));
    document.querySelector('[data-print]').addEventListener('click', async () => {
        await ensureDeckImagesLoaded(document);
        window.print();
    });
    page.addEventListener('touchstart', (event) => {
        if (dialog.open || event.touches.length !== 1 || event.target.closest('button, a, input, video, dialog')) { touch = null; return; }
        touch = { x: event.touches[0].clientX, y: event.touches[0].clientY };
    }, { passive: true });
    page.addEventListener('touchend', (event) => {
        if (!touch || dialog.open) return;
        const x = event.changedTouches[0].clientX - touch.x;
        const y = event.changedTouches[0].clientY - touch.y;
        if (Math.abs(x) >= 65 && Math.abs(x) > Math.abs(y) * 1.5) renderSlide(current + (x < 0 ? 1 : -1), true);
        touch = null;
    }, { passive: true });
    page.addEventListener('touchcancel', () => { touch = null; }, { passive: true });
    document.querySelectorAll('[data-fullscreen]').forEach((button) => button.addEventListener('click', async () => {
        try {
            if (document.fullscreenElement) await document.exitFullscreen();
            else if (document.documentElement.requestFullscreen) await document.documentElement.requestFullscreen();
            else toast('Fullscreen belum didukung. Gunakan mode layar penuh dari menu browser.');
        } catch { toast('Fullscreen belum diizinkan. Gunakan mode layar penuh dari menu browser.'); }
    }));
    document.addEventListener('fullscreenchange', () => {
        document.querySelectorAll('[data-fullscreen]').forEach((button) => {
            const label = document.fullscreenElement ? 'Keluar Fullscreen' : 'Masuk Fullscreen';
            button.setAttribute('aria-label', label);
            const text = button.querySelector('span');
            if (text) text.textContent = label;
        });
    });
    const renderTimer = () => {
        const seconds = elapsed + (timerStartedAt === null ? 0 : (window.performance.now() - timerStartedAt) / 1000);
        const state = timerState(seconds);
        timer.textContent = state.text;
        timer.dataset.level = state.level;
        timer.setAttribute('aria-label', `${timerStartedAt === null ? 'Lanjutkan' : 'Jeda'} timer, sisa ${state.text}`);
        timer.setAttribute('aria-pressed', String(timerStartedAt !== null));
    };
    timer.addEventListener('click', () => {
        if (timerStartedAt !== null) {
            elapsed += (window.performance.now() - timerStartedAt) / 1000;
            timerStartedAt = null;
            window.clearInterval(timerInterval);
        } else {
            timerStartedAt = window.performance.now();
            timerInterval = window.setInterval(renderTimer, 250);
        }
        renderTimer();
    });
    timer.addEventListener('dblclick', () => {
        window.clearInterval(timerInterval); elapsed = 0; timerStartedAt = null;
        timer.textContent = 'Mulai 6 Menit'; timer.dataset.level = 'normal'; timer.setAttribute('aria-label', 'Mulai timer enam menit'); timer.setAttribute('aria-pressed', 'false');
    });
    const notes = parameters.get('notes') === '1';
    page.classList.toggle('gtk-notes-mode', notes);
    page.classList.toggle('gtk-present', parameters.get('present') === '1');
    document.querySelectorAll('[data-notes]').forEach((note) => { note.hidden = !notes; });
    const hash = /^#slide-([1-8])$/.exec(window.location.hash);
    renderSlide(hash ? Number(hash[1]) - 1 : 0);
    page.classList.add('gtk-enhanced');
    initializeMediaStages(document, fitActiveSlide);
    ensureDeckImagesLoaded(document);
    fitActiveSlide();
    window.addEventListener('resize', fitActiveSlide);
    document.fonts?.ready.then(fitActiveSlide);
    window.addEventListener('hashchange', () => { const match = /^#slide-([1-8])$/.exec(window.location.hash); if (match) renderSlide(Number(match[1]) - 1, true); });
    const status = document.querySelector('[data-offline-status]');
    let offlineReady = false;
    const updateStatus = () => { status.textContent = !window.navigator.onLine ? (offlineReady ? 'Offline' : 'Koneksi terputus') : offlineReady ? 'Offline siap' : ''; };
    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
    updateStatus();
    if (page.dataset.worker && window.isSecureContext && 'serviceWorker' in window.navigator) {
        const register = async () => {
            try {
                const registration = await window.navigator.serviceWorker.register(page.dataset.worker, { scope: page.dataset.scope });
                const markReady = () => { if (registration.active) { offlineReady = true; updateStatus(); } };
                const watchInstallation = () => {
                    const worker = registration.installing;
                    worker?.addEventListener('statechange', () => { if (worker.state === 'activated') markReady(); });
                };
                markReady();
                watchInstallation();
                registration.addEventListener('updatefound', watchInstallation);
            } catch { status.textContent = 'Cetak PDF untuk cadangan'; }
        };
        if (document.readyState === 'complete') register();
        else window.addEventListener('load', register, { once: true });
    }
}

if (typeof document !== 'undefined') {
    initializeVideoPlayers(document);
    window.addEventListener('pagehide', () => stopEmbeddedVideos(document));
    window.addEventListener('beforeprint', () => {
        stopEmbeddedVideos(document);
        document.querySelectorAll('.gtk-deck img').forEach((img) => {
            img.loading = 'eager';
        });
    });
    initializePresentation(document, window);
}
