import React from "react";
import { motion } from "framer-motion";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";
import { GlobeAltIcon, CheckCircleIcon, UserGroupIcon } from "@heroicons/react/24/outline";

export default function WhyCertificationsMatter() {
    const challenges = [
        { title: 'Rapid Regulatory Change', icon: '📋' },
        { title: 'Cross-border Financial Activity', icon: '🌍' },
        { title: 'Digital Transformation', icon: '💻' },
        { title: 'Sophisticated Financial Crime', icon: '🔒' }
    ];

    const principles = [
        {
            title: "Integrated Systems Thinking",
            icon: <GlobeAltIcon className="w-6 h-6 text-blue-900" />,
            description: "Governance, risk, compliance, and financial crime prevention are interdependent disciplines. IGRCFP certifications teach professionals how these systems operate together rather than in isolation."
        },
        {
            title: "Practical Professional Capability",
            icon: <CheckCircleIcon className="w-6 h-6 text-blue-900" />,
            description: "Courses focus on real-world scenarios, regulatory expectations, operational frameworks, and investigative techniques used by professionals in practice."
        },
        {
            title: "Global Relevance",
            icon: <UserGroupIcon className="w-6 h-6 text-blue-900" />,
            description: "IGRCFP programmes reflect international standards, cross-border regulatory environments, and global financial systems."
        }
    ];

    return (
        <section className="py-16 bg-white">
            <div className="max-w-7xl mx-auto px-6">

                {/* --- REASON BEHIND IT --- */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-16"
                >
                    <div className="relative inline-flex items-center mb-3">
                        <motion.span
                            initial={{ width: 0 }}
                            whileInView={{ width: 48 }}
                            transition={{ duration: 0.5, ease: "easeOut" }}
                            viewport={{ once: true }}
                            className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
                        />
                        <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                            REASON BEHIND IT
                        </span>
                    </div>

                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        WHY IGRCFP <span className="text-[#0A2463]">CERTIFICATIONS</span><br />
                        MATTER
                    </h2>

                    <p className="text-gray-700 mb-8">
                        Modern organisations operate within an increasingly complex risk environment shaped by:
                    </p>

                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                    >
                        {challenges.map((item, index) => (
                            <div key={index} className="border border-gray-300 rounded-lg p-6 text-center">
                                <div className="text-4xl mb-3">{item.icon}</div>
                                <h3 className="font-semibold text-gray-900">{item.title}</h3>
                            </div>
                        ))}
                    </motion.div>
                </motion.div>

                {/* --- CERTIFICATION PHILOSOPHY --- */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                >
                    <div className="relative inline-flex items-center mb-3">
                        <motion.span
                            initial={{ width: 0 }}
                            whileInView={{ width: 48 }}
                            transition={{ duration: 0.5, ease: "easeOut" }}
                            viewport={{ once: true }}
                            className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
                        />
                        <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                            CORE PRINCIPLES
                        </span>
                    </div>

                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-8">
                        <span className="text-[#0A2463]">CERTIFICATION</span> PHILOSOPHY
                    </h2>

                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="grid grid-cols-1 md:grid-cols-3 gap-8"
                    >
                        {principles.map((item, index) => (
                            <div
                                key={index}
                                className="bg-white border border-gray-200 rounded-xl p-6 shadow-lg shadow-gray-200/70"
                            >
                                <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                   {item.icon}
                                </div>
                                <h3 className="text-xl font-bold text-gray-900 mb-4">{item.title}</h3>
                                <p className="text-gray-700 leading-relaxed">{item.description}</p>
                            </div>
                        ))}
                    </motion.div>
                </motion.div>

            </div>
        </section>
    );
}