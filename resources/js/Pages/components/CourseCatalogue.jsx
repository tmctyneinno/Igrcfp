import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";
import React from "react";

export default function CourseCatalogue({ courseCategories }) {
    return (
        <section className="bg-gray-50 py-16">
            <div className="max-w-7xl mx-auto px-6">
                {/* Header */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10"
                >
                    <div>
                        <div className="relative inline-flex items-center mb-3">
                            <span className="absolute left-0 top-1/2 w-12 h-px bg-gray-300 -z-10"></span>
                            <span className="text-sm tracking-widest text-gray-500 pl-16 uppercase font-medium">
                                Course Catalog
                            </span>
                        </div>
                        <h2 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                            PROFESSIONAL COURSES IN <span className="text-[#0A2463]">GRC, COMPLIANCE &</span>
                            <br />
                            <span className="text-[#0A2463]">FINANCIAL CRIME PREVENTION</span>
                        </h2>
                    </div>

                    <Link
                        href={route('course.catalog.index')}
                        className="mt-6 md:mt-0 bg-[#0A2463] text-white px-6 py-2.5 rounded-full font-medium shadow-md hover:bg-[#081E52] hover:shadow-lg transition-all duration-200 whitespace-nowrap"
                    >
                        View full course catalog
                    </Link>
                </motion.div>

                {/* Categories Grid */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    {courseCategories.map((category, index) => (
                        <motion.div
                            key={index}
                            variants={scaleIn}
                            transition={{ delay: index * 0.1 }}
                        >
                            <Link
                                href={route('course.catalog.index')}
                                className="block bg-white rounded-lg border border-gray-200 p-6 hover:shadow-sm transition-shadow duration-200"
                            >
                                <div className="flex items-start justify-between mb-4">
                                    <span className="text-2xl text-gray-700">
                                        {category.icon || "🏛️"}
                                    </span>
                                    <span className="bg-gray-200 text-gray-700 text-xs font-medium px-3 py-1 rounded-md">
                                        {category.count} Courses
                                    </span>
                                </div>

                                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                    {category.name}
                                </h3>

                                <p className="text-sm text-gray-500 mb-4">
                                    e.g. {category.sampleCourse}
                                </p>

                                <span className="text-sm text-[#0A2463] font-medium hover:underline">
                                    Explore Courses →
                                </span>
                            </Link>
                        </motion.div>
                    ))}
                </motion.div>
            </div>
        </section>
    );
}