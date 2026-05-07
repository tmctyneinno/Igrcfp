import { Link } from "@inertiajs/react";
import React, { useEffect, useState, useRef } from "react";

export default function NavBar({ auth }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);
    const [openMegaMenu, setOpenMegaMenu] = useState(false);
    const mobileMenuRef = useRef(null);
    const megaMenuRef = useRef(null);
    
    // Close mobile menu when clicking outside
    useEffect(() => {
        const handleClickOutside = (e) => {
            if (mobileMenuRef.current && !mobileMenuRef.current.contains(e.target)) {
                setIsMobileMenuOpen(false);
            }
            if (megaMenuRef.current && !megaMenuRef.current.contains(e.target)) {
                setOpenMegaMenu(false);
            }
        };

        if (isMobileMenuOpen || openMegaMenu) {
            document.addEventListener("mousedown", handleClickOutside);
        }

        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, [isMobileMenuOpen, openMegaMenu]);

    // Close mega menu on Escape key
    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === 'Escape') {
                setOpenMegaMenu(false);
                setIsMobileMenuOpen(false);
            }
        };
        document.addEventListener('keydown', handleEsc);
        return () => document.removeEventListener('keydown', handleEsc);
    }, []);

    return (
        <nav className="bg-white shadow-sm sticky top-0 z-50">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-16">
                    
                    {/* Logo */}
                    <div className="flex-shrink-0 flex items-center">
                        <Link href="/" className="flex items-center">
                            <img 
                                src="/assets/images/home-three/logo/logo-main.png" 
                                alt="IGRCFP Logo" 
                                className="h-10 w-auto"
                            />
                            <span className="ml-3 text-xl font-bold text-gray-900 hidden sm:block">
                                IGRCFP
                            </span>
                        </Link>
                    </div>

                    {/* Desktop Navigation */}
                    <div className="hidden lg:flex items-center space-x-1">
                        
                        {/* Home */}
                        <Link 
                            href="/" 
                            className="px-3 py-2 text-md font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200"
                        >
                            Home
                        </Link>
                        
                        {/* About Us Dropdown */}
                        <div className="relative group">
                            <button className="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 flex items-center">
                                About Us
                                <svg className="ml-1 w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div className="absolute left-0 mt-1 w-56 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <Link href='/welcome-to-igrcfp' className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-150">
                                    Welcome to IGRCFP
                                </Link>
                                <Link href='/our-structure' className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-150">
                                    Our Structure
                                </Link>
                                <Link href='/why-igrcfp' className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-150">
                                    Why IGRCFP
                                </Link>
                            </div>
                        </div>

                        {/* MEGA MENU: Programmes & Qualifications */}
                        {/* MEGA MENU: Programmes & Qualifications */}
                        <div 
                            className="relative"
                            ref={megaMenuRef}
                            onMouseLeave={() => setOpenMegaMenu(false)}
                        >
                            <button 
                                className={`px-3 py-2 text-sm font-medium rounded-lg transition duration-200 flex items-center whitespace-nowrap ${
                                    openMegaMenu 
                                        ? 'text-blue-900 bg-blue-50' 
                                        : 'text-gray-700 hover:text-blue-900 hover:bg-blue-50'
                                }`}
                                onClick={() => setOpenMegaMenu(!openMegaMenu)}
                                onMouseEnter={() => setOpenMegaMenu(true)}
                                aria-expanded={openMegaMenu}
                            >
                                Programmes & Qualifications
                                <svg className={`ml-1 w-4 h-4 transition-transform duration-200 ${openMegaMenu ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            {/* Invisible hover bridge */}
                            <div className="absolute left-0 right-0 h-4 -bottom-4"></div>
                            
                            {/* MEGA MENU PANEL - CENTERED */}
                            {openMegaMenu && (
                                <>
                                    {/* Transparent backdrop for click-outside */}
                                    <div 
                                        className="fixed inset-0 z-40 cursor-default"
                                        onClick={() => setOpenMegaMenu(false)}
                                    ></div>
                                    
                                    <div 
                                        className="fixed left-1/2 -translate-x-1/2 mt-2 w-[95vw] max-w-[1200px] bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
                                        style={{ 
                                            top: '64px', // Adjust based on your navbar height
                                            maxHeight: 'calc(100vh - 90px)',
                                        }}
                                    >
                                        <div className="p-6 lg:p-8">
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                                                
                                                {/* COLUMN 1: Qualification Levels */}
                                                <div>
                                                    <h3 className="text-xs font-bold text-blue-900 uppercase tracking-wider mb-4 flex items-center">
                                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Qualification Levels
                                                    </h3>
                                                    <div className="space-y-1">
                                                        <Link href={route('igrcfp.certificates.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2 rounded-lg hover:bg-blue-50 transition duration-150 group">
                                                            <span className="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition flex-shrink-0">
                                                                <span className="text-blue-900 font-bold text-xs">L1</span>
                                                            </span>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Certificate</p>
                                                            </div>
                                                        </Link>
                                                        
                                                        <Link href={route('igrcfp.diploma.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2 rounded-lg hover:bg-green-50 transition duration-150 group">
                                                            <span className="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition flex-shrink-0">
                                                                <span className="text-green-900 font-bold text-xs">L2</span>
                                                            </span>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Diploma</p>
                                                            </div>
                                                        </Link>
                                                        
                                                        <Link href={route('igrcfp.advanced-diploma.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50 transition duration-150 group">
                                                            <span className="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-200 transition flex-shrink-0">
                                                                <span className="text-indigo-900 font-bold text-xs">L3</span>
                                                            </span>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Advanced Diploma</p>
                                                            </div>
                                                        </Link>
                                                        
                                                        <Link href={route('igrcfp.certified-grc-financial-crime-specialist.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2 rounded-lg hover:bg-amber-50 transition duration-150 group">
                                                            <span className="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-amber-200 transition flex-shrink-0">
                                                                <span className="text-amber-900 font-bold text-xs">L4</span>
                                                            </span>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Certified GRC & Financial Crime Specialist</p>
                                                            </div>
                                                        </Link>
                                                        
                                                        <Link href={route('igrcfp.postgraduate-diploma.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2 rounded-lg hover:bg-rose-50 transition duration-150 group">
                                                            <span className="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-rose-200 transition flex-shrink-0">
                                                                <span className="text-rose-900 font-bold text-xs">L5</span>
                                                            </span>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Postgraduate Diploma</p>
                                                            </div>
                                                        </Link>
                            
                                                        <Link href={route('igrcfp.fellowship.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2 rounded-lg hover:bg-violet-50 transition duration-150 group">
                                                            <span className="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-violet-200 transition flex-shrink-0">
                                                                <span className="text-violet-900 font-bold text-xs">L6</span>
                                                            </span>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Fellowship</p>
                                                            </div>
                                                        </Link>
                                                    </div>
                                                </div>

                                                {/* COLUMN 2: Certifications & Courses */}
                                                <div>
                                                    <h3 className="text-xs font-bold text-blue-900 uppercase tracking-wider mb-4 flex items-center">
                                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                        </svg>
                                                        Professional Certifications
                                                    </h3>
                                                    <div className="space-y-1">
                                                        <Link href={route('certifications.overview')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-50 transition duration-150">
                                                            <div className="w-2 h-2 bg-blue-600 rounded-full mr-3 flex-shrink-0"></div>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Certifications Overview</p>
                                                                <p className="text-xs text-gray-500">Complete pathway guide</p>
                                                            </div>
                                                        </Link>
                                                        
                                                        <Link href={route('certifications.pathway')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2.5 rounded-lg hover:bg-green-50 transition duration-150">
                                                            <div className="w-2 h-2 bg-green-600 rounded-full mr-3 flex-shrink-0"></div>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">Certification Pathway</p>
                                                                <p className="text-xs text-gray-500">Your career roadmap</p>
                                                            </div>
                                                        </Link>
                                                        
                                                        <Link href={route('cgfcs.specialist')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2.5 rounded-lg hover:bg-purple-50 transition duration-150">
                                                            <div className="w-2 h-2 bg-purple-600 rounded-full mr-3 flex-shrink-0"></div>
                                                            <div>
                                                                <p className="text-sm font-medium text-gray-800">CGFCS Specialist</p>
                                                                <p className="text-xs text-gray-500">GRC & Financial Crime</p>
                                                            </div>
                                                        </Link>

                                                    </div>

                                                    <div className="mt-4 pt-4 border-t border-gray-100">
                                                        <Link href={route('course.catalog.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-50 transition duration-150">
                                                            <svg className="w-4 h-4 text-gray-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                                            </svg>
                                                            <p className="text-sm font-medium text-gray-800">Course Catalogue</p>
                                                        </Link>
                                                    </div>
                                                </div>

                                                {/* COLUMN 3: University & Partnerships */}
                                                <div>
                                                    <h3 className="text-xs font-bold text-blue-900 uppercase tracking-wider mb-4 flex items-center">
                                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                        Academic Partnerships
                                                    </h3>
                                                    <div className="space-y-1">
                                                        <Link href={route('qualifications.pack')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="block px-3 py-2.5 rounded-lg hover:bg-blue-50 transition duration-150">
                                                            <p className="text-sm font-medium text-gray-800">Qualification Framework</p>
                                                        </Link>
                                                        
                                                        
                                                        <Link href={route('course-equivalency.index')} 
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="block px-3 py-2.5 rounded-lg hover:bg-blue-50 transition duration-150">
                                                            <p className="text-sm font-medium text-gray-800">Course Equivalency Framework</p>
                                                            <p className="text-xs text-gray-500 mt-0.5">20-200+ Professional Hours</p>
                                                        </Link>
                                                    </div>
                                                </div>

                                                {/* COLUMN 4: Quick Links & CTA */}
                                                <div className="bg-gradient-to-br from-blue-50 to-gray-50 rounded-xl p-5">
                                                    <h3 className="text-xs font-bold text-blue-900 uppercase tracking-wider mb-4">
                                                        Getting Started
                                                    </h3>
                                                    <div className="space-y-3">
                                                        <div className="bg-white rounded-lg p-4 shadow-sm">
                                                            <p className="text-sm font-semibold text-gray-800 mb-3">Not sure where to start?</p>
                                                            <div className="space-y-2">
                                                                <div className="flex items-center text-xs text-gray-600">
                                                                    <span className="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mr-2 text-blue-900 font-bold flex-shrink-0">1</span>
                                                                    Choose your certification
                                                                </div>
                                                                <div className="flex items-center text-xs text-gray-600">
                                                                    <span className="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mr-2 text-blue-900 font-bold flex-shrink-0">2</span>
                                                                    Complete your programme
                                                                </div>
                                                                <div className="flex items-center text-xs text-gray-600">
                                                                    <span className="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mr-2 text-blue-900 font-bold flex-shrink-0">3</span>
                                                                    Advance your career
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <Link 
                                                            href={route('qualifications.pack')}
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="block w-full text-center bg-blue-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-blue-800 transition duration-200"
                                                        >
                                                            View Full Framework
                                                        </Link>
                                                        
                                                        <Link 
                                                            href="/contact"
                                                            onClick={() => setOpenMegaMenu(false)}
                                                            className="block w-full text-center border border-blue-900 text-blue-900 text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-blue-50 transition duration-200"
                                                        >
                                                            Speak to an Advisor
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {/* Mega Menu Footer */}
                                        <div className="bg-gray-50 px-6 lg:px-8 py-3 border-t border-gray-100">
                                            <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                                                <p className="text-xs text-gray-500">
                                                    Internationally aligned qualifications • University credit recognition • Professional CPD pathway
                                                </p>
                                                <Link 
                                                    href={route('course.catalog.index')}
                                                    onClick={() => setOpenMegaMenu(false)}
                                                    className="text-sm font-medium text-blue-900 hover:text-blue-700 flex items-center whitespace-nowrap"
                                                >
                                                    Browse all programmes
                                                    <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>

                        {/* Membership */}
                        <Link 
                            href="/membership" 
                            className="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200"
                        >
                            Membership
                        </Link>

                        {/* Events */}
                        <Link 
                            href="/events" 
                            className="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200"
                        >
                            Events
                        </Link>

                        {/* Resources Dropdown (Blog + News) */}
                        <div className="relative group">
                            <button className="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 flex items-center">
                                Resources
                                <svg className="ml-1 w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div className="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <Link href="/blog" className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-150">
                                    <div className="flex items-center">
                                        <svg className="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                        Blog
                                    </div>
                                </Link>
                                <Link href={route('news.index')} className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-900 transition duration-150">
                                    <div className="flex items-center">
                                        <svg className="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                        News
                                    </div>
                                </Link>
                            </div>
                        </div>

                        {/* Connect */}
                        <Link 
                            href="/contact" 
                            className="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200"
                        >
                            Connect
                        </Link>
                    </div>

                    {/* Right Side: Auth Buttons + Mobile Toggle */}
                    <div className="flex items-center space-x-3">
                        {auth && auth.user ? (
                            <div className="hidden lg:flex items-center space-x-3">
                                <Link 
                                    href='/dashboard'
                                    className="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200"
                                >
                                    Dashboard
                                </Link>
                                <div className="relative group">
                                    <button className="flex items-center space-x-2 p-1.5 hover:bg-gray-50 rounded-lg transition duration-200">
                                        <div className="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {auth.user.name.charAt(0).toUpperCase()}
                                        </div>
                                        <span className="text-sm text-gray-700 font-medium">{auth.user.name}</span>
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div className="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 border border-gray-100">
                                        <Link href="/profile" className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 transition duration-150">
                                            Profile
                                        </Link>
                                        <Link href="/settings" className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 transition duration-150">
                                            Settings
                                        </Link>
                                        <div className="border-t border-gray-100 my-1"></div>
                                        <Link 
                                            href='/logout' 
                                            method="post"
                                            className="block px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition duration-150"
                                        >
                                            Logout
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="hidden lg:block">
                                <Link
                                    href='/login'
                                    className="bg-blue-900 text-white text-sm px-5 py-2.5 rounded-lg font-medium hover:bg-blue-800 transition duration-200 shadow-sm hover:shadow-md"
                                >
                                    Join the Institute
                                </Link>
                            </div>
                        )}
                        
                        {/* Mobile menu button */}
                        <button 
                            className="lg:hidden text-gray-700 focus:outline-none p-2 hover:bg-gray-50 rounded-lg transition duration-200"
                            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                            aria-label="Toggle menu"
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
            </div>

            {/* Mobile Menu */}
            {isMobileMenuOpen && (
                <div 
                    ref={mobileMenuRef}
                    className="lg:hidden bg-white border-t border-gray-100 shadow-lg"
                >
                    <div className="px-4 py-3 space-y-1">
                        <Link href="/" className="block px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg text-sm font-medium" onClick={() => setIsMobileMenuOpen(false)}>
                            Home
                        </Link>
                        
                        {/* Mobile: About Dropdown */}
                        <div>
                            <button 
                                className="w-full flex justify-between items-center px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg text-sm font-medium"
                                onClick={() => setOpenDropdown(openDropdown === 'about' ? null : 'about')}
                            >
                                About Us
                                <svg className={`w-4 h-4 transition-transform duration-200 ${openDropdown === 'about' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {openDropdown === 'about' && (
                                <div className="ml-4 pl-4 border-l-2 border-gray-200 space-y-1 mt-1">
                                    <Link href="/welcome-to-igrcfp" className="block px-3 py-2 text-sm text-gray-600 hover:text-blue-900" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Welcome to IGRCFP</Link>
                                    <Link href="/our-structure" className="block px-3 py-2 text-sm text-gray-600 hover:text-blue-900" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Our Structure</Link>
                                    <Link href="/why-igrcfp" className="block px-3 py-2 text-sm text-gray-600 hover:text-blue-900" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Why IGRCFP</Link>
                                </div>
                            )}
                        </div>

                        {/* Mobile: Programmes & Qualifications */}
                        <div>
                            <button 
                                className="w-full flex justify-between items-center px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg text-sm font-medium"
                                onClick={() => setOpenDropdown(openDropdown === 'programmes' ? null : 'programmes')}
                            >
                                Programmes & Qualifications
                                <svg className={`w-4 h-4 transition-transform duration-200 ${openDropdown === 'programmes' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {openDropdown === 'programmes' && (
                                <div className="ml-4 pl-4 border-l-2 border-gray-200 space-y-1 mt-1">
                                    <p className="text-xs font-bold text-blue-900 uppercase tracking-wider px-3 py-1">Qualification Levels</p>
                                    <Link href={route('qualifications.show', 'certificate')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>L1: Certificate (RQF 4-5)</Link>
                                    <Link href={route('qualifications.show', 'diploma')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>L2: Diploma (RQF 5-6)</Link>
                                    <Link href={route('qualifications.show', 'advanced-diploma')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>L3: Advanced Diploma (RQF 6-7)</Link>
                                    <Link href={route('qualifications.show', 'postgraduate-diploma')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>L4: Postgraduate Dip. (RQF 7)</Link>
                                    
                                    <div className="border-t border-gray-100 my-2"></div>
                                    <p className="text-xs font-bold text-blue-900 uppercase tracking-wider px-3 py-1">Certifications</p>
                                    <Link href={route('certifications.overview')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>Certifications Overview</Link>
                                    <Link href={route('certifications.pathway')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>Certification Pathway</Link>
                                    <Link href={route('cgfcs.specialist')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>CGFCS Specialist</Link>
                                    <Link href={route('igrcfp.fellowship.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>Fellowship</Link>
                                    
                                    <div className="border-t border-gray-100 my-2"></div>
                                    <p className="text-xs font-bold text-blue-900 uppercase tracking-wider px-3 py-1">Partnerships & Training</p>
                                    <Link href={route('partnerships.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>University Progression</Link>
                                    <Link href={route('partnerships.models')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>Partnership Models</Link>
                                    <Link href={route('corporate.training')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>Corporate Training</Link>
                                    <Link href={route('cpd.overview')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => setOpenDropdown(null)}>CPD Framework</Link>
                                    <Link href={route('course.catalog.index')} className="block px-3 py-2 text-sm text-blue-900 font-medium" onClick={() => setOpenDropdown(null)}>Browse Course Catalogue →</Link>
                                </div>
                            )}
                        </div>
                        
                        <Link href="/membership" className="block px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg text-sm font-medium" onClick={() => setIsMobileMenuOpen(false)}>Membership</Link>
                        <Link href="/events" className="block px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg text-sm font-medium" onClick={() => setIsMobileMenuOpen(false)}>Events</Link>
                        
                        {/* Mobile: Resources */}
                        <div>
                            <button 
                                className="w-full flex justify-between items-center px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg text-sm font-medium"
                                onClick={() => setOpenDropdown(openDropdown === 'resources' ? null : 'resources')}
                            >
                                Resources
                                <svg className={`w-4 h-4 transition-transform duration-200 ${openDropdown === 'resources' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {openDropdown === 'resources' && (
                                <div className="ml-4 pl-4 border-l-2 border-gray-200 space-y-1 mt-1">
                                    <Link href="/blog" className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Blog</Link>
                                    <Link href={route('news.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>News</Link>
                                </div>
                            )}
                        </div>
                        
                        <Link href="/contact" className="block px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg text-sm font-medium" onClick={() => setIsMobileMenuOpen(false)}>Connect</Link>
                        
                        {!auth?.user && (
                            <div className="pt-3 border-t border-gray-100 mt-3">
                                <Link href='/login' className="block w-full text-center bg-blue-900 text-white px-4 py-3 rounded-lg text-sm font-medium" onClick={() => setIsMobileMenuOpen(false)}>
                                    Join the Institute
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}