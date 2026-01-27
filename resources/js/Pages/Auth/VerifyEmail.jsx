import React, { useState } from "react";
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { 
  EnvelopeIcon, 
  ArrowRightOnRectangleIcon,
  CheckCircleIcon,
  ExclamationTriangleIcon,
  ArrowPathIcon,
  ShieldCheckIcon
} from '@heroicons/react/24/outline';

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});
    const [isHovering, setIsHovering] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        post(route('verification.send'));
    };

    return (
        <GuestLayout>
            <Head title="Email Verification | IGRCFP" />

            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-gray-50 flex flex-col justify-center py-1 sm:px-3 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-md">
                    
                    <h2 className="mt-8 text-center text-3xl font-bold tracking-tight text-gray-900">
                        Verify Your Email
                    </h2>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        One final step to access your IGRCFP account
                    </p>
                </div>

                <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
                    <div className="bg-white py-8 px-6 shadow-xl rounded-2xl sm:px-10 border border-gray-100">
                        {/* Status Message */}
                        {status === 'verification-link-sent' && (
                            <div className="mb-8 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl">
                                <div className="flex items-start">
                                    <CheckCircleIcon className="h-6 w-6 text-green-600 mt-0.5 mr-3 flex-shrink-0" />
                                    <div>
                                        <h3 className="font-semibold text-green-800">Verification Email Sent!</h3>
                                        <p className="text-green-700 text-sm mt-1">
                                            A new verification link has been sent to your email address.
                                            Please check your inbox (and spam folder).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Main Content */}
                        <div className="space-y-6">
                            <div className="text-center">
                                <div className="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <EnvelopeIcon className="h-8 w-8 text-blue-600" />
                                </div>
                                
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                    Almost There!
                                </h3>
                                
                                <p className="text-gray-700 leading-relaxed">
                                    Welcome to the Institute of GRC and Financial Crime Prevention! 
                                    Before you can access all member benefits and professional resources, 
                                    we need to verify your email address.
                                </p>
                            </div>


                            {/* Didn't Receive Email Section */}
                            <div className="border-t border-gray-200 pt-6">
                                
                                <div className="space-y-4">
                                    {/* Resend Form */}
                                    <form onSubmit={submit} className="space-y-4">
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            onMouseEnter={() => setIsHovering(true)}
                                            onMouseLeave={() => setIsHovering(false)}
                                            className="group w-full flex items-center justify-center py-3 px-4 bg-gradient-to-r from-blue-900 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            {processing ? (
                                                <>
                                                    <ArrowPathIcon className="animate-spin h-5 w-5 mr-3" />
                                                    Sending...
                                                </>
                                            ) : (
                                                <>
                                                    <EnvelopeIcon className="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" />
                                                    Resend Verification Email
                                                    {isHovering && (
                                                        <span className="ml-2 text-blue-200 text-xs">
                                                            (Click to send)
                                                        </span>
                                                    )}
                                                </>
                                            )}
                                        </button>

                                        <p className="text-xs text-gray-500 text-center">
                                            You can request a new verification email every 2 minutes
                                        </p>
                                    </form>
                                </div>
                            </div>

                            {/* Contact Support */}
                            <div className="mt-6 pt-6 border-t border-gray-200">
                                <div className="text-center">
                                    <p className="text-sm text-gray-600 mb-2">
                                        Still having trouble? Our support team is here to help.
                                    </p>
                                    <a 
                                        href="mailto:support@igrcfp.org?subject=Email%20Verification%20Help" 
                                        className="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm"
                                    >
                                        Contact Support
                                        <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {/* Logout Option */}
                        <div className="mt-8 pt-6 border-t border-gray-200">
                            <div className="flex items-center justify-center">
                                <span className="text-sm text-gray-600 mr-3">Registered by mistake?</span>
                                <Link
                                    href={route('logout')}
                                    method="post"
                                    as="button"
                                    className="inline-flex items-center text-sm text-gray-700 hover:text-gray-900 font-medium px-4 py-2 border border-gray-300 hover:border-gray-400 rounded-lg transition-colors"
                                >
                                    <ArrowRightOnRectangleIcon className="h-4 w-4 mr-2" />
                                    Log Out & Cancel
                                </Link>
                            </div>
                        </div>

                        {/* Timer & Info */}
                        <div className="mt-8 text-center">
                            <div className="inline-flex items-center px-4 py-2 bg-gray-100 rounded-full">
                                <svg className="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span className="text-sm text-gray-700">
                                    Verification link expires in <span className="font-semibold">24 hours</span>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </GuestLayout>
    );
}