import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { fadeLeft, scaleIn } from '@/utils/motionPresets';

export default function Certification({ courses }) {
    // Safe courses initialization
    const coursesData = Array.isArray(courses) ? courses : []; 
  
    // Format price safely
    const formatPrice = (price) => {
        if (!price && price !== 0) return null;
        const numPrice = parseFloat(price); 
        return isNaN(numPrice) ? null : `$${numPrice.toFixed(2)}`;
    }; 

    // Check if course has discount
    const hasDiscount = (course) => {
        if (!course?.discount_price || !course?.price) return false;
        const price = parseFloat(course.price);
        const discountPrice = parseFloat(course.discount_price);
        return !isNaN(price) && !isNaN(discountPrice) && discountPrice < price;
    };
    
    const getCleanDescription = (course) => {
        const text = course?.short_description || course?.description;
        if (!text) return 'No description available';
        
        const cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
        return cleanText.length > 90 ? cleanText.substring(0, 90) + '...' : cleanText;
    };

    return (
         <section className="bg-gray-50 py-16 overflow-hidden">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* HEADER */}
                <motion.div 
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="flex flex-col md:flex-row md:justify-between md:items-center mb-10"
                >
                    <div>
                        <div className="relative inline-flex items-center mb-3">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 48 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="absolute left-0 top-1/2 h-px bg-gray-400"
                            />
                            <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                                Certifications & Trainings
                            </span>
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900">
                            OUR <span className="text-[#0A2463]">PROGRAMMES</span>
                        </h2>
                    </div>

                    <Link
                        href={route('igrcfp.certificates.index')}
                        className="mt-6 md:mt-0 bg-[#0A2463] text-white px-5 py-2 rounded-full text-sm font-medium shadow hover:bg-[#081E52] transition-all duration-200"
                    >
                        View all courses
                    </Link>
                </motion.div>

                {/* COURSES GRID */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    {coursesData.length > 0 ? (
                        coursesData.map((course, index) => { 
                            const price = parseFloat(course?.price || 0);
                            const discountPrice = parseFloat(course?.discount_price || 0);
                            const hasDisc = hasDiscount(course);
                            const discountPercentage = hasDisc && price > 0 
                                ? Math.round(((price - discountPrice) / price) * 100) 
                                : 0;

                            return (
                                <motion.div 
                                    key={course?.id || index}
                                    variants={scaleIn}
                                    transition={{ delay: index * 0.1 }}
                                    className="relative rounded-3xl overflow-hidden h-[420px] group"
                                > 
                                    {/* Background Image + Overlay */}
                                    <div className="absolute inset-0 w-full h-full">
                                        <img
                                            src={course?.image_url || '/images/fallback-course.jpg'}
                                            alt={course?.title || 'Course image'}
                                            className="w-full h-full object-cover"
                                            onError={(e) => {
                                                e.target.src = '/images/fallback-course.jpg';
                                            }}
                                        />
                                        <div className="absolute inset-0 bg-black/55"></div>
                                    </div>

                                    {/* Content */}
                                    <div className="relative z-10 h-full flex flex-col justify-end p-3 text-white">
                                        <h3 className="text-lg font-bold leading-tight mb-2">
                                            {course?.title || 'Untitled Course'}
                                        </h3>
                                        
                                        <p className="text-sm text-gray-100 mb-4 leading-relaxed">
                                            {getCleanDescription(course)}
                                        </p>

                                        {/* Level & Duration */}
                                        <div className="flex items-center justify-between mb-5 text-xs">
                                            <span className="bg-white/20 px-2.5 py-0.5 rounded-sm">
                                                {course?.level || 'Advanced'}
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <span>ⓘ</span> {course?.duration || '4 - 6 Months'}
                                            </span>
                                        </div>

                                        {/* Price & Button */}
                                        <div className="flex items-center justify-between">
                                            <div>
                                                {price > 0 ? (
                                                    hasDisc ? (
                                                        <div className="flex items-center gap-2">
                                                            <span className="text-xl font-semibold">
                                                                {formatPrice(discountPrice)}
                                                            </span>
                                                            <span className="text-sm text-gray-300 line-through">
                                                                {formatPrice(price)}
                                                            </span>
                                                            <span className="text-xs text-red-300">-{discountPercentage}%</span>
                                                        </div>
                                                    ) : (
                                                        <span className="text-xl font-semibold">
                                                            {formatPrice(price)}
                                                        </span>
                                                    )
                                                ) : (
                                                    <span className="text-xl font-semibold text-green-300">FREE</span>
                                                )}
                                            </div>

                                            <Link
                                                href={route('courses.enroll', course.slug || '#')}
                                                className="bg-white/90 text-gray-900 px-4 py-1.5 rounded text-sm font-medium hover:bg-white transition-colors"
                                            >
                                                Enrol now
                                            </Link>
                                        </div>
                                    </div>
                                </motion.div>
                            );
                        })
                    ) : (
                        <div className="col-span-4 text-center py-16">
                            <p className="text-gray-500 text-lg mb-4">
                                No courses available at the moment.
                            </p>
                            <Link
                                href={route('igrcfp.certificates.index')}
                                className="inline-flex items-center px-5 py-2 bg-[#0A2463] text-white rounded-lg hover:bg-[#081E52] transition"
                            >
                                Browse All Courses
                            </Link>
                        </div>
                    )}
                </motion.div>
            </div>
        </section>
    );
}