import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function Certification() {
    return (
       
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div 
                        className="flex justify-between items-center mb-12"
                        data-aos="fade-up"
                        data-aos-duration="1400"
                    >
                        <div>
                            <span className="text-sm uppercase tracking-widest text-gray-400">
                               Certifications & Trainings
                            </span>
                            <h2 className="text-3xl font-bold text-gray-900">Our Programmes</h2>
                            <p className="text-gray-600 mt-2">
                                Our professional certifications are designed to equip individuals 
                                and institutions with  globally relevant skills to tackle <br/>financial 
                                crime and compliance risks.
                            </p>
                        </div>
                        <Link href="/courses" className="text-blue-950 hover:text-blue-700 font-semibold">
                            View All Courses →
                        </Link>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {[1, 2, 3].map((course, index) => (
                            <div 
                                key={course}
                                className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 transform hover:-translate-y-2"
                                data-aos="fade-up"
                                data-aos-delay={index * 200}
                                data-aos-duration="1400"
                            >
                                <div className="h-48 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                                <div className="p-6">
                                    <div className="flex items-center justify-between mb-4">
                                        <span className="bg-blue-100 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full">
                                            {course === 1 ? 'Beginner' : course === 2 ? 'Intermediate' : 'Advanced'}
                                        </span>
                                        <span className="text-gray-500">4.8 ★</span>
                                    </div>
                                    <h3 className="text-xl font-semibold mb-2">Course Title {course}</h3>
                                    <p className="text-gray-600 mb-4">
                                        Learn essential skills from industry experts in this comprehensive course.
                                    </p>
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center">
                                            <div className="w-8 h-8 bg-gray-300 rounded-full"></div>
                                            <span className="ml-2 text-sm text-gray-600">John Doe</span>
                                        </div>
                                        <span className="text-blue-600 font-semibold">$99</span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
           

    );
}
