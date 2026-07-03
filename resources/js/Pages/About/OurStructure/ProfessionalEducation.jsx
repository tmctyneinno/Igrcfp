import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function ProfessionalEducation() {
    const pathways = [
        { 
            label: "Governance, Risk & Compliance",
            route: "programmes.grc"
        },
        { 
            label: "Financial Crime Prevention",
            route: "programmes.financial-crime"
        },
        { 
            label: "Crypto & Digital Assets",
            route: "programmes.crypto"
        },
        { 
            label: "Cybersecurity & Digital Risk",
            route: "programmes.cybersecurity"
        },
        { 
            label: "AI & Emerging Technology",
            route: "programmes.ai"
        }
    ];

    return (
        <section className="py-16 bg-white">
            <div className="max-w-6xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                    {/* --- LEFT CONTENT --- */}
                    <motion.div
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                    >
                        {/* Section Label */}
                        <div className="relative inline-flex items-center mb-3">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 48 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
                            />
                            <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                                WHY IGRCFP
                            </span>
                        </div>

                        {/* Heading */}
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                            COMPREHENSIVE <span className="text-[#0A2463] pr-1">PROFESSIONAL </span>
                             EDUCATION
                        </h2>

                        {/* Main Description */}
                        <p className="text-gray-700 leading-relaxed mb-5">
                            IGRCFP delivers advanced professional programmes designed for today's complex regulatory, digital, and financial crime landscape. Our courses sit at the intersection of regulation, risk, technology, and financial crime prevention.
                        </p>

                        <p className="text-gray-700 leading-relaxed mb-6">
                            All programmes are framework-led, practitioner-focused, and globally relevant, ensuring professionals are equipped to handle emerging challenges in their respective fields.
                        </p>

                        {/* Key Points */}
                        <div className="space-y-3 text-sm">
                            <p>
                                <span className="font-semibold text-[#0A2463]">Core Programme Pathways</span><br />
                                Five specialized pathways covering GRC, financial crime, crypto, cybersecurity, and AI governance
                            </p>
                            <p>
                                <span className="font-semibold text-[#0A2463]">Practical Focus</span><br />
                                Real-world case studies and implementation frameworks.
                            </p>
                            <p>
                                <span className="font-semibold text-[#0A2463]">Global Recognition</span><br />
                                Professional certifications aligned with international standards
                            </p>
                            <p className="mt-4">
                                <Link href={ route('programmes')} className="text-[#0A2463] font-medium hover:underline">
                                    Explore Our Programmes →
                                </Link>
                            </p>
                        </div>
                    </motion.div>

                    {/* --- RIGHT PATHWAYS PANEL --- */}
                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="w-full max-w-lg ml-auto"
                    >
                        <div className="border border-gray-200 rounded-2xl p-6">
                            <h3 className="text-xl font-bold text-[#0A2463] mb-6">OUR PROGRAMME PATHWAYS</h3>

                            <ul className="space-y-3 mb-6">
                                {pathways.map((item, index) => (
                                    <li key={index}>
                                        <Link
                                            href={route(item.route)}
                                            className="flex items-center justify-between w-full px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                                        >
                                            <div className="flex items-center gap-3">
                                                <span className="w-2 h-2 rounded-full bg-gray-700"></span>
                                                <span className="text-gray-800 font-medium">{item.label}</span>
                                            </div>
                                            <svg className="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </Link>
                                    </li>
                                ))}
                            </ul>

                            <Link
                                href={route('course.catalog.index')}
                                className="block w-full text-center bg-[#0A2463] hover:bg-[#081E52] text-white font-medium py-3 rounded-lg transition-colors"
                            >
                                View Complete Course Catalog
                            </Link>
                        </div>
                    </motion.div>

                </div>
            </div>
        </section>
    );
}