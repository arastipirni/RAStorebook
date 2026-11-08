/**
 * Pemberitahuan pesanan baru pada area administrator.
 * Memeriksa pesanan berstatus Menunggu setiap 15 detik.
 */
(function () {
    'use strict';

    const notificationRoot = document.querySelector('[data-admin-order-notifications]');
    if (!notificationRoot) return;

    const toast = document.getElementById('admin-order-toast');
    const indicatorElements = document.querySelectorAll('[data-order-indicator]');
    const dashboardCountElements = document.querySelectorAll('[data-order-count-display]');
    let currentCount = Number.parseInt(notificationRoot.dataset.initialCount || '0', 10);
    let latestOrderId = Number.parseInt(notificationRoot.dataset.latestOrderId || '0', 10);
    let toastTimeout = null;
    let requestInProgress = false;

    function updateCount(count) {
        currentCount = Math.max(0, Number(count) || 0);
        // Indikator visual berupa titik merah, bukan angka. Jumlah tetap
        // tersedia pada aria-label agar informasi dapat dibaca pembaca layar.
        indicatorElements.forEach((element) => {
            element.hidden = currentCount === 0;
        });
        dashboardCountElements.forEach((element) => {
            element.textContent = String(currentCount);
        });

        const accessibleLabel = currentCount > 0
            ? `Pesanan, ${currentCount} pesanan baru menunggu`
            : 'Pesanan';
        const navLink = document.querySelector('.admin-nav a[href="orders.php"]');
        if (navLink) {
            navLink.setAttribute('aria-label', accessibleLabel);
        }
        const notificationLink = document.querySelector('[data-admin-notification-link]');
        if (notificationLink) {
            notificationLink.setAttribute('aria-label', currentCount > 0
                ? `Lihat ${currentCount} pesanan baru`
                : 'Lihat pesanan');
        }
        const notificationSubtitle = document.querySelector('[data-admin-notification-subtitle]');
        if (notificationSubtitle) {
            notificationSubtitle.textContent = currentCount > 0
                ? 'Ada pesanan yang perlu ditinjau'
                : 'Belum ada pesanan baru';
        }
    }

    function showToast(order) {
        if (!toast || !order) return;
        const orderNumber = order.order_number || 'Pesanan baru';
        const customerName = order.customer_name || 'Pelanggan';

        toast.textContent = `Pesanan baru masuk: ${orderNumber} dari ${customerName}. Buka menu Pesanan untuk melihat detail.`;
        toast.hidden = false;
        toast.classList.add('is-visible');

        if (toastTimeout) window.clearTimeout(toastTimeout);
        toastTimeout = window.setTimeout(() => {
            toast.classList.remove('is-visible');
            toast.hidden = true;
        }, 7000);
    }

    async function checkNewOrders() {
        if (requestInProgress || document.visibilityState === 'prerender') return;
        requestInProgress = true;

        try {
            const response = await fetch(`order_notifications.php?_=${Date.now()}`, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: { 'Accept': 'application/json' }
            });

            if (response.status === 401 || response.status === 403) {
                window.clearInterval(pollingTimer);
                return;
            }
            if (!response.ok) return;

            const data = await response.json();
            updateCount(data.pending_count);

            const order = data.latest_order;
            const incomingId = Number.parseInt(order?.id || '0', 10);
            if (order && incomingId > latestOrderId) {
                latestOrderId = incomingId;
                showToast(order);
            }
        } catch (error) {
            // Kegagalan jaringan tidak mengganggu fungsi dashboard lainnya.
        } finally {
            requestInProgress = false;
        }
    }

    updateCount(currentCount);
    const pollingTimer = window.setInterval(checkNewOrders, 15000);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') checkNewOrders();
    });
    checkNewOrders();
})();
