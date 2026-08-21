// resources/js/Pages/Certificate/PublicVerify.jsx

import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function PublicVerify({ auth, valid = null, certificate = null, searched_number = null, message = null }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        certificate_number: searched_number || '',
    });

    const [verificationResult, setVerificationResult] = useState(false);
    const [resultData, setResultData] = useState(certificate);
    const statusStyles = {
        valid: { badge: 'bg-green-100 text-green-800', dot: 'bg-green-500' },
        active: { badge: 'bg-green-100 text-green-800', dot: 'bg-green-500' },
        expired: { badge: 'bg-yellow-100 text-yellow-800', dot: 'bg-yellow-500' },
        revoked: { badge: 'bg-red-100 text-red-800', dot: 'bg-red-500' },
        suspended: { badge: 'bg-gray-100 text-gray-800', dot: 'bg-gray-500' },
    };
    const currentStatusStyle = statusStyles[resultData?.status?.toLowerCase()] || statusStyles.suspended;

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('certificate.verify.public.check'), {
            preserveState: true,
            onSuccess: (page) => {
                setVerificationResult(true);
                setResultData(page.props.certificate);
            },
        });
    };

    const handleReset = () => {
        reset();
        setVerificationResult(false);
        setResultData(null);
    };

    return (
        <> 
        <GuestLayout auth={auth}>
            <Head title="Verify Certificate - IGRCFP" />
            <div className="min-h-screen bg-gradient-to-b from-gray-50 to-white">
                {/* Header Section */}
                <div className="bg-blue-900 text-white">
                    <div className="max-w-4xl mx-auto px-4 py-16 sm:px-6 lg:px-8 text-center">
                        <div className="inline-flex items-center justify-center w-16 h-16 bg-blue-800 rounded-full mb-6">
                            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h1 className="text-3xl sm:text-4xl font-bold mb-4">
                            Certificate Verification
                        </h1>
                        <p className="text-blue-200 text-lg max-w-2xl mx-auto">
                            Verify the authenticity of IGRCFP certificates. Enter the certificate number found on the document to confirm its validity.
                        </p>
                    </div>
                </div>

                {/* Main Content */}
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
                    {/* Verification Form Card */}
                    <div className="bg-white rounded-2xl shadow-xl p-6 sm:p-8 mb-8">
                        <div className="flex items-center mb-6">
                            <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg className="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 className="text-xl font-semibold text-gray-900">Enter Certificate Number</h2>
                                <p className="text-sm text-gray-500">Found at the bottom of your certificate</p>
                            </div>
                        </div>

                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label htmlFor="certificate_number" className="block text-sm font-medium text-gray-700 mb-2">
                                    Certificate Number
                                </label>
                                <div className="relative">
                                    <input
                                        type="text"
                                        id="certificate_number"
                                        value={data.certificate_number}
                                        onChange={(e) => setData('certificate_number', e.target.value.toUpperCase())}
                                        placeholder="e.g., CERT-2026-000001-ABC123"
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-400 uppercase tracking-wider"
                                        required
                                    />
                                    {data.certificate_number && (
                                        <button
                                            type="button"
                                            onClick={() => {
                                                setData('certificate_number', '');
                                                handleReset();
                                            }}
                                            className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                        >
                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    )}
                                </div>
                                {errors.certificate_number && (
                                    <p className="mt-2 text-sm text-red-600">{errors.certificate_number}</p>
                                )}
                            </div>

                            <div className="flex flex-col sm:flex-row gap-3">
                                <button
                                    type="submit"
                                    disabled={processing || !data.certificate_number}
                                    className="flex-1 bg-blue-900 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {processing ? (
                                        <span className="flex items-center justify-center">
                                            <svg className="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                            </svg>
                                            Verifying...
                                        </span>
                                    ) : (
                                        'Verify Certificate'
                                    )}
                                </button>
                                <button
                                    type="button"
                                    onClick={handleReset}
                                    className="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition duration-200"
                                >
                                    Clear
                                </button>
                            </div>
                        </form>

                        {/* Help Text */}
                        <div className="mt-6 p-4 bg-blue-50 rounded-lg">
                            <div className="flex">
                                <svg className="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div className="text-sm text-blue-800">
                                    <p className="font-medium mb-1">Where to find your certificate number?</p>
                                    <p>The certificate number is located at the bottom of your certificate document, formatted as <code className="bg-blue-100 px-1.5 py-0.5 rounded text-xs">CERT-YYYY-XXXXXX-XXXXXX</code></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Verification Result */}
                    {verificationResult && (
                        <div className={`rounded-2xl shadow-xl p-6 sm:p-8 mb-8 ${resultData ? 'bg-white border border-gray-200' : 'bg-white border border-gray-200'}`}>
                            {resultData ? (
                                <>
                                    {/* Valid Certificate */}
                                    <div className="flex items-center mb-6">
                                        <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 className="text-xl font-bold text-green-700">Certificate Verified</h3>
                                            <p className="text-sm text-gray-500">This is a valid IGRCFP certificate</p>
                                        </div>
                                        {/* <div className="ml-auto">
                                            <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${currentStatusStyle.badge}`}>
                                                <span className={`w-2 h-2 rounded-full mr-2 ${currentStatusStyle.dot}`}></span>
                                                {resultData.status}
                                            </span>
                                        </div> */}
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {/* Certificate Details */}
                                        <div className="space-y-4">
                                            <h4 className="text-sm font-semibold text-gray-500 uppercase tracking-wider">Certificate Details</h4>
                                            
                                            <div className="bg-gray-50 rounded-lg p-4 space-y-3">
                                                <div>
                                                    <label className="block text-xs text-gray-500 uppercase tracking-wider">Certificate Number</label>
                                                    <p className="text-sm font-mono font-bold text-gray-900">{resultData.number}</p>
                                                </div>
                                                <div className="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label className="block text-xs text-gray-500 uppercase tracking-wider">Issue Date</label>
                                                        <p className="text-sm font-medium text-gray-900">{resultData.issue_date}</p>
                                                    </div>
                                                    <div>
                                                        <label className="block text-xs text-gray-500 uppercase tracking-wider">Status</label>
                                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${currentStatusStyle.badge}`}>
                                                            <span className={`w-2 h-2 rounded-full mr-2 ${currentStatusStyle.dot}`}></span>
                                                            {resultData.status}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label className="block text-xs text-gray-500 uppercase tracking-wider">Issuing Body</label>
                                                    <p className="text-sm font-medium text-gray-900">{resultData.issuing_body_full}</p>
                                                </div>
                                            </div>
                                        </div>

                                        {/* Recipient Details */}
                                        <div className="space-y-4">
                                            <h4 className="text-sm font-semibold text-gray-500 uppercase tracking-wider">Recipient Information</h4>
                                            
                                            <div className="bg-gray-50 rounded-lg p-4 space-y-3">
                                                <div>
                                                    <label className="block text-xs text-gray-500 uppercase tracking-wider">Recipient Name</label>
                                                    <p className="text-sm font-bold text-gray-900">{resultData.recipient_name}</p>
                                                </div>
                                                <div>
                                                    <label className="block text-xs text-gray-500 uppercase tracking-wider">Email</label>
                                                    <p className="text-sm font-mono text-gray-600">{resultData.recipient_email}</p>
                                                </div>
                                                <div>
                                                    <label className="block text-xs text-gray-500 uppercase tracking-wider">Course/Programme</label>
                                                    <p className="text-sm font-medium text-gray-900">{resultData.course_title}</p>
                                                </div>
                                                <div className="grid grid-cols-2 gap-3">
                                                    {/* <div>
                                                        <label className="block text-xs text-gray-500 uppercase tracking-wider">Course Code</label>
                                                        <p className="text-sm font-medium text-gray-900">{resultData.course_code}</p>
                                                    </div> */}
                                                    <div>
                                                        <label className="block text-xs text-gray-500 uppercase tracking-wider">Grade</label>
                                                        <p className="text-sm font-medium text-gray-900">{resultData.grade}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Verification Badge */}
                                    <div className="mt-6 pt-6 border-t border-gray-200">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center text-sm text-gray-500">
                                                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Verified on {new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
                                            </div>
                                            <button
                                                onClick={() => window.print()}
                                                className="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-200"
                                            >
                                                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                                Print Verification
                                            </button>
                                        </div>
                                    </div>
                                </>
                            ) : (
                                <>
                                    {/* Invalid Certificate */}
                                    <div className="text-center py-8">
                                        <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg className="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                        </div>
                                        <h3 className="text-xl font-bold text-red-700 mb-2">Certificate Not Found</h3>
                                        <p className="text-gray-500 mb-6 max-w-md mx-auto">{message || 'No valid certificate found with the provided number.'}</p>
                                        <div className="bg-gray-50 rounded-lg p-4 inline-block">
                                            <p className="text-sm text-gray-600">
                                                <span className="font-medium">Searched Number:</span>{' '}
                                                <code className="bg-white px-2 py-1 rounded text-red-600 font-mono">{searched_number}</code>
                                            </p>
                                        </div>
                                    </div>
                                </>
                            )}
                        </div>
                    )}

                    {/* Trust Indicators */}
                    {!verificationResult && (
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div className="bg-white rounded-xl shadow-sm p-6 text-center">
                                <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg className="w-6 h-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <h3 className="text-sm font-semibold text-gray-900 mb-1">Secure Verification</h3>
                                <p className="text-xs text-gray-500">All verifications are encrypted and secure</p>
                            </div>
                            <div className="bg-white rounded-xl shadow-sm p-6 text-center">
                                <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg className="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h3 className="text-sm font-semibold text-gray-900 mb-1">Tamper-Proof</h3>
                                <p className="text-xs text-gray-500">Certificates cannot be forged or altered</p>
                            </div>
                            <div className="bg-white rounded-xl shadow-sm p-6 text-center">
                                <div className="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg className="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-sm font-semibold text-gray-900 mb-1">Instant Verification</h3>
                                <p className="text-xs text-gray-500">Real-time certificate validation</p>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </GuestLayout>
        </>
    );
}