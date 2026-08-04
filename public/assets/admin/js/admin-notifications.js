(function () {
    const routes = window.adminNotificationRoutes;

    if (!routes) {
        return;
    }

    const button = document.getElementById('admin-notification-button');
    const count = document.getElementById('admin-notification-count');
    const list = document.getElementById('admin-notification-list');
    const markAll = document.getElementById('admin-notification-mark-all');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!button || !count || !list) {
        return;
    }

    const request = (url, options = {}) => fetch(url, {
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf || '',
            ...(options.headers || {}),
        },
        ...options,
    });

    const escapeHtml = (value) => String(value || '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[character]));

    const markAsRead = async (notification) => {
        await request(routes.markReadTemplate.replace('__ID__', notification.id), { method: 'POST' });

        if (notification.url) {
            window.location.href = notification.url;
            return;
        }

        fetchNotifications();
    };

    const render = (payload) => {
        const unreadCount = Number(payload.unread_count || 0);
        count.textContent = unreadCount > 99 ? '99+' : unreadCount;
        button.classList.toggle('has-indicator', unreadCount > 0);

        if (!payload.notifications || payload.notifications.length === 0) {
            list.innerHTML = '<div class="px-24 py-24 text-center text-secondary-light text-sm">No notifications yet</div>';
            return;
        }

        list.innerHTML = payload.notifications.map((notification) => `
            <a href="javascript:void(0)" data-notification-id="${notification.id}" class="admin-notification-item px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between ${notification.is_read ? '' : 'bg-primary-50'}">
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                    <span class="w-44-px h-44-px ${escapeHtml(notification.color_class)} rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                        <iconify-icon icon="${escapeHtml(notification.icon)}" class="icon text-xxl"></iconify-icon>
                    </span>
                    <div>
                        <h6 class="text-md fw-semibold mb-4">${escapeHtml(notification.title)}</h6>
                        <p class="mb-0 text-sm text-secondary-light text-w-200-px">${escapeHtml(notification.message)}</p>
                    </div>
                </div>
                <span class="text-sm text-secondary-light flex-shrink-0">${escapeHtml(notification.time_ago)}</span>
            </a>
        `).join('');

        list.querySelectorAll('.admin-notification-item').forEach((item, index) => {
            item.addEventListener('click', () => markAsRead(payload.notifications[index]));
        });
    };

    async function fetchNotifications() {
        try {
            const response = await request(routes.recent);
            render(await response.json());
        } catch (error) {
            list.innerHTML = '<div class="px-24 py-24 text-center text-danger-main text-sm">Unable to load notifications</div>';
        }
    }

    markAll?.addEventListener('click', async () => {
        await request(routes.markAllRead, { method: 'POST' });
        fetchNotifications();
    });

    fetchNotifications();
    setInterval(fetchNotifications, 15000);
})();
