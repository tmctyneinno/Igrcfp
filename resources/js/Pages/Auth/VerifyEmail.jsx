import React from "react";
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { EnvelopeIcon, ArrowPathIcon, ShieldCheckIcon } from '@heroicons/react/24/outline';

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});

    return (
        <GuestLayout>
            <Head title="Verify Email | IGRCFP" />

            <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-blue-50 p-4">
                <div className="w-full max-w-md">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                            <ShieldCheckIcon className="h-8 w-8 text-white" />
                        </div>
                        <h1 className="text-2xl font-bold text-gray-900">Verify Your Email</h1>
                        <p className="text-gray-600 mt-2">One final step to access your account</p>
                    </div>

                    {/* Card */}
                    <div className="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        {/* Status Message */}
                        {status === 'verification-link-sent' && (
                            <div className="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p className="text-sm text-green-700 font-medium">
                                    ✅ New verification link sent to your email
                                </p>
                            </div>
                        )}

                        {/* Main Content */}
                        <div className="text-center mb-6">
                            <EnvelopeIcon className="h-12 w-12 text-blue-400 mx-auto mb-4" />
                            <p className="text-gray-700">
                                We've sent a verification link to your email address. 
                                Please click the link to activate your account.
                            </p>
                        </div>

                        {/* Resend Button */}
                        <form onSubmit={(e) => { e.preventDefault(); post(route('verification.send')); }}>
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full flex items-center justify-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50"
                            >
                                {processing ? (
                                    <>
                                        <ArrowPathIcon className="animate-spin h-5 w-5 mr-2" />
                                        Sending...
                                    </>
                                ) : (
                                    'Resend Verification Email'
                                )}
                            </button>
                        </form>

                        {/* Help Text */}
                        <div className="mt-6 p-4 bg-gray-50 rounded-lg">
                            <p className="text-sm text-gray-600 mb-2">📝 Didn't receive the email?</p>
                            <ul className="text-xs text-gray-500 space-y-1">
                                <li>• Check your spam folder</li>
                                <li>• Wait a few minutes</li>
                                <li>• Contact support@igrcfp.org</li>
                            </ul>
                        </div>

                        {/* Logout */}
                        <div className="mt-6 pt-5 border-t border-gray-200 text-center">
                            <Link
                                href={route('logout')}
                                method="post"
                                as="button"
                                className="text-sm text-gray-500 hover:text-gray-700"
                            >
                                Signed in by mistake? Log out
                            </Link>
                        </div>
                    </div>

                    {/* Footer */}
                    <div className="mt-8 text-center">
                        <p className="text-xs text-gray-400">
                            Link expires in 24 hours • IGRCFP Professional Certification
                        </p>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}