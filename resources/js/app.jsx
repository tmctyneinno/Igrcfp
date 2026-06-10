import '../css/app.css';
import './bootstrap';

import React from 'react';
import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { EnrollmentProvider } from './Contexts/EnrollmentContext';
import { CartProvider } from './Contexts/CartContext';

const pages = import.meta.glob('./Pages/**/*.{js,jsx}', { eager: false });

AOS.init({
    duration: 1000,
    once: true,
    offset: 100,
});

if (typeof document !== 'undefined') {
    const preventClipboardActions = (event) => {
        event.preventDefault();
    };
    const preventCopyShortcuts = (event) => {
        const isModifierPressed = event.ctrlKey || event.metaKey;
        const key = event.key?.toLowerCase();
        if (isModifierPressed && ['c', 'x'].includes(key)) {
            event.preventDefault();
        }
    };
    document.addEventListener('contextmenu', preventClipboardActions);
    document.addEventListener('copy', preventClipboardActions);
    document.addEventListener('cut', preventClipboardActions);
    document.addEventListener('keydown', preventCopyShortcuts);
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, pages),
    setup({ el, App, props }) {
        const auth = props.initialPage.props.auth || null;
        const enrollmentRedirect = props.initialPage.props.enrollmentRedirect || null;

        const root = createRoot(el);
        root.render(
            <EnrollmentProvider user={auth?.user} enrollmentRedirect={enrollmentRedirect}>
                <CartProvider>
                    <App {...props} />
                </CartProvider>
            </EnrollmentProvider>
        );

        router.on('navigate', () => {
            AOS.refresh();
        });
    },
    progress: {
        color: '#4B5563',
    },
});