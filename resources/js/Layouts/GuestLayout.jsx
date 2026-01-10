import { Head, Link } from '@inertiajs/react';
import { useEffect, useState, useRef } from "react";
// import Footer from '@/Pages/components/Footer'; 

export default function GuestLayout({ children, auth }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);
    const mobileMenuRef = useRef(null);
    
   
    // Close mobile menu when clicking outside
    useEffect(() => {
        const handleClickOutside = (e) => {
            if (
                mobileMenuRef.current &&
                !mobileMenuRef.current.contains(e.target)
            ) {
                setIsMobileMenuOpen(false);
            }
        };

        if (isMobileMenuOpen) {
            document.addEventListener("mousedown", handleClickOutside);
        }

        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, [isMobileMenuOpen]);

    return (
        <div className="min-h-screen bg-gray-50">
           
            {/* Navigation Bar */}
            <nav 
                className="bg-white shadow-lg fixed w-full z-50"
                data-aos="fade-down"
                data-aos-duration="1400"
            >
                <NavBar auth={auth}/>
            </nav>

            {/* Main Content */}
            <main className="pt-16">
                {children}
            </main>

            {/* Footer */}
            <Footer />
        </div>
    );
}