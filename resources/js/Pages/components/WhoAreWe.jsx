import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";
import React from "react";

export default function WhoAreWe() {
    return ( 
        <div className="max-w-7xl mx-auto px-6">

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="flex flex-col"
                >
                    {/* HEADER */}
                    <div
                        className=" flex-col md:flex-row md:justify-between md:items-center mb-12"
                        data-aos="fade-up"
                    >
                        <div>
                            <div className="relative inline-flex items-center mb-3">
                                <span className="absolute left-0 top-1/2 w-16 h-px bg-gray-300 -z-10"></span>
                                <span className="text-sm tracking-widest text-gray-400 pl-20 uppercase">
                                    Who We Are
                                </span>
                            </div>

                            <h2 className="text-3xl xl:text-5xl font-bold text-slate-900 mb-6">
                                Know More About Us
                            </h2>
                        </div>
                    </div>
                   
                </motion.div>

                <motion.div
                    initial={{ opacity: 0, x: -30 }}
                    whileInView={{ opacity: 1, x: 0 }}
                    transition={{ duration: 0.5, ease: "easeOut" }}
                    viewport={{ once: true }}
                >
                    <p className="text-gray-600 mb-4">
                       IGRCFP is an independent professional body serving practitioners across governance, risk, compliance, anti-money laundering (AML), fraud prevention, anti-bribery and corruption, sanctions compliance, and financial crime investigation. Our members work across banking, insurance, asset management, legal services, the public sector, and regulated industries worldwide.
                    </p>
                     <p className="text-gray-600 mb-4">
                        We are practitioner-led. Our programmes, designations and standards are shaped by professionals who have spent their careers on the front lines of financial crime prevention and regulatory compliance not by academics alone or by commercial interests. This gives everything we produce a practical credibility that members and employers rely upon.
                    </p>

                    <Link
                        href="/about-us"
                        className="inline-flex mt-6 px-6 py-3 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition"
                    >
                        Learn More →
                    </Link>
                </motion.div>
            </div>

            
        </div>
    );
}
