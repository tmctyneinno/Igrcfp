// resources/js/Components/CourseCard.jsx

import React from 'react';
import { Link } from '@inertiajs/react';

export default function CourseCard({ course }) {
    const hasDiscount = () => {
        if (!course.discount_price || !course.price) return false;
        return parseFloat(course.discount_price) < parseFloat(course.price);
    };

    const discountPercentage = () => {
        if (!hasDiscount()) return 0;
        const price = parseFloat(course.price);
        const discountPrice = parseFloat(course.discount_price);
        return Math.round(((price - discountPrice) / price) * 100);
    };

    const getCleanDescription = () => {
        const text = course.short_description || course.description;
        if (!text) return 'No description available';
        
        const cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
        return cleanText.length > 120 ? cleanText.substring(0, 100) + '...' : cleanText;
    };

    return (
        <div className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">
            {/* Image */}
            <Link href={`/courses/${course.slug}`} className="block h-48 overflow-hidden">
                <img
                    src={course.image_url || '/images/fallback-course.jpg'}
                    alt={course.title}
                    className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                    onError={(e) => {
                        e.target.src = '/images/fallback-course.jpg';
                    }}
                />
            </Link>

            {/* Content */}
            <div className="p-5 flex-1 flex flex-col">
                {/* Category */}
                {course.category && (
                    <Link 
                        href={`/courses?category=${course.category.id}`}
                        className="text-xs text-blue-600 font-semibold uppercase tracking-wide hover:text-blue-800 mb-2"
                    >
                        {course.category.name}
                    </Link>
                )}

                {/* Title */}
                <Link href={`/courses/${course.slug}`}>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-700 transition line-clamp-2">
                        {course.title}
                    </h3>
                </Link>

                {/* Description */}
                <p className="text-gray-600 text-sm mb-4 line-clamp-2 flex-1">
                    {getCleanDescription()}
                </p>

                {/* Metadata */}
                <div className="flex items-center justify-between mb-3">
                    <span className="text-xs font-medium px-3 py-1 bg-blue-100 text-blue-800 rounded-full capitalize">
                        {course.level || 'All Levels'}
                    </span>
                    
                    <div className="flex items-center space-x-2 text-sm text-gray-500">
                        {course.duration && (
                            <span className="flex items-center">
                                ⏱️ {course.duration}
                            </span>
                        )}
                        {course.modules_count > 0 && (
                            <span className="flex items-center">
                                📚 {course.modules_count}
                            </span>
                        )}
                    </div>
                </div>

                {/* Instructor */}
                {course.instructor && (
                    <div className="flex items-center mb-3">
                        <img
                            src={course.instructor.avatar || '/images/default-avatar.jpg'}
                            alt={course.instructor.name}
                            className="w-6 h-6 rounded-full mr-2 object-cover"
                        />
                        <span className="text-xs text-gray-600">
                            {course.instructor.name}
                        </span>
                    </div>
                )}

                {/* Price and Enroll */}
                <div className="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div>
                        {course.price > 0 ? (
                            <div>
                                {hasDiscount() ? (
                                    <>
                                        <span className="text-lg font-bold text-gray-900">
                                            ${parseFloat(course.discount_price).toFixed(0)}
                                        </span>
                                        <span className="text-sm text-gray-500 line-through ml-2">
                                            ${parseFloat(course.price).toFixed(0)}
                                        </span>
                                        <span className="text-xs font-semibold text-red-600 ml-2">
                                            -{discountPercentage()}%
                                        </span>
                                    </>
                                ) : (
                                    <span className="text-lg font-bold text-gray-900">
                                        ${parseFloat(course.price).toFixed(2)}
                                    </span>
                                )}
                            </div>
                        ) : (
                            <span className="text-lg font-bold text-green-600">
                                FREE
                            </span>
                        )}
                    </div>

                    <Link
                        href={`/courses/${course.slug}/enroll`}
                        className="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1"
                    >
                        Enroll
                        <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>

                {/* Badges */}
                <div className="mt-2 flex flex-wrap gap-1">
                    {course.is_featured && (
                        <span className="text-xs font-medium px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded">
                            ⭐ Featured
                        </span>
                    )}
                    {course.is_popular && (
                        <span className="text-xs font-medium px-2 py-0.5 bg-green-100 text-green-800 rounded">
                            🔥 Popular
                        </span>
                    )}
                    {course.format && (
                        <span className="text-xs font-medium px-2 py-0.5 bg-purple-100 text-purple-800 rounded">
                            {course.format}
                        </span>
                    )}
                </div>
            </div>
        </div>
    );
}