import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeIn, scaleIn, staggerContainer } from "@/utils/motionPresets";

export default function Footer() {
    const currentYear = new Date().getFullYear();
     
    return (    
        <footer className="">
            {/* Decorative elements */}
          
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 relative">
               
                {/* Main Footer Content */}
                <motion.div
                    variants={staggerContainer}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 lg:grid-cols-5 gap-10 mb-12"
                >
                    {/* Brand Column - Wider */}
                    <motion.div variants={fadeIn} className="lg:col-span-2">
                        <div className="flex items-center gap-4 mb-6">
                            <div className="relative">
                                <img
                                    src="/assets/images/home-three/logo/logo-main.png"
                                    alt="IGRCFP Logo"
                                    className="h-16 w-auto"
                                />
                                <div className="absolute -inset-1 bg-blue-500/10 blur-md rounded-full"></div>
                            </div>
                            <div>
                                <span className="text-2xl font-bold  bg-clip-text text-white">
                                    IGRCFP
                                </span>
                                <p className="text-sm text-gray-400">Global Professional Body</p>
                            </div>
                        </div>

                        <p className="text-gray-400 leading-relaxed mb-6">
                            The Institute Governance, Risk, Compliance & Financial Crime 
                            Professionals body advancing professional standards, ethics, 
                            and global best practices worldwide.
                        </p>

                        {/* Social Media */}
                        <div className="space-y-4">
                            <h4 className="text-white font-semibold">Connect With Us</h4>
                            <div className="flex gap-3">
                                {/* LinkedIn Icon */}
                                <motion.a
                                    href="#"
                                    variants={scaleIn}
                                    whileHover={{ scale: 1.1 }}
                                    whileTap={{ scale: 0.95 }}
                                    className="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center hover:bg-blue-700 transition-all duration-300 group"
                                    aria-label="LinkedIn"
                                >
                                    <svg className="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </motion.a>

                                {/* Twitter Icon */}
                                <motion.a
                                    href="#"
                                    variants={scaleIn}
                                    whileHover={{ scale: 1.1 }}
                                    whileTap={{ scale: 0.95 }}
                                    className="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center hover:bg-sky-500 transition-all duration-300 group"
                                    aria-label="Twitter"
                                >
                                    <svg className="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </motion.a>

                                {/* Facebook Icon */}
                                <motion.a
                                    href="#"
                                    variants={scaleIn}
                                    whileHover={{ scale: 1.1 }}
                                    whileTap={{ scale: 0.95 }}
                                    className="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center hover:bg-blue-600 transition-all duration-300 group"
                                    aria-label="Facebook"
                                >
                                    <svg className="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </motion.a>

                                {/* Instagram Icon */}
                                <motion.a
                                    href="#"
                                    variants={scaleIn}
                                    whileHover={{ scale: 1.1 }}
                                    whileTap={{ scale: 0.95 }}
                                    className="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center hover:bg-pink-600 transition-all duration-300 group"
                                    aria-label="Instagram"
                                >
                                    <svg className="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </motion.a>

                                {/* YouTube Icon */}
                                <motion.a
                                    href="#"
                                    variants={scaleIn}
                                    whileHover={{ scale: 1.1 }}
                                    whileTap={{ scale: 0.95 }}
                                    className="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center hover:bg-red-600 transition-all duration-300 group"
                                    aria-label="YouTube"
                                >
                                    <svg className="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                </motion.a>
                            </div>
                        </div>
                    </motion.div>

                    {/* Quick Links */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-white font-semibold text-lg mb-6 pb-3 border-b border-blue-800/30 flex items-center gap-2">
                            {/* Chevron Right Icon */}
                            <svg className="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                            Quick Links
                        </h4>
                        <ul className="space-y-3">
                            {[
                                { label: "Membership", href: "/membership" },
                                { label: "Certifications", href: "/certifications" },
                                { label: "Events", href: "/events" },
                                { label: "News", href: "/news" },
                                // { label: "", href: "/course" },
                            ].map((link, index) => (
                                <li key={index}>
                                    <Link 
                                        href={link.href}
                                        className="flex items-center gap-3 text-gray-400 hover:text-white transition-all duration-300 hover:translate-x-1 group"
                                    >
                                        {/* Shield Icon for first item */}
                                        {index === 0 && (
                                            <svg className="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        )}
                                        {/* Graduation Cap Icon for second item */}
                                        {index === 1 && (
                                            <svg className="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14l9-5-9-5-9 5 9 5z" />
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" />
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14v6l-9-5" />
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14v6l9-5" />
                                            </svg>
                                        )}
                                        {/* Calendar Icon for third item */}
                                        {index === 2 && (
                                            <svg className="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        )}
                                        {/* Document Icon for fourth item */}
                                        {index === 3 && (
                                            <svg className="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        )}
                                        <span>{link.label}</span>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </motion.div>

                    {/* Legal & Policies */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-white font-semibold text-lg mb-6 pb-3 border-b border-blue-800/30">
                            More Links
                        </h4>
                        <ul className="space-y-3">
                            {[
                                "About Us",
                                "Welcome to IGRCFP",
                                "Our Structure",
                                'Course'
                            ].map((item, index) => (
                                <li key={index}>
                                    <Link 
                                        href={`/${item.toLowerCase().replace(/ /g, '-')}`}
                                        className="text-gray-400 hover:text-white transition-colors duration-300 flex items-center gap-2 group"
                                    >
                                        <div className="w-1.5 h-1.5 bg-blue-500/50 rounded-full group-hover:bg-blue-400 transition-colors"></div>
                                        {item}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </motion.div>

                    {/* Contact Information */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-white font-semibold text-lg mb-6 pb-3 border-b border-blue-800/30">
                            Contact Information
                        </h4>
                        <ul className="space-y-3">
                            <li className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    {/* Email Icon */}
                                    <svg className="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span className="block text-sm text-gray-500 mb-1">Email</span>
                                    <a 
                                        href="mailto:enquiries@igrfcp.org" 
                                        className="text-white hover:text-blue-300 transition-colors font-medium"
                                    >
                                        enquiries@igrfcp.org
                                    </a>
                                </div>
                            </li>
                            <li className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    {/* Location Icon */}
                                    <svg className="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span className="block text-sm text-gray-500 mb-1">Headquarters</span>
                                    <address className="not-italic text-gray-300 leading-relaxed">
                                        85 Great Portland Street<br />
                                        First Floor, W1W 7LT<br />
                                        London, United Kingdom
                                    </address>
                                </div>
                            </li>
                            {/* <li className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                     <svg className="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <span className="block text-sm text-gray-500 mb-1">Phone</span>
                                    <a 
                                        href="tel:+442071234567" 
                                        className="text-white hover:text-blue-300 transition-colors font-medium"
                                    >
                                        +44 (0)20 7123 4567
                                    </a>
                                </div>
                            </li> */}
                        </ul>

                       
                    </motion.div>
                </motion.div>

               

                {/* Bottom Bar */}
                <div className="flex flex-col md:flex-row justify-between items-center gap-6 pt-8 border-t border-blue-900/30">
                    
                    <div className="flex items-center gap-6">
                        
                        
                        <div className="flex gap-4">
                            <Link href="/privacy-policy" className="text-sm text-gray-400 hover:text-white transition">
                                Privacy Policy
                            </Link>
                            <span className="text-gray-600">•</span>
                            <Link href="/terms-conditions" className="text-sm text-gray-400 hover:text-white transition">
                                Terms and Conditions
                            </Link>
                            <span className="text-gray-600">•</span>
                            <Link href="/privacy-preference-center" className="text-sm text-gray-400 hover:text-white transition">
                                Privacy Preference Center
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Accreditation Badge */}
                <div className="absolute bottom-4 right-4 hidden lg:block">
                    <div className="text-xs text-gray-500 text-right">
                        <div className="inline-flex items-center gap-2 px-3 py-1.5 bg-white/5 rounded-full border border-blue-800/30">
                            <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span>Accredited Professional Body</span>
                        </div>
                    </div>
                </div>
                
            </div>
            <div className="text-center md:text-left">
                <p className="text-gray-400">
                    © {currentYear} The Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP).
                </p>
            </div>
        </footer>
    );
}