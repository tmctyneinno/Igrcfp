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
        console.log('=== INERTIA DEBUG ===');
        console.log('📦 Component name requested:', name);
        console.log('🔍 Looking for file:', `./Pages/${name}.jsx`);
        
        // Get ALL .jsx and .js files
        const jsxPages = import.meta.glob('./Pages/**/*.jsx', { eager: false });
        const jsPages = import.meta.glob('./Pages/**/*.js', { eager: false });
        const allPages = { ...jsxPages, ...jsPages };
        
        console.log('📄 Total pages found:', Object.keys(allPages).length);
        
        // Check for exact match
        const exactPath = `./Pages/${name}.jsx`;
        if (allPages[exactPath]) {
            console.log('✅ Exact match found:', exactPath);
        } else {
            console.log('❌ Exact match NOT found:', exactPath);
            
            // Look for similar files
            const similarFiles = Object.keys(allPages).filter(path => 
                path.toLowerCase().includes(name.toLowerCase()) ||
                path.includes('show') || 
                path.includes('course')
            );
            
            if (similarFiles.length > 0) {
                console.log('🔍 Similar files found:');
                similarFiles.forEach(file => console.log('   -', file));
            } else {
                console.log('🔍 No similar files found');
            }
            
            // List all files for debugging
            console.log('📁 All available pages:');
            Object.keys(allPages).sort().forEach(file => {
                console.log('   -', file);
            });
        }
        
        console.log('=== END DEBUG ===');
        
        try {
            return resolvePageComponent(
                `./Pages/${name}.jsx`,
                allPages
            );
        } catch (error) {
            console.error('❌ Error resolving component:', error);
            throw error;
        }
    },
    setup({ el, App, props }) {
        console.log('🚀 Inertia setup complete');
        console.log('📦 Props received:', Object.keys(props));
        
        const root = createRoot(el);
        root.render(
            <EnrollmentProvider user={props.auth?.user}>
                 <CartProvider> {/* Add CartProvider here */}
                    <App {...props} />
                </CartProvider>
            </EnrollmentProvider>
        );
    },  
    progress: {
        color: '#4B5563',
    },
});