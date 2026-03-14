import React, { useState, useEffect } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Checkout({ cart, user }) {
    const [processing, setProcessing] = useState(false);
    const [error, setError] = useState(null);
    
    const { data, setData, post, errors } = useForm({
        name: user.name || '',
        email: user.email || '',
        phone: '',
        payment_method: 'card',
        terms_accepted: false,
    });

    const totalAmount = parseFloat(cart?.total_amount) || 0;

    // Load Stripe.js script
    useEffect(() => {
        // Only load Stripe if total amount is greater than 0
        if (totalAmount > 0) {
            const stripeScript = document.createElement('script');
            stripeScript.src = 'https://js.stripe.com/v3/';
            stripeScript.async = true;
            document.body.appendChild(stripeScript);

            return () => {
                if (document.body.contains(stripeScript)) {
                    document.body.removeChild(stripeScript);
                }
            };
        }
    }, [totalAmount]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        setProcessing(true);
        setError(null);

        try {
            // First, submit the form to create enrollments
            post(route('checkout.process'), {
                preserveScroll: true,
                onSuccess: (page) => {
                    // If total is 0, redirect to success page
                    if (totalAmount === 0) {
                        router.visit(route('checkout.success'));
                        return;
                    }

                    // For paid courses, redirect to Stripe Checkout
                    if (page.props.redirect) {
                        window.location.href = page.props.redirect;
                    }
                },
                onError: (errors) => {
                    console.error('Form errors:', errors);
                    setError('Please check your information and try again.');
                    setProcessing(false);
                },
            });
        } catch (error) {
            console.error('Submission error:', error);
            setError('An error occurred. Please try again.');
            setProcessing(false);
        }
    };

    if (!cart?.items?.length) {
        return (
            <AuthenticatedLayout>
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div className="bg-white rounded-xl shadow-md p-6 text-center">
                        <h2 className="text-xl font-semibold text-gray-900">Your cart is empty</h2>
                        <Link href={route('courses.index')} className="mt-4 inline-block text-blue-600 hover:text-blue-800">
                            Browse Courses
                        </Link>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }

    return (
        <AuthenticatedLayout>
            <Head title="Checkout" />
            
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 className="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>
                
                {error && (
                    <div className="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                        <p className="text-red-700">{error}</p>
                    </div>
                )}
                
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
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required
                                            disabled={processing}
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
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required
                                            disabled={processing}
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
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            disabled={processing}
                                        />
                                    </div>

                                    {totalAmount > 0 && (
                                        <div className="bg-blue-50 p-4 rounded-lg">
                                            <p className="text-sm text-blue-800">
                                                <strong>Payment Method:</strong> Credit / Debit Card
                                            </p>
                                            <p className="text-xs text-blue-600 mt-1">
                                                You will be redirected to Stripe's secure payment page.
                                            </p>
                                        </div>
                                    )}

                                    <div className="pt-4">
                                        <label className="flex items-center">
                                            <input
                                                type="checkbox"
                                                checked={data.terms_accepted}
                                                onChange={e => setData('terms_accepted', e.target.checked)}
                                                className="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                required
                                                disabled={processing}
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
                                        className="w-full py-3 px-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white font-semibold rounded-lg hover:from-blue-800 hover:to-indigo-800 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {processing ? (
                                            <span className="flex items-center justify-center">
                                                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Processing...
                                            </span>
                                        ) : (
                                            `Pay €${totalAmount.toFixed(2)}`
                                        )}
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
                                            <span className="text-gray-600 truncate max-w-[150px]">{item.title}</span>
                                            <span className="text-gray-900 font-medium">€{itemPrice.toFixed(2)}</span>
                                        </div>
                                    );
                                })}
                                
                                <div className="border-t pt-3 flex justify-between font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>€{totalAmount.toFixed(2)}</span>
                                </div>
                            </div>

                            <Link
                                href={route('dashboard.cart.index')}
                                className="block w-full text-center py-2 px-4 text-blue-900 hover:text-blue-700 font-semibold"
                                disabled={processing}
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