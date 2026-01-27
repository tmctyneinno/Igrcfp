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
    resolve: (name) => {
        console.log('🔄 Inertia is trying to resolve component:', name);
        console.log('📁 Looking for file:', `./Pages/${name}.jsx`);
        
        const pages = import.meta.glob('./Pages/**/*.jsx');
        console.log('📄 Available pages:', Object.keys(pages));
        
        // Check if our specific file exists
        const targetPath = `./Pages/${name}.jsx`;
        if (pages[targetPath]) {
            console.log('✅ Found component at:', targetPath);
        } else {
            console.log('❌ Component NOT found at:', targetPath);
            console.log('🔍 Similar files:', 
                Object.keys(pages).filter(p => p.includes(name.toLowerCase()) || 
                                               p.includes('show') || 
                                               p.includes('course'))
            );
        }
        
        return resolvePageComponent(
            `./Pages/${name}.jsx`,
            pages
        );
    },
    setup({ el, App, props }) {
        console.log('🚀 Setting up Inertia app with props:', props);
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