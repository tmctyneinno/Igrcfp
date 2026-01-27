import '../css/app.css';
import './bootstrap';
import React from 'react';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { EnrollmentProvider } from './Contexts/EnrollmentContext';

AOS.init({
    duration: 1000,
    once: true, 
    offset: 100,
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
   
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <EnrollmentProvider user={props.auth?.user}>
                <App {...props} />
            </EnrollmentProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});