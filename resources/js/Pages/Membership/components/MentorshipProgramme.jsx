import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function MentorshipProgramme() {
    const steps = [
        { label: "Join IGRCFP" },
        { label: "Apply as Mentor/Mentee" },
        { label: "Get Matched" },
        { label: "Start Mentorship" }
    ];

    return (
        <section className="py-16 bg-white">
            <div className="max-w-6xl mx-auto px-6">

                {/* --- HEADER --- */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-12"
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
                            MENTORSHIP PROGRAMMES
                        </span>
                    </div>

                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        KNOW MORE <span className="text-[#0A2463]">ABOUT US</span>
                    </h2>

                    <p className="text-gray-700 leading-relaxed max-w-4xl">
                        Take your professional journey to the next level by joining IGRCFP. Membership gives you exclusive access to a global network of governance, risk, compliance, and financial crime professionals, cutting-edge research, specialized training, and career-boosting opportunities.
                    </p>
                </motion.div>

                {/* --- PROCESS TIMELINE --- */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="relative flex justify-between items-center max-w-4xl mx-auto mb-16 px-4"
                >
                    {/* Connecting line */}
                    <div className="absolute top-5 left-0 w-full h-px bg-gray-400 z-0"></div>

                    {steps.map((step, index) => (
                        <div key={index} className="relative z-10 flex flex-col items-center text-center">
                            <div className="w-10 h-10 rounded-full bg-white border border-gray-400 flex items-center justify-center mb-3 shadow-sm">
                                <svg className="w-4 h-4 text-[#0A2463]" fill="none" stroke="currentColor" strokeWidth={2} viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span className="text-sm font-medium text-gray-800">{step.label}</span>
                        </div>
                    ))}
                </motion.div>

                {/* --- TWO COLUMN CARDS --- */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 lg:grid-cols-2 gap-8"
                >
                    {/* For Mentees */}
                    <div className="border border-gray-200 rounded-2xl p-8">
                        <div className="flex justify-center mb-6">
                            <img
                                src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Mentee discussion"
                                className="w-28 h-24 object-cover rounded-lg shadow-sm"
                            />
                        </div>
                        <h3 className="text-xl font-bold text-[#0A2463] text-center mb-6">For Mentees</h3>

                        <h4 className="font-semibold text-gray-900 mb-3">Benefits</h4>
                        <ul className="list-disc pl-5 text-gray-700 space-y-2 mb-4">
                            <li>Learn from experienced Professionals</li>
                            <li>Gain career guidance</li>
                            <li>Build your network and confidence</li>
                        </ul>
                        <p className="text-xs text-gray-500 italic mb-6">
                            Eligibility: Available to students, affiliate and associate members
                        </p>

                        <Link
                            href="/login"
                            className="inline-block bg-[#0A2463] hover:bg-[#081E52] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Join as Mentee
                        </Link>
                    </div>

                    {/* For Mentors */}
                    <div className="border border-gray-200 rounded-2xl p-8">
                        <div className="flex justify-center mb-6">
                            <img
                                src="/assets/images/innerpage/gallery/mentor.png"
                                // src="https://images.unsplash.com/photo-1556740755-069a40165b40?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80"
                                alt="Mentor meeting"
                                className="w-28 h-24 object-cover rounded-lg shadow-sm"
                            />
                        </div>
                        <h3 className="text-xl font-bold text-[#0A2463] text-center mb-6">For Mentors</h3>

                        <h4 className="font-semibold text-gray-900 mb-3">Benefits</h4>
                        <ul className="list-disc pl-5 text-gray-700 space-y-2 mb-4">
                            <li>Learn from experienced Professionals</li>
                            <li>Gain career guidance</li>
                            <li>Build your network and confidence</li>
                        </ul>
                        <p className="text-xs text-gray-500 italic mb-6">
                            Eligibility: Available to students, affiliate and associate members
                        </p>

                        <Link
                            href="/login"
                            className="inline-block bg-[#0A2463] hover:bg-[#081E52] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Join as Mentor
                        </Link>
                    </div>
                </motion.div>

            </div>
        </section>
    );
}