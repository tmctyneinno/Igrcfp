// resources/js/Components/CourseCard.jsx

import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { useCart } from '@/contexts/CartContext'; // Add this import

export default function DashboardCourseCard({ course, onAddToCart, isInCart, isAdding }) { 
    const { props } = usePage(); 
    const { removeFromCart } = useCart(); // Add this line
    const [isCourseInCart, setIsCourseInCart] = useState(isInCart || false);
    const [isRemoving, setIsRemoving] = useState(false);

    // Update local state when prop changes or when page props update
    useEffect(() => { 
        setIsCourseInCart(isInCart || false); 
    }, [isInCart]);

    // Also check against page props for real-time updates
    useEffect(() => {
        if (props.cart?.items && course?.id) {
            const inCart = props.cart.items.some(item => {
                if (item.course) {
                    return item.course.id === course.id;
                }
                return item.id === course.id;
            });
            setIsCourseInCart(inCart);
        }
    }, [props.cart, course?.id]);

    // If course is undefined, return null or a placeholder
    if (!course) {
        return null;
    }

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

    const handleAddToCartClick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (onAddToCart && course) {
            onAddToCart(course);
        }
    };

    const handleViewCartClick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (typeof route === 'function') {
            window.location.href = route('dashboard.cart.index');
        }
    };

    return (
        <div
            className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2 h-full flex flex-col"
            data-aos="fade-up"
        > 
            {/* IMAGE */} 
            <Link href={ route('dashboard.courses.show', course?.slug)} className="block h-48 overflow-hidden">
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
                {/* <Link href={ route('dashboard.courses.show', course?.slug)}> */}
                <Link href={ route('dashboard.courses.show', course?.slug)}>
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
                        <div className="">
                            {hasDisc ? (
                                <>
                                    <span className="text-lg font-bold text-gray-900">
                                        £{discountPrice.toFixed(0)}
                                    </span>
                                    <span className="text-sm text-gray-500 line-through ml-2">
                                        £{price.toFixed(0)}
                                    </span>
                                    <span className="text-xs font-semibold text-red-600 ml-2">
                                        -{discountPercentage}%
                                    </span>
                                </>
                            ) : (
                                <span className="text-lg font-bold text-gray-900">
                                    £{price.toFixed(2)}
                                </span>
                            )}
                        </div>
                    ) : (
                        <span className="text-lg font-bold text-green-600">
                            FREE
                        </span>
                    )}
                      
                    {/* CART BUTTON - Now with remove option when in cart */}
                    {course?.is_enrolled ? (
                        <Link
                            href={route('dashboard.courses.show', course.slug)}
                            className="inline-flex items-center px-2 py-1.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition transform hover:-translate-y-1"
                        >
                            Continue Course
                        </Link>
                    ) : isCourseInCart ? (
                        <div className="flex gap-2">
                            <Link
                                href={route('dashboard.cart.index')}
                                onClick={handleViewCartClick}
                                className="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition transform hover:-translate-y-1"
                            >
                                View Cart
                            </Link>
                        </div>
                    ) : (
                        <button
                            onClick={handleAddToCartClick}
                            disabled={isAdding}
                            className="inline-flex items-center px-2.5 py-1.5 bg-blue-900 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {isAdding ? (
                                <>
                                    <svg className="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Adding...
                                </>
                            ) : (
                                <>
                                    Add to Cart
                                    <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </>
                            )}
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
}