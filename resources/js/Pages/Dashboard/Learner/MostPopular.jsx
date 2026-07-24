import React, { useState } from "react";
import { Link, usePage, router } from "@inertiajs/react";
import { motion } from 'framer-motion';
import { useCart } from '@/contexts/CartContext'; 
import { CheckCircleIcon } from '@heroicons/react/24/solid'; 
  
export default function MostPopular({ initialCourses = [], onScholarshipEnroll, authUser }) {
    const { addToCart, cartItems } = useCart(); 
    const [addingToCart, setAddingToCart] = useState({});
    
    const currentUser = authUser;
    const isScholarshipApplicant = !!currentUser?.is_scholarship_applicant;
 
    const getLevelBadgeColor = (level) => { 
        const levelMap = { 
            'Beginner': 'bg-blue-100 text-blue-800',
            'Intermediate': 'bg-amber-100 text-amber-800',
            'Advanced': 'bg-emerald-100 text-emerald-800',
            'Expert': 'bg-purple-100 text-purple-800'
        };
        return levelMap[level] || 'bg-blue-100 text-blue-800';
    }; 

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
        return cleanText.length > 100 ? cleanText.substring(0, 60) + '...' : cleanText;
    };

    const formatPrice = (value) => {
        return value.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    };

    const isInCart = (courseId) => {
        return cartItems?.some(item => item.id === courseId);
    };

    const handleAddToCart = async (course, e) => {
        e.preventDefault();
        e.stopPropagation();
        
        if (isInCart(course.id)) {
            alert('Course is already in your cart!');
            return;
        }

        setAddingToCart(prev => ({ ...prev, [course.id]: true }));
        
        try {
            const success = await addToCart({
                id: course.id,
                title: course.title,
                slug: course.slug,
                price: course.price,
                discount_price: course.discount_price,
                image_url: course.image || course.image_url,
                level: course.level,
                duration: course.duration
            });

            if (success) {
                alert('Course added to cart successfully!');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            alert('Failed to add course to cart. Please try again.');
        } finally {
            setAddingToCart(prev => ({ ...prev, [course.id]: false }));
        }
    };

    const handleScholarshipEnroll = (course, e) => {
        e.preventDefault();
        e.stopPropagation();
        if (onScholarshipEnroll) {
            onScholarshipEnroll(course);
        } else {
            router.post(route('courses.enroll', course.slug), {}, {
                preserveScroll: true,
                onSuccess: () => window.location.reload(),
                onError: (errors) => console.error("Enrollment failed", errors)
            });
        }
    };

    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                        <span className="text-sm tracking-widest text-gray-400 pl-20 uppercase">Trending Now</span>
                    </div>
                    <h2 className="text-3xl font-bold text-gray-900 mt-2">Most Popular Courses</h2> 
                    <p className="text-gray-600 mt-3 max-w-2xl">
                        Trending courses that other learners are taking right now. Join hundreds of students advancing their careers.
                    </p>
                </div>

                {initialCourses.length > 0 && (
                    <Link href={route('dashboard.courses.index', { popular: 1 })} className="mt-6 md:mt-0 text-blue-950 font-semibold hover:text-blue-700 transition">
                        View All Popular →
                    </Link>
                )}
            </div>

            {initialCourses.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                    {initialCourses.map((course, index) => { 
                        const price = parseFloat(course?.price || 0);
                        const discountPrice = parseFloat(course?.discount_price || 0);
                        const hasDisc = hasDiscount(course);
                        const isAdding = addingToCart[course.id] || false;
                        const inCart = isInCart(course.id);
                        const enrolled = Boolean(course?.is_enrolled);
                        
                        // CHECK BOTH USER STATUS AND COURSE ELIGIBILITY
                        const canUseScholarship = isScholarshipApplicant && course?.is_scholarship_eligible;
 
                        return (
                            <div 
                                key={course?.id || index} 
                                className="bg-white rounded-[24px] shadow-md shadow-blue-100 border border-blue-100 hover:shadow-lg hover:shadow-blue-200/70 transition-shadow duration-300 h-full flex flex-col overflow-hidden"
                                data-aos="fade-up" 
                                data-aos-delay={index * 150}
                            > 
                                {/* Image */}
                                <Link href={route('dashboard.courses.show', course.slug || course.id)} className="block h-48 overflow-hidden relative shrink-0">
                                    <img
                                        src={course?.image || course?.image_url || '/images/fallback-course.jpg'}
                                        alt={course?.title || 'Course image'}
                                        className="w-full h-full object-cover"
                                        onError={(e) => { e.target.src = '/images/fallback-course.jpg'; }}
                                    />
                                    {course?.category && (
                                        <Link href={route('dashboard.courses.by-category', { slug: course.category.slug })} className="absolute top-3 left-3 rounded-full bg-purple-200 px-3 py-1 text-xs font-medium text-purple-800 shadow-lg">
                                            {course.category.name}
                                        </Link>
                                    )}
                                </Link>

                                {/* Content Area */}
                                <div className="px-3 pt-3 pb-3 flex-1 flex flex-col">
                                    {/* Title */}
                                    <Link href={route('dashboard.courses.show', course.slug || course.id)}>
                                        <h3 className="text-xl font-bold text-gray-900 mb-2 leading-snug hover:text-[#0A2463] transition-colors">
                                            {course?.title || 'Untitled Course'}
                                        </h3>
                                    </Link>

                                    {/* Description */}
                                    <p className="text-gray-400 text-sm leading-relaxed mb-1 line-clamp-2">
                                        {getCleanDescription(course)}
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
                                        
                                        {/* PRICE / ELIGIBILITY */}
                                        {canUseScholarship ? (
                                            <span className="text-sm font-semibold text-emerald-600 flex items-center gap-1">
                                                <CheckCircleIcon className="w-4 h-4" />
                                                Scholarship Eligible
                                            </span>
                                        ) : enrolled ? (
                                            <span className="text-sm font-bold text-green-700">Enrolled</span>
                                        ) : price > 0 ? (
                                            <div className="flex items-baseline gap-2">
                                                {hasDisc ? (
                                                    <>
                                                        <span className="text-1xl font-bold text-gray-900">£{formatPrice(discountPrice)}</span>
                                                        <span className="text-sm text-gray-400 line-through">£{formatPrice(price)}</span>
                                                    </>
                                                ) : (
                                                    <span className="text-1xl font-bold text-gray-900">£{formatPrice(price)}</span>
                                                )}
                                            </div>
                                        ) : (
                                            <span className="text-2xl font-bold text-green-600">FREE</span>
                                        )}

                                        {/* ACTION BUTTONS */}
                                        {enrolled ? (
                                            <Link
                                                href={route('dashboard.courses.show', course.slug)}
                                                className="px-1 py-2 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition"
                                            >
                                                Continue
                                            </Link>
                                        ) : canUseScholarship ? (
                                            <button
                                                onClick={(e) => handleScholarshipEnroll(course, e)}
                                                disabled={isAdding}
                                                className="px-2 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-full hover:bg-emerald-700 transition shadow-sm flex items-center gap-2"
                                            >
                                                <CheckCircleIcon className="w-4 h-4" />
                                                Activate
                                            </button>
                                        ) : inCart ? (
                                            <Link
                                                href={route('dashboard.cart.index')}
                                                className="px-2 py-2 bg-slate-400 text-white text-sm font-medium rounded-full hover:bg-slate-500 transition"
                                            >
                                                View Cart
                                            </Link>
                                        ) : (
                                            <button
                                                onClick={(e) => handleAddToCart(course, e)}
                                                disabled={isAdding}
                                                className="px-1.5 py-2 bg-slate-400 text-white text-sm font-medium rounded-full hover:bg-slate-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
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
                                                    <span className="flex items-center gap-1.0">
                                                        Enroll now
                                                        <svg className="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    })}
                </div>
            ) : (
                <div className="col-span-4 text-center py-16 bg-white rounded-[24px] shadow-md shadow-blue-100 border border-blue-100">
                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100"><span className="text-2xl">🔥</span></div>
                    <h3 className="text-xl font-semibold text-gray-900 mb-2">No popular courses yet</h3>
                    <p className="text-gray-600 mb-6 max-w-md mx-auto">Check back later for trending courses, or browse our catalog to find something interesting.</p>
                    <Link href={route('dashboard.courses.index')} className="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1">
                        Browse All Courses
                        <svg className="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </Link>
                </div>
            )}
        </div>
    );
}