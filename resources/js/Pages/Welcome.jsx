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
 
export default function Welcome({ auth, courses }) {
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

            {/* Hero Section with AOS effects */}
            <div className="pt-16">
                <SplitHeroSlider auth={auth} />
            </div>

            <section className="bg-white py-24 overflow-hidden">
               <WhoAreWe auth={auth} />
            </section>

            <section className="bg-gray-50 py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1000">
               <Certification courses={courses} />
            </section>

            <section className="bg-white py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <GlobalEvents />
            </section>

            <section className="bg-gray py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <BecomeMember />
            </section>

            <section className="bg-white py-0 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <MembershipOptions />
            </section>

            <section className="bg-white py-0 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <FAQSection />
            </section>

            <section className="bg-white py-0 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
                <Testimonials />
            </section>

            {/* CTA Section with AOS zoom effect */}
            <section 
                className="py-20 bg-gradient-to-r from-blue-950 to-blue-900"
                data-aos="zoom-in"
                data-aos-duration="1400"
            >
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-6">Ready to Start Your Learning Journey?</h2>
                    <p className="text-blue-100 mb-8 text-lg">
                        Join thousands of successful learners who have transformed their careers with our platform.
                    </p>
                    <Link
                        href={auth.user ? route('dashboard') : route('register')}
                        className="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition duration-300 shadow-lg transform hover:scale-105"
                        data-aos="fade-up"
                        data-aos-delay="300"
                        data-aos-duration="1400"
                    >
                        {auth.user ? 'Continue Learning' : 'Get Started for Free'}
                    </Link>
                </div>
            </section>
  
        
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