
import { Link } from "@inertiajs/react";
import React, { useEffect, useState, useRef } from "react";

export default function NavBar({ auth }) {

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
                <div className="hidden md:flex items-center space-x-4">
                    <Link 
                        href="/" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        Home
                    </Link>
                    {/* About Dropdown - Fixed with hover bridge */}
                    <div className="relative group">
                        <button className="text-gray-700 hover:text-blue-900 font-medium flex items-center focus:outline-none transition duration-300 relative z-10">
                            About Us
                            <svg className="ml-1 w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        {/* Invisible hover bridge */}
                        <div className="absolute left-0 right-0 h-4 -bottom-4 group-hover:block hidden"></div>
                        
                        {/* Dropdown Menu */}
                        <div className="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 border border-gray-100 z-50 hidden group-hover:block">
                            <Link href='/welcome-to-igrcfp' className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Welcome to IGRCFP
                            </Link>
                            <Link href='our-structure' className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Our Structure
                            </Link>
                        </div>
                    </div>

                        <Link 
                        href="/membership" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        Membership
                    </Link>

                    {/* Courses Dropdown - Fixed with hover bridge */}
                    <div className="relative group">
                        <Link href="/certifications">
                            <button className="text-gray-700 hover:text-blue-900 font-medium flex items-center focus:outline-none transition duration-300 relative z-10">
                                Certifications
                                <svg className="ml-1 w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </Link>
                        
                        {/* Invisible hover bridge */}
                        <div className="absolute left-0 right-0 h-4 -bottom-4 group-hover:block hidden"></div>
                        
                        {/* Dropdown Menu */}
                        <div className="absolute left-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 border border-gray-100 z-50 hidden group-hover:block">
                            <Link href="/courses/development" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Advanced Diploma in GRC & Financial Crime Prevention
                            </Link>
                            <Link href="/courses/design" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Cybersecurity & Data Security for Financial Institutions
                            </Link>
                            <Link href="/courses/business" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Monitoring, Reporting & Risk Analytics
                            </Link>
                            <Link href="/courses/marketing" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Regulatory Compliance & Supervisory Engagement
                            </Link>
                            <Link href="/courses/data-science" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                RegTech, SupTech & Innovation in Compliance
                            </Link>
                        </div>
                    </div>
                    
                    
                    <Link 
                        href="/events" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        Events
                    </Link>
                    <Link 
                        href="/blog" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        Blog
                    </Link>
                    <Link 
                        href="/about" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        News
                    </Link>
                    <Link 
                        href="/contact" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        Contact
                    </Link>
                </div>

                {/* Authentication Buttons */}
                <div className="flex items-center space-x-4">
                    {auth && auth.user ? (
                        <div className="flex items-center space-x-4">
                            <Link 
                                href='/dashboard'
                                className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
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
                                    <Link href="/profile" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                        <div className="flex items-center">
                                            <svg className="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Profile
                                        </div>
                                    </Link>
                                    <Link href="/settings" className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
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
                                        href='/logout' 
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
                                href='/login'
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
                            className="text-gray-700 hover:text-blue-900 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
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
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Welcome to IGRCFP
                                    </Link>
                                    <Link 
                                        href="/about/structure" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
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
                            className="text-gray-700 hover:text-blue-900 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
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
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Advanced Diploma in GRC & Financial Crime Prevention
                                    </Link>
                                    <Link 
                                        href="/courses/cybersecurity" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Cybersecurity & Data Security for Financial Institutions
                                    </Link>
                                    <Link 
                                        href="/courses/monitoring" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Monitoring, Reporting & Risk Analytics
                                    </Link>
                                    <Link 
                                        href="/courses/regulatory" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Regulatory Compliance & Supervisory Engagement
                                    </Link>
                                    <Link 
                                        href="/courses/regtech" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
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
                            className="text-gray-700 hover:text-blue-900 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                            onClick={() => setIsMobileMenuOpen(false)}
                        >
                            Events
                        </Link>
                        <Link 
                            href="/blog" 
                            className="text-gray-700 hover:text-blue-900 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                            onClick={() => setIsMobileMenuOpen(false)}
                        >
                            Blog
                        </Link>
                        <Link 
                            href="/news" 
                            className="text-gray-700 hover:text-blue-900 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                            onClick={() => setIsMobileMenuOpen(false)}
                        >
                            News
                        </Link>
                        <Link 
                            href="/contact" 
                            className="text-gray-700 hover:text-blue-900 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                            onClick={() => setIsMobileMenuOpen(false)}
                        >
                            Contact
                        </Link>
                        
                        {!auth.user && (
                            <div className="pt-4 border-t border-gray-100 mt-2">
                                <Link
                                    href='/login'
                                    className="block text-gray-700 hover:text-blue-900 font-medium py-3 px-4 hover:bg-gray-50 rounded-lg transition duration-200"
                                    onClick={() => setIsMobileMenuOpen(false)}
                                >
                                    Sign In
                                </Link>
                                <Link
                                    href='/register'
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
    );
}
