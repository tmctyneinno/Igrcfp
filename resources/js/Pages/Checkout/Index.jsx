import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Checkout({ cart, user }) {
    const { data, setData, post, processing, errors } = useForm({
        name: user.name || '',
        email: user.email || '',
        phone: '',
        payment_method: 'card',
        terms_accepted: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('checkout.process'));
    };

    // Parse total_amount as a number
    const totalAmount = parseFloat(cart?.total_amount) || 0;

    return (
        <GuestLayout>
            <Head title="Checkout" />
            
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 className="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>
                
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Checkout Form */}
                    <div className="lg:col-span-2">
                        <div className="bg-white rounded-xl shadow-md p-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-6">Billing Information</h2>
                            
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
                                            <label className="flex items-center">
                                                <input
                                                    type="radio"
                                                    value="bank"
                                                    checked={data.payment_method === 'bank'}
                                                    onChange={e => setData('payment_method', e.target.value)}
                                                    className="mr-2"
                                                />
                                                Bank Transfer
                                            </label>
                                            <label className="flex items-center">
                                                <input
                                                    type="radio"
                                                    value="paypal"
                                                    checked={data.payment_method === 'paypal'}
                                                    onChange={e => setData('payment_method', e.target.value)}
                                                    className="mr-2"
                                                />
                                                PayPal
                                            </label>
                                        </div>
                                    </div>

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
                                        className="w-full py-3 px-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 disabled:opacity-50"
                                    >
                                        {processing ? 'Processing...' : `Pay $${totalAmount.toFixed(2)}`}
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
                                        <div key={item.id} className="flex justify-between text-sm">
                                            <span className="text-gray-600">{item.title}</span>
                                            <span className="text-gray-900 font-medium">${itemPrice.toFixed(2)}</span>
                                        </div>
                                    );
                                })}
                                
                                <div className="border-t pt-3 flex justify-between font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>${totalAmount.toFixed(2)}</span>
                                </div>
                            </div>

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
        </GuestLayout>
    );
}