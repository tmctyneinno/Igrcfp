// resources/js/Components/CourseCard.jsx

import React from 'react';
import { Link } from '@inertiajs/react';

export default function CourseCard({ course }) {
    // Check if course has discount
    const hasDiscount = () => {
        if (!course?.discount_price || !course?.price) return false;
        const price = parseFloat(course.price);
        const discountPrice = parseFloat(course.discount_price);
        return !isNaN(price) && !isNaN(discountPrice) && discountPrice < price;
    };
    
    const getCleanDescription = () => {
        const text = course?.short_description || course?.description;
        if (!text) return 'No description available';
         
        const cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
        return cleanText.length > 100 ? cleanText.substring(0, 60) + '...' : cleanText;
    };

    const price = parseFloat(course?.price || 0);
    const discountPrice = parseFloat(course?.discount_price || 0);
    const hasDisc = hasDiscount();
    const discountPercentage = hasDisc && price > 0 
        ? Math.round(((price - discountPrice) / price) * 100) 
        : 0;

    return (
        <div
            className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2 h-full flex flex-col"
            data-aos="fade-up"
        > 
            {/* IMAGE */}
            <Link href={`/courses/${course?.slug || '#'}`} className="block h-48 overflow-hidden">
                <img
                    src={course?.image_url || '/images/fallback-course.jpg'}
                    alt={course?.title || 'Course image'}
                    className="w-full h-full object-cover transition-transform duration-300 hover:scale-110 cursor-zoom-in"
                    onError={(e) => {
                        e.target.src = '/images/fallback-course.jpg';
                    }}
                />
            </Link>

            {/* CONTENT */}
            <div className="p-2 flex-1 flex flex-col">
                <Link href={`/courses/${course?.slug || '#'}`}>
                    <h4 className="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-900 transition line-clamp-2">
                        {course?.title || 'Untitled Course'}
                    </h4>
                </Link> 
                
                <p className="text-gray-600 text-sm mb-1 line-clamp-2 flex-1">
                    {getCleanDescription()}
                </p>

                {/* COURSE METADATA */}
                <div className="flex items-center justify-between mb-2">
                    <span className="text-xs font-semibold px-3 capitalize py-1 bg-blue-100 text-blue-800 rounded-full">
                        {course?.level || 'All Levels'}
                    </span>
                    
                    <div className="flex items-center space-x-3">
                        {course?.duration && (
                            <span className="text-sm text-gray-500">
                                ⏱️ {course.duration}
                            </span>
                        )}
                        {course?.modules_count > 0 && (
                            <span className="text-sm text-gray-500">
                                📚 {course.modules_count} modules
                            </span>
                        )}
                    </div>
                </div>

                {/* PRICE SECTION */}
                <div className="flex items-center justify-between pt-1 border-t">
                    
                    {price > 0 ? (
                        <div className="text-right">
                            {hasDisc ? (
                                <>
                                    <span className="text-lg font-bold text-gray-900">
                                        ${discountPrice.toFixed(0)}
                                    </span>
                                    <span className="text-sm text-gray-500 line-through ml-2">
                                        ${price.toFixed(0)}
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
                    
                    {/* ENROLL BUTTON */}
                    <Link
                        href={route('courses.enroll', course.slug)}
                        className="inline-flex items-center px-3 py-1.5 bg-blue-900 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1"
                    >
                        Enroll Now
                        <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </Link>
                </div>

                {/* BADGES */}
                <div className="mt-1 flex flex-wrap gap-2">
                    {/* {course?.is_featured && (
                        <span className="text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">
                            ⭐ Featured
                        </span>
                    )}
                    {course?.is_popular && (
                        <span className="text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 rounded">
                            🔥 Popular
                        </span>
                    )}
                    {course?.format && (
                        <span className="text-xs font-semibold px-2 py-1 bg-purple-100 text-purple-800 rounded">
                            {course.format}
                        </span>
                    )} */}
                </div>
            </div>
        </div>
    );
}