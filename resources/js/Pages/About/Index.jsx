import { Head, Link } from "@inertiajs/react";
import { useEffect, useState, useRef } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import MembershipOptions from "@/Pages/components/MembershipOptions";
import SplitHeroSlider from "@/Pages/components/SplitHeroSlider";
import Certification from "@/Pages/components/Certification";
import GlobalEvents from "@/Pages/components/GlobalEvents";
import BecomeMember from "@/Pages/components/BecomeMember";
import Testimonials from "@/Pages/components/Testimonials";
import FAQSection from "@/Pages/components/FAQSection";
import WhoAreWe from "@/Pages/components/WhoAreWe";
import Footer from "@/Pages/components/Footer";
import NavBar from "@/Pages/components/NavBar";
 
export default function AboutIndex({ auth, courses }) {
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
        <>
            <Head title="IGRCFP - Professional Learning Platform" />
            
            {/* Navigation Bar */}
            <nav 
                className="bg-white shadow-lg fixed w-full z-50"
                data-aos="fade-down"
                data-aos-duration="1400"
            >
                <NavBar auth={auth}/>
            </nav> 

            
        
            {/* Footer */}
            <footer 
                className="bg-gray-900 text-white py-12"
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
        </>
    );
}