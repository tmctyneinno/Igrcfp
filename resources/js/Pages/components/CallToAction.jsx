import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { scaleIn } from "@/utils/motionPresets";

export default function CallToAction({ auth }) {
    return (
        <motion.section
            variants={scaleIn}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            className="py-16 bg-gray-200"
        >
            <div className="max-w-5xl mx-auto px-6 text-center">
                <h2 className="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-4">
                    Ready to advance your practice?
                </h2>
                <p className="text-gray-700 text-base md:text-lg max-w-2xl mx-auto mb-8">
                    Join a global community of GRC and financial crime prevention professionals committed to excellence, rigour, and impact.
                </p>
                <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <Link
                        href={route('membership')}
                        className="bg-[#0A2463] text-white px-6 py-2.5 rounded-full font-medium hover:bg-[#081E52] transition-colors"
                    >
                        Apply for Membership
                    </Link>
                    <Link
                        href={route('igrcfp.certificates.index')}
                        className="bg-transparent border-2 border-[#0A2463] text-[#0A2463] px-6 py-2.5 rounded-full font-semibold hover:bg-[#0A2463] hover:text-white transition-all duration-300 shadow-sm hover:shadow-md"
                    >
                        Explore Programmes
                    </Link>
                </div>
            </div>
        </motion.section>
    );
}