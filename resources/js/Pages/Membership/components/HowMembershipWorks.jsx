import React from "react";
import { motion } from "framer-motion";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function HowMembershipWorks() {
    const steps = [
        "Choose a membership category that suits you",
        "Complete the membership application form",
        "Pay your annual membership fee securely online",
        "Start accessing your membership benefits instantly"
    ];

    return (
        <section className="py-16 bg-white">
            <div className="max-w-6xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                    {/* --- LEFT CONTENT --- */}
                    <motion.div
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                    >
                        {/* Section Label */}
                        <div className="relative inline-flex items-center mb-4">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 48 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
                            />
                            <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                                MEMBERSHIP
                            </span>
                        </div>

                        {/* Heading */}
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-8">
                            HOW MEMBERSHIP <span className="text-[#0A2463]">WORKS</span>
                        </h2>

                        {/* Steps List */}
                        <ol className="space-y-6">
                            {steps.map((step, index) => (
                                <li key={index} className="flex items-center gap-4">
                                    <span className="flex items-center justify-center w-10 h-10 rounded-full border border-[#0A2463] text-[#0A2463] font-semibold text-lg">
                                        {index + 1}
                                    </span>
                                    <span className="text-gray-700 text-lg">{step}</span>
                                </li>
                            ))}
                        </ol>
                    </motion.div>

                    {/* --- RIGHT IMAGE --- */}
                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="w-full"
                    >
                        <img
                            src="/assets/images/membership_6.jpg"
                            alt="Community of professionals"
                            className="w-full h-auto rounded-2xl object-cover shadow-sm"
                        />
                    </motion.div>

                </div>
            </div>
        </section>
    );
}