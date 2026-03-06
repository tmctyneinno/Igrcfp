// resources/js/Pages/Dashboard/Learner/CourseCategories.jsx

import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';

export default function CourseCategories({ categories = [] }) {
    // Icon mapping for categories
    const getCategoryIcon = (iconName) => {
        const icons = {
            'grc': '⚖️',
            'financial-crime': '🛡️',
            'crypto': '₿',
            'cybersecurity': '🔒',
            'ai': '🤖',
            'default': '📚'
        };
        return icons[iconName] || icons.default;
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                            Explore by Topic
                        </span>
                    </div>
                    <h2 className="text-3xl font-bold text-gray-900 mt-2">
                        Course Categories
                    </h2>
                    <p className="text-gray-600 mt-3 max-w-2xl">
                        Browse our comprehensive range of professional courses by category. 
                        Find the perfect program for your career goals.
                    </p>
                </div>

                {categories.length > 0 && (
                    <Link
                        href={route('courses.index')}
                        className="mt-6 md:mt-0 text-blue-950 font-semibold hover:text-blue-700 transition"
                    >
                        View All Categories →
                    </Link>
                )}
            </div>

            {/* CATEGORIES GRID */}
            {categories.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    {categories.map((category, index) => (
                        <motion.div
                            key={category.id}
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.3, delay: index * 0.1 }}
                            whileHover={{ y: -8 }}
                            className="group"
                        >
                            <Link
                                href={route('courses.by-category', { slug: category.slug })}
                                className="block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden"
                            >
                                <div className="p-6 text-center">
                                    {/* Icon */}
                                    <div className="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">
                                        {category.icon || getCategoryIcon(category.slug)}
                                    </div>
                                    
                                    {/* Category Name */}
                                    <h3 className="font-semibold text-gray-900 mb-1 group-hover:text-blue-900 transition">
                                        {category.name}
                                    </h3>
                                    
                                    {/* Course Count */}
                                    <p className="text-sm text-gray-500">
                                        {category.courses_count} {category.courses_count === 1 ? 'Course' : 'Courses'}
                                    </p>
                                    
                                    {/* Description (optional) */}
                                    {category.description && (
                                        <p className="text-xs text-gray-400 mt-2 line-clamp-2">
                                            {category.description}
                                        </p>
                                    )}
                                </div>
                                
                                {/* Hover Indicator */}
                                <div className="h-1 w-0 group-hover:w-full bg-blue-900 transition-all duration-300"></div>
                            </Link>
                        </motion.div>
                    ))}
                </div>
            ) : (
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="text-center py-16 bg-white rounded-xl shadow-md"
                >
                    <div className="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-blue-100">
                        <span className="text-3xl">📚</span>
                    </div>
                    <h3 className="text-xl font-semibold text-gray-900 mb-2">No categories yet</h3>
                    <p className="text-gray-600 mb-6 max-w-md mx-auto">
                        We're currently organizing our course categories. Check back soon!
                    </p>
                </motion.div>
            )}
        </div>
    );
}