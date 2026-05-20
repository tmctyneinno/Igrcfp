import React from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
    CheckCircleIcon,
    ClockIcon,
    XCircleIcon,
    ExclamationCircleIcon,
} from '@heroicons/react/24/outline';

const getStatusConfig = (status) => {
    switch (status) {
        case 'active':
            return {
                classes: 'border-emerald-200 bg-emerald-50 text-emerald-800',
                icon: CheckCircleIcon,
                message: 'Your membership is active. You can apply for mentorship and manage your mentor profile.',
            };
        case 'pending_approval':
            return {
                classes: 'border-amber-200 bg-amber-50 text-amber-800',
                icon: ClockIcon,
                message: 'Your membership is awaiting admin approval. We will notify you once it is approved.',
            };
        case 'expired':
            return {
                classes: 'border-gray-200 bg-gray-50 text-gray-700',
                icon: ExclamationCircleIcon,
                message: 'Your membership has expired. Renew to restore access.',
            };
        case 'cancelled':
            return {
                classes: 'border-rose-200 bg-rose-50 text-rose-800',
                icon: XCircleIcon,
                message: 'This membership was cancelled. Contact support if this is unexpected.',
            };
        default:
            return {
                classes: 'border-slate-200 bg-white text-gray-700',
                icon: ClockIcon,
                message: '',
            };
    }
};

export default function MembershipStatus({ auth, membership }) {
    const { user } = auth;

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

                {/* Page Header */}
                <div className="mb-8">
                    <h2 className="text-3xl font-bold text-gray-900">Your Membership</h2>
                    <p className="mt-2 text-gray-600">
                        Track your membership approval, renewal, and next steps.
                    </p>
                </div>

                {/* No Membership State */}
                {!membership ? (
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm"
                    >
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">No membership found</h3>
                        <p className="text-gray-600 mb-6">
                            Select a plan to unlock mentorship access and member benefits.
                        </p>
                        <Link
                            href={route('dashboard.memberships.index')}
                            className="inline-flex items-center px-6 py-3 rounded-lg bg-blue-900 text-white font-semibold hover:bg-blue-800 transition"
                        >
                            View Membership Plans
                        </Link>
                    </motion.div>
                ) : (
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                    >
                        {/* Status Banner */}
                        {(() => {
                            const { classes, icon: StatusIcon, message } = getStatusConfig(membership.status);
                            return (
                                <div className={`rounded-2xl border ${classes} p-5 mb-6`}>
                                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div className="flex items-center gap-3">
                                            <StatusIcon className="w-8 h-8 flex-shrink-0" />
                                            <div>
                                                <p className="text-xs uppercase tracking-widest">Status</p>
                                                <h3 className="text-2xl font-semibold">{membership.status_label}</h3>
                                            </div>
                                        </div>
                                        <p className="text-sm max-w-sm">{message}</p>
                                    </div>
                                </div>
                            );
                        })()}

                        {/* Details Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {/* Plan Details */}
                            <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h4 className="text-lg font-semibold text-gray-900 mb-4">Plan Details</h4>
                                <div className="space-y-2 text-sm text-gray-600">
                                    <p>
                                        Plan:{' '}
                                        <span className="font-semibold text-gray-900">
                                            {membership.plan?.name ?? 'N/A'}
                                        </span>
                                    </p>
                                    <p>
                                        Tier:{' '}
                                        <span className="font-semibold text-gray-900">
                                            {membership.plan?.tier?.name ?? 'N/A'}
                                        </span>
                                    </p>
                                    <p>
                                        Billing:{' '}
                                        <span className="font-semibold text-gray-900">
                                            {membership.plan?.billing_interval ?? 'N/A'}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            {/* Timeline */}
                            <div className="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                <h4 className="text-lg font-semibold text-gray-900 mb-4">Timeline</h4>
                                <div className="space-y-2 text-sm text-gray-600">
                                    <p>
                                        Purchased:{' '}
                                        <span className="font-semibold text-gray-900">
                                            {membership.purchased_at ?? 'N/A'}
                                        </span>
                                    </p>
                                    <p>
                                        Approved:{' '}
                                        <span className="font-semibold text-gray-900">
                                            {membership.approved_at ?? 'Pending'}
                                        </span>
                                    </p>
                                    <p>
                                        Expires:{' '}
                                        <span className="font-semibold text-gray-900">
                                            {membership.expires_at ?? 'N/A'}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="mt-8 flex flex-wrap gap-3">
                            <Link
                                href={route('dashboard.mentors.index')}
                                className="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition"
                            >
                                Discover Mentors
                            </Link>
                            <Link
                                href={route('dashboard.mentorships.index')}
                                className="inline-flex items-center px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition"
                            >
                                Mentorship Dashboard
                            </Link>
                        </div>
                    </motion.div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}