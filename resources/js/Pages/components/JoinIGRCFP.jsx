import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function JoinIGRCFP({ benefits = [] }) {
    // Default data if no props are passed
    const defaultBenefits = [
        {
            title: "Network Opportunities",
            description: "Connect with a global network of compliance professionals"
        },
        {
            title: "Get Relevant Updates",
            description: "Access exclusive research, insights and regulatory updates"
        },
        {
            title: "Member Benefits",
            description: "Gain access to member-only programmes connecting experienced professionals with you"
        },
        {
            title: "Exclusive Discount",
            description: "Receive discounts on publications, certifications and events"
        },
        {
            title: "Recognition",
            description: "Gain recognition with post-nominals (e.g. MIGRCFP, FIGRCFP)"
        },
        {
            title: "Network Opportunities",
            description: "Connect with a global network of compliance professionals"
        }
    ];

    const benefitsData = benefits.length > 0 ? benefits : defaultBenefits;

    return (
        <section className="bg-white py-16">
            <div className="max-w-7xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    
                    {/* Left Content */}
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
                                Why Join?
                            </span>
                        </div>

                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                            WHY SHOULD YOU <br />
                            <span className="text-[#0A2463]">JOIN IGRCFP</span>
                        </h2>

                        <p className="text-gray-600 leading-relaxed mb-6">
                            At IGRCFP, we go beyond certification we build careers and shape leaders. By joining us, you become part of a global network of governance, risk, compliance, and financial crime professionals who are driving change across industries. Membership gives you access to exclusive insights, research, and training, alongside opportunities for professional recognition, CPD credits, and the use of respected post-nominals. Whether you are in banking, fintech, insurance, or regulation, IGRCFP equips you with the knowledge, tools, and connections to stay ahead, stay compliant, and stand out as a trusted expert.
                        </p>

                        <Link
                            href="/membership"
                            className="inline-block bg-[#0A2463] text-white px-6 py-2.5 rounded-md font-medium hover:bg-[#081E52] transition-colors"
                        >
                            Learn more
                        </Link>
                    </motion.div>

                    {/* Right Benefits Grid */}
                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="grid grid-cols-2 md:grid-cols-3 gap-4"
                    >
                        {benefitsData.map((item, index) => (
                            <div
                                key={index}
                                className="bg-gray-100 rounded-md p-4 shadow-sm hover:shadow transition-shadow duration-200"
                            >
                                <h3 className="text-sm font-semibold text-gray-900 mb-2">
                                    {item.title}
                                </h3>
                                <p className="text-xs text-gray-600 leading-relaxed">
                                    {item.description}
                                </p>
                            </div>
                        ))}
                    </motion.div>

                </div>
            </div>
        </section>
    );
}