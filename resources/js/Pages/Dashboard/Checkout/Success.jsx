import React from 'react';
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function CheckoutSuccess({ enrollments }) {
    return (
        <GuestLayout>
            <Head title="Checkout Successful" />
            
            <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="bg-white rounded-xl shadow-lg overflow-hidden text-center">
                    <div className="bg-green-500 p-6">
                        <svg className="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <div className="p-8">
                        <h1 className="text-2xl font-bold text-gray-900 mb-2">
                            Payment Successful!
                        </h1>
                        <p className="text-gray-600 mb-6">
                            Thank you for your enrollment. You have been successfully enrolled in your courses.
                        </p>

                        <div className="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                            <h3 className="font-semibold text-gray-900 mb-2">Enrollment Details</h3>
                            {enrollments && enrollments.length > 0 ? (
                                enrollments.map((enrollment, index) => (
                                    <p key={index} className="text-sm text-gray-600">
                                        Course {index + 1}: Enrollment #{enrollment.id}
                                    </p>
                                ))
                            ) : (
                                <p className="text-sm text-gray-600">Your enrollment has been confirmed.</p>
                            )}
                        </div>

                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link
                                href="/courses"
                                className="px-6 py-2 bg-gradient-to-r from-blue-900 to-indigo-900 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700"
                            >
                                Browse More Courses
                            </Link>
                            <Link
                                href="/dashboard"
                                className="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                            >
                                Go to Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}