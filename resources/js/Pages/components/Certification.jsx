import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import React from "react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

 
export default function Certification({ courses = [] }) {
    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {/* HEADER */}
            <div
                className="flex flex-col md:flex-row md:justify-between md:items-center mb-12"
                data-aos="fade-up"
            >      
                <div>
                    <div className="relative inline-flex items-center mb-3">
                        <motion.span
                            initial={{ width: 0 }}
                            whileInView={{ width: 64 }}
                            transition={{ duration: 0.5, ease: "easeOut" }}
                            viewport={{ once: true }}
                            className="absolute left-0 top-1/2 h-px bg-gray-300"
                        />
                        <span className="text-sm tracking-widest text-gray-400 pl-20 uppercase">
                            Certifications & Trainings
                        </span>
                    </div>
                    <h2 className="text-3xl font-bold text-gray-900 mt-2">
                        Our Programmes
                    </h2>
                    <p className="text-gray-600 mt-3 max-w-2xl">
                        Our professional certifications are designed to equip individuals
                        and institutions with globally relevant skills to tackle financial
                        crime and compliance risks.
                    </p>
                </div>

                <Link
                    href="/courses"
                    className="mt-6 md:mt-0 text-blue-950 font-semibold hover:text-blue-700 transition"
                >
                    View All Courses →
                </Link>
            </div>
 
            {/* COURSES */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                {courses.length > 0 ? (
                    courses.map((course, index) => (
                        <div
                            key={course.id}
                            className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2"
                            data-aos="fade-up"
                            data-aos-delay={index * 150}
                        >
                            {/* IMAGE */}
                            <div className="h-48 overflow-hidden">
                                <img
                                    src={course.image_url || '/fallback-image.png'}
                                    alt={course.title} 
                                    className="w-full h-full object-cover"
                                />
                            </div>

                            {/* CONTENT */}
                            <div className="p-2">
                                <Link href={`/courses/${course.id}`} >
                                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                    {course.title}
                                </h3>
                                </Link>
                                <p className="text-gray-600 text-sm mb-4">
                                {course.description.length > 400
                                    ? `${course.description.slice(0, 50)}...`
                                    : course.description
                                }
                                </p>


                                <div className="flex items-center justify-start">
                                    <Link
                                        href={`/courses/${course.id}`} // assuming you have a course detail page
                                        className="text-blue-950 font-bold hover:underline transition duration-200"
                                    >
                                        Learn More →
                                    </Link>
                                </div>

                            </div>
                        </div>
                    ))
                ) : (
                    <p className="col-span-3 text-center text-gray-500">
                        No courses available at the moment.
                    </p>
                )}
            </div>
        </div>
    );
}
