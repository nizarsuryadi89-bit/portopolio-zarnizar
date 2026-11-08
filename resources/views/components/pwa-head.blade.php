<link rel="manifest" href="{{ route('pwa.manifest') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<meta name="theme-color" content="#1e3a8a">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Portfolio">
<meta name="pwa-service-worker" content="{{ asset('service-worker.js') }}">
<style>
    [data-pwa-status][hidden], [data-pwa-install][hidden] { display: none !important; }
    .pwa-install-action {
        display: block; margin: 0 0 1rem auto; padding: 0.75rem 1rem;
        border: 1px solid #1e3a8a; border-radius: 12px; background: #fff;
        color: #1e3a8a; font: inherit; font-size: 0.875rem; font-weight: 700; cursor: pointer;
    }
    .pwa-status {
        position: fixed; z-index: 1100; top: calc(76px + env(safe-area-inset-top));
        left: 50%; transform: translateX(-50%); width: min(92%, 480px);
        padding: 0.875rem 1rem; border-radius: 14px; background: #1e3a8a;
        color: #fff; font-size: 0.875rem; box-shadow: 0 8px 24px #0002;
    }
    .pwa-install-guide {
        box-sizing: border-box; width: min(90%, 420px); margin: auto;
        border: 0; border-radius: 20px; padding: 1.5rem; color: #111827;
        font-family: 'Space Grotesk', sans-serif; background: #fff;
    }
    .pwa-install-guide::backdrop { background: #0007; }
    .pwa-install-guide h2 { margin: 0 0 1rem; font-size: 1.25rem; color: #1e3a8a; }
    .pwa-install-guide p { margin: 0 0 1.5rem; line-height: 1.6; }
    .pwa-install-guide button {
        min-height: 44px; width: 100%; padding: 0.75rem; border: 0;
        border-radius: 12px; background: #1e3a8a; color: #fff; font: inherit;
        font-weight: 700; cursor: pointer;
    }
</style>
