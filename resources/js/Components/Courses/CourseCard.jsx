import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { useCart } from '@/contexts/CartContext';
 
export default function CourseCard({ course, onAddToCart, isInCart, isAdding, isEnrolled }) {
    const { props } = usePage();
    const { removeFromCart } = useCart();
    const [isCourseInCart, setIsCourseInCart] = useState(isInCart || false);
    const [isRemoving, setIsRemoving] = useState(false);

    useEffect(() => {
        setIsCourseInCart(isInCart || false);
    }, [isInCart]);

    const enrolled = Boolean(course?.is_enrolled ?? isEnrolled);

    useEffect(() => {
        if (props.cart?.items && course?.id) {
            const inCart = props.cart.items.some(item => {
                if (item.course) return item.course.id === course.id;
                return item.id === course.id;
            });
            setIsCourseInCart(inCart);
        }
    }, [props.cart, course?.id]);

    if (!course) return null;

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

    // Format a number as "2,000.00"
    const formatPrice = (value) => {
        return value.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
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
        window.location.href = route('dashboard.cart.index');
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
            className="bg-white rounded-[24px] shadow-md shadow-blue-100 border border-blue-100 hover:shadow-lg hover:shadow-blue-200/70 transition-shadow duration-300 h-full flex flex-col overflow-hidden"
            data-aos="fade-up"
        >
            {/* Image */}
            <Link href={route('courses.show', course?.slug)} className="block h-48 overflow-hidden relative shrink-0">
                <img
                    src={course?.image_url || '/images/fallback-course.jpg'}
                    alt={course?.title || 'Course image'}
                    className="w-full h-full object-cover"
                    onError={(e) => {
                        e.target.src = '/images/fallback-course.jpg';
                    }}
                />
            </Link>

            {/* Content Area */}
            <div className="px-3 pt-3 pb-3 flex-1 flex flex-col">
                {/* Title */} 
                <Link href={route('courses.show', course?.slug)}>
                    <h3 className="text-xl font-bold text-gray-900 mb-2 leading-snug hover:text-[#0A2463] transition-colors">
                        {course?.title || 'Untitled Course'}
                    </h3>
                </Link>

                {/* Description */}
                <p className="text-gray-400 text-sm leading-relaxed mb-1 line-clamp-2">
                    {getCleanDescription()}
                </p>

                {/* Level, Duration & Modules */}
                <div className="flex items-center justify-between mb-2">
                    <span className="text-xs font-medium px-3.5 py-1.5 text-blue-700 rounded-full border border-blue-400 leading-none">
                        {course?.level || 'All Levels'}
                    </span>
                    <div className="flex flex-col gap-1 text-xs text-gray-400">
                        {course?.duration && (
                            <span className="flex items-center justify-end gap-1.5">
                                <svg className="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {course.duration}
                            </span>
                        )}
                        {course?.modules_count > 0 && (
                            <span className="flex items-center justify-end gap-1.5">
                                <svg className="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                                </svg>
                                {course.modules_count} Modules
                            </span>
                        )}
                    </div>
                </div>

                {/* Divider */}
                <div className="border-t border-gray-100 mb-4"></div>

                {/* Price & Action Row */}
                <div className="flex items-center justify-between mt-auto">
                    {enrolled ? (
                        <span className="text-sm font-bold text-green-700">Enrolled</span>
                    ) : price > 0 ? (
                        <div className="flex items-baseline gap-2">
                            {hasDisc ? (
                                <>
                                    <span className="text-2xl font-bold text-gray-900 ">£{formatPrice(discountPrice)}</span>
                                    <span className="text-sm text-gray-400 line-through">£{formatPrice(price)}</span>
                                </>
                            ) : (
                                <span className="text-2xl font-bold text-gray-900 ">£{formatPrice(price)}</span>
                            )}
                        </div>
                    ) : (
                        <span className="text-2xl font-bold text-green-600">FREE</span>
                    )}

                    {enrolled ? (
                        <Link
                            href={route('dashboard.courses.show', course.slug)}
                            className="px-1 py-2 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition"
                        >
                            Continue
                        </Link>
                    ) : isCourseInCart ? (
                        <Link
                            href={route('dashboard.cart.index')}
                            className="px-2 py-2 bg-slate-400 text-white text-sm font-medium rounded-full hover:bg-slate-500 transition"
                        >
                            View Cart
                        </Link>
                    ) : (
                        <button
                            onClick={handleAddToCartClick}
                            disabled={isAdding}
                            className="px-2 py-2 bg-slate-400 text-white text-sm font-medium rounded-full hover:bg-slate-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {isAdding ? (
                                <span className="flex items-center gap-1.5">
                                    <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8z"></path>
                                    </svg>
                                    Adding...
                                </span>
                            ) : (
                                <span className="flex items-center gap-1.5">
                                    Enrol now
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            )}
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
}