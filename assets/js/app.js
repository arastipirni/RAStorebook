// Interaksi kecil agar UI terasa hidup.
// Semua fitur utama tetap bisa berjalan tanpa framework.

document
    .querySelectorAll('[data-confirm]')
    .forEach((element) => {
        element.addEventListener('click', (event) => {
            const message =
                element.dataset.confirm
                || 'Yakin ingin melanjutkan?';

            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    });

// Pertahankan mode pratinjau admin hanya di tab website yang sedang dibuka.
// Tab lain tetap menggunakan tampilan dan identitas user seperti biasa.
(() => {
    const currentUrl = new URL(window.location.href);

    if (currentUrl.searchParams.get('admin_preview') !== '1') {
        return;
    }

    const isAdminPage = (url) => /\/admin(?:\/|$)/i.test(url.pathname);

    // Bawa penanda pratinjau ke tautan internal website publik.
    document.querySelectorAll('a[href]').forEach((link) => {
        const href = link.getAttribute('href');

        if (!href || href.startsWith('#')) {
            return;
        }

        try {
            const targetUrl = new URL(href, window.location.href);

            if (targetUrl.origin !== window.location.origin || isAdminPage(targetUrl)) {
                return;
            }

            targetUrl.searchParams.set('admin_preview', '1');
            link.href = targetUrl.href;
        } catch (error) {
            // Abaikan tautan yang bukan URL biasa, misalnya mailto:.
        }
    });

    // Sertakan penanda pada form GET maupun POST di website publik.
    document.querySelectorAll('form').forEach((form) => {
        const action = form.getAttribute('action') || window.location.href;

        try {
            const targetUrl = new URL(action, window.location.href);

            if (targetUrl.origin !== window.location.origin || isAdminPage(targetUrl)) {
                return;
            }

            if (form.method.toLowerCase() === 'get') {
                targetUrl.searchParams.set('admin_preview', '1');
                form.action = targetUrl.href;
                return;
            }

            if (!form.querySelector('input[name="admin_preview"]')) {
                const previewInput = document.createElement('input');
                previewInput.type = 'hidden';
                previewInput.name = 'admin_preview';
                previewInput.value = '1';
                form.appendChild(previewInput);
            }
        } catch (error) {
            // Abaikan form yang dikirim ke tujuan bukan URL biasa.
        }
    });
})();

