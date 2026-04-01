import React, { useEffect, useRef, useState } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import CountryCodeSelector from '@/Components/CountryCodeSelector';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'learner',
        phone: '',
        linkedin: '',
        date_of_birth: '',
        'g-recaptcha-response': '',   // ← v2 field
    });

    const [currentStep, setCurrentStep] = useState(1);
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    const recaptchaRef = useRef(null);
    const widgetIdRef = useRef(null);

    // Load & render reCAPTCHA v2 widget when user reaches Step 3
    useEffect(() => {
        if (currentStep !== 3) return;

        const SITE_KEY = import.meta.env.VITE_RECAPTCHA_SITE_KEY;

        const renderWidget = () => {
            // Only render if the div is mounted and widget not already created
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
            delete window.onRecaptchaLoad;
        };
    }, [currentStep]); // re-runs when user reaches step 3

    useEffect(() => {
        if (Object.keys(errors).length > 0) {
            const firstErrorElement = document.querySelector('[id^="error-"]');
            if (firstErrorElement) {
                firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }, [errors]);

    const submit = (e) => {
        e.preventDefault();
        if (currentStep < 3) {
            setCurrentStep(currentStep + 1);
        } else {
            post(route('register'), {
                onFinish: () => {
                    reset('password', 'password_confirmation');
                    // Reset widget after failed attempt
                    if (window.grecaptcha && widgetIdRef.current !== null) {
                        window.grecaptcha.reset(widgetIdRef.current);
                        setData('g-recaptcha-response', '');
                    }
                },
            });
        }
    };

    const nextStep = () => {
        if (currentStep === 1 && !data.role) {
            alert('Please select a role');
            return;
        }
        if (currentStep === 2 && (!data.name || !data.email)) {
            alert('Please fill in name and email');
            return;
        }
        setCurrentStep(prev => prev + 1);
    };

    const prevStep = () => {
        // Reset widget state when going back from step 3
        if (currentStep === 3) {
            widgetIdRef.current = null;
            setData('g-recaptcha-response', '');
        } 
        setCurrentStep(currentStep - 1);
    };

    const steps = [
        { number: 1, title: 'Select Role', description: 'Choose your account type' },
        { number: 2, title: 'Personal Info', description: 'Enter your details' },
        { number: 3, title: 'Security', description: 'Create your password' },
    ];

    return (
        <GuestLayout>
            <Head title="Register" />
            <div className="min-h-screen flex">
                {/* Left Side - Image */}
                <div className="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100">
                    <div className="flex items-center justify-center w-full p-12">
                        <div className="text-center">
                            <div className="mb-8">
                                <img
                                    src="/assets/admin/images/auth/auth-img.png"
                                    alt="Registration"
                                    className="mx-auto max-w-full h-auto"
                                    style={{ maxHeight: '500px' }}
                                />
                            </div>
                            <h2 className="text-2xl font-bold text-gray-800 mb-4">Join Our Community</h2>
                            <p className="text-gray-600">
                                Create an account to access all features and start your journey
                            </p>
                        </div>
                    </div>
                </div>

                {/* Right Side - Form */}
                <div className="w-full lg:w-1/2 flex items-center justify-center p-8">
                    <div className="w-full max-w-2xl">
                        {/* Header */}
                        <div className="text-center mb-8">
                            <div className="site-branding mb-4">
                                <Link href="/" className="brand-logo inline-block">
                                    <img
                                        src="/assets/images/home-three/logo/logo-main.png"
                                        style={{ width: '60px' }}
                                        alt="Brand Logo"
                                    />
                                </Link>
                            </div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-2">Join Us!</h2>
                            <p className="text-gray-600">Please provide your details</p>
                        </div>

                        {/* Global Error Summary */}
                        {Object.keys(errors).length > 0 && (
                            <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div className="flex items-start">
                                    <svg className="h-5 w-5 text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <div>
                                        <h4 className="text-sm font-medium text-red-800">Please fix the following errors:</h4>
                                        <ul className="mt-2 text-sm text-red-700 space-y-1">
                                            {Object.entries(errors).map(([field, messages]) =>
                                                Array.isArray(messages)
                                                    ? messages.map((message, index) => (
                                                        <li key={`${field}-${index}`}>• {message}</li>
                                                    ))
                                                    : <li key={field}>• {messages}</li>
                                            )}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Progress Steps */}
                        <div className="mb-8">
                            <div className="flex items-center justify-between">
                                {steps.map((step) => (
                                    <div key={step.number} className="flex flex-col items-center flex-1">
                                        <div className={`w-10 h-10 rounded-full flex items-center justify-center mb-2 ${currentStep >= step.number ? 'bg-blue-950 text-white' : 'bg-gray-200 text-gray-500'}`}>
                                            <span className="font-semibold">{step.number}</span>
                                        </div>
                                        <div className="text-center">
                                            <div className={`text-sm font-medium ${currentStep >= step.number ? 'text-blue-950' : 'text-gray-500'}`}>
                                                {step.title}
                                            </div>
                                            <div className="text-xs text-gray-500 hidden md:block">
                                                {step.description}
                                            </div>
                                        </div>
                                        {step.number < steps.length && (
                                            <div className={`h-1 flex-1 mt-0 mx-2 ${currentStep > step.number ? 'bg-blue-950' : 'bg-gray-200'}`} />
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Form */}
                        <form onSubmit={submit}>
                            {/* Step 1 - Role Selection */}
                            {currentStep === 1 && (
                                <div className="space-y-6">
                                    <div className="text-center mb-6">
                                        <h3 className="text-xl font-semibold text-gray-900">Select Your Role</h3>
                                        <p className="text-gray-600 mt-2">Choose how you want to use our platform</p>
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div
                                            className={`border-2 rounded-lg p-6 cursor-pointer transition-all duration-300 ${data.role === 'learner' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'}`}
                                            onClick={() => setData('role', 'learner')}
                                        >
                                            <div className="text-center">
                                                <div className="mb-4">
                                                    <svg className="h-12 w-12 mx-auto text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 14l9-5-9-5-9 5 9 5z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                                    </svg>
                                                </div>
                                                <h4 className="font-semibold text-lg mb-2">Professional</h4>
                                                <p className="text-sm text-gray-600">Learn new skills, take courses, and track your progress</p>
                                            </div>
                                        </div>
                                        <div
                                            className={`border-2 rounded-lg p-6 cursor-pointer transition-all duration-300 ${data.role === 'tutor' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'}`}
                                            onClick={() => setData('role', 'tutor')}
                                        >
                                            <div className="text-center">
                                                <div className="mb-4">
                                                    <svg className="h-12 w-12 mx-auto text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                    </svg>
                                                </div>
                                                <h4 className="font-semibold text-lg mb-2">Tutor</h4>
                                                <p className="text-sm text-gray-600">Create and sell courses, mentor students, and share knowledge</p>
                                            </div>
                                        </div>
                                    </div>
                                    <InputError message={errors.role} className="mt-2 text-center" />
                                    <div className="flex justify-end pt-4">
                                        <button
                                            type="button"
                                            onClick={nextStep}
                                            disabled={!data.role}
                                            className={`px-6 py-3 rounded-md font-medium ${data.role ? 'bg-blue-950 text-white hover:bg-blue-900' : 'bg-gray-300 text-gray-500 cursor-not-allowed'}`}
                                        >
                                            Continue
                                        </button>
                                    </div>
                                </div>
                            )}

                            {/* Step 2 - Personal Info */}
                            {currentStep === 2 && (
                                <div className="space-y-6">
                                    <div className="text-center mb-6">
                                        <h3 className="text-xl font-semibold text-gray-900">Personal Information</h3>
                                        <p className="text-gray-600 mt-2">Tell us a bit about yourself</p>
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <InputLabel htmlFor="name" value="Full Name *" />
                                            <div className="mt-1 relative">
                                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clipRule="evenodd" />
                                                    </svg>
                                                </div>
                                                <TextInput id="name" name="name" value={data.name} className="pl-10 w-full" placeholder="John Doe" autoComplete="name" isFocused={true} onChange={(e) => setData('name', e.target.value)} required />
                                            </div>
                                            <InputError message={errors.name} className="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel htmlFor="email" value="Email Address *" />
                                            <div className="mt-1 relative">
                                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                    </svg>
                                                </div>
                                                <TextInput id="email" type="email" name="email" value={data.email} className="pl-10 w-full" placeholder="email@gmail.com" autoComplete="email" onChange={(e) => setData('email', e.target.value)} required />
                                            </div>
                                            <InputError message={errors.email} className="mt-2" />
                                        </div>
                                        {/* Phone Number with Country Code Selector */}
                                        <div>
                                            <InputLabel htmlFor="phone" value="Phone Number" />
                                            <div className="mt-1 relative flex">
                                                
                                                {/* Country Code Dropdown */}
                                                <CountryCodeSelector
                                                    value={data.phoneCountryCode || '+234'}
                                                    onChange={(code) => {
                                                        setData('phoneCountryCode', code);
                                                        // Rebuild full phone whenever country code changes
                                                        const localNumber = (data.phone || '').replace(/^\+\d+\s*/, '');
                                                        setData('phone', `${code} ${localNumber}`.trim());
                                                    }}
                                                />

                                                {/* Local Number Input */}
                                                <div className="relative flex-1">
                                                    <TextInput
                                                        id="phone"
                                                        type="tel"
                                                        name="phone_local"
                                                        value={data.phoneLocal || ''}
                                                        className="w-full rounded-l-none border-l-0 pl-3"
                                                        placeholder="8012345678"
                                                        autoComplete="tel-national"
                                                        onChange={(e) => {
                                                            const local = e.target.value.replace(/\D/g, ''); // digits only
                                                            setData('phoneLocal', local);
                                                            setData('phone', `${data.phoneCountryCode || '+234'} ${local}`.trim());
                                                        }}
                                                    />
                                                </div>
                                            </div>
                                            <InputError message={errors.phone} className="mt-2" />
                                        </div>
                                        <div>
                                            <InputLabel htmlFor="linkedin" value="LinkedIn Profile" />
                                            <div className="mt-1 relative">
                                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg className="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                                    </svg>
                                                </div>
                                                <TextInput id="linkedin" type="text" name="linkedin" value={data.linkedin} className="pl-10 w-full" placeholder="https://linkedin.com/in/username" onChange={(e) => setData('linkedin', e.target.value)} />
                                            </div>
                                            <InputError message={errors.linkedin} className="mt-2" />
                                        </div>
                                        <div className="md:col-span-2">
                                            <InputLabel htmlFor="date_of_birth" value="Date of Birth" />
                                            <div className="mt-1 relative">
                                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                                                    </svg>
                                                </div>
                                                <TextInput id="date_of_birth" type="date" name="date_of_birth" value={data.date_of_birth} className="pl-10 w-full" onChange={(e) => setData('date_of_birth', e.target.value)} />
                                            </div>
                                            <InputError message={errors.date_of_birth} className="mt-2" />
                                        </div>
                                    </div>
                                    <div className="flex justify-between pt-4">
                                        <button type="button" onClick={prevStep} className="px-6 py-3 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50">
                                            Back
                                        </button>
                                        <button
                                            type="button"
                                            onClick={nextStep}
                                            disabled={!data.name || !data.email}
                                            className={`px-3 py-2 rounded-md font-medium ${data.name && data.email ? 'bg-blue-950 text-white hover:bg-blue-900' : 'bg-gray-300 text-gray-500 cursor-not-allowed'}`}
                                        >
                                            Continue
                                        </button>
                                    </div>
                                </div>
                            )}

                            {/* Step 3 - Password + reCAPTCHA */}
                            {currentStep === 3 && (
                                <div className="space-y-6">
                                    <div className="text-center mb-6">
                                        <h3 className="text-xl font-semibold text-gray-900">Create Password</h3>
                                        <p className="text-gray-600 mt-2">Secure your account with a strong password</p>
                                    </div>

                                    <div className="space-y-6">
                                        {/* Password */}
                                        <div>
                                            <InputLabel htmlFor="password" value="Password *" />
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
                                                    placeholder="••••••••"
                                                    autoComplete="new-password"
                                                    onChange={(e) => setData('password', e.target.value)}
                                                    required
                                                />
                                                <button type="button" className="absolute inset-y-0 right-0 pr-3 flex items-center" onClick={() => setShowPassword(!showPassword)}>
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

                                        {/* Confirm Password */}
                                        <div>
                                            <InputLabel htmlFor="password_confirmation" value="Confirm Password *" />
                                            <div className="mt-1 relative">
                                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" />
                                                    </svg>
                                                </div>
                                                <TextInput
                                                    id="password_confirmation"
                                                    type={showConfirmPassword ? 'text' : 'password'}
                                                    name="password_confirmation"
                                                    value={data.password_confirmation}
                                                    className="pl-10 pr-10 w-full"
                                                    placeholder="••••••••"
                                                    autoComplete="new-password"
                                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                                    required
                                                />
                                                <button type="button" className="absolute inset-y-0 right-0 pr-3 flex items-center" onClick={() => setShowConfirmPassword(!showConfirmPassword)}>
                                                    {showConfirmPassword ? (
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
                                            <InputError message={errors.password_confirmation} className="mt-2" />
                                        </div>

                                        {/* Terms */}
                                        <div className="bg-gray-50 p-4 rounded-lg">
                                            <div className="flex items-start">
                                                <div className="flex items-center h-5">
                                                    <input id="terms" name="terms" type="checkbox" className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required />
                                                </div>
                                                <div className="ml-3 text-sm">
                                                    <label htmlFor="terms" className="font-medium text-gray-700">
                                                        I agree to the{' '}
                                                        <Link href="/terms" className="text-blue-600 hover:text-blue-500">Terms of Service</Link>
                                                        {' '}and{' '}
                                                        <Link href="/privacy" className="text-blue-600 hover:text-blue-500">Privacy Policy</Link>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        {/* ✅ reCAPTCHA v2 Widget */}
                                        <div>
                                            <div ref={recaptchaRef} />
                                            {errors['g-recaptcha-response'] && (
                                                <p className="mt-2 text-sm text-red-600">
                                                    Please complete the reCAPTCHA check.
                                                </p>
                                            )}
                                        </div>

                                        {/* Account Summary */}
                                        <div className="bg-blue-50 p-4 rounded-lg">
                                            <h4 className="font-medium text-blue-900 mb-2">Account Summary</h4>
                                            <div className="space-y-2 text-sm">
                                                <div className="flex justify-between">
                                                    <span className="text-gray-600">Role:</span>
                                                    <span className="font-medium">{data.role.charAt(0).toUpperCase() + data.role.slice(1)}</span>
                                                </div>
                                                <div className="flex justify-between">
                                                    <span className="text-gray-600">Name:</span>
                                                    <span className="font-medium">{data.name}</span>
                                                </div>
                                                <div className="flex justify-between">
                                                    <span className="text-gray-600">Email:</span>
                                                    <span className="font-medium">{data.email}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex justify-between pt-4">
                                        <button type="button" onClick={prevStep} className="px-4 py-3 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50">
                                            Back
                                        </button>
                                        <div className="flex space-x-4">
                                            <Link
                                                href={route('login')}
                                                className="px-4 py-3 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 inline-flex items-center"
                                            >
                                                Already registered?
                                            </Link>
                                            <PrimaryButton
                                                type="submit"
                                                className="px-4 py-3"
                                                disabled={
                                                    processing ||
                                                    !data.password ||
                                                    !data.password_confirmation ||
                                                    data.password !== data.password_confirmation ||
                                                    !data['g-recaptcha-response']  // ← disabled until captcha checked
                                                }
                                            >
                                                {processing ? 'Creating Account...' : 'Complete Registration'}
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </form>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}