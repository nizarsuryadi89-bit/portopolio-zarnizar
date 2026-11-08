document.addEventListener('DOMContentLoaded', () => {
    const workerUrl = document.querySelector('meta[name="pwa-service-worker"]')?.content;
    const installButtons = document.querySelectorAll('[data-pwa-install]');
    const status = document.querySelector('[data-pwa-status]');
    const installGuide = document.getElementById('pwa-install-guide');
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone === true;
    const isAppleMobile = /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    let installPrompt = null;

    const showInstallButtons = (visible) => {
        installButtons.forEach((button) => { button.hidden = !visible; });
    };
    const updateConnectionStatus = () => {
        if (status) {
            status.hidden = navigator.onLine;
            status.textContent = 'Anda sedang offline. Hubungkan internet untuk memuat dan menyimpan data.';
        }
    };

    updateConnectionStatus();
    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);

    if (workerUrl && window.isSecureContext && 'serviceWorker' in navigator) {
        navigator.serviceWorker.register(workerUrl).catch((error) => {
            console.warn('PWA belum dapat diaktifkan:', error);
        });
    }

    showInstallButtons(window.isSecureContext && isAppleMobile && !isStandalone);
    window.addEventListener('beforeinstallprompt', (event) => {
        if (!workerUrl || isStandalone) {
            return;
        }
        event.preventDefault();
        installPrompt = event;
        showInstallButtons(true);
    });
    window.addEventListener('appinstalled', () => {
        installPrompt = null;
        showInstallButtons(false);
    });

    installButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            if (installPrompt) {
                button.disabled = true;
                try {
                    await installPrompt.prompt();
                    await installPrompt.userChoice;
                } catch (error) {
                    console.warn('Pemasangan aplikasi belum selesai:', error);
                } finally {
                    installPrompt = null;
                    button.disabled = false;
                    showInstallButtons(false);
                }
            } else if (isAppleMobile) {
                installGuide?.showModal();
            }
        });
    });

    document.addEventListener('submit', (event) => {
        if (workerUrl && !navigator.onLine && event.target instanceof HTMLFormElement && event.target.method.toLowerCase() === 'post') {
            event.preventDefault();
            if (status) {
                status.hidden = false;
                status.textContent = 'Form belum dikirim. Hubungkan internet, lalu tekan Simpan atau Masuk kembali.';
            }
        }
    }, true);
});
