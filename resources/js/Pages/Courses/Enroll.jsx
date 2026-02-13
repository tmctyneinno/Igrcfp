import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Enroll({ course }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        payment_method: 'card',
        terms_accepted: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('courses.enroll.process', course.slug));
    };

    return (
        <GuestLayout>
            <Head title={`Enroll in ${course.title}`} />
            
            <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="bg-white rounded-xl shadow-lg overflow-hidden">
                    {/* Header */}
                    <div className="bg-gradient-to-r from-blue-900 to-indigo-900 px-6 py-8 text-white">
                        <h1 className="text-2xl font-bold mb-2">Enroll in Course</h1>
                        <p className="text-blue-100">{course.title}</p>
                    </div>

                    <div className="p-6">
                        {/* Course Summary */}
                        <div className="flex items-center space-x-4 mb-6 pb-6 border-b">
                            <img 
                                src={course.image_url || '/images/fallback-course.jpg'}
                                alt={course.title}
                                className="w-20 h-20 object-cover rounded-lg"
                            />
                            <div>
                                <h2 className="font-semibold text-gray-900">{course.title}</h2>
                                <div className="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                    <span>Level: {course.level}</span>
                                    <span>Duration: {course.duration}</span>
                                </div>
                                <div className="mt-2">
                                    {course.discount_price ? (
                                        <div>
                                            <span className="text-2xl font-bold text-gray-900">
                                                ${course.discount_price}
                                            </span>
                                            <span className="text-sm text-gray-500 line-through ml-2">
                                                ${course.price}
                                            </span>
                                        </div>
                                    ) : course.price > 0 ? (
                                        <span className="text-2xl font-bold text-gray-900">
                                            ${course.price}
                                        </span>
                                    ) : (
                                        <span className="text-2xl font-bold text-green-600">FREE</span>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Enrollment Form */}
                        <form onSubmit={handleSubmit}>
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Full Name *
                                    </label>
                                    <input
                                        type="text"
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {errors.name && (
                                        <p className="text-red-600 text-sm mt-1">{errors.name}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Email Address *
                                    </label>
                                    <input
                                        type="email"
                                        value={data.email}
                                        onChange={e => setData('email', e.target.value)}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        required
                                    />
                                    {errors.email && (
                                        <p className="text-red-600 text-sm mt-1">{errors.email}</p>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Phone Number
                                    </label>
                                    <input
                                        type="tel"
                                        value={data.phone}
                                        onChange={e => setData('phone', e.target.value)}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    />
                                </div>

                                {course.price > 0 && (
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Payment Method
                                        </label>
                                        <div className="space-y-2">
                                            <label className="flex items-center">
                                                <input
                                                    type="radio"
                                                    value="card"
                                                    checked={data.payment_method === 'card'}
                                                    onChange={e => setData('payment_method', e.target.value)}
                                                    className="mr-2"
                                                />
                                                Credit / Debit Card
                                            </label>
                                            <label className="flex items-center">
                                                <input
                                                    type="radio"
                                                    value="bank"
                                                    checked={data.payment_method === 'bank'}
                                                    onChange={e => setData('payment_method', e.target.value)}
                                                    className="mr-2"
                                                />
                                                Bank Transfer
                                            </label>
                                            <label className="flex items-center">
                                                <input
                                                    type="radio"
                                                    value="paypal"
                                                    checked={data.payment_method === 'paypal'}
                                                    onChange={e => setData('payment_method', e.target.value)}
                                                    className="mr-2"
                                                />
                                                PayPal
                                            </label>
                                        </div>
                                    </div>
                                )}

                                <div className="pt-4">
                                    <label className="flex items-center">
                                        <input
                                            type="checkbox"
                                            checked={data.terms_accepted}
                                            onChange={e => setData('terms_accepted', e.target.checked)}
                                            className="mr-2 rounded"
                                            required
                                        />
                                        <span className="text-sm text-gray-600">
                                            I agree to the terms and conditions and understand the course policies
                                        </span>
                                    </label>
                                    {errors.terms_accepted && (
                                        <p className="text-red-600 text-sm mt-1">{errors.terms_accepted}</p>
                                    )}
                                </div>

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full py-3 px-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 disabled:opacity-50"
                                >
                                    {processing ? 'Processing...' : 'Complete Enrollment'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}