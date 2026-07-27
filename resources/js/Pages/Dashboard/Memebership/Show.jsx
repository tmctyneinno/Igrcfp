import React from 'react';
import toast from 'react-hot-toast';
import { Head, Link, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { 
  CheckCircleIcon, 
  ShieldCheckIcon, 
  SparklesIcon,
  StarIcon,
  ArrowRightIcon,
  CreditCardIcon
} from '@heroicons/react/24/outline';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MembershipsIndex({ auth, tiers, activeMembership }) {

    const addToCart = (planId) => {
        router.post(route('dashboard.memberships.add-to-cart', planId));
    };

    // Safety check: ensure tiers is an array
    if (!Array.isArray(tiers)) {
        return <div>Loading...</div>;
    }

    // Color mapping for tier levels
    const tierColors = {
        'starter': 'from-blue-50 to-blue-100 border-blue-200',
        'professional': 'from-indigo-50 to-indigo-100 border-indigo-200',
        'expert': 'from-purple-50 to-purple-100 border-purple-200',
        'enterprise': 'from-amber-50 to-amber-100 border-amber-200',
    };

    const getTierColor = (tierName) => {
        const name = tierName?.toLowerCase() || '';
        if (name.includes('starter')) return tierColors.starter;
        if (name.includes('professional')) return tierColors.professional;
        if (name.includes('expert')) return tierColors.expert;
        if (name.includes('enterprise')) return tierColors.enterprise;
        return 'from-gray-50 to-gray-100 border-gray-200';
    };

    // Get tier icon
    const getTierIcon = (tierName) => {
        const name = tierName?.toLowerCase() || '';
        if (name.includes('starter')) return <SparklesIcon className="w-6 h-6 text-blue-600" />;
        if (name.includes('professional')) return <ShieldCheckIcon className="w-6 h-6 text-indigo-600" />;
        if (name.includes('expert')) return <StarIcon className="w-6 h-6 text-purple-600" />;
        if (name.includes('enterprise')) return <CreditCardIcon className="w-6 h-6 text-amber-600" />;
        return <SparklesIcon className="w-6 h-6 text-gray-600" />;
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Membership Plans" />
            
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                {/* Page Header with gradient */}
                <div className="relative mb-12">
                    <div className="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl -mx-4 sm:-mx-6 lg:-mx-8 opacity-50"></div>
                    <div className="relative px-4 sm:px-6 lg:px-8 py-12">
                        <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <div className="flex items-center gap-3 mb-2">
                                    <ShieldCheckIcon className="w-8 h-8 text-blue-600" />
                                    <span className="text-sm font-semibold text-blue-600 uppercase tracking-wider">
                                        Membership Plans
                                    </span>
                                </div>
                                <h1 className="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">
                                    Choose Your Path
                                </h1>
                                <p className="mt-3 text-lg text-gray-600 max-w-2xl">
                                    Unlock premium mentorship opportunities and accelerate your career growth with the right membership tier.
                                </p>
                            </div>
                            {activeMembership && (
                                <div className="mt-4 md:mt-0">
                                    <span className="inline-flex items-center px-4 py-2 rounded-full bg-emerald-100 text-emerald-800 text-sm font-medium">
                                        <CheckCircleIcon className="w-4 h-4 mr-2" />
                                        Active Member
                                    </span>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Active Membership Banner */}
                <AnimatePresence>
                    {activeMembership && (
                        <motion.div
                            initial={{ opacity: 0, y: -20 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -20 }}
                            className="mb-10"
                        >
                            <div className="rounded-2xl border border-emerald-200 bg-emerald-50/80 backdrop-blur-sm p-6 text-emerald-800 shadow-sm">
                                <div className="flex items-start gap-4">
                                    <CheckCircleIcon className="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" />
                                    <div className="flex-1">
                                        <p className="font-semibold text-emerald-900">
                                            You're currently an active member
                                        </p>
                                        <p className="text-sm text-emerald-700 mt-1">
                                            Visit your{' '}
                                            <Link
                                                href={route('dashboard.memberships.status')}
                                                className="font-semibold underline hover:text-emerald-900 transition-colors"
                                            >
                                                membership status
                                            </Link>
                                            {' '}to review your plan details, expiry date, and approval status.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </motion.div>
                    )}
                </AnimatePresence>

                {/* Tiers */}
                <div className="space-y-16">
                    {tiers.map((tier, tierIndex) => (
                        <motion.div
                            key={tier.id}
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: tierIndex * 0.1 }}
                            className="relative"
                        >
                            {/* Tier Header with gradient background */}
                            <div className={`bg-gradient-to-r ${getTierColor(tier.name)} rounded-2xl p-6 mb-8 border`}>
                                <div className="flex items-center gap-3">
                                    {getTierIcon(tier.name)}
                                    <div>
                                        <span className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tier {tierIndex + 1}
                                        </span>
                                        <h2 className="text-3xl font-bold text-gray-900 mt-1">
                                            {tier.name}
                                        </h2>
                                    </div>
                                </div>
                                {tier.description && (
                                    <p className="mt-2 text-gray-600 max-w-2xl ml-9">
                                        {tier.description}
                                    </p>
                                )}
                            </div>

                            {/* Plans Grid */}
                            {Array.isArray(tier.plans) && tier.plans.length > 0 ? (
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    {tier.plans.map((plan, planIndex) => (
                                        <motion.div
                                            key={plan.id}
                                            initial={{ opacity: 0, scale: 0.95 }}
                                            animate={{ opacity: 1, scale: 1 }}
                                            transition={{ delay: (tierIndex * 0.1) + (planIndex * 0.05) }}
                                            whileHover={{ y: -4 }}
                                            className="group relative bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300"
                                        >
                                            {/* Premium badge for highest plan */}
                                            {planIndex === tier.plans.length - 1 && tier.plans.length > 1 && (
                                                <div className="absolute top-0 right-0">
                                                    <div className="bg-gradient-to-r from-amber-400 to-amber-500 text-white text-xs font-bold px-4 py-1 rounded-bl-lg">
                                                        Most Popular
                                                    </div>
                                                </div>
                                            )}

                                            <div className="p-6 flex flex-col h-full">
                                                <div>
                                                    <h3 className="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                        {plan.name}
                                                    </h3>
                                                    
                                                    <div className="mt-3 flex items-baseline gap-2">
                                                        <span className="text-4xl font-extrabold text-blue-600">
                                                            {plan.currency} {Number(plan.price).toFixed(2)}
                                                        </span>
                                                        <span className="text-sm text-gray-500">
                                                            /{plan.billing_interval}
                                                        </span>
                                                    </div>

                                                    {/* Benefits List */}
                                                    {(Array.isArray(plan.benefits) ? plan.benefits : Array.isArray(tier.benefits) ? tier.benefits : []).length > 0 && (
                                                        <ul className="mt-6 space-y-3">
                                                            {(Array.isArray(plan.benefits) ? plan.benefits : Array.isArray(tier.benefits) ? tier.benefits : []).map((benefit, i) => (
                                                                <li key={i} className="flex items-start gap-3 text-sm text-gray-600">
                                                                    <CheckCircleIcon className="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" />
                                                                    <span>{benefit}</span>
                                                                </li>
                                                            ))}
                                                        </ul>
                                                    )}
                                                </div>

                                                {/* Purchase Button */}
                                                <div className="mt-8">
                                                    <button
                                                        onClick={() => addToCart(plan.id)}
                                                        className="w-full group relative inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#061E34] text-white font-semibold hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg"
                                                    >
                                                        <span>Purchase Plan</span>
                                                        <ArrowRightIcon className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                                                    </button>
                                                    <p className="mt-2 text-xs text-gray-400 text-center">
                                                        Secure payment • Instant access
                                                    </p>
                                                </div>
                                            </div>
                                        </motion.div>
                                    ))}
                                </div>
                            ) : (
                                <div className="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
                                    <p className="text-gray-500">No plans available for this tier yet.</p>
                                </div>
                            )}
                        </motion.div>
                    ))}
                </div>

                {/* Footer CTA */}
                <div className="mt-16 text-center border-t border-gray-200 pt-12">
                    <p className="text-gray-500 text-sm">
                        Need help choosing the right plan?{' '}
                        <Link href={route('contact')} className="text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                            Contact our team
                        </Link>
                    </p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}