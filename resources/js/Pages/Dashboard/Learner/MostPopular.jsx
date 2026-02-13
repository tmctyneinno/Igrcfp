import React, { useState } from "react";
import { Link } from "@inertiajs/react";

export default function MostPopular({ initialCourses = [] }) {
    const [addingToCart, setAddingToCart] = useState({});

    // Helper function to get level badge color
    const getLevelBadgeColor = (level) => {
        const levelMap = { 
            'Beginner': 'bg-blue-100 text-blue-700',
            'Intermediate': 'bg-amber-100 text-amber-700',
            'Advanced': 'bg-emerald-100 text-emerald-700',
            'Expert': 'bg-purple-100 text-purple-700'
        };
        return levelMap[level] || 'bg-slate-100 text-slate-700';
    };

    // Helper function to get format badge color
    const getFormatBadgeColor = (format) => {
        const formatMap = {
            'Reading Materials': 'bg-teal-100 text-teal-700',
            'Video Series': 'bg-indigo-100 text-indigo-700',
            'Interactive Workshop': 'bg-pink-100 text-pink-700',
            'Live Sessions': 'bg-red-100 text-red-700',
            'Self-Paced': 'bg-gray-100 text-gray-700'
        };
        return formatMap[format] || 'bg-gray-100 text-gray-700';
    };

    // Format price with discount
    const formatPrice = (course) => {
        // Ensure prices are numbers
        const price = parseFloat(course.price) || 0;
        const discountPrice = parseFloat(course.discount_price) || 0;
        
        if (discountPrice > 0 && discountPrice < price) {
            return (
                <div className="flex items-center gap-2">
                    <span className="text-sm font-bold text-green-600">
                        ₦{discountPrice.toLocaleString()}
                    </span>
                    <span className="text-xs text-gray-500 line-through">
                        ₦{price.toLocaleString()}
                    </span>
                </div>
            );
        }
        return price > 0 ? (
            <span className="text-sm font-bold text-gray-900">
                ₦{price.toLocaleString()}
            </span>
        ) : (
            <span className="text-sm font-bold text-green-600">Free</span>
        );
    };

    // Display rating stars
    const renderRating = (rating) => {
        const ratingNum = parseFloat(rating);
        const isValidRating = !isNaN(ratingNum) && ratingNum > 0;
        
        if (!isValidRating) {
            return (
                <div className="flex items-center gap-1">
                    <span className="text-sm text-gray-400">No ratings yet</span>
                </div>
            );
        }
        
        return (
            <div className="flex items-center gap-1">
                <span className="text-sm font-medium text-amber-600">{ratingNum.toFixed(1)}</span>
                <span className="text-sm text-gray-400">/5.0</span>
            </div>
        );
    };

    const getCleanDescription = (course) => {
        const text = course?.short_description || course?.description;
        if (!text) return 'No description available';
        
        const cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
        return cleanText.length > 100 ? cleanText.substring(0, 60) + '...' : cleanText;
    };

    // Handle Add to Cart
    const handleAddToCart = (courseId, e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Set loading state for this specific course
        setAddingToCart(prev => ({ ...prev, [courseId]: true }));
        
        // Simulate API call
        setTimeout(() => {
            // In real app, make API call to add to cart
            console.log(`Adding course ${courseId} to cart`);
            
            // Remove loading state
            setAddingToCart(prev => ({ ...prev, [courseId]: false }));
            
            // Show success message (you can use toast notification)
            alert(`Course added to cart successfully!`);
        }, 1000);
    };

    // Check if course is in cart (simulated - in real app, fetch from API)
    const isInCart = (courseId) => {
        // This would be replaced with actual cart check from your store/state
        return false;
    };

    return (
        <div className="p-6">
            
            {/* Header */}
            <div className="mb-6 flex items-center justify-between">
                <div>
                    <h2 className="text-xl font-semibold text-gray-900">
                        Most Popular Courses
                    </h2>
                    <p className="mt-1 text-sm text-gray-600">
                        Trending courses that other learners are taking
                    </p>
                </div> 
                {initialCourses.length > 0 && (
                    <Link 
                        href={route('courses.index', { popular: 1 })}
                        className="text-sm font-medium text-blue-600 hover:underline"
                    >
                        View All Popular Courses
                    </Link>
                )}
            </div>

            {/* Course Cards */}
            {initialCourses.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                    {initialCourses.map((course) => {
                        // Check if course has slug or ID for routing
                        const courseIdentifier = course.slug || course.id;
                        const isAdding = addingToCart[course.id] || false;
                        const inCart = isInCart(course.id);
                        
                        return (
                            <div 
                                key={course.id}
                                className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2 flex flex-col"
                            >
                                {/* Course Image with Popular Badge */}
                                <Link 
                                    href={route('courses.show', { course: courseIdentifier })}
                                    className="block relative h-40 w-full overflow-hidden"
                                >   
                                    <img
                                        src={course?.image}
                                        alt={course?.title || 'Course image'}
                                        className="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                                        onError={(e) => {
                                            e.target.src = '/images/fallback-course.jpg';
                                        }}
                                    />
                                    <div className="absolute top-3 left-3 rounded-full bg-red-500 px-3 py-1 text-xs font-medium text-white">
                                        Popular
                                    </div>
                                </Link>

                                <div className="p-4 flex-1 flex flex-col">
                                    {/* Badges */}
                                    <div className="mb-3 flex flex-wrap gap-2">
                                        {course.level && (
                                            <span className={`rounded-full px-3 py-1 text-xs ${getLevelBadgeColor(course.level)}`}>
                                                {course.level}
                                            </span>
                                        )}
                                        {course.format && (
                                            <span className={`rounded-full px-3 py-1 text-xs ${getFormatBadgeColor(course.format)}`}>
                                                {course.format}
                                            </span>
                                        )}
                                    </div>
                                    
                                    {/* Course Title */}
                                    <h3 className="mb-2 font-semibold text-gray-900 line-clamp-2 h-12 hover:text-blue-700 transition-colors">
                                        <Link 
                                            href={route('courses.show', { course: courseIdentifier })}
                                            className="hover:text-blue-700"
                                        >
                                            {course.title}
                                        </Link>
                                    </h3>

                                    {/* Course Description */}
                                    <p className="mb-4 text-sm text-gray-500 line-clamp-2 flex-1">
                                        {getCleanDescription(course)}
                                    </p>

                                    {/* COURSE METADATA */}
                                    <div className="flex items-center justify-between mb-3">
                                        {renderRating(course.rating || 0)}
                                        
                                        <div className="flex items-center space-x-3">
                                            {course?.duration && (
                                                <span className="text-xs text-gray-500 flex items-center gap-1">
                                                    <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {course.duration}
                                                </span>
                                            )}
                                            {course?.modules_count > 0 && (
                                                <span className="text-xs text-gray-500 flex items-center gap-1">
                                                    <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                    {course.modules_count}
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    {/* Price and Add to Cart Button */}
                                    <div className="mt-auto pt-3 border-t border-gray-100">
                                        <div className="flex items-center justify-between">
                                            <div className="flex-1 pr-2">
                                                {formatPrice(course)}
                                            </div>
                                            <div className="flex gap-2">
                                               
                                                {/* Add to Cart Button */}
                                                {inCart ? (
                                                    <button
                                                        disabled
                                                        className="rounded-lg bg-green-100 px-4 py-2 text-xs font-medium text-green-700 flex items-center gap-1"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Added
                                                    </button>
                                                ) : (
                                                    <button
                                                        onClick={(e) => handleAddToCart(course.id, e)}
                                                        disabled={isAdding}
                                                        className="rounded-lg bg-blue-900 px-2 py-2 text-xs font-medium text-white hover:bg-blue-800 transition-colors duration-200 flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    >
                                                        {isAdding ? (
                                                            <>
                                                                <svg className="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                </svg>
                                                                Adding...
                                                            </>
                                                        ) : (
                                                            <>
                                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                                </svg>
                                                                Add to Cart
                                                            </>
                                                        )}
                                                    </button>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div> 
            ) : (
                <div className="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                        <span className="text-2xl">🔥</span>
                    </div>
                    <h3 className="mb-2 text-lg font-medium text-gray-900">No popular courses</h3>
                    <p className="mb-6 text-gray-600 max-w-md mx-auto">
                        Check back later for trending courses, or browse our catalog to find something interesting.
                    </p>
                    <Link
                        href={route('courses.index')}
                        className="inline-flex items-center rounded-lg bg-blue-900 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors duration-200"
                    >
                        Browse All Courses
                        <svg className="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>
            )}
        </div>
    );
}