import React from "react";
import { motion } from "framer-motion";
import { fadeLeft } from "@/utils/motionPresets";

export default function BoardOfTrustees() {
    return (
        <section className="bg-white py-16">
            <div className="max-w-6xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">

                    {/* --- MAIN CONTENT --- */}
                    <motion.div
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="lg:col-span-9"
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
                                OUR STRUCTURE
                            </span>
                        </div>

                        {/* Heading */}
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            OUR <span className="text-[#0A2463]">BOARD OF TRUSTEES</span>
                        </h2>

                        {/* Description */}
                        <p className="text-gray-700 leading-relaxed mb-8">
                            The Board of Trustees serves as the strategic oversight body of IGRCFP and is responsible for guiding the long-term direction of the Institute. The board ensures that the IGRCFP remains aligned with its mission and vision while upholding ethical standards.
                        </p>

                        {/* Key Responsibilities */}
                        <div className="relative inline-flex items-center mb-3">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 48 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
                            />
                            <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                                KEY RESPONSIBILITIES
                            </span>
                        </div>

                        <ul className="list-disc list-inside text-gray-700 space-y-2 pl-2">
                            <li>Establishing the strategic vision and mission of IGRCFP.</li>
                            <li>Overseeing governance policies and ensuring sustainability.</li>
                            <li>Approving annual budgets and financial reports.</li>
                            <li>Appointing council members and leadership succession.</li>
                            <li>Ensuring compliance with Local and international standards.</li>
                        </ul>
                    </motion.div>

                    {/* --- RIGHT SIDEBAR --- */}
                    <motion.aside
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        transition={{ delay: 0.2 }}
                        className="lg:col-span-3"
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
                                PEOPLE
                            </span>
                        </div>

                        <ul className="space-y-3 text-gray-900">
                            <li className="font-medium">Board of Trustees</li>
                            <li>The Governing Council</li>
                            <li>Advisory Committees</li>
                        </ul>
                    </motion.aside>

                </div>
            </div>
        </section>
    );
}