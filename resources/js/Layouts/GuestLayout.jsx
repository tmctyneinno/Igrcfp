import { Head, Link } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import Footer from '@/Pages/components/Footer'; // Adjust path based on your footer location

export default function GuestLayout({ children, auth }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    // Close mobile menu on route change
    useEffect(() => {
        setIsMobileMenuOpen(false);
    }, []);

    return (
        <div className="min-h-screen bg-gray-50">
           
            

            {/* Main Content */}
            <main className="pt-16">
                {children}
            </main>

            {/* Footer */}
            <Footer />
        </div>
    );
}