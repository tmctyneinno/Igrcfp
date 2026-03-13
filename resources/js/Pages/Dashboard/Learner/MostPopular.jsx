import React, { useState } from "react";
import { Link } from "@inertiajs/react";
import { motion } from 'framer-motion';
import { useCart } from '@/contexts/CartContext'; 
 
export default function MostPopular({ initialCourses = [] }) {
    const { addToCart, cartItems } = useCart(); 
    const [addingToCart, setAddingToCart] = useState({});
 
    // Helper function to get level badge color
    const getLevelBadgeColor = (level) => { 
        const levelMap = { 
            'Beginner': 'bg-blue-100 text-blue-800',
            'Intermediate': 'bg-amber-100 text-amber-800',
            'Advanced': 'bg-emerald-100 text-emerald-800',
            'Expert': 'bg-purple-100 text-purple-800'
        };
        return levelMap[level] || 'bg-blue-100 text-blue-800';
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
        return cleanText.length > 100 ? cleanText.substring(0, 60) + '...' : cleanText;
    };

    // Check if course is already in cart
    const isInCart = (courseId) => {
        return cartItems?.some(item => item.id === courseId);
    };

    // Handle Add to Cart using your existing cart method
    const handleAddToCart = async (course, e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Check if already in cart
        if (isInCart(course.id)) {
            alert('Course is already in your cart!');
            return;
        }

        setAddingToCart(prev => ({ ...prev, [course.id]: true }));
        
        try {
            // Use your existing addToCart method from CartContext
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
                // Show success message (you can replace with toast notification)
                alert('Course added to cart successfully!');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            alert('Failed to add course to cart. Please try again.');
        } finally {
            setAddingToCart(prev => ({ ...prev, [course.id]: false }));
        }
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
                            Trending Now
                        </span>
                    </div>
                    <h2 className="text-3xl font-bold text-gray-900 mt-2">
                        Most Popular Courses
                    </h2> 
                    <p className="text-gray-600 mt-3 max-w-2xl">
                        Trending courses that other learners are taking right now. 
                        Join hundreds of students advancing their careers.
                    </p>
                </div>

                {initialCourses.length > 0 && (
                    <Link
                        href={route('dashboard.courses.index', { popular: 1 })}
                        className="mt-6 md:mt-0 text-blue-950 font-semibold hover:text-blue-700 transition"
                    >
                        View All Popular →
                    </Link>
                )}
            </div>

            {/* COURSES */}
            {initialCourses.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                    {initialCourses.map((course, index) => { 
                        const price = parseFloat(course?.price || 0);
                        const discountPrice = parseFloat(course?.discount_price || 0);
                        const hasDisc = hasDiscount(course);
                        const discountPercentage = hasDisc && price > 0 
                            ? Math.round(((price - discountPrice) / price) * 100) 
                            : 0;
                        const isAdding = addingToCart[course.id] || false;
                        const inCart = isInCart(course.id);
 
                        return (
                            <div
                                key={course?.id || index}
                                className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2"
                                data-aos="fade-up"
                                data-aos-delay={index * 150}
                            > 
                                {/* IMAGE */}
                                <div className="h-48 overflow-hidden relative">
                                    <img
                                        src={course?.image || course?.image_url || '/images/fallback-course.jpg'}
                                        alt={course?.title || 'Course image'}
                                        className="w-full h-full object-cover transition-transform duration-300 hover:scale-110 cursor-zoom-in"
                                        onError={(e) => {
                                            e.target.src = '/images/fallback-course.jpg';
                                        }}
                                    />
                                    {/* <div className="absolute top-3 left-3 rounded-full bg-red-500 px-3 py-1 text-xs font-medium text-white shadow-lg">
                                        🔥 Popular
                                    </div>  */}
                                    {course?.category && (
                                        <Link 
                                            href={route('dashboard.courses.by-category', { slug: course.category.slug })}
                                            className="absolute top-3 left-3 rounded-full bg-purple-200 px-3 py-1 text-xs font-medium text-purple-800 shadow-lg"
                                            // className="text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 text-purple-800 hover:bg-purple-200 transition"
                                        >
                                            {course.category.name}
                                        </Link>
                                    )}
                                </div>

                                {/* CONTENT */}
                                <div className="p-2">
                                    <Link href={route('dashboard.courses.show', course.slug || course.id)}>
                                        <h4 className="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-900 transition">
                                            {course?.title || 'Untitled Course'}
                                        </h4>
                                    </Link> 
                                    
                                    <p className="text-gray-600 text-sm mb-1">
                                        {getCleanDescription(course)}
                                    </p> 

                                    {/* COURSE METADATA */}
                                    <div className="flex items-center justify-between mb-2">
                                        <span className={`text-xs font-semibold px-3 capitalize py-1 rounded-full ${getLevelBadgeColor(course?.level)}`}>
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
                                                    📚 {course.modules_count} {course.modules_count === 1 ? 'module' : 'modules'}
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    {/* Rating */}
                                    {course?.rating > 0 && (
                                        <div className="flex items-center mb-2">
                                            <span className="text-sm font-medium text-amber-600">{course.rating}</span>
                                            <span className="text-sm text-gray-400 ml-1">/5.0</span>
                                        </div>
                                    )}

                                    {/* PRICE SECTION */}
                                    <div className="flex items-center justify-between pt-1 border-t">
                                        
                                        {price > 0 ? (
                                            <div className="text-right">
                                                {hasDisc ? (
                                                    <>
                                                        <span className="text-lg font-bold text-gray-900">
                                                            €{discountPrice.toFixed(0)}
                                                        </span>
                                                        <span className="text-sm text-gray-500 line-through ml-2">
                                                            €{price.toFixed(0)}
                                                        </span>
                                                        <span className="text-xs font-semibold text-red-600 ml-2">
                                                            -{discountPercentage}%
                                                        </span>
                                                    </>
                                                ) : (
                                                    <span className="text-lg font-bold text-gray-900">
                                                        €{price.toFixed(2)}
                                                    </span>
                                                )}
                                            </div>
                                        ) : (
                                            <span className="text-lg font-bold text-green-600">
                                                FREE
                                            </span>
                                        )}
                                        
                                        {/* ADD TO CART BUTTON */}
                                        {inCart ? (
                                            <Link
                                                href={route('dashboard.cart.index')}
                                                className="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition transform hover:-translate-y-1"
                                            >
                                               
                                                View in Cart
                                            </Link>
                                        ) : (
                                            <button
                                                onClick={(e) => handleAddToCart(course, e)}
                                                disabled={isAdding}
                                                className="inline-flex items-center px-3 py-1.5 bg-blue-900 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed"
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
                                                    </>
                                                )}
                                            </button>
                                        )}
                                    </div>

                                    {/* BADGES */}
                                    <div className="mt-1 flex flex-wrap gap-2">
                                        {/* {course?.is_featured && (
                                            <span className="text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">
                                                ⭐ Featured
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
                    })}
                </div>
            ) : (
                <div className="col-span-4 text-center py-16 bg-white rounded-xl shadow-md">
                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                        <span className="text-2xl">🔥</span>
                    </div>
                    <h3 className="text-xl font-semibold text-gray-900 mb-2">No popular courses yet</h3>
                    <p className="text-gray-600 mb-6 max-w-md mx-auto">
                        Check back later for trending courses, or browse our catalog to find something interesting.
                    </p>
                    <Link
                        href={route('courses.index')}
                        className="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1"
                    >
                        Browse All Courses
                        <svg className="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>
            )}
        </div>
    );
}