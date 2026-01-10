import { Head, Link } from '@inertiajs/react';
import { useEffect, useState, useRef } from "react";
import NavBar from "@/Pages/components/NavBar";
import Footer from '@/Pages/components/Footer'; 
import AOS from "aos";
import "aos/dist/aos.css";

export default function GuestLayout({ children, auth }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);
    const mobileMenuRef = useRef(null);
    // ✅ AOS INIT — ONCE ONLY
    useEffect(() => {
        AOS.init({
            duration: 500,
            easing: "ease-out-cubic",
            once: true,
            offset: 80,
        });
    }, []);
   
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
            {/* Footer */}
            <footer 
                className="bg-blue-950 text-white py-12"
                data-aos="fade-up"
                data-aos-duration="1400"
            > 
                <Footer/>
            </footer>

        </div>
    );
}