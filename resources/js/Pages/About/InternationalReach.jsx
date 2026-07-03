import React from "react";
import { motion } from "framer-motion";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function InternationalReach() {
    const regions = [
        {
            name: "United Kingdom",
            flag: "🇬🇧",
            description: "Our Primary operational regulatory base"
        },
        {
            name: "Nigeria & W.Africa",
            flag: "🇳🇬",
            description: "A major centre of GRC and financial crime activities"
        },
        {
            name: "East Africa",
            flag: "🇰🇪",
            description: "Including Kenya where financial crime prevention is a regulatory priority"
        },
        {
            name: "Ghana & ECOWAS",
            flag: "🇬🇭",
            description: "Growing compliance education demand"
        },
        {
            name: "The Caribbean",
            flag: "🌴",
            description: "Our Primary operational regulatory base"
        },
        {
            name: "Middle East & Gulf",
            flag: "🇦🇪",
            description: "Our Primary operational regulatory base"
        },
        {
            name: "Malta & EU Region",
            flag: "🇲🇹",
            description: "Our Primary operational regulatory base"
        },
        {
            name: "International",
            flag: "🌐",
            description: "Our Primary operational regulatory base"
        }
    ];

    return (
        <section className="py-16 bg-gray-50">
            <div className="max-w-7xl mx-auto px-6">

                {/* Header */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-10"
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

                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        OUR <span className="text-[#0A2463]">INTERNATIONAL</span><br />
                        REACH
                    </h2>

                    <p className="text-gray-700">
                        IGRCFP Operates across multiple jurisdictions, with particular focus on:
                    </p>
                </motion.div>

                {/* Regions Grid */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10"
                >
                    {regions.map((region, index) => (
                        <div
                            key={index}
                            className="bg-white rounded-xl border border-gray-200 p-5 shadow-sm"
                        >
                            <div className="flex items-center gap-3 mb-3">
                                <span className="text-2xl">{region.flag}</span>
                                <h3 className="font-semibold text-gray-900">
                                    {region.name}
                                </h3>
                            </div>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                {region.description}
                            </p>
                        </div>
                    ))}
                </motion.div>

                {/* Bottom Button */}
                <div className="flex justify-center">
                    <div className="bg-[#0A2463] text-white px-8 py-3 rounded-full shadow-md">
                        <span className="font-medium">Global presence across 4 continents</span>
                    </div>
                </div>

            </div>
        </section>
    );
}