// resources/js/Pages/Auth/Login.jsx
import { useEffect, useRef } from 'react';
import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import React, { useState } from 'react';
 
export default function Login({ status, canResetPassword }) {
    const { auth } = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
        'g-recaptcha-response': '',   // ← v2 field name the backend expects
    });

    const [showPassword, setShowPassword] = useState(false);
    const recaptchaRef = useRef(null);
    const widgetIdRef = useRef(null);
 
    // Load reCAPTCHA v2 script and render the widget
    useEffect(() => {
        const SITE_KEY = import.meta.env.VITE_RECAPTCHA_SITE_KEY;
        console.log('Site Key:', import.meta.env.VITE_RECAPTCHA_SITE_KEY);
 
        const renderWidget = () => {
            if (recaptchaRef.current && widgetIdRef.current === null && window.grecaptcha) {
                widgetIdRef.current = window.grecaptcha.render(recaptchaRef.current, {
                    sitekey: SITE_KEY,
                    callback: (token) => {
                        setData('g-recaptcha-response', token);
                    },
                    'expired-callback': () => {
                        setData('g-recaptcha-response', '');
                    },
                    'error-callback': () => {
                        setData('g-recaptcha-response', '');
                    },
                });
            }
        };

        if (window.grecaptcha && window.grecaptcha.render) {
            renderWidget();
            return;
        }

        // Expose a global callback so the script can call it when ready
        window.onRecaptchaLoad = renderWidget;

        const existing = document.querySelector('script[data-recaptcha]');
        if (!existing) {
            const script = document.createElement('script');
            script.src = `https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit`;
            script.async = true;
            script.defer = true;
            script.setAttribute('data-recaptcha', 'true');
            document.head.appendChild(script);
        }

        return () => {
            // Cleanup global callback on unmount
            delete window.onRecaptchaLoad;
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
 
        post(route('login'), {
            onFinish: () => {
                reset('password');
                // Reset the reCAPTCHA widget after each attempt
                if (window.grecaptcha && widgetIdRef.current !== null) {
                    window.grecaptcha.reset(widgetIdRef.current);
                    setData('g-recaptcha-response', '');
                }
            },
        });
    };

    return (
        <GuestLayout auth={auth}>
            <Head title="Log in" />

            <div className="min-h-screen flex">
                {/* Left Side - Image */}
                <div className="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100">
                    <div className="flex items-center justify-center w-full p-12">
                        <div className="text-center">
                            <div className="mb-8">
                                <Link href="/">
                                    <img
                                        src="/assets/admin/images/auth/auth-img.png"
                                        alt="Authentication"
                                        className="mx-auto max-w-full h-auto"
                                        style={{ maxHeight: '600px' }}
                                    />
                                </Link>
                            </div>
                            <h2 className="text-2xl font-bold text-gray-800 mb-4">
                                Welcome to Our Platform
                            </h2>
                            <p className="text-gray-600">
                                Sign in to access your account and manage your dashboard
                            </p>
                        </div>
                    </div>
                </div>

                {/* Right Side - Form */}
                <div className="w-full lg:w-1/2 flex items-center justify-center p-8">
                    <div className="w-full max-w-md">
                        {/* Logo */}
                        <div className="text-center mb-8">
                            <Link href="/" className="inline-block">
                                <img
                                    src="/assets/images/home-three/logo/logo-main.png"
                                    alt="Brand Logo"
                                    className="h-12 w-auto mx-auto"
                                />
                            </Link>
                            <h1 className="mt-6 text-3xl font-bold text-gray-900">
                                Welcome Back
                            </h1>
                            <p className="mt-2 text-gray-600">
                                Sign in to your account to continue
                            </p>
                        </div>

                        {/* Status Messages */}
                        {status && (
                            <div className="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <svg className="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <div className="ml-3">
                                        <p className="text-sm font-medium text-green-800">{status}</p>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* reCAPTCHA Error */}
                        {errors['g-recaptcha-response'] && (
                            <div className="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p className="text-sm text-red-600">{errors['g-recaptcha-response']}</p>
                            </div>
                        )}

                        {/* Form */}
                        <form onSubmit={submit} className="space-y-6">
                            {/* Email Field */}
                            <div>
                                <InputLabel htmlFor="email" value="Email Address" />
                                <div className="mt-1 relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <TextInput
                                        id="email"
                                        type="email"
                                        name="email"
                                        value={data.email}
                                        className="pl-10 w-full"
                                        placeholder="Email"
                                        autoComplete="email"
                                        isFocused={true}
                                        onChange={(e) => setData('email', e.target.value)}
                                    />
                                </div>
                                <InputError message={errors.email} className="mt-2" />
                            </div>

                            {/* Password Field */}
                            <div>
                                <div className="flex items-center justify-between">
                                    <InputLabel htmlFor="password" value="Password" />
                                    {canResetPassword && (
                                        <Link
                                            href={route('password.request')}
                                            className="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                                        >
                                            Forgot password?
                                        </Link>
                                    )}
                                </div>
                                <div className="mt-1 relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <TextInput
                                        id="password"
                                        type={showPassword ? 'text' : 'password'}
                                        name="password"
                                        value={data.password}
                                        className="pl-10 pr-10 w-full"
                                        placeholder="Password"
                                        autoComplete="current-password"
                                        onChange={(e) => setData('password', e.target.value)}
                                    />
                                    <button
                                        type="button"
                                        className="absolute inset-y-0 right-0 pr-3 flex items-center"
                                        onClick={() => setShowPassword(!showPassword)}
                                    >
                                        {showPassword ? (
                                            <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fillRule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clipRule="evenodd" />
                                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 1.7 0 3.27-.415 4.654-1.142l-1.96-1.96z" />
                                            </svg>
                                        ) : (
                                            <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fillRule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clipRule="evenodd" />
                                            </svg>
                                        )}
                                    </button>
                                </div>
                                <InputError message={errors.password} className="mt-2" />
                            </div>

                            {/* Remember Me */}
                            {/* <div className="flex items-center">
                                <Checkbox
                                    name="remember"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                />
                                <label className="ml-2 block text-sm text-gray-900">
                                    Remember me
                                </label>
                            </div> */}

                            {/* ✅ reCAPTCHA v2 Checkbox Widget */}
                            <div>
                                <div ref={recaptchaRef} />
                                {/* Fallback error shown below the widget */}
                                {errors['g-recaptcha-response'] && (
                                    <p className="mt-1 text-sm text-red-600">
                                        Please complete the reCAPTCHA check.
                                    </p>
                                )}
                            </div>

                            {/* Submit Button */}
                            <div>
                                <PrimaryButton
                                    className="w-full justify-center py-3 px-4 text-sm font-medium"
                                    disabled={processing}
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                            </svg>
                                            Signing in...
                                        </>
                                    ) : (
                                        'Sign in'
                                    )}
                                </PrimaryButton>
                            </div>
                        </form>

                        {/* Register Link */}
                        {route().has('register') && (
                            <div className="mt-8 text-center">
                                <p className="text-sm text-gray-600">
                                    Don't have an account?{' '}
                                    <Link
                                        href={route('register')}
                                        className="font-medium text-blue-900 hover:text-indigo-500"
                                    >
                                        Sign up
                                    </Link>
                                </p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            <style>{`
                @media (max-width: 1023px) {
                    .min-h-screen {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    }
                    .w-full.lg\\:w-1\\/2 {
                        background: white;
                        border-radius: 1rem;
                        margin: 1rem;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
                    }
                }
                @media (min-width: 1024px) {
                    .min-h-screen { background: white; }
                }
            `}</style>
        </GuestLayout>
    );
}