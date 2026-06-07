import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link } from '@inertiajs/react';

export default function ScholarshipAcceptedSimple({ application, auth }) {
    return (
        <>
            <GuestLayout auth={auth} forceWhiteNavbar>
                <Head title="Scholarship Accepted - IGRCFP" />
                
                <div className="pt-20 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                    <div className="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-2xl transform transition-all">
                        {/* Success Animation */}
                        <div className="text-center">
                            <div className="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 animate-bounce">
                                <svg 
                                    className="h-14 w-14 text-green-600" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path 
                                        strokeLinecap="round" 
                                        strokeLinejoin="round" 
                                        strokeWidth={2} 
                                        d="M5 13l4 4L19 7" 
                                    />
                                </svg>
                            </div>
                            
                            <h1 className="mt-6 text-3xl font-extrabold text-gray-900">
                                🎉 Scholarship Accepted!
                            </h1>
                            
                            <div className="mt-6 text-left">
                                <p className="text-lg text-gray-700">
                                    Dear <span className="font-semibold text-blue-600">{application.full_name}</span>,
                                </p>
                                <p className="mt-4 text-gray-600 leading-relaxed">
                                    Thank you for accepting the IGRCFP scholarship. We're absolutely thrilled to have you on board!
                                </p>
                                <div className="mt-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-5 text-white">
                                    <p className="font-semibold mb-2">✨ What happens next?</p>
                                    <ul className="space-y-2 text-sm">
                                        <li>✓ Onboarding instructions will be sent to your email within 2-3 business days</li>
                                        <li>✓ You'll receive access to your learning dashboard</li>
                                        <li>✓ Welcome kit and resources will be provided</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <Link
                                href={route('dashboard.index')}
                                className="mt-8 w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 shadow-lg"
                            >
                                Go to Dashboard
                                <svg className="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </GuestLayout>
        </> 
    );
}