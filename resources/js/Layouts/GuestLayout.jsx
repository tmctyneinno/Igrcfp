import { Head, Link } from '@inertiajs/react';
import React, { useEffect, useState, useRef } from "react";
import NavBar from "@/Layouts/NavBar";
import Footer from '@/Layouts/Footer'; 
import AOS from "aos";
import "aos/dist/aos.css";

export default function GuestLayout({ children, auth }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);
    const [isScrolled, setIsScrolled] = useState(false);
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
    
    // Handle scroll event to change navbar background
    useEffect(() => {
        const handleScroll = () => {
            if (window.scrollY > 50) {
                setIsScrolled(true);
            } else {
                setIsScrolled(false);
            }
        };

        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
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
        <div className="min-h-screen bg-gray-50 rounded-xl">
           
            {/* Navigation Bar - Changes from transparent to white on scroll */}
            <nav 
                className={`fixed z-50  mx-4 rounded-full inset-x-0 transition-all duration-300 shadow-lg ${
                    isScrolled 
                        ? 'bg-white shadow-md  mt-0' 
                        : 'bg-black/20  mt-3'
                }`}
                data-aos="fade-down"
                data-aos-duration="1400"
            >
                <NavBar auth={auth}/>
            </nav> 

            {/* Main Content */}
            <main className="pt-0">
                {children}
            </main>

            {/* Footer */}
            <footer 
                className="bg-blue-950 text-white py-12"
                data-aos="fade-up"
                data-aos-duration="1400"
            > 
                <Footer/>
            </footer>

            {/* Mobile Menu Toggle Script */}
            <script>{`
                document.addEventListener('DOMContentLoaded', function() {
                    const mobileMenuButton = document.querySelector('button.md\\:hidden');
                    const mobileMenu = document.querySelector('.md\\:hidden.bg-white');
                    
                    if (mobileMenuButton && mobileMenu) {
                        mobileMenuButton.addEventListener('click', function() {
                            mobileMenu.classList.toggle('hidden');
                        });
                    }
                });
            `}</script>

            {/* Refresh AOS on page load */}
            <script>{`
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                });
            `}</script>

        </div>
    );
}