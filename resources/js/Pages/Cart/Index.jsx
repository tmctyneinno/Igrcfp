import React from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Cart({ cart }) {
    const { props } = usePage();
    
    const calculateTotal = () => {
        return cart?.items?.reduce((total, item) => total + (item.price * item.quantity), 0) || 0;
    };

    return (
        <GuestLayout>
            <Head title="Shopping Cart" />
            
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 className="text-3xl font-bold text-gray-900 mb-8">Your Cart</h1>
                
                {props.flash?.success && (
                    <div className="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {props.flash.success}
                    </div>
                )}
                
                {props.flash?.info && (
                    <div className="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        {props.flash.info}
                    </div>
                )}
                
                {cart && cart.items && cart.items.length > 0 ? (
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Cart Items */}
                        <div className="lg:col-span-2">
                            <div className="bg-white rounded-xl shadow-md overflow-hidden">
                                <div className="p-6">
                                    <h2 className="text-lg font-semibold text-gray-900 mb-4">
                                        Cart Items ({cart.item_count})
                                    </h2>
                                    
                                    <div className="space-y-4">
                                        {cart.items.map((item) => (
                                            <div key={item.id} className="flex items-center space-x-4 py-4 border-b last:border-0">
                                                <img 
                                                    src={item.course?.image_url || '/images/fallback-course.jpg'}
                                                    alt={item.course?.title}
                                                    className="w-20 h-20 object-cover rounded-lg"
                                                />
                                                
                                                <div className="flex-1">
                                                    <Link href={`/courses/${item.course?.slug}`}>
                                                        <h3 className="font-semibold text-gray-900 hover:text-blue-700">
                                                            {item.course?.title}
                                                        </h3>
                                                    </Link>
                                                    <p className="text-sm text-gray-600 mt-1">
                                                        Level: {item.course?.level} | Duration: {item.course?.duration}
                                                    </p>
                                                </div>
                                                
                                                <div className="text-right">
                                                    <p className="text-lg font-bold text-gray-900">
                                                        ${item.price}
                                                    </p>
                                                    <button className="text-sm text-red-600 hover:text-red-800 mt-1">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {/* Cart Summary */}
                        <div className="lg:col-span-1">
                            <div className="bg-white rounded-xl shadow-md p-6 sticky top-24">
                                <h2 className="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                                
                                <div className="space-y-3 mb-4">
                                    <div className="flex justify-between text-gray-600">
                                        <span>Subtotal ({cart.item_count} items)</span>
                                        <span>${calculateTotal().toFixed(2)}</span>
                                    </div>
                                    <div className="flex justify-between text-gray-600">
                                        <span>Tax</span>
                                        <span>$0.00</span>
                                    </div>
                                    <div className="border-t pt-3 flex justify-between font-bold text-gray-900">
                                        <span>Total</span>
                                        <span>${calculateTotal().toFixed(2)}</span>
                                    </div>
                                </div>
                                
                                <Link
                                    href={route('checkout')}
                                    className="block w-full text-center py-3 px-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300"
                                >
                                    Proceed to Checkout
                                </Link>
                                
                                <Link
                                    href={route('courses.index')}
                                    className="block w-full text-center py-2 px-4 mt-3 text-blue-900 hover:text-blue-700 font-semibold"
                                >
                                    Continue Shopping
                                </Link>
                            </div>
                        </div>
                    </div>
                ) : (
                    <div className="text-center py-16 bg-white rounded-xl shadow-md">
                        <svg className="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                        <p className="text-gray-600 mb-6">Start adding courses to your cart</p>
                        <Link
                            href={route('courses.index')}
                            className="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
                        >
                            Browse Courses
                        </Link>
                    </div>
                )}
            </div>
        </GuestLayout>
    );
}