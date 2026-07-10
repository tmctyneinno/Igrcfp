import { Link } from "@inertiajs/react";
import React, { useEffect, useState, useRef } from "react";
import TranslateSelector from "@/Components/TranslateSelector";

function NavBar({ auth }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const [openDropdown, setOpenDropdown] = useState(null);
    const [openMegaMenu, setOpenMegaMenu] = useState(false);
    const mobileMenuRef = useRef(null);
    const megaMenuRef = useRef(null);
    const ignoreMegaMenuHoverRef = useRef(false);
     
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

    // Close menu on Escape key
    useEffect(() => {
        const handleEsc = (e) => {
            if (e.key === 'Escape') {
                setOpenMegaMenu(false);
                setIsMobileMenuOpen(false);
                setOpenDropdown(null);
            }
        };
        document.addEventListener('keydown', handleEsc);
        return () => document.removeEventListener('keydown', handleEsc);
    }, []);

    // Lock body scroll when mobile menu is open
    useEffect(() => {
        document.body.style.overflow = isMobileMenuOpen ? 'hidden' : 'unset';
        return () => { document.body.style.overflow = 'unset'; };
    }, [isMobileMenuOpen]);

    return (
        <nav className="sticky top-0 z-50 bg-white shadow-sm rounded-full">
            <div className="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
                <div className="flex justify-between items-center h-16">
                    
                    {/* Logo */}
                    <div className="flex-shrink-0 flex items-center">
                        <Link href="/" className="flex items-center">
                            <img 
                                src="/assets/images/home-three/logo/logo-main.png" 
                                alt="IGRCFP Logo" 
                                className="h-9 w-auto"
                            />
                            <span className="ml-2 text-lg sm:text-xl font-bold text-gray-900 hidden sm:block">
                                IGRCFP
                            </span>
                        </Link>
                    </div>

                    {/* Desktop Navigation — NO WRAP, COMPACT */}
                    <div className="hidden lg:flex items-center space-x-0.5">
                        
                        <Link 
                            href="/" 
                            className="px-2.5 py-2 text-sm font-medium text-gray-800 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 whitespace-nowrap"
                        >
                            Home
                        </Link>
                        
                        {/* About Dropdown */}
                        <div className="relative group">
                            <button className="px-2.5 py-2 text-sm font-medium text-gray-800 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 flex items-center whitespace-nowrap">
                                About
                                <svg className="ml-1 w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div className="absolute left-0 mt-1 w-56 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <Link href={route('welcome-to-igrcfp')} className="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Welcome to IGRCFP</Link>
                                <Link href={route('our-structure')} className="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Our Structure</Link>
                                {/* <Link href={route('why-igrcfp')} className="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Why IGRCFP</Link> */}
                            </div>
                        </div> 

                        {/* Certifications Mega Menu */}
                        <div 
                            className="relative"
                            ref={megaMenuRef}
                            onMouseLeave={() => {
                                ignoreMegaMenuHoverRef.current = false;
                                setOpenMegaMenu(false);
                            }}
                        >
                            <button 
                                className={`px-2.5 py-2 text-sm font-medium rounded-lg transition duration-200 flex items-center whitespace-nowrap ${
                                    openMegaMenu ? 'text-blue-900 bg-blue-50' : 'text-gray-800 hover:text-blue-900 hover:bg-blue-50'
                                }`}
                                onClick={() => {
                                    ignoreMegaMenuHoverRef.current = openMegaMenu;
                                    setOpenMegaMenu(!openMegaMenu);
                                }}
                                onMouseEnter={() => !ignoreMegaMenuHoverRef.current && setOpenMegaMenu(true)}
                                aria-expanded={openMegaMenu}
                            >
                                🎓 Certifications
                                <svg className={`ml-1 w-3.5 h-3.5 transition-transform duration-200 ${openMegaMenu ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {openMegaMenu && (
                                <>
                                    <div className="fixed inset-0 z-40" onClick={() => setOpenMegaMenu(false)}></div>
                                    <div className="fixed left-1/2 -translate-x-1/2 mt-2 w-[95vw] max-w-[1000px] bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                                        <div className="overflow-y-auto max-h-[calc(95vh-160px)] p-6 lg:p-8">
                                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                                {/* Qualification Levels */}
                                                <div>
                                                    <h3 className="text-xs font-bold text-blue-900 uppercase tracking-wider mb-4">Qualification Levels</h3>
                                                    <div className="space-y-1">
                                                        <Link href={route('igrcfp.certificates.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2 rounded-lg hover:bg-blue-50">
                                                            <span className="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 text-blue-900 font-bold text-xs">L1</span>
                                                            <span className="text-sm text-gray-800">Certificate</span>
                                                        </Link>
                                                        <Link href={route('igrcfp.diploma.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2 rounded-lg hover:bg-green-50">
                                                            <span className="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 text-green-900 font-bold text-xs">L2</span>
                                                            <span className="text-sm text-gray-800">Diploma</span>
                                                        </Link>
                                                        <Link href={route('igrcfp.advanced-diploma.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2 rounded-lg hover:bg-indigo-50">
                                                            <span className="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 text-indigo-900 font-bold text-xs">L3</span>
                                                            <span className="text-sm text-gray-800">Advanced Diploma</span>
                                                        </Link>
                                                        <Link href={route('igrcfp.certified-grc-financial-crime-specialist.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2 rounded-lg hover:bg-amber-50">
                                                            <span className="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3 text-amber-900 font-bold text-xs">L4</span>
                                                            <span className="text-sm text-gray-800">Certified GRC Specialist</span>
                                                        </Link>
                                                        <Link href={route('igrcfp.postgraduate-diploma.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2 rounded-lg hover:bg-rose-50">
                                                            <span className="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center mr-3 text-rose-900 font-bold text-xs">L5</span>
                                                            <span className="text-sm text-gray-800">Postgraduate Diploma</span>
                                                        </Link>
                                                        <Link href={route('igrcfp.fellowship.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2 rounded-lg hover:bg-violet-50">
                                                            <span className="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center mr-3 text-violet-900 font-bold text-xs">L6</span>
                                                            <span className="text-sm text-gray-800">Fellowship</span>
                                                        </Link>
                                                    </div>
                                                </div>
                                                {/* Professional Certifications */}
                                                <div>
                                                    <h3 className="text-xs font-bold text-blue-900 uppercase tracking-wider mb-4">Professional Certifications</h3>
                                                    <div className="space-y-1">
                                                        <Link href={route('certifications.overview')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-50">
                                                            <div className="w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                                                            <div>
                                                                <p className="text-sm text-gray-800">Certifications Overview</p>
                                                                <p className="text-xs text-gray-500">Complete pathway guide</p>
                                                            </div>
                                                        </Link>
                                                        <Link href={route('certifications.pathway')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2.5 rounded-lg hover:bg-green-50">
                                                            <div className="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                                                            <div>
                                                                <p className="text-sm text-gray-800">Certification Pathway</p>
                                                                <p className="text-xs text-gray-500">Your career roadmap</p>
                                                            </div>
                                                        </Link>
                                                        <Link href={route('cgfcs.specialist')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2.5 rounded-lg hover:bg-purple-50">
                                                            <div className="w-2 h-2 bg-purple-600 rounded-full mr-3"></div>
                                                            <div>
                                                                <p className="text-sm text-gray-800">CGFCS Specialist</p>
                                                                <p className="text-xs text-gray-500">GRC & Financial Crime</p>
                                                            </div>
                                                        </Link>
                                                    </div>
                                                    <div className="mt-4 pt-4 border-t border-gray-100">
                                                        <Link href={route('course.catalog.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2.5 rounded-lg hover:bg-blue-50">
                                                            <svg className="w-4 h-4 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                                            <span className="text-sm text-gray-800">Course Catalogue</span>
                                                        </Link>
                                                        <Link href={route('certificate.verify.public.index')} onClick={() => setOpenMegaMenu(false)} className="flex items-center px-3 py-2.5 rounded-lg hover:bg-green-50 mt-1">
                                                            <svg className="w-4 h-4 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                            <div>
                                                                <p className="text-sm text-gray-800">Verify Certificate</p>
                                                                <p className="text-xs text-gray-500">Validate credentials</p>
                                                            </div>
                                                        </Link>
                                                    </div>
                                                </div>
                                                {/* Academic Partnerships */}
                                                <div>
                                                    <h3 className="text-xs font-bold text-blue-900 uppercase tracking-wider mb-4">Academic Partnerships</h3>
                                                    <div className="space-y-1">
                                                        <Link href={route('qualifications.pack')} onClick={() => setOpenMegaMenu(false)} className="block px-3 py-2.5 rounded-lg hover:bg-blue-50">
                                                            <p className="text-sm text-gray-800">Qualification Framework</p>
                                                        </Link>
                                                        <Link href={route('course-equivalency.index')} onClick={() => setOpenMegaMenu(false)} className="block px-3 py-2.5 rounded-lg hover:bg-blue-50">
                                                            <p className="text-sm text-gray-800">Course Equivalency Framework</p>
                                                            <p className="text-xs text-gray-500">20-200+ Professional Hours</p>
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="bg-gray-50 px-6 lg:px-8 py-4 border-t border-gray-200">
                                            <div className="flex flex-col sm:flex-row justify-between items-center gap-3">
                                                <p className="text-xs text-gray-500">Internationally aligned qualifications • University credit recognition</p>
                                                <div className="flex gap-3"> 
                                                    <Link href={route('certificate.verify.public.index')} onClick={() => setOpenMegaMenu(false)} className="text-sm font-medium text-green-700 bg-green-50 px-3 py-1.5 rounded-lg hover:bg-green-100">Verify Certificate</Link>
                                                    <Link href={route('programmes')} onClick={() => setOpenMegaMenu(false)} className="text-sm font-medium text-blue-900 hover:text-blue-700">Browse all programmes →</Link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>

                        <Link 
                            href={route('membership')} 
                            className="px-2.5 py-2 text-sm font-medium text-gray-800 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 whitespace-nowrap"
                        >
                            Membership
                        </Link>

                        {/* ✅ Combined Events & Chapters — NO WRAP */}
                        <div className="relative group">
                            <button className="px-2.5 py-2 text-sm font-medium text-gray-800 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 flex items-center whitespace-nowrap">
                                📅 Events & Chapters
                                <svg className="ml-1 w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div className="absolute left-0 mt-1 w-60 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <Link href={route('events.index')} className="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50">
                                    <svg className="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    All Events
                                </Link>
                                <Link href={route('chapters.index')} className="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50">
                                    <svg className="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 5H21a2 2 0 012 2v8a2 2 0 01-2 2h-5.5l-1-5H5z" /></svg>
                                    Regional Chapters
                                </Link>
                            </div>
                        </div>

                        {/* Resources */}
                        <div className="relative group">
                            <button className="px-2.5 py-2 text-sm font-medium text-gray-800 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 flex items-center whitespace-nowrap">
                                Resources
                                <svg className="ml-1 w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div className="absolute right-0 mt-1 w-56 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <Link href={route('blog')} className="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50">
                                    <svg className="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                    Blog
                                </Link>
                                <Link href={route('news.index')} className="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50">
                                    <svg className="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                    News
                                </Link>
                                <div className="border-t border-gray-100 my-1"></div>
                                <Link href={route('research.index')} className="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50">
                                    <svg className="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Research & White Papers
                                </Link>
                            </div>
                        </div>

                        <Link 
                            href={route('contact')} 
                            className="px-2.5 py-2 text-sm font-medium text-gray-800 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200 whitespace-nowrap"
                        >
                            Connect
                        </Link>
                    </div>

                    {/* Right Side */}
                    <div className="flex items-center space-x-2 sm:space-x-3">
                        <div className="hidden lg:block">
                            <TranslateSelector pageLanguage="en" />
                        </div>

                        {auth && auth.user ? (
                            <div className="hidden lg:flex items-center space-x-2">
                                <Link href={route('dashboard.index')} className="px-2.5 py-2 text-sm font-medium text-gray-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg whitespace-nowrap">Dashboard</Link>
                                <div className="relative group">
                                    <button className="flex items-center space-x-1.5 p-1.5 hover:bg-gray-50 rounded-lg whitespace-nowrap">
                                        <div className="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {auth.user.name.charAt(0).toUpperCase()}
                                        </div>
                                        <span className="text-sm text-gray-700 max-w-[100px] truncate">{auth.user.name}</span>
                                        <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                    <div className="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <Link href={route('profile.edit')} className="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Profile</Link>
                                        <Link href={route('settings')} className="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Settings</Link>
                                        <div className="border-t border-gray-100 my-1"></div>
                                        <Link href={route('logout')} method="post" as="button" className="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</Link>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="hidden lg:block">
                                <Link href={route('login')} className="bg-blue-900 text-white text-sm px-3 py-1.5 rounded-full hover:bg-blue-800 whitespace-nowrap">
                                    Join
                                </Link>
                            </div>
                        )}

                        {/* Mobile Toggle */}
                        <button 
                            className="lg:hidden text-gray-700 p-2 hover:bg-gray-50 rounded-lg"
                            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                        >
                            {isMobileMenuOpen ? (
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
                            ) : (
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" /></svg>
                            )}
                        </button>
                    </div>
                </div>
            </div>

            {/* Mobile Menu */}
            {isMobileMenuOpen && (
                <div ref={mobileMenuRef} className="lg:hidden bg-white border-t border-gray-100 shadow-lg fixed left-0 right-0 overflow-y-auto" style={{ maxHeight: 'calc(100vh - 64px)', top: '64px' }}>
                    <div className="px-4 py-3 space-y-1">
                        <Link href="/" className="block px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg" onClick={() => setIsMobileMenuOpen(false)}>🏠 Home</Link>

                        {/* Mobile About */}
                        <div>
                            <button className="w-full flex justify-between items-center px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg" onClick={() => setOpenDropdown(openDropdown === 'about' ? null : 'about')}>
                                About <svg className={`w-4 h-4 transition-transform ${openDropdown === 'about' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            {openDropdown === 'about' && (
                                <div className="ml-4 pl-4 border-l-2 border-gray-200 space-y-1 mt-1">
                                    <Link href={route('welcome-to-igrcfp')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Welcome to IGRCFP</Link>
                                    <Link href={route('our-structure')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Our Structure</Link>
                                    {/* <Link href={route('why-igrcfp')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Why IGRCFP</Link> */}
                                </div>
                            )}
                        </div>

                        {/* Mobile Certifications */}
                        <div>
                            <button className="w-full flex justify-between items-center px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg" onClick={() => setOpenDropdown(openDropdown === 'programmes' ? null : 'programmes')}>
                                🎓 Certifications & Trainings <svg className={`w-4 h-4 transition-transform ${openDropdown === 'programmes' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            {openDropdown === 'programmes' && (
                                <div className="ml-4 pl-4 border-l-2 border-gray-200 space-y-1 mt-1">
                                    <p className="text-xs font-bold text-blue-900 uppercase tracking-wider px-3 py-1">Qualification Levels</p>
                                    <Link href={route('igrcfp.certificates.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>L1: Certificate</Link>
                                    <Link href={route('igrcfp.diploma.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>L2: Diploma</Link>
                                    <Link href={route('igrcfp.advanced-diploma.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>L3: Advanced Diploma</Link>
                                    <Link href={route('igrcfp.certified-grc-financial-crime-specialist.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>L4: Certified GRC Specialist</Link>
                                    <Link href={route('igrcfp.postgraduate-diploma.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>L5: Postgraduate Diploma</Link>
                                    <Link href={route('igrcfp.fellowship.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>L6: Fellowship</Link>
                                    <div className="border-t border-gray-100 my-2"></div>
                                    <p className="text-xs font-bold text-blue-900 uppercase tracking-wider px-3 py-1">Certifications</p>
                                    <Link href={route('certifications.overview')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Certifications Overview</Link>
                                    <Link href={route('certifications.pathway')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Certification Pathway</Link>
                                    <Link href={route('cgfcs.specialist')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>CGFCS Specialist</Link>
                                    <div className="border-t border-gray-100 my-2"></div>
                                    <Link href={route('course.catalog.index')} className="block px-3 py-2 text-sm text-blue-900 font-medium" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>📚 Browse Course Catalogue →</Link>
                                </div>
                            )}
                        </div>

                        <Link href={route('membership')} className="block px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg" onClick={() => setIsMobileMenuOpen(false)}>💎 Membership</Link>
                        <Link href={route('certificate.verify.public.index')} className="flex items-center px-3 py-2.5 text-gray-700 hover:bg-green-50 rounded-lg" onClick={() => setIsMobileMenuOpen(false)}>✅ Verify Certificate</Link>

                        {/* Mobile Events & Chapters */}
                        <div>
                            <button className="w-full flex justify-between items-center px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg" onClick={() => setOpenDropdown(openDropdown === 'events-chapters' ? null : 'events-chapters')}>
                                📅 Events & Chapters <svg className={`w-4 h-4 transition-transform ${openDropdown === 'events-chapters' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            {openDropdown === 'events-chapters' && (
                                <div className="ml-4 pl-4 border-l-2 border-gray-200 space-y-1 mt-1">
                                    <Link href={route('events.index')} className="flex items-center px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>📅 All Events</Link>
                                    <Link href={route('chapters.index')} className="flex items-center px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>🌍 Regional Chapters</Link>
                                </div>
                            )}
                        </div>

                        {/* Mobile Resources */}
                        <div>
                            <button className="w-full flex justify-between items-center px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg" onClick={() => setOpenDropdown(openDropdown === 'resources' ? null : 'resources')}>
                                📖 Resources <svg className={`w-4 h-4 transition-transform ${openDropdown === 'resources' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            {openDropdown === 'resources' && (
                                <div className="ml-4 pl-4 border-l-2 border-gray-200 space-y-1 mt-1">
                                    <Link href={route('blog')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Blog</Link>
                                    <Link href={route('news.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>News</Link>
                                    <div className="border-t border-gray-200 my-1"></div>
                                    <Link href={route('research.index')} className="block px-3 py-2 text-sm text-gray-600" onClick={() => {setIsMobileMenuOpen(false); setOpenDropdown(null);}}>Research & White Papers</Link>
                                </div>
                            )}
                        </div>

                        <Link href={route('contact')} className="block px-3 py-2.5 text-gray-700 hover:bg-blue-50 rounded-lg" onClick={() => setIsMobileMenuOpen(false)}>📞 Connect</Link>

                        <div className="px-3 py-2.5 border-t border-gray-100 mt-3 pt-3">
                            <p className="text-xs text-gray-400 mb-2 uppercase tracking-wider">Translate page</p>
                            <TranslateSelector pageLanguage="en" />
                        </div>

                        {!auth?.user && (
                            <div className="pt-3 border-t border-gray-100 mt-3">
                                <Link href={route('login')} className="block w-full text-center bg-blue-900 text-white px-4 py-2.5 rounded-lg font-medium" onClick={() => setIsMobileMenuOpen(false)}>Join the Institute</Link>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}

export default React.memo(NavBar);