import React, { useEffect, useState } from "react";
import NavBar from "@/Layouts/NavBar";
import Footer from '@/Layouts/Footer';

export default function GuestLayout({ children, auth, forceWhiteNavbar = false }) {
    const [isScrolled, setIsScrolled] = useState(false);
    
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

    return (
        <div className="min-h-screen bg-gray-50 rounded-xl">
           
            {/* Navigation Bar - Changes from transparent to white on scroll */}
            <nav 
                className={`fixed z-50  mx-4 rounded-full inset-x-0 transition-all duration-300 shadow-lg ${
                    forceWhiteNavbar || isScrolled
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
                className="text-white"
                data-aos="fade-up"
                data-aos-duration="1400"
            > 
                <Footer/>
            </footer>

        </div>
    );
}
