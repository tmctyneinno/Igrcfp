import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeIn, scaleIn, staggerContainer } from "@/utils/motionPresets";
import { 
    FaEnvelope, 
    FaMapMarkerAlt, 
    FaPhone, 
    FaLinkedin, 
    FaTwitter, 
    FaYoutube,
    FaFacebook,
    FaInstagram,
    FaChevronRight,
    FaShieldAlt,
    FaGraduationCap,
    FaCalendarAlt,
    FaFileContract
} from "react-icons/fa";

export default function Footer() {
    const currentYear = new Date().getFullYear();
    
    return (
        <footer className="bg-gradient-to-b from-slate-900 to-slate-950 text-gray-300 relative overflow-hidden">
            {/* Decorative elements */}
            <div className="absolute inset-0 bg-grid-slate-800/[0.02] bg-[size:20px_20px]"></div>
            <div className="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
            
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 relative">
                {/* Newsletter Section */}
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.6 }}
                    viewport={{ once: true }}
                    className="mb-16 p-8 bg-gradient-to-r from-blue-900/30 to-indigo-900/30 backdrop-blur-sm rounded-2xl border border-blue-800/30"
                >
                    <div className="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h3 className="text-2xl font-bold text-white mb-3">
                                Stay Updated with Industry Insights
                            </h3>
                            <p className="text-blue-100">
                                Subscribe to our newsletter for the latest GRC & Financial Crime Prevention updates.
                            </p>
                        </div>
                        <div>
                            <form className="flex flex-col sm:flex-row gap-3">
                                <input
                                    type="email"
                                    placeholder="Enter your email"
                                    className="flex-grow px-4 py-3 bg-white/5 border border-blue-700/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                                />
                                <button
                                    type="submit"
                                    className="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-500/25"
                                >
                                    Subscribe
                                </button>
                            </form>
                            <p className="text-xs text-gray-400 mt-3">
                                By subscribing, you agree to our Privacy Policy. Unsubscribe anytime.
                            </p>
                        </div>
                    </div>
                </motion.div>

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
                                <span className="text-2xl font-bold bg-gradient-to-r from-blue-400 to-blue-600 bg-clip-text text-transparent">
                                    IGRCFP
                                </span>
                                <p className="text-sm text-gray-400">Global Professional Body</p>
                            </div>
                        </div>

                        <p className="text-gray-400 leading-relaxed mb-6">
                            The International Governance, Risk, Compliance & Financial Crime 
                            Professionals body advancing professional standards, ethics, 
                            and global best practices worldwide.
                        </p>

                        {/* Social Media */}
                        <div className="space-y-4">
                            <h4 className="text-white font-semibold">Connect With Us</h4>
                            <div className="flex gap-3">
                                {[
                                    { icon: FaLinkedin, color: "hover:bg-blue-700", label: "LinkedIn" },
                                    { icon: FaTwitter, color: "hover:bg-sky-500", label: "Twitter" },
                                    { icon: FaFacebook, color: "hover:bg-blue-600", label: "Facebook" },
                                    { icon: FaInstagram, color: "hover:bg-pink-600", label: "Instagram" },
                                    { icon: FaYoutube, color: "hover:bg-red-600", label: "YouTube" }
                                ].map((social, index) => (
                                    <motion.a
                                        key={index}
                                        href="#"
                                        variants={scaleIn}
                                        whileHover={{ scale: 1.1 }}
                                        whileTap={{ scale: 0.95 }}
                                        className={`w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center ${social.color} transition-all duration-300 group`}
                                        aria-label={social.label}
                                    >
                                        <social.icon className="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" />
                                    </motion.a>
                                ))}
                            </div>
                        </div>
                    </motion.div>

                    {/* Quick Links */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-white font-semibold text-lg mb-6 pb-3 border-b border-blue-800/30 flex items-center gap-2">
                            <FaChevronRight className="w-4 h-4 text-blue-400" />
                            Quick Links
                        </h4>
                        <ul className="space-y-3">
                            {[
                                { icon: FaShieldAlt, label: "Membership", href: "/membership" },
                                { icon: FaGraduationCap, label: "Certifications", href: "/certifications" },
                                { icon: FaCalendarAlt, label: "Events", href: "/events" },
                                { icon: FaFileContract, label: "Resources", href: "/resources" },
                                { label: "Training Programs", href: "/training" },
                                { label: "Research & Publications", href: "/research" },
                                { label: "Career Center", href: "/careers" },
                                { label: "Partner With Us", href: "/partnerships" }
                            ].map((link, index) => (
                                <li key={index}>
                                    <Link 
                                        href={link.href}
                                        className="flex items-center gap-3 text-gray-400 hover:text-white transition-all duration-300 hover:translate-x-1 group"
                                    >
                                        {link.icon && <link.icon className="w-4 h-4 text-blue-400" />}
                                        <span>{link.label}</span>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </motion.div>

                    {/* Legal & Policies */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-white font-semibold text-lg mb-6 pb-3 border-b border-blue-800/30">
                            Legal & Policies
                        </h4>
                        <ul className="space-y-3">
                            {[
                                "Terms of Service",
                                "Privacy Policy",
                                "Cookie Policy",
                                "Code of Ethics",
                                "Anti-Bribery Policy",
                                "Complaints Procedure",
                                "Whistleblowing Policy",
                                "Accessibility Statement"
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
                        <ul className="space-y-6">
                            <li className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    <FaEnvelope className="w-5 h-5 text-blue-400" />
                                </div>
                                <div>
                                    <span className="block text-sm text-gray-500 mb-1">Email</span>
                                    <a 
                                        href="mailto:enquiries@igrcfp.org" 
                                        className="text-white hover:text-blue-300 transition-colors font-medium"
                                    >
                                        enquiries@igrcfp.org
                                    </a>
                                </div>
                            </li>
                            <li className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    <FaMapMarkerAlt className="w-5 h-5 text-blue-400" />
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
                            <li className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    <FaPhone className="w-5 h-5 text-blue-400" />
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
                            </li>
                        </ul>

                        {/* Office Hours */}
                        <div className="mt-8 p-4 bg-white/5 rounded-lg border border-blue-800/20">
                            <h5 className="text-white font-medium mb-2">Office Hours</h5>
                            <p className="text-sm text-gray-400">
                                Mon - Fri: 9:00 AM - 6:00 PM GMT<br />
                                Emergency: 24/7 Support Available
                            </p>
                        </div>
                    </motion.div>
                </motion.div>

                {/* Divider */}
                <div className="relative my-12">
                    <div className="absolute inset-0 flex items-center">
                        <div className="w-full border-t border-blue-900/30"></div>
                    </div>
                    <div className="relative flex justify-center">
                        <span className="px-4 bg-slate-950 text-gray-500 text-sm">
                            Certified Professional Body
                        </span>
                    </div>
                </div>

                {/* Bottom Bar */}
                <div className="flex flex-col md:flex-row justify-between items-center gap-6 pt-8 border-t border-blue-900/30">
                    <div className="text-center md:text-left">
                        <p className="text-gray-400">
                            © {currentYear} International Governance, Risk, Compliance & Financial Crime Professionals (IGRCFP).
                        </p>
                        <p className="text-sm text-gray-500 mt-1">
                            Registration Number: 12345678 • VAT: GB 123 4567 89
                        </p>
                    </div>
                    
                    <div className="flex items-center gap-6">
                        <div className="flex items-center gap-4">
                            <img 
                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/ISO_7010_E002.svg/1200px-ISO_7010_E002.svg.png" 
                                alt="ISO Certified" 
                                className="h-8 w-auto opacity-70 hover:opacity-100 transition-opacity"
                                title="ISO 9001 Certified"
                            />
                            <img 
                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/57/UK_government_logo.svg/1200px-UK_government_logo.svg.png" 
                                alt="UK Government Recognized" 
                                className="h-8 w-auto opacity-70 hover:opacity-100 transition-opacity"
                                title="UK Government Recognized"
                            />
                        </div>
                        
                        <div className="flex gap-4">
                            <Link href="/sitemap" className="text-sm text-gray-400 hover:text-white transition">
                                Sitemap
                            </Link>
                            <span className="text-gray-600">•</span>
                            <Link href="/accessibility" className="text-sm text-gray-400 hover:text-white transition">
                                Accessibility
                            </Link>
                            <span className="text-gray-600">•</span>
                            <Link href="/disclaimer" className="text-sm text-gray-400 hover:text-white transition">
                                Disclaimer
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
        </footer>
    );
}