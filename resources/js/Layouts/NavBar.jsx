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
                    
                    {/* About Dropdown */}
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
                            <Link href='/our-structure' className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Our Structure
                            </Link>
                            <Link href='/why-igrcfp' className="block px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-200">
                                Why IGRCFP
                            </Link>
                        </div>
                    </div>

                    <Link 
                        href="/membership" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        Membership
                    </Link>

                    {/* Programmes & Courses Dropdown - UPDATED WITH DETAILED CONTENT */}
                    <div className="relative group">
                        <button className="text-gray-700 hover:text-blue-900 font-medium flex items-center focus:outline-none transition duration-300 relative z-10">
                           <Link href={route('courses.index')}>  Programmes & Courses </Link>
                            <svg className="ml-1 w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        {/* Invisible hover bridge */}
                        <div className="absolute left-0 right-0 h-4 -bottom-4 group-hover:block hidden"></div>
                        
                        {/* Dropdown Menu - Expanded with programme details */}
                        <div className="absolute left-0 mt-2 w-96 bg-white rounded-lg shadow-xl py-3 border border-gray-100 z-50 hidden group-hover:block">
                            
                            {/* Programme List */}
                            <div className="max-h-[480px] overflow-y-auto">
                                {/* GRC Pathway */}
                                <div className="px-4 py-3 hover:bg-blue-50 transition duration-200">
                                    <Link href={route('courses.by-category', { slug: 'governance-risk-compliance' })}>
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                                            <h4 className="font-semibold text-gray-800">Governance, Risk & Compliance (GRC)</h4>
                                        </div>
                                    </Link>
                                </div>
                                
                                {/* Financial Crime Prevention */}
                                <div className="px-4 py-3 hover:bg-blue-50 transition duration-200">
                                    <Link href={route('courses.by-category', { slug: 'financial-crime' })}>
                                    <div className="flex items-center">
                                        <div className="w-2 h-2 bg-red-600 rounded-full mr-3"></div>
                                        <h4 className="font-semibold text-gray-800">Financial Crime Prevention</h4>
                                    </div>
                                    </Link>
                                </div>
                                
                                {/* Crypto & Digital Assets */}
                                <div className="px-4 py-3 hover:bg-blue-50 transition duration-200">
                                    <Link href={route('courses.by-category', { slug: 'crypto-digital-assets' })}>
                                    <div className="flex items-center">
                                        <div className="w-2 h-2 bg-purple-600 rounded-full mr-3"></div>
                                        <h4 className="font-semibold text-gray-800">Crypto & Digital Assets</h4>
                                    </div>
                                    </Link>
                                </div>
                                
                                {/* Cybersecurity & Digital Risk */}
                                <div className="px-4 py-3 hover:bg-blue-50 transition duration-200">
                                    <Link href={route('courses.by-category', { slug: 'cybersecurity-digital-risk' })}>
                                    <div className="flex items-center">
                                        <div className="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                                        <h4 className="font-semibold text-gray-800">Cybersecurity & Digital Risk</h4>
                                    </div>
                                    </Link>
                                </div>
                                
                                {/* AI & Emerging Technology */}
                                <div className="px-4 py-3 hover:bg-blue-50 transition duration-200">
                                    <Link href={route('courses.by-category', { slug: 'ai-emerging-technology' })}>
                                    <div className="flex items-center">
                                        <div className="w-2 h-2 bg-yellow-600 rounded-full mr-3"></div>
                                        <h4 className="font-semibold text-gray-800">AI & Emerging Technology</h4>
                                    </div>
                                    </Link>
                                </div>
                            </div>
                            
                            {/* Footer */}
                            <div className="px-4 py-3 border-t border-gray-100 bg-gray-50">
                                <Link 
                                    href="courses" 
                                    className="text-sm font-medium text-blue-900 hover:text-blue-700 flex items-center justify-between"
                                >
                                    View All Programmes
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div className="relative group">
                        <button className="text-gray-700 hover:text-blue-900 font-medium flex items-center focus:outline-none transition duration-300 relative z-10">
                           <Link href={route('certifications')}>  Certifications</Link>
                            <svg className="ml-1 w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        {/* Invisible hover bridge */}
                        <div className="absolute left-0 right-0 h-4 -bottom-4 group-hover:block hidden"></div>
                        
                        {/* Dropdown Menu - Expanded with programme details */}
                        <div className="absolute left-0 mt-2 w-96 bg-white rounded-lg shadow-xl py-3 border border-gray-100 z-50 hidden group-hover:block">
                            
                            {/* Programme List */}
                            <div className="max-h-[480px] overflow-y-auto">
                                {/* GRC Pathway */}
                                <div className="px-4 py-3 hover:bg-blue-50 transition duration-200">
                                    <Link href={route('courses.by-category', { slug: 'governance-risk-compliance' })}>
                                        <div className="flex items-center">
                                            <h4 className="font-semibold text-gray-800">Governance, Risk & Compliance (GRC)</h4>
                                        </div>
                                    </Link>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                    {/* <Link 
                        href={route('certifications')} 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >Certifications
                    </Link> */}
                    
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
                        href="/news" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        News
                    </Link>
                    <Link 
                        href="/contact" 
                        className="text-gray-700 hover:text-blue-900 font-medium transition duration-300"
                    >
                        Connect
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
                                className="bg-blue-900 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-300 shadow-md hover:shadow-lg"
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

            {/* Mobile Menu - UPDATED with Programmes Section */}
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
                                        href="/welcome-to-igrcfp" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Welcome to IGRCFP
                                    </Link>
                                    <Link 
                                        href="/our-structure" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Our Structure
                                    </Link>
                                    <Link 
                                        href="/why-igrcfp" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Why IGRCFP
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
                        
                        {/* Programmes & Courses Dropdown for Mobile */}
                        <div className="py-1 px-4">
                            <button 
                                className="font-medium text-gray-700 py-2 w-full text-left flex justify-between items-center"
                                onClick={() => setOpenDropdown(openDropdown === 'programmes' ? null : 'programmes')}
                            >
                                Programmes & Courses
                                <svg className={`w-4 h-4 transition-transform duration-300 ${openDropdown === 'programmes' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {openDropdown === 'programmes' && (
                                <div className="pl-4 space-y-1 border-l-2 border-gray-200 mt-2">
                                    <div className="text-xs uppercase text-blue-900 font-semibold tracking-wide py-2">
                                        Core Programme Pathways
                                    </div>
                                    
                                    <Link 
                                        href="/programmes/grc" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                                            GRC & Risk Management
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/financial-crime" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-red-600 rounded-full mr-3"></div>
                                            Financial Crime Prevention
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/crypto" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-purple-600 rounded-full mr-3"></div>
                                            Crypto & Digital Assets
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/cybersecurity" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                                            Cybersecurity & Digital Risk
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/ai" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-yellow-600 rounded-full mr-3"></div>
                                            AI & Emerging Technology
                                        </div>
                                    </Link>
                                    
                                    <div className="border-t border-gray-200 my-2 pt-2">
                                        <Link 
                                            href="/programmes/all-courses" 
                                            className="block text-blue-900 font-medium py-2 px-3 hover:bg-blue-50 rounded transition duration-200"
                                            onClick={() => {
                                                setIsMobileMenuOpen(false);
                                                setOpenDropdown(null);
                                            }}
                                        >
                                            View All Programmes
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </div>
                        
                        {/* Certifications Dropdown for Mobile */}
                        <div className="py-1 px-4">
                            <button 
                                className="font-medium text-gray-700 py-2 w-full text-left flex justify-between items-center"
                                onClick={() => setOpenDropdown(openDropdown === 'certifications' ? null : 'certifications')}
                            >
                                Certifications
                                <svg className={`w-4 h-4 transition-transform duration-300 ${openDropdown === 'certifications' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {openDropdown === 'certifications' && (
                                <div className="pl-4 space-y-1 border-l-2 border-gray-200 mt-2">
                                    <Link 
                                        href="/certifications/cgrc" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Certified GRC Professional
                                    </Link>
                                    <Link 
                                        href="/certifications/cfc" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Certified Financial Crime Specialist
                                    </Link>
                                    <Link 
                                        href="/certifications/cyber" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        Certified Cyber Risk Leader
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
                        
                        {!auth?.user && (
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