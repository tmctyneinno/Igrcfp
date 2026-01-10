import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function Footer() {
    return (
        <footer className=" text-gray-400">
            <div className="max-w-7xl mx-auto px-6 py-16">

                {/* TOP GRID */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 md:grid-cols-4 gap-10"
                >
                    {/* BRAND */}
                    <motion.div variants={fadeLeft}>
                        <div className="flex items-center gap-3 mb-4">
                            <img
                                src="/assets/images/home-three/logo/logo-main.png"
                                alt="IGRCFP Logo"
                                className="h-15 w-auto"
                            />
                            <span className="text-xl font-semibold text-white">
                                IGRCFP
                            </span>
                        </div>

                        <p className="text-sm leading-relaxed">
                            The International Governance, Risk, Compliance & Financial
                            Crime Professionals body — advancing professional standards,
                            ethics, and global best practices.
                        </p>
                    </motion.div>

                    {/* QUICK LINKS */}
                    <motion.div variants={fadeLeft}>
                        <h4 className="text-white font-semibold mb-5">
                            Quick Links
                        </h4>
                        <ul className="space-y-3 text-sm">
                            <li>
                                <Link href="/" className="hover:text-white transition">
                                    Home
                                </Link>
                            </li>
                            <li>
                                <Link href="/memberships" className="hover:text-white transition">
                                    Memberships
                                </Link>
                            </li>
                            <li>
                                <Link href="/certifications" className="hover:text-white transition">
                                    Certifications
                                </Link>
                            </li>
                            <li>
                                <Link href="/events" className="hover:text-white transition">
                                    Events
                                </Link>
                            </li>
                        </ul>
                    </motion.div>

                    {/* LEGAL */}
                    <motion.div variants={fadeLeft}>
                        <h4 className="text-white font-semibold mb-5">
                            Legal
                        </h4>
                        <ul className="space-y-3 text-sm">
                            <li>
                                <Link href="/terms" className="hover:text-white transition">
                                    Terms of Service
                                </Link>
                            </li>
                            <li>
                                <Link href="/privacy" className="hover:text-white transition">
                                    Privacy Policy
                                </Link>
                            </li>
                            <li>
                                <Link href="/cookies" className="hover:text-white transition">
                                    Cookie Policy
                                </Link>
                            </li>
                        </ul>
                    </motion.div>

                    {/* CONTACT */}
                    <motion.div variants={fadeLeft}>
                        <h4 className="text-white font-semibold mb-5">
                            Contact
                        </h4>
                        <ul className="space-y-3 text-sm leading-relaxed">
                            <li>
                                <span className="block text-gray-500">Email</span>
                                <a
                                    href="mailto:enquiries@igrcfp.org"
                                    className="hover:text-white transition"
                                >
                                    enquiries@igrcfp.org
                                </a>
                            </li>
                            <li>
                                <span className="block text-gray-500">Address</span>
                                85 Great Portland Street,<br />
                                First Floor, W1W 7LT,<br />
                                London, United Kingdom
                            </li>
                        </ul>
                    </motion.div>
                </motion.div>

                {/* DIVIDER */}
                <div className="border-t border-slate-800 mt-14 pt-8 text-center text-sm">
                    <p>
                        © {new Date().getFullYear()} IGRCFP. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    );
}
