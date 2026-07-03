import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, fadeRight } from "@/utils/motionPresets";
import React from "react";

export default function WhoAreWe() {
    const features = [
        {
            title: "Professional Standards",
            description: "Setting global benchmarks for GRC practitioners",
            icon: "✓"
        },
        {
            title: "Accredited Education",
            description: "Rigorous certifications recognised internationally",
            icon: "✓"
        },
        {
            title: "Global Reach",
            description: "Members across 50+ countries on 4 continents",
            icon: "✓"
        },
        {
            title: "Community & Advocacy",
            description: "Championing the profession at every level",
            icon: "✓"
        },
        {
            title: "Research & Intelligence",
            description: "Thought leadership on emerging risks and threats",
            icon: "✓"
        }
    ];

    return (
        <section className="py-16 bg-white">
            <div className="max-w-7xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
                    
                    {/* Left Column: Features List */}
                    <motion.div
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="w-full"
                    >
                        <div className="border border-slate-200 rounded-lg overflow-hidden">
                            {features.map((item, index) => (
                                <div 
                                    key={index}
                                    className={`flex items-start gap-4 p-4 ${
                                        index !== features.length - 1 ? "border-b border-slate-200" : ""
                                    }`}
                                >
                                    <div className="w-10 h-10 flex-shrink-0 border border-slate-300 rounded flex items-center justify-center text-slate-600 font-medium">
                                        {item.icon}
                                    </div>
                                    <div>
                                        <h3 className="text-base font-semibold text-slate-900">
                                            {item.title}
                                        </h3>
                                        <p className="text-sm text-slate-500 mt-1">
                                            {item.description}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </motion.div>

                    {/* Right Column: Text Content */}
                    <motion.div
                        variants={fadeRight}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="w-full"
                    >
                        {/* Heading Section */}
                        <div className="mb-6">
                            <div className="relative inline-flex items-center mb-3">
                                <span className="absolute left-0 top-1/2 w-12 h-px bg-slate-300 -z-10"></span>
                                <span className="text-sm tracking-widest text-slate-500 pl-16 uppercase font-medium">
                                    Who Are We?
                                </span>
                            </div>
                            <h2 className="text-4xl xl:text-5xl font-bold text-slate-900 leading-tight">
                                KNOW MORE<br />
                                <span className="text-[#0A2463]">ABOUT US</span>
                            </h2>
                        </div>

                        <div className="space-y-4 text-slate-600 leading-relaxed">
                            <p>
                                IGRCFP is an independent professional body serving practitioners across governance, risk, compliance, anti-money laundering (AML), fraud prevention, anti-bribery and corruption, sanctions compliance, and financial crime investigation. Our members work across banking, insurance, asset management, legal services, the public sector, and regulated industries worldwide.
                            </p>
                            <p>
                                We are practitioner-led. Our programmes, designations and standards are shaped by professionals who have spent their careers on the front lines of financial crime prevention and regulatory compliance not by academics alone or by commercial interests. This gives everything we produce a practical credibility that members and employers rely upon.
                            </p>
                        </div>

                        <Link
                            href={ route('welcome-to-igrcfp')}
                            className="inline-block mt-8 px-7 py-2.5 bg-[#0A2463] text-white rounded-full font-medium hover:bg-[#081E52] transition-all duration-200 shadow-sm hover:shadow"
                        >
                            Learn more
                        </Link>
                    </motion.div>

                </div>
            </div>
        </section>
    );
}