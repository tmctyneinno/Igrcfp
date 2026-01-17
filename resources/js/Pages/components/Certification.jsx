import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';

export default function Certification({ courses = [] }) {

    // Format price safely
    const formatPrice = (price) => {
        if (!price && price !== 0) return null;
        const numPrice = parseFloat(price);
        return isNaN(numPrice) ? null : `$${numPrice.toFixed(2)}`;
    };

    // Check if course has discount
    const hasDiscount = (course) => {
        if (!course.discount_price || !course.price) return false;
        const price = parseFloat(course.price);
        const discountPrice = parseFloat(course.discount_price);
        return !isNaN(price) && !isNaN(discountPrice) && discountPrice < price;
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {/* HEADER */}
            <div className="flex flex-col md:flex-row md:justify-between md:items-center mb-12">
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
                    courses.map((course, index) => {
                        const price = parseFloat(course.price);
                        const discountPrice = parseFloat(course.discount_price);
                        const hasDisc = hasDiscount(course);
                        const discountPercentage = hasDisc && price > 0 
                            ? Math.round(((price - discountPrice) / price) * 100) 
                            : 0;

                        return (
                            <div
                                key={course.id}
                                className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2"
                                data-aos="fade-up"
                                data-aos-delay={index * 150}
                            >
                                {/* IMAGE */}
                                <div className="h-48 overflow-hidden">
                                    <img
                                        src={course.image_url || '/images/fallback-course.jpg'}
                                        alt={course.title}
                                        className="w-full h-full object-cover"
                                        onError={(e) => {
                                            e.target.src = '/images/fallback-course.jpg';
                                        }}
                                    />
                                </div>

                                {/* CONTENT */}
                                <div className="p-6">
                                    <Link href={`/courses/${course.slug}`}>
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition">
                                            {course.title}
                                        </h3>
                                    </Link>
                                    
                                    <p className="text-gray-600 text-sm mb-4">
                                        {course.short_description || 
                                         (course.description && course.description.length > 100 
                                            ? `${course.description.substring(0, 100)}...` 
                                            : course.description || 'No description available')}
                                    </p>

                                    {/* COURSE METADATA */}
                                    <div className="flex items-center justify-between mb-4">
                                        <span className="text-xs font-semibold px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            {course.level || 'All Levels'}
                                        </span>
                                        
                                        <div className="flex items-center space-x-3">
                                            {course.duration && (
                                                <span className="text-sm text-gray-500">
                                                    ⏱️ {course.duration}
                                                </span>
                                            )}
                                            {course.modules_count > 0 && (
                                                <span className="text-sm text-gray-500">
                                                    📚 {course.modules_count} modules
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    {/* PRICE SECTION */}
                                    <div className="flex items-center justify-between pt-4 border-t">
                                        <Link
                                            href={`/courses/${course.slug}`}
                                            className="text-blue-950 font-bold hover:underline transition duration-200"
                                        >
                                            Learn More →
                                        </Link>
                                        
                                        {price > 0 ? (
                                            <div className="text-right">
                                                {hasDisc ? (
                                                    <>
                                                        <span className="text-lg font-bold text-gray-900">
                                                            ${discountPrice.toFixed(2)}
                                                        </span>
                                                        <span className="text-sm text-gray-500 line-through ml-2">
                                                            ${price.toFixed(2)}
                                                        </span>
                                                        <span className="text-xs font-semibold text-red-600 ml-2">
                                                            -{discountPercentage}%
                                                        </span>
                                                    </>
                                                ) : (
                                                    <span className="text-lg font-bold text-gray-900">
                                                        ${price.toFixed(2)}
                                                    </span>
                                                )}
                                            </div>
                                        ) : (
                                            <span className="text-lg font-bold text-green-600">
                                                FREE
                                            </span>
                                        )}
                                    </div>

                                    {/* BADGES */}
                                    <div className="mt-3 flex flex-wrap gap-2">
                                        {course.is_featured && (
                                            <span className="text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">
                                                ⭐ Featured
                                            </span>
                                        )}
                                        {course.is_popular && (
                                            <span className="text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 rounded">
                                                🔥 Popular
                                            </span>
                                        )}
                                        {course.format && (
                                            <span className="text-xs font-semibold px-2 py-1 bg-purple-100 text-purple-800 rounded">
                                                {course.format}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            </div>
                        );
                    })
                ) : (
                    <div className="col-span-3 text-center py-12">
                        <p className="text-gray-500 text-lg mb-4">
                            No courses available at the moment.
                        </p>
                        <Link
                            href="/courses"
                            className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        >
                            Browse All Courses
                        </Link>
                    </div>
                )}
            </div>


            {/* VIEW ALL BUTTON */}
            {courses.length > 0 && (
                <div className="text-center mt-12">
                    <Link
                        href="/courses"
                        className="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1"
                    >
                        View All Courses →
                    </Link>
                </div>
            )}
        </div>
    );
}