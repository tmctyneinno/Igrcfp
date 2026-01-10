import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function WhoAreWe() {
    return (
        <div className="max-w-7xl mx-auto px-6">

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="flex flex-col"
                >
                    <div className="relative inline-flex items-center mb-3">
                        <span className="absolute left-0 top-1/2 w-16 h-px bg-gray-300 -z-10"></span>
                        <span className="text-sm tracking-widest text-gray-400 pl-20 uppercase">
                            Who We Are
                        </span>
                    </div>
                    <h2 className="text-4xl xl:text-5xl font-bold text-slate-900 mt-3 mb-6">
                        Know More About Us
                    </h2>
                </motion.div>

                <motion.div
                    initial={{ opacity: 0, x: -30 }}
                    whileInView={{ opacity: 1, x: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    viewport={{ once: true }}
                >
                    <p className="text-gray-600 mb-4">
                        The Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP) is a global professional body dedicated to raising standards in governance, risk management, compliance, and financial crime prevention.
                    </p>
                     <p className="text-gray-600 mb-4">
                        We equip professionals and organizations with world-class training, certifications, and resources to stay ahead in a fast-changing regulatory environment.
                    </p>
                     <p className="text-gray-600 mb-4">
                        With a presence across Africa, Europe, Asia, the Middle East, and the Americas, IGRCFP connects experts, regulators, and industry leaders to share knowledge, drive innovation, and build stronger institutions worldwide.
                    </p>

                    <Link
                        href="/about-us"
                        className="inline-flex mt-6 px-6 py-3 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition"
                    >
                        Learn More →
                    </Link>
                </motion.div>
            </div>

            <motion.div
                variants={scaleIn}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true }}
                className="mt-24 flex justify-center"
            >
                <img
                    src="assets/images/home-three/bg/intro-bg.png"
                    alt="IGRCFP"
                    className="max-w-2xl w-full"
                />
            </motion.div>
        </div>
    );
}
