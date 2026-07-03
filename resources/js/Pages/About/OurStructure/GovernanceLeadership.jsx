import React from "react";
import { motion } from "framer-motion";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function GovernanceLeadership() {
    return (
        <section className="py-16 bg-gray-50">
            <div className="max-w-6xl mx-auto px-6">

                {/* Main Heading */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-8"
                >
                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900">
                        GOVERNANCE <span className="text-[#0A2463]">& LEADERSHIP</span>
                    </h2>
                </motion.div>

                {/* Content Cards */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="space-y-5"
                >
                    {/* Governance Structure */}
                    <div className="bg-white border border-gray-200 rounded-xl p-6 flex items-start gap-4">
                        <div className="flex-shrink-0 mt-1">
                            <svg className="w-7 h-7 text-[#0A2463]" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M4 22h16M4 18h16M6 2v16h12V2H6z" />
                            </svg>
                        </div>
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Governance Structure</h3>
                            <p className="text-gray-700 leading-relaxed">
                                IGRCFP is governed by the President, Advisory Board and guided by a professional standards framework that underpins the integrity of our qualifications, membership and designations.
                            </p>
                        </div>
                    </div>

                    {/* Core Principles */}
                    <div className="bg-white border border-gray-200 rounded-xl p-6 flex items-start gap-4">
                        <div className="flex-shrink-0 mt-1">
                            <svg className="w-7 h-7 text-[#0A2463]" fill="none" stroke="currentColor" strokeWidth={1.5} viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm0-14v8m-4-4h8" />
                            </svg>
                        </div>
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Core Principles</h3>
                            <p className="text-gray-700 leading-relaxed">
                                Our governance structure is designed to ensure that IGRCFP operates with full transparency, independence and accountability to its members and the wider professional community.
                            </p>
                        </div>
                    </div>

                    {/* Our Commitment */}
                    <div className="bg-white border border-gray-200 rounded-xl p-6 flex items-start gap-4 relative overflow-hidden">
                        <div className="absolute left-0 top-0 bottom-0 w-2 bg-[#0A2463] rounded-l-xl"></div>
                        <div className="pl-2">
                            <p className="text-gray-900 font-medium">
                                <span className="font-semibold">Our Commitment:</span> Every decision, qualification, and membership designation is backed by a governance framework that prioritizes professional integrity above all else.
                            </p>
                        </div>
                    </div>

                </motion.div>
            </div>
        </section>
    );
}