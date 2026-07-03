import React from "react";
import { motion } from "framer-motion";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function PurposeValuesOffer() {
    const values = [
        { title: "INTEGRITY" },
        { title: "EXCELLENCE" },
        { title: "INDEPENDENCE" },
        { title: "GLOBAL REACH" },
        { title: "INCLUSION" },
        { title: "COLLABORATION" },
    ];

    return (
        <section className="py-16 bg-white">
            <div className="max-w-7xl mx-auto px-6 md:px-8">

                {/* -------------------------- OUR PURPOSE -------------------------- */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-16"
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
                            WHO ARE WE?
                        </span>
                    </div>

                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-5">
                        OUR <span className="text-[#0A2463]">PURPOSE</span>
                    </h2>

                    <p className="text-gray-700 leading-relaxed max-w-7xl">
                        IGRCFP the Institute of Governance, Risk, Compliance and Financial Crime Prevention is a professional body committed to raising standards among practitioners working in governance, risk management, regulatory compliance, and the prevention of financial crime. We exist because financial crime is a global threat, and the professionals who stand against it deserve a dedicated home: a body that champions their expertise, develops their capabilities, and gives their work the professional recognition it deserves.
                    </p>
                </motion.div>

                {/* -------------------------- OUR VALUES -------------------------- */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-16"
                >
                    <div className="flex flex-col lg:flex-row gap-10 lg:gap-16 items-start">
                        
                        {/* LEFT: Text Content */}
                        <div className="w-full lg:w-1/2 order-2 lg:order-1">
                            <div className="relative inline-flex items-center mb-3">
                                <motion.span
                                    initial={{ width: 0 }}
                                    whileInView={{ width: 48 }}
                                    transition={{ duration: 0.5, ease: "easeOut" }}
                                    viewport={{ once: true }}
                                    className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
                                />
                                <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                                    WHO ARE WE?
                                </span>
                            </div>

                            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-5">
                                OUR <span className="text-[#0A2463]">VALUES</span>
                            </h2>

                            <p className="text-gray-700 leading-relaxed mb-4">
                                Our values are the foundation of everything we do. We operate with integrity, ensuring honesty, transparency, and accountability in every action. We pursue excellence by delivering high-quality solutions and continuously striving for improvement. Our independence enables us to provide objective insights and make decisions based on expertise and sound judgment.
                            </p>

                            <p className="text-gray-700 leading-relaxed">
                                With a global reach, we embrace diverse perspectives and connect opportunities across borders, while our commitment to collaboration fosters strong partnerships, teamwork, and shared success for our clients, communities, and stakeholders.
                            </p>
                        </div>

                        {/* RIGHT: Values Grid */}
                        <div className="w-full lg:w-1/2 order-1 lg:order-2">
                            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                {values.map((item, index) => (
                                    <motion.div
                                        key={index}
                                        variants={scaleIn}
                                        initial="hidden"
                                        whileInView="visible"
                                        viewport={{ once: true }}
                                        transition={{ delay: index * 0.1 }}
                                        className="border border-gray-200 rounded-md p-4 text-center hover:border-[#0A2463]/30 hover:shadow-sm transition-all duration-300"
                                    >
                                        <div className="flex justify-center mb-2">
                                            <svg className="w-5 h-5 text-[#0A2463]" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 className="text-xs md:text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                            {item.title}
                                        </h3>
                                    </motion.div>
                                ))}
                            </div>
                        </div>

                    </div>
                </motion.div>

                {/* -------------------------- WHAT WE OFFER -------------------------- */}
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
                            WHO ARE WE?
                        </span>
                    </div>

                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-8">
                        WHAT WE <span className="text-[#0A2463]">OFFER</span>
                    </h2>

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {/* Qualifications Card */}
                        <motion.div
                            variants={scaleIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="border border-gray-200 rounded-md p-5 hover:border-[#0A2463]/30 hover:shadow-md transition-all duration-300"
                        >
                            <div className="flex items-center gap-3 mb-3">
                                <svg className="w-6 h-6 text-[#0A2463]" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                                    <path d="M4 17l6-6 4 4 6-6" />
                                </svg>
                                <h3 className="text-lg md:text-xl font-bold text-gray-900 uppercase">
                                    PROFESSIONAL QUALIFICATIONS
                                </h3>
                            </div>
                            <p className="text-gray-700 text-sm leading-relaxed">
                                IGRCFP offers a structured suite of professional qualifications from foundational to advanced level, covering: Governance and Corporate Accountability, Risk Management (including Basel III and Operational Risk), Regulatory Compliance and Financial Regulation, Anti-Money Laundering (AML) and Know Your Customer (KYC), Financial Crime Prevention and Investigation, Fraud Risk Management, Anti-Bribery and Corruption (ABC), and Sanctions Compliance.
                            </p>
                        </motion.div>

                        {/* Membership Card */}
                        <motion.div
                            variants={scaleIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="border border-gray-200 rounded-md p-5 hover:border-[#0A2463]/30 hover:shadow-md transition-all duration-300"
                        >
                            <div className="flex items-center gap-3 mb-3">
                                <svg className="w-6 h-6 text-[#0A2463]" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <h3 className="text-lg md:text-xl font-bold text-gray-900 uppercase">
                                    PROFESSIONAL MEMBERSHIP
                                </h3>
                            </div>
                            <p className="text-gray-700 text-sm leading-relaxed">
                                IGRCFP membership provides practitioners with: A recognised professional designation (AIGRCFP, MIGRCFP or FIGRCFP), Access to a global community of GRC and financial crime professionals, Continuing Professional Development (CPD) tools and resources, Thought leadership, publications and regulatory updates, Networking events, webinars and conferences, and Career support and professional recognition.
                            </p>
                        </motion.div>
                    </div>
                </motion.div>

            </div>
        </section>
    );
}