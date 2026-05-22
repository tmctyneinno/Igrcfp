// resources/js/Pages/Dashboard/Checkout/Success.jsx

import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function CheckoutSuccess({ 
    enrollments = [], 
    memberships = [], 
    specificEnrollment = null,
    latestMembership = null,
    isMembershipPurchase = false,
    isMixedPurchase = false 
}) {
    const confirmedEnrollment = specificEnrollment || enrollments?.[0];
    const confirmedMembership = latestMembership || memberships?.[0];
    
    const isFree = (item) => item?.payment_method === 'free' || Number(item?.amount) === 0;
    const hasEnrollments = enrollments.length > 0;
    const hasMemberships = memberships.length > 0;
    
    // Determine the primary confirmation type
    const isFreeEnrollment = confirmedEnrollment && isFree(confirmedEnrollment);
    const isPaidMembership = hasMemberships && (!hasEnrollments || isMembershipPurchase);
    const isFreeMembership = confirmedMembership && isFree(confirmedMembership);

    return (
        <AuthenticatedLayout>
            <Head title={
                isMixedPurchase ? 'Purchase Confirmed' :
                isPaidMembership ? 'Membership Purchase Successful' :
                isFreeMembership ? 'Membership Activated' :
                isFreeEnrollment ? 'Enrollment Confirmed' : 
                'Checkout Successful'
            } />
            
            <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="bg-white rounded-xl shadow-lg overflow-hidden text-center">
                    {/* Header Banner */}
                    <div className={`${
                        isPaidMembership || isMixedPurchase ? 'bg-gradient-to-r from-purple-600 to-indigo-600' :
                        isFreeMembership ? 'bg-gradient-to-r from-teal-600 to-cyan-600' :
                        isFreeEnrollment ? 'bg-blue-900' : 
                        'bg-green-500'
                    } p-6`}>
                        <svg className="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                     
                    <div className="p-8">
                        <h1 className="text-2xl font-bold text-gray-900 mb-2">
                            {isMixedPurchase && 'Purchase Confirmed!'}
                            {isPaidMembership && 'Membership Purchase Successful!'}
                            {isFreeMembership && 'Membership Activated!'}
                            {isFreeEnrollment && 'Enrollment Confirmed!'}
                            {!isMixedPurchase && !isPaidMembership && !isFreeMembership && !isFreeEnrollment && 'Payment Successful!'}
                        </h1>
                        
                        <p className="text-gray-600 mb-6">
                            {isMixedPurchase && 'Your course enrollment and membership purchase have been confirmed.'}
                            {isPaidMembership && !isMixedPurchase && 
                                (confirmedMembership?.is_pending_approval 
                                    ? 'Your membership payment has been received. It is pending admin approval and will be activated shortly.'
                                    : 'Your membership is now active. Enjoy all the benefits of your plan!'
                                )
                            }
                            {isFreeMembership && 'Your free membership has been activated. Start exploring your benefits!'}
                            {isFreeEnrollment && 'Your free course access is active. You can start learning from your dashboard now.'}
                            {!isMixedPurchase && !isPaidMembership && !isFreeMembership && !isFreeEnrollment && 
                                'Thank you for your purchase. You have been successfully enrolled in your courses.'
                            }
                        </p>

                        {/* Membership Details */}
                        {hasMemberships && (
                            <div className="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-4 mb-6 text-left border border-purple-200">
                                <h3 className="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg className="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    Membership Details
                                </h3>
                                
                                {memberships.map((membership, index) => (
                                    <div key={membership.id} className={`${index > 0 ? 'mt-3 pt-3 border-t border-purple-200' : ''}`}>
                                        <div className="space-y-2 text-sm text-gray-600">
                                            <p>
                                                <span className="font-medium text-gray-800">Plan:</span> {membership.plan_name}
                                                {membership.tier_name && (
                                                    <span className="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                        {membership.tier_name}
                                                    </span>
                                                )}
                                            </p>
                                            <p>
                                                <span className="font-medium text-gray-800">Membership ID:</span> #{membership.id}
                                            </p>
                                            <p>
                                                <span className="font-medium text-gray-800">Billing:</span> {membership.billing_interval ? membership.billing_interval.charAt(0).toUpperCase() + membership.billing_interval.slice(1) : 'Monthly'}
                                            </p>
                                            {membership.amount > 0 && (
                                                <p>
                                                    <span className="font-medium text-gray-800">Amount:</span> £{Number(membership.amount).toFixed(2)}
                                                </p>
                                            )}
                                            <p>
                                                <span className="font-medium text-gray-800">Status:</span>{' '}
                                                <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                    membership.is_active ? 'bg-green-100 text-green-800' :
                                                    membership.is_pending_approval ? 'bg-yellow-100 text-yellow-800' :
                                                    'bg-gray-100 text-gray-800'
                                                }`}>
                                                    {membership.status_label}
                                                </span>
                                            </p>
                                            {membership.expires_at && (
                                                <p>
                                                    <span className="font-medium text-gray-800">Expires:</span> {membership.expires_at}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                ))}
                                
                                {confirmedMembership?.is_pending_approval && (
                                    <div className="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p className="text-sm text-yellow-800">
                                            <span className="font-medium">⏳ Pending Approval:</span> Your membership will be activated once an administrator reviews and approves your application. This usually takes 1-2 business days.
                                        </p>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Course Enrollment Details */}
                        {hasEnrollments && (
                            <div className="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                                <h3 className="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg className="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    {hasMemberships ? 'Course Enrollment Details' : 'Enrollment Details'}
                                </h3>
                                
                                {enrollments.map((enrollment, index) => (
                                    <div key={enrollment.id} className={`${index > 0 ? 'mt-3 pt-3 border-t border-gray-200' : ''}`}>
                                        <div className="space-y-2 text-sm text-gray-600">
                                            <p>
                                                <span className="font-medium text-gray-800">Course:</span> {enrollment.course_title}
                                            </p>
                                            <p>
                                                <span className="font-medium text-gray-800">Enrollment:</span> #{enrollment.id}
                                            </p>
                                            <p>
                                                <span className="font-medium text-gray-800">Access:</span>{' '}
                                                {isFree(enrollment) ? 'Free course' : 'Paid course'}
                                            </p>
                                            {enrollment.amount > 0 && (
                                                <p>
                                                    <span className="font-medium text-gray-800">Amount:</span> £{Number(enrollment.amount).toFixed(2)}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Action Buttons */}
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            {/* Course Actions */}
                            {confirmedEnrollment?.course_slug && (
                                <Link
                                    href={route('dashboard.courses.show', confirmedEnrollment.course_slug)}
                                    className="px-6 py-2 bg-gradient-to-r from-blue-900 to-indigo-900 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200"
                                >
                                    Start Course
                                </Link>
                            )}
                            
                            {/* Membership Actions */}
                            {hasMemberships && (
                                <Link
                                    href={route('dashboard.memberships.status')}
                                    className="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-500 hover:to-indigo-500 transition duration-200"
                                >
                                    View Membership
                                </Link>
                            )}

                            {/* My Courses */}
                            {hasEnrollments && (
                                <Link
                                    href={route('dashboard.my-courses')}
                                    className="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition duration-200"
                                >
                                    My Courses
                                </Link>
                            )}
                            
                            {/* Dashboard */}
                            <Link
                                href={route('dashboard.index')}
                                className="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200"
                            >
                                Go to Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}