import React from 'react';
import toast from 'react-hot-toast';
import { Head, Link, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MembershipsIndex({ auth, tiers, activeMembership }) {

    const addToCart = (planId) => {
        router.post(route('dashboard.memberships.add-to-cart', planId));
    };

    // Safety check: ensure tiers is an array
    if (!Array.isArray(tiers)) {
        return <div>Loading...</div>;
    }
  
    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {/* Page Header */}
                <div className="mb-10">
                    <h2 className="text-3xl font-bold text-gray-900">Membership Plans</h2>
                    <p className="mt-2 text-gray-600">
                        Choose the membership tier that fits your career stage and unlock mentorship access.
                    </p>
                </div>

                {/* Active Membership Banner */}
                {activeMembership && (
                    <div className="mb-8 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                        You already have an active membership. Visit your{' '}
                        <Link
                            href={route('dashboard.memberships.status')}
                            className="font-semibold underline"
                        >
                            membership status
                        </Link>
                        {' '}to review expiry and approvals.
                    </div>
                )}

                {/* Tiers */}
                <div className="space-y-12">
                    {tiers.map((tier) => (
                        <div key={tier.id} className="border-b border-gray-200 pb-10 last:border-0">

                            {/* Tier Header */}
                            <div className="mb-6 flex flex-col gap-1">
                                <span className="uppercase tracking-widest text-gray-400 text-sm font-semibold">Tier</span>
                                <h3 className="text-2xl font-bold text-gray-900">{tier.name}</h3>
                                {tier.description && (
                                    <p className="text-gray-600 max-w-2xl">{tier.description}</p>
                                )}
                            </div>

                            {/* Plans Row Container */}
                            {/* Changed from grid to flex row */}
                            <div className="flex flex-row gap-6 overflow-x-auto pb-4 snap-x">
                                {/* Safety check: ensure tier.plans is an array */}
                                {Array.isArray(tier.plans) && tier.plans.length > 0 ? (
                                    tier.plans.map((plan) => (
                                        <div
                                            key={plan.id}
                                            // Fixed width for row layout, snap-align for scrolling
                                            className="min-w-[300px] w-full md:w-[350px] snap-center bg-white rounded-2xl shadow-md border border-slate-200 p-6 flex flex-col justify-between shrink-0"
                                        >
                                            <div>
                                                <h4 className="text-lg font-semibold text-gray-900">{plan.name}</h4>
                                                <div className="mt-2 flex items-baseline gap-2">
                                                    <span className="text-2xl font-bold text-blue-900">
                                                        {plan.currency} {Number(plan.price).toFixed(2)}
                                                    </span>
                                                    <span className="text-xs text-gray-500">/{plan.billing_interval}</span>
                                                </div>

                                                {/* Benefits List */}
                                                <ul className="mt-4 space-y-2 text-sm text-gray-600 list-disc list-inside">
                                                    {/* Safety check: ensure benefits is an array */}
                                                    {(Array.isArray(plan.benefits) ? plan.benefits : Array.isArray(tier.benefits) ? tier.benefits : []).map((benefit, i) => (
                                                        <li key={i}>{benefit}</li>
                                                    ))}
                                                </ul>
                                            </div>

                                            {/* Purchase Button */}
                                            <button
                                                onClick={() => addToCart(plan.id)}
                                                className="mt-6 w-full rounded-lg bg-blue-900 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition"
                                            >
                                                Purchase Plan
                                            </button>
                                        </div>
                                    ))
                                ) : (
                                    <div className="rounded-xl border border-dashed border-slate-300 p-6 text-gray-500 min-w-[300px]">
                                        No plans available for this tier yet.
                                    </div>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}