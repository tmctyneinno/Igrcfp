import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { staggerContainer, fadeIn } from "@/utils/motionPresets";

function Footer() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="bg-[#0A1A2F] text-gray-300">
            <div className="max-w-7xl mx-auto px-6 py-12">
                {/* Main Footer Grid */}
                <motion.div
                    variants={staggerContainer}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
                >
                    {/* Brand Column */}
                    <motion.div variants={fadeIn}>
                        <div className="flex items-center gap-3 mb-4">
                            <img
                                src="/assets/images/home-three/logo/logo-main.png"
                                alt="IGRCFP Logo"
                                className="h-12 w-auto"
                            />
                            <span className="text-3xl font-bold text-white">IGRCFP</span>
                        </div>
                        <p className="text-gray-300 leading-relaxed">
                            Advancing standards in Governance, Risk & Compliance and Financial Crime Prevention globally since our founding in London.
                        </p>
                    </motion.div>

                    {/* Programmes Column */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-[#6384C7] text-sm font-medium uppercase tracking-wider mb-4">
                            Programmes
                        </h4>
                        <ul className="space-y-2 text-sm">
                            <li>
                                <Link href="/programmes/grc-foundations" className="hover:text-white transition-colors">
                                    GRC Foundations
                                </Link>
                            </li>
                            <li>
                                <Link href="/programmes/financial-crime-prevention" className="hover:text-white transition-colors">
                                    Financial Crime Prevention
                                </Link>
                            </li>
                            <li>
                                <Link href="/programmes/it-grc-cyber" className="hover:text-white transition-colors">
                                    IT GRC & Cyber
                                </Link>
                            </li>
                            <li>
                                <Link href="/programmes/esg-sustainable-finance" className="hover:text-white transition-colors">
                                    ESG & Sustainable Finance
                                </Link>
                            </li>
                            <li>
                                <Link href="/programmes/ai-driven-compliance" className="hover:text-white transition-colors">
                                    AI-Driven Compliance
                                </Link>
                            </li>
                            <li>
                                <Link href="/programmes" className="hover:text-white transition-colors font-medium">
                                    All Programmes
                                </Link>
                            </li>
                        </ul>
                    </motion.div>

                    {/* Institute Column */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-[#6384C7] text-sm font-medium uppercase tracking-wider mb-4">
                            Institute
                        </h4>
                        <ul className="space-y-2 text-sm">
                            <li>
                                <Link href="/about-igrcfp" className="hover:text-white transition-colors">
                                    About IGRCFP
                                </Link>
                            </li>
                            <li>
                                <Link href="/membership" className="hover:text-white transition-colors">
                                    Membership
                                </Link>
                            </li>
                            <li>
                                <Link href="#" className="hover:text-white transition-colors">
                                    Council & Governance
                                </Link>
                            </li>
                            <li>
                                <Link href="#" className="hover:text-white transition-colors">
                                    Academic Partnerships
                                </Link>
                            </li>
                            <li>
                                <Link href="#" className="hover:text-white transition-colors">
                                    Corporate Partnerships
                                </Link>
                            </li>
                            <li>
                                <Link href="/contact" className="hover:text-white transition-colors">
                                    Contact
                                </Link>
                            </li>
                        </ul>
                    </motion.div>

                    {/* Resources Column */}
                    <motion.div variants={fadeIn}>
                        <h4 className="text-[#6384C7] text-sm font-medium uppercase tracking-wider mb-4">
                            Resources
                        </h4>
                        <ul className="space-y-2 text-sm">
                            <li>
                                <Link href={route('blog')} className="hover:text-white transition-colors">
                                    Blog
                                </Link>
                            </li>
                            <li>
                                <Link href={route('research.index')} className="hover:text-white transition-colors">
                                    Research Papers
                                </Link>
                            </li>
                            <li>
                                <Link href={route('chapters.index')} className="hover:text-white transition-colors">
                                    Regional Chapters
                                </Link>
                            </li>
                            <li>
                                <Link href={route('events.index')} className="hover:text-white transition-colors">
                                    Events
                                </Link>
                            </li>
                            <li>
                                <Link href="https://grcfincrimetoday.org/" className="hover:text-white transition-colors">
                                    GRC Fincrime Today
                                </Link>
                            </li>
                            <li>
                                <Link href="https://oysterchecks.com/" className="hover:text-white transition-colors">
                                    Oysterechecks Platform
                                </Link>
                            </li>
                        </ul>
                    </motion.div>
                </motion.div>

                {/* Bottom Copyright & Contact Bar */}
                <div className="mt-10 pt-6 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-sm">
                    <p>© {currentYear} Institute of GRC & Financial Crime Prevention. All rights reserved.</p>
                    <div className="mt-4 md:mt-0 text-right">
                        <p>85 Great Portland Street • London W1W 7LT • United Kingdom</p>
                        <p>info@igrcfp.org • +44-2078560149</p>
                    </div>
                </div>
            </div>
        </footer>
    );
}

export default React.memo(Footer);