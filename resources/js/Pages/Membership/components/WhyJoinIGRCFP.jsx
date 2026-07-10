import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function WhyJoinIGRCFP() {
    const benefits = [
        {
            title: "Network Opportunities",
            description: "Connect with a global network of compliance professionals"
        },
        {
            title: "Get Relevant Updates",
            description: "Access exclusive research, insights, and regulatory updates"
        },
        {
            title: "Member Benefits",
            description: "Gain access to IGRCFP's mentor-mentee programme, connecting experienced professionals with emerging talents"
        },
        {
            title: "Exclusive Discount",
            description: "Receive discounts on certifications, events and publications"
        },
        {
            title: "Recognition",
            description: "Gain recognition with post nominals (e.g., A.IGRCFP, M.IGRCFP, F.IGRCFP)."
        }
    ];

    return (
        <section className="py-16 bg-white">
            <div className="max-w-6xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                    {/* --- LEFT TEXT CONTENT --- */}
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
                                WHY JOIN?
                            </span>
                        </div>

                        {/* Heading */}
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            WHY <span className="text-[#0A2463]">JOIN IGRCFP</span>
                        </h2>

                        {/* Description Text */}
                        <div className="text-gray-700 leading-relaxed space-y-4 mb-8">
                            <p>
                                IGRCFP credentials: from specialist certificates to advanced diplomas and the flagship CFCS designation
                            </p>
                            <p>
                                The Institute of GRC & Financial Crime Prevention (IGRCFP) offers a portfolio of professional certifications designed to develop advanced capability in governance, risk management, regulatory compliance, and financial crime prevention.
                            </p>
                            <p>
                                IGRCFP credentials: from specialist certificates to advanced diplomas and the flagship CFCS designation
                            </p>
                            <p>
                                The Institute of GRC & Financial Crime Prevention (IGRCFP) offers a portfolio of professional certifications designed
                            </p>
                        </div>

                        {/* CTA Button */}
                        <Link
                            href="/login"
                            className="inline-block bg-[#0A2463] hover:bg-[#081E52] text-white font-medium px-8 py-3 rounded-md transition-colors"
                        >
                            Become a Member
                        </Link>
                    </motion.div>

                    {/* --- RIGHT IMAGE GRID --- */}
                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="relative min-h-[350px] hidden lg:block"
                    >
                        {/* You can replace these src with your actual image URLs */}
                        <img
                            src="/assets/images/membership_1.jpg"
                            alt="Library of books"
                            className="absolute top-0 left-0 w-40 h-48 object-cover rounded-lg shadow-md"
                        />
                        <img
                            src="/assets/images/membership_2.jpg"
                            alt="Professionals working"
                            className="absolute top-4 right-12 w-44 h-40 object-cover rounded-lg shadow-md"
                        />
                        <img
                            src="/assets/images/membership_3.jpg"
                            alt="Graduation cap"
                            className="absolute top-24 right-40 w-36 h-44 object-cover rounded-lg shadow-md"
                        />
                        <img
                            src="/assets/images/membership_4.jpg"
                            alt="Justice statue"
                            className="absolute bottom-0 left-8 w-40 h-48 object-cover rounded-lg shadow-md"
                        />
                        <img
                            src="/assets/images/membership_5.jpg"
                            alt="Diploma and certificate"
                            className="absolute bottom-4 right-16 w-36 h-40 object-cover rounded-lg shadow-md"
                        />
                    </motion.div>

                </div>

                {/* --- BENEFITS CARDS --- */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    transition={{ delay: 0.2 }}
                    className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-16"
                >
                    {benefits.map((item, index) => (
                        <div
                            key={index}
                            className="border border-gray-200 rounded-lg p-6 text-center hover:shadow-sm transition-shadow"
                        >
                            <div className="w-10 h-10 mx-auto mb-5 border border-gray-300 rounded-md"></div>
                            <h3 className="text-lg font-bold text-gray-900 mb-3">{item.title}</h3>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                {item.description}
                            </p>
                        </div>
                    ))}
                </motion.div>

            </div>
        </section>
    );
}