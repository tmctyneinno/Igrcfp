import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
    CheckCircleIcon,
    LockClosedIcon,
    ShieldCheckIcon,
    SparklesIcon,
} from '@heroicons/react/24/outline';

export default function Checkout({ cart, user }) {
    const totalAmount = parseFloat(cart?.total_amount) || 0;
    const isFreeCheckout = totalAmount === 0;
    const itemCount = cart?.items?.length || 0;
    const formatAmount = (amount) => `£${(parseFloat(amount) || 0).toFixed(2)}`;

    const { data, setData, post, processing, errors } = useForm({
        name: user.name || '',
        email: user.email || '',
        phone: '',
        payment_method: isFreeCheckout ? 'free' : 'card',
        terms_accepted: false,
    });

    const handleSubmit = (e) => { 
        e.preventDefault();
        post(route('checkout.process'));
    };

    return (
        <AuthenticatedLayout> 
            <Head title="Checkout" />
            
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="mb-8">
                    <p className="text-sm font-semibold uppercase tracking-wide text-blue-900">
                        {isFreeCheckout ? 'Free enrollment' : 'Secure checkout'}
                    </p>
                    <h1 className="mt-2 text-3xl font-bold text-gray-900">
                        {isFreeCheckout ? 'Confirm Your Course Access' : 'Checkout'}
                    </h1>
                    <p className="mt-2 max-w-2xl text-sm text-gray-600">
                        {isFreeCheckout
                            ? 'No card is required. Confirm your details and your course will be added to your learning dashboard immediately.'
                            : 'Enter your billing details to continue to the secure payment page.'}
                    </p>
                </div>
                
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Checkout Form */}
                    <div className="lg:col-span-2">
                        <div className="bg-white rounded-xl shadow-md p-6">
                            <div className="mb-6 flex items-start gap-4">
                                <div className={`flex h-12 w-12 shrink-0 items-center justify-center rounded-lg ${isFreeCheckout ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-900'}`}>
                                    {isFreeCheckout ? (
                                        <SparklesIcon className="h-6 w-6" />
                                    ) : (
                                        <LockClosedIcon className="h-6 w-6" />
                                    )}
                                </div>
                                <div>
                                    <h2 className="text-lg font-semibold text-gray-900">
                                        {isFreeCheckout ? 'Enrollment Information' : 'Billing Information'}
                                    </h2>
                                    <p className="mt-1 text-sm text-gray-600">
                                        {isFreeCheckout
                                            ? 'We will use these details for your enrollment record and course certificate profile.'
                                            : 'Your payment will be handled securely after this step.'}
                                    </p>
                                </div>
                            </div>
                            
                            <form onSubmit={handleSubmit}>
                                <div className="space-y-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Full Name *
                                        </label>
                                        <input
                                            type="text"
                                            value={data.name}
                                            onChange={e => setData('name', e.target.value)}
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            required
                                        />
                                        {errors.name && (
                                            <p className="text-red-600 text-sm mt-1">{errors.name}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Email Address *
                                        </label>
                                        <input
                                            type="email"
                                            value={data.email}
                                            onChange={e => setData('email', e.target.value)}
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            required
                                        />
                                        {errors.email && (
                                            <p className="text-red-600 text-sm mt-1">{errors.email}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Phone Number
                                        </label>
                                        <input 
                                            type="tel"
                                            value={data.phone}
                                            onChange={e => setData('phone', e.target.value)}
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        />
                                    </div>

                                    {isFreeCheckout ? (
                                        <div className="rounded-lg border border-green-200 bg-green-50 p-4">
                                            <div className="flex gap-3">
                                                <CheckCircleIcon className="mt-0.5 h-5 w-5 shrink-0 text-green-700" />
                                                <div>
                                                    <p className="text-sm font-semibold text-green-900">
                                                        Free course enrollment
                                                    </p>
                                                    <p className="mt-1 text-sm text-green-800">
                                                        Your total is {formatAmount(0)}. Submit this form to activate access without payment.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    ) : (
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                                Payment Method
                                            </label>
                                            <div className="space-y-2">
                                                <label className="flex items-center">
                                                    <input
                                                        type="radio"
                                                        value="card"
                                                        checked={data.payment_method === 'card'}
                                                        onChange={e => setData('payment_method', e.target.value)}
                                                        className="mr-2"
                                                    />
                                                    Credit / Debit Card
                                                </label>
                                            </div>
                                        </div>
                                    )}

                                    <div className="pt-4">
                                        <label className="flex items-center">
                                            <input
                                                type="checkbox"
                                                checked={data.terms_accepted}
                                                onChange={e => setData('terms_accepted', e.target.checked)}
                                                className="mr-2 rounded"
                                                required
                                            />
                                            <span className="text-sm text-gray-600">
                                                I agree to the terms and conditions
                                            </span>
                                        </label>
                                        {errors.terms_accepted && (
                                            <p className="text-red-600 text-sm mt-1">{errors.terms_accepted}</p>
                                        )}
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className={`w-full py-3 px-4 text-white font-semibold rounded-lg transition-all duration-300 disabled:opacity-50 ${
                                            isFreeCheckout
                                                ? 'bg-green-700 hover:bg-green-800'
                                                : 'bg-gradient-to-r from-blue-900 to-indigo-900 hover:from-blue-700 hover:to-indigo-700'
                                        }`}
                                    >
                                        {processing
                                            ? (isFreeCheckout ? 'Confirming enrollment...' : 'Processing...')
                                            : (isFreeCheckout ? 'Enroll for Free' : `Pay ${formatAmount(totalAmount)}`)}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {/* Order Summary */}
                    <div className="lg:col-span-1">
                        <div className="bg-white rounded-xl shadow-md p-6 sticky top-24">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                            
                            <div className="space-y-3 mb-4">
                                {cart?.items?.map((item) => {
                                    const itemPrice = parseFloat(item.price) || 0;
                                    return (
                                        <div key={item.id} className="flex justify-between gap-4 text-sm">
                                            <span className="text-gray-600">{item.title}</span>
                                            <span className={`font-medium ${itemPrice === 0 ? 'text-green-700' : 'text-gray-900'}`}>
                                                {itemPrice === 0 ? 'Free' : formatAmount(itemPrice)}
                                            </span>
                                        </div>
                                    );
                                })}
                                
                                <div className="border-t pt-3 flex justify-between text-sm text-gray-600">
                                    <span>Courses</span>
                                    <span>{itemCount}</span>
                                </div>

                                <div className="flex justify-between font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>{isFreeCheckout ? 'Free' : formatAmount(totalAmount)}</span>
                                </div>
                            </div>

                            {isFreeCheckout && (
                                <div className="mb-4 rounded-lg bg-gray-50 p-4">
                                    <div className="flex gap-3">
                                        <ShieldCheckIcon className="mt-0.5 h-5 w-5 shrink-0 text-blue-900" />
                                        <p className="text-sm text-gray-600">
                                            Access begins immediately after confirmation. You can continue from My Courses.
                                        </p>
                                    </div>
                                </div>
                            )}
 
                            <Link
                                href={route('dashboard.cart.index')}
                                className="block w-full text-center py-2 px-4 text-blue-900 hover:text-blue-700 font-semibold"
                            >
                                Edit Cart
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout> 
    );
}
