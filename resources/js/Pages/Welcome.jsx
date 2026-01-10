import { Head, Link } from "@inertiajs/react";
import { useEffect, useState, useRef } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import SplitHeroSlider from "@/Pages/components/SplitHeroSlider";
import Certification from "@/Pages/components/Certification";
import WhoAreWe from "@/Pages/components/WhoAreWe";

export default function Welcome({ auth }) {
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
            <Head title="Welcome - Professional Learning Platform" />
            
            {/* Navigation Bar */}
            <nav 
                className="bg-white shadow-lg fixed w-full z-50"
                data-aos="fade-down"
                data-aos-duration="1400"
            >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        {/* Logo */}
                        <div className="flex items-center">
                            <Link href="/" className="flex items-center">
                                <img 
                                    src="/assets/images/home-three/logo/logo-main.png" 
                                    alt="Logo" 
                                    className="h-10 w-auto"
                                />
                                <span className="ml-3 text-xl font-bold text-gray-900">IGRCFP</span>
                            </Link>
                        </div>

                        {/* Desktop Navigation Links */}
                        <div className="hidden md:flex items-center space-x-8">
                            <Link 
                                href="/" 
                                className="text-gray-700 hover:text-blue-600 font-medium transition duration-300"
                            >
                                Home
                            </Link>
                            {/* About Dropdown - Fixed with hover bridge */}
                            <div className="relative group">
                                <button className="text-gray-700 hover:text-blue-600 font-medium flex items-center focus:outline-none transition duration-300 relative z-10">
                                    About Us
                                    <svg className="ml-1 w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                {/* Invisible hover bridge */}
                                <div className="absolute left-0 right-0 h-4 -bottom-4 group-hover:block hidden"></div>
                                
                                {/* Dropdown Menu */}
                                <div className="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 border border-gray-100 z-50 hidden group-hover:block">
                                    <Link href="/courses/development" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        Welcome to IGRCFP
                                    </Link>
                                    <Link href="/courses/design" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        Our Structure
                                    </Link>
                                </div>
                            </div>

                             <Link 
                                href="/membership" 
                                className="text-gray-700 hover:text-blue-600 font-medium transition duration-300"
                            >
                                Membership
                            </Link>

                            {/* Courses Dropdown - Fixed with hover bridge */}
                            <div className="relative group">
                                <button className="text-gray-700 hover:text-blue-600 font-medium flex items-center focus:outline-none transition duration-300 relative z-10">
                                    Courses
                                    <svg className="ml-1 w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                {/* Invisible hover bridge */}
                                <div className="absolute left-0 right-0 h-4 -bottom-4 group-hover:block hidden"></div>
                                
                                {/* Dropdown Menu */}
                                <div className="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 border border-gray-100 z-50 hidden group-hover:block">
                                    <Link href="/courses/development" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        Advanced Diploma in GRC & Financial Crime Prevention
                                    </Link>
                                    <Link href="/courses/design" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        Cybersecurity & Data Security for Financial Institutions
                                    </Link>
                                    <Link href="/courses/business" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        Monitoring, Reporting & Risk Analytics
                                    </Link>
                                    <Link href="/courses/marketing" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        Regulatory Compliance & Supervisory Engagement
                                    </Link>
                                    <Link href="/courses/data-science" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                        RegTech, SupTech & Innovation in Compliance
                                    </Link>
                                </div>
                            </div>
                            
                            
                            <Link 
                                href="/about" 
                                className="text-gray-700 hover:text-blue-600 font-medium transition duration-300"
                            >
                                Events
                            </Link>
                            <Link 
                                href="/about" 
                                className="text-gray-700 hover:text-blue-600 font-medium transition duration-300"
                            >
                                Blog
                            </Link>
                            <Link 
                                href="/about" 
                                className="text-gray-700 hover:text-blue-600 font-medium transition duration-300"
                            >
                                News
                            </Link>
                            <Link 
                                href="/contact" 
                                className="text-gray-700 hover:text-blue-600 font-medium transition duration-300"
                            >
                                Contact
                            </Link>
                        </div>

                        {/* Authentication Buttons */}
                        <div className="flex items-center space-x-4">
                            {auth.user ? (
                                <div className="flex items-center space-x-4">
                                    <Link
                                        href={route('dashboard')}
                                        className="text-gray-700 hover:text-blue-600 font-medium transition duration-300"
                                    >
                                        Dashboard
                                    </Link>
                                    <div className="relative group">
                                        <button className="flex items-center space-x-2 focus:outline-none">
                                            <div className="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                {auth.user.name.charAt(0).toUpperCase()}
                                            </div>
                                            <span className="text-gray-700 font-medium">{auth.user.name}</span>
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        {/* User Dropdown Menu */}
                                        <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 border border-gray-100">
                                            <Link href="/profile" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                                <div className="flex items-center">
                                                    <svg className="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    Profile
                                                </div>
                                            </Link>
                                            <Link href="/settings" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200">
                                                <div className="flex items-center">
                                                    <svg className="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Settings
                                                </div>
                                            </Link>
                                            <div className="border-t border-gray-100 my-1"></div>
                                            <Link 
                                                href={route('logout')} 
                                                method="post"
                                                className="block px-4 py-3 text-red-600 hover:bg-red-50 transition duration-200"
                                            >
                                                <div className="flex items-center">
                                                    <svg className="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                    </svg>
                                                    Logout
                                                </div>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <>
                                   
                                    <Link
                                        href={route('login')}
                                        className="bg-blue-950 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-300 shadow-md hover:shadow-lg"
                                    >
                                        Join the Institute
                                    </Link>
                                </>
                            )}
                            
                            {/* Mobile menu button */}
                            <button 
                                className="md:hidden text-gray-700 focus:outline-none"
                                onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                            >
                                {isMobileMenuOpen ? (
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                ) : (
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                )}
                            </button>
                        </div>
                    </div>

                   {/* Mobile Menu - UPDATED */}
                    {isMobileMenuOpen && (
                        <div 
                            className="md:hidden bg-white py-4 shadow-lg rounded-lg mt-2 mobile-menu-container"
                            data-aos="fade-down"
                            data-aos-duration="300"
                        >
                            <div className="flex flex-col space-y-2">
                                <Link 
                                    href="/" 
                                    className="text-gray-700 hover:text-blue-600 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                    onClick={() => setIsMobileMenuOpen(false)}
                                >
                                    Home
                                </Link>
                                
                                {/* About Dropdown for Mobile */}
                                <div className="py-1 px-4">
                                    <button 
                                        className="font-medium text-gray-700 py-2 w-full text-left flex justify-between items-center"
                                        onClick={() => setOpenDropdown(openDropdown === 'about' ? null : 'about')}
                                    >
                                        About Us
                                        <svg className={`w-4 h-4 transition-transform duration-300 ${openDropdown === 'about' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    {openDropdown === 'about' && (
                                        <div className="pl-4 space-y-1 border-l-2 border-gray-200 mt-2">
                                            <Link 
                                                href="/about/welcome" 
                                                className="block text-gray-600 hover:text-blue-600 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                                onClick={() => {
                                                    setIsMobileMenuOpen(false);
                                                    setOpenDropdown(null);
                                                }}
                                            >
                                                Welcome to IGRCFP
                                            </Link>
                                            <Link 
                                                href="/about/structure" 
                                                className="block text-gray-600 hover:text-blue-600 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                                onClick={() => {
                                                    setIsMobileMenuOpen(false);
                                                    setOpenDropdown(null);
                                                }}
                                            >
                                                Our Structure
                                            </Link>
                                        </div>
                                    )}
                                </div>
                                
                                <Link 
                                    href="/membership" 
                                    className="text-gray-700 hover:text-blue-600 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                    onClick={() => setIsMobileMenuOpen(false)}
                                >
                                    Membership
                                </Link>
                                
                                {/* Courses Dropdown for Mobile */}
                                <div className="py-1 px-4">
                                    <button 
                                        className="font-medium text-gray-700 py-2 w-full text-left flex justify-between items-center"
                                        onClick={() => setOpenDropdown(openDropdown === 'courses' ? null : 'courses')}
                                    >
                                        Courses
                                        <svg className={`w-4 h-4 transition-transform duration-300 ${openDropdown === 'courses' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    {openDropdown === 'courses' && (
                                        <div className="pl-4 space-y-1 border-l-2 border-gray-200 mt-2">
                                            <Link 
                                                href="/courses/grc" 
                                                className="block text-gray-600 hover:text-blue-600 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                                onClick={() => {
                                                    setIsMobileMenuOpen(false);
                                                    setOpenDropdown(null);
                                                }}
                                            >
                                                Advanced Diploma in GRC & Financial Crime Prevention
                                            </Link>
                                            <Link 
                                                href="/courses/cybersecurity" 
                                                className="block text-gray-600 hover:text-blue-600 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                                onClick={() => {
                                                    setIsMobileMenuOpen(false);
                                                    setOpenDropdown(null);
                                                }}
                                            >
                                                Cybersecurity & Data Security for Financial Institutions
                                            </Link>
                                            <Link 
                                                href="/courses/monitoring" 
                                                className="block text-gray-600 hover:text-blue-600 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                                onClick={() => {
                                                    setIsMobileMenuOpen(false);
                                                    setOpenDropdown(null);
                                                }}
                                            >
                                                Monitoring, Reporting & Risk Analytics
                                            </Link>
                                            <Link 
                                                href="/courses/regulatory" 
                                                className="block text-gray-600 hover:text-blue-600 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                                onClick={() => {
                                                    setIsMobileMenuOpen(false);
                                                    setOpenDropdown(null);
                                                }}
                                            >
                                                Regulatory Compliance & Supervisory Engagement
                                            </Link>
                                            <Link 
                                                href="/courses/regtech" 
                                                className="block text-gray-600 hover:text-blue-600 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                                onClick={() => {
                                                    setIsMobileMenuOpen(false);
                                                    setOpenDropdown(null);
                                                }}
                                            >
                                                RegTech, SupTech & Innovation in Compliance
                                            </Link>
                                        </div>
                                    )}
                                </div>
                                
                                <Link 
                                    href="/events" 
                                    className="text-gray-700 hover:text-blue-600 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                    onClick={() => setIsMobileMenuOpen(false)}
                                >
                                    Events
                                </Link>
                                <Link 
                                    href="/blog" 
                                    className="text-gray-700 hover:text-blue-600 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                    onClick={() => setIsMobileMenuOpen(false)}
                                >
                                    Blog
                                </Link>
                                <Link 
                                    href="/news" 
                                    className="text-gray-700 hover:text-blue-600 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                    onClick={() => setIsMobileMenuOpen(false)}
                                >
                                    News
                                </Link>
                                <Link 
                                    href="/contact" 
                                    className="text-gray-700 hover:text-blue-600 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                    onClick={() => setIsMobileMenuOpen(false)}
                                >
                                    Contact
                                </Link>
                                
                                {!auth.user && (
                                    <div className="pt-4 border-t border-gray-100 mt-2">
                                        <Link
                                            href={route('login')}
                                            className="block text-gray-700 hover:text-blue-600 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                            onClick={() => setIsMobileMenuOpen(false)}
                                        >
                                            Sign In
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className="block bg-blue-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-blue-700 mt-2 text-center transition duration-300"
                                            onClick={() => setIsMobileMenuOpen(false)}
                                        >
                                            Get Started
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    )}

                </div>
            </nav>

            {/* Hero Section with AOS effects */}
            <div className="pt-16">
                <SplitHeroSlider auth={auth} />
            </div>

            <section className="bg-white py-24 overflow-hidden">
               <WhoAreWe auth={auth} />
            </section>

            <section className="bg-white py-24 overflow-hidden">
               <Certification auth={auth} />
            </section>
                
                

                {/* Features Section with staggered AOS effects */}
                <section className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 
                                className="text-3xl font-bold text-gray-900 mb-4"
                                data-aos="fade-up"
                                data-aos-duration="1400"
                            >
                                Why Choose Our Platform?
                            </h2>
                            <p 
                                className="text-gray-600 max-w-2xl mx-auto"
                                data-aos="fade-up"
                                data-aos-delay="200"
                                data-aos-duration="1400"
                            >
                                We provide the best learning experience with industry-leading features
                            </p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {[
                                {
                                    icon: (
                                        <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    ),
                                    title: "Expert Tutors",
                                    description: "Learn from industry professionals with years of experience"
                                },
                                {
                                    icon: (
                                        <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    ),
                                    title: "Certified Courses",
                                    description: "Get recognized certifications to boost your career"
                                },
                                {
                                    icon: (
                                        <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    ),
                                    title: "Community Learning",
                                    description: "Join a community of learners and grow together"
                                }
                            ].map((feature, index) => (
                                <div 
                                    key={index}
                                    className="text-center p-6"
                                    data-aos="fade-up"
                                    data-aos-delay={index * 200}
                                    data-aos-duration="1400"
                                >
                                    <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        {feature.icon}
                                    </div>
                                    <h3 className="text-xl font-semibold mb-2">{feature.title}</h3>
                                    <p className="text-gray-600">
                                        {feature.description}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Popular Courses Section */}
                <section className="py-20 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div 
                            className="flex justify-between items-center mb-12"
                            data-aos="fade-up"
                            data-aos-duration="1400"
                        >
                            <div>
                                <h2 className="text-3xl font-bold text-gray-900">Popular Courses</h2>
                                <p className="text-gray-600 mt-2">Start learning with our most popular courses</p>
                            </div>
                            <Link href="/courses" className="text-blue-600 hover:text-blue-700 font-semibold">
                                View All Courses →
                            </Link>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {[1, 2, 3].map((course, index) => (
                                <div 
                                    key={course}
                                    className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 transform hover:-translate-y-2"
                                    data-aos="fade-up"
                                    data-aos-delay={index * 200}
                                    data-aos-duration="1400"
                                >
                                    <div className="h-48 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                                    <div className="p-6">
                                        <div className="flex items-center justify-between mb-4">
                                            <span className="bg-blue-100 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full">
                                                {course === 1 ? 'Beginner' : course === 2 ? 'Intermediate' : 'Advanced'}
                                            </span>
                                            <span className="text-gray-500">4.8 ★</span>
                                        </div>
                                        <h3 className="text-xl font-semibold mb-2">Course Title {course}</h3>
                                        <p className="text-gray-600 mb-4">
                                            Learn essential skills from industry experts in this comprehensive course.
                                        </p>
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center">
                                                <div className="w-8 h-8 bg-gray-300 rounded-full"></div>
                                                <span className="ml-2 text-sm text-gray-600">John Doe</span>
                                            </div>
                                            <span className="text-blue-600 font-semibold">$99</span>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA Section with AOS zoom effect */}
                <section 
                    className="py-20 bg-gradient-to-r from-blue-600 to-blue-800"
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
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1400">
                                <div className="flex items-center mb-4">
                                    <img 
                                        src="/assets/images/home-three/logo/logo-main.png" 
                                        alt="Logo" 
                                        className="h-8 w-auto"
                                    />
                                    <span className="ml-2 text-xl font-bold">LearnHub</span>
                                </div>
                                <p className="text-gray-400">
                                    Transforming education through personalized learning experiences.
                                </p>
                            </div>
                            <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1400">
                                <h4 className="font-semibold mb-4">Quick Links</h4>
                                <ul className="space-y-2">
                                    <li><Link href="/" className="text-gray-400 hover:text-white">Home</Link></li>
                                    <li><Link href="/courses" className="text-gray-400 hover:text-white">Courses</Link></li>
                                    <li><Link href="/tutors" className="text-gray-400 hover:text-white">Tutors</Link></li>
                                    <li><Link href="/about" className="text-gray-400 hover:text-white">About Us</Link></li>
                                </ul>
                            </div>
                            <div data-aos="fade-up" data-aos-delay="300" data-aos-duration="1400">
                                <h4 className="font-semibold mb-4">Legal</h4>
                                <ul className="space-y-2">
                                    <li><Link href="/terms" className="text-gray-400 hover:text-white">Terms of Service</Link></li>
                                    <li><Link href="/privacy" className="text-gray-400 hover:text-white">Privacy Policy</Link></li>
                                    <li><Link href="/cookies" className="text-gray-400 hover:text-white">Cookie Policy</Link></li>
                                </ul>
                            </div>
                            <div data-aos="fade-up" data-aos-delay="400" data-aos-duration="1400">
                                <h4 className="font-semibold mb-4">Contact Us</h4>
                                <ul className="space-y-2 text-gray-400">
                                    <li>Email: support@learnhub.com</li>
                                    <li>Phone: +1 (555) 123-4567</li>
                                    <li>Address: 123 Learning St, Education City</li>
                                </ul>
                            </div>
                        </div>
                        <div 
                            className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400"
                            data-aos="fade-up"
                            data-aos-delay="500"
                            data-aos-duration="1400"
                        >
                            <p>&copy; {new Date().getFullYear()} LearnHub. All rights reserved.</p>
                        </div>
                    </div>
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