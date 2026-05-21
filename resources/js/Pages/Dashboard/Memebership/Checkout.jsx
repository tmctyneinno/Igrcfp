import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
    ShieldCheckIcon,
    LockClosedIcon,
} from '@heroicons/react/24/outline';

export default function MembershipCheckout({ auth, cart, membershipItems, user }) {

    const [form, setForm] = useState({
        name: user.name ?? '',
        email: user.email ?? '',
        phone: '',
        terms_accepted: false,
    });

    const [errors, setErrors] = useState({});
    const [isSubmitting, setIsSubmitting] = useState(false);

    const currency = membershipItems[0]?.plan?.currency ?? '£';
    const totalAmount = cart?.total_amount ?? 0;

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setForm(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value,
        }));
    };

    const handleSubmit = () => {
        setIsSubmitting(true);
        router.post(route('checkout.process'), form, {
            preserveState: true,
            onSuccess: () => {
                setIsSubmitting(false);
            },
            onError: (errs) => {
                setErrors(errs);
                setIsSubmitting(false);
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

                {/* Page Header */}
                <div className="mb-8">
                    <h2 className="text-3xl font-bold text-gray-900">Membership Checkout</h2>
                    <p className="mt-2 text-gray-600">
                        Confirm your membership details and complete payment.
                    </p>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {/* Billing Form */}
                    <div className="lg:col-span-2">
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            className="bg-white rounded-2xl shadow-md p-6 border border-slate-200"
                        >
                            <h3 className="text-lg font-semibold text-gray-900 mb-6">
                                Billing Information
                            </h3>

                            <div className="space-y-4">
                                {/* Full Name */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Full Name *
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        value={form.name}
                                        onChange={handleChange}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        required
                                    />
                                    {errors.name && (
                                        <p className="text-sm text-rose-600 mt-1">{errors.name}</p>
                                    )} 
                                </div>

                                {/* Email */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Email Address *
                                    </label>
                                    <input
                                        type="email"
                                        name="email"
                                        value={form.email}
                                        onChange={handleChange}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        required
                                    />
                                    {errors.email && (
                                        <p className="text-sm text-rose-600 mt-1">{errors.email}</p>
                                    )}
                                </div>

                                {/* Phone */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Phone Number
                                    </label>
                                    <input
                                        type="tel"
                                        name="phone"
                                        value={form.phone}
                                        onChange={handleChange}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    />
                                </div>

                                {/* Terms */}
                                <div className="pt-2">
                                    <label className="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="terms_accepted"
                                            checked={form.terms_accepted}
                                            onChange={handleChange}
                                            className="rounded"
                                            required
                                        />
                                        I agree to the terms and conditions
                                    </label>
                                    {errors.terms_accepted && (
                                        <p className="text-sm text-rose-600 mt-1">{errors.terms_accepted}</p>
                                    )}
                                </div>

                                {/* Submit Button */}
                                <button
                                    onClick={handleSubmit}
                                    disabled={isSubmitting || !form.terms_accepted}
                                    className={`w-full py-3 px-4 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2 ${
                                        isSubmitting || !form.terms_accepted
                                            ? 'bg-gray-400 cursor-not-allowed'
                                            : 'bg-blue-900 hover:bg-blue-800'
                                    }`}
                                >
                                    <LockClosedIcon className="w-4 h-4" />
                                    {isSubmitting
                                        ? 'Processing...'
                                        : `Pay ${currency} ${Number(totalAmount).toFixed(2)}`
                                    }
                                </button>

                                {/* Security Note */}
                                <p className="text-xs text-gray-500 flex items-center justify-center gap-1 mt-2">
                                    <ShieldCheckIcon className="w-4 h-4" />
                                    Your payment is secure and encrypted
                                </p>
                            </div>
                        </motion.div>
                    </div>

                    {/* Order Summary */}
                    <div className="lg:col-span-1">
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.1 }}
                            className="bg-white rounded-2xl shadow-md p-6 border border-slate-200 sticky top-24"
                        >
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

                            <div className="space-y-3">
                                {membershipItems.map((item) => (
                                    <div key={item.id} className="flex justify-between text-sm">
                                        <span className="text-gray-600">{item.plan?.name ?? 'Plan'}</span>
                                        <span className="font-semibold text-gray-900">
                                            {item.plan?.currency} {Number(item.price).toFixed(2)}
                                        </span>
                                    </div>
                                ))}

                                <div className="border-t pt-3 flex justify-between font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>{currency} {Number(totalAmount).toFixed(2)}</span>
                                </div>
                            </div>

                            <Link
                                href={route('dashboard.memberships.index')}
                                className="block text-center mt-4 text-blue-900 font-semibold hover:text-blue-700 transition"
                            >
                                ← Back to Plans
                            </Link>
                        </motion.div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}