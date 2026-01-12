import React from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { CheckCircleIcon, ExclamationCircleIcon } from '@heroicons/react/24/outline';

export default function Index({ auth, title }) {
    const pageProps = usePage().props;
    const flash = pageProps?.flash || {};
    
    const { data, setData, post, processing, errors, reset } = useForm({
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        countryCode: 'NG',
        message: '',
        agree: false,
    });

    const countryCodes = [
        { code: 'NG', name: 'Nigeria (+234)' },
        { code: 'US', name: 'USA (+1)' },
        { code: 'GB', name: 'UK (+44)' },
        { code: 'GH', name: 'Ghana (+233)' },
        { code: 'KE', name: 'Kenya (+254)' },
        { code: 'ZA', name: 'South Africa (+27)' },
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('contact.store'), {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    };

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setData(name, type === 'checkbox' ? checked : value);
    };

    const InputField = ({ label, id, type = 'text', required = true, ...props }) => (
        <div>
            <label 
                htmlFor={id} 
                className="block text-sm font-medium text-gray-700 mb-1"
            >
                {label} {required && <span className="text-red-500">*</span>}
            </label>
            <input
                id={id}
                type={type}
                value={data[props.name] || ''}
                onChange={handleChange}
                disabled={processing}
                className={`w-full px-4 py-3 border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ${
                    errors[props.name] 
                        ? 'border-red-300 bg-red-50' 
                        : 'border-gray-300 hover:border-gray-400'
                } ${processing ? 'opacity-50 cursor-not-allowed' : ''}`}
                required={required}
                aria-invalid={errors[props.name] ? 'true' : 'false'}
                aria-describedby={errors[props.name] ? `${id}-error` : undefined}
                {...props}
            />
            {errors[props.name] && (
                <p 
                    id={`${id}-error`} 
                    className="mt-1 text-sm text-red-600 flex items-center gap-1"
                    role="alert"
                >
                    <ExclamationCircleIcon className="h-4 w-4" />
                    {errors[props.name]}
                </p>
            )}
        </div>
    );

    return (
        <GuestLayout auth={auth}>
            <Head title={title}>
                <meta name="description" content="Get in touch with IGRCFP. Our team is ready to assist you with any inquiries or support you may need." />
            </Head>
            
            {/* Hero Section */}
            <section 
                className="relative bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-20 md:py-28"
                aria-labelledby="page-title"
            >
                <div className="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]" />
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto">
                        <h1 
                            id="page-title"
                            className="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight"
                        >
                            {title}
                        </h1>
                        <p className="text-xl text-gray-600 leading-relaxed">
                            Our dedicated team is committed to providing exceptional support. 
                            Reach out with your questions, feedback, or partnership inquiries.
                        </p>
                    </div>
                </div>
            </section>

            {/* Status Messages */}
            {flash?.success && (
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                    <div className="bg-green-50 border-l-4 border-green-400 p-4 rounded-r">
                        <div className="flex items-center">
                            <CheckCircleIcon className="h-5 w-5 text-green-400 mr-3" />
                            <p className="text-green-700">{flash?.success}</p>
                        </div>
                    </div>
                </div>
            )}

            {flash?.error && (
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                    <div className="bg-red-50 border-l-4 border-red-400 p-4 rounded-r">
                        <div className="flex items-center">
                            <ExclamationCircleIcon className="h-5 w-5 text-red-400 mr-3" />
                            <p className="text-red-700">{flash?.error}</p>
                        </div>
                    </div>
                </div>
            )}

            {flash?.message && (
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                    <div className="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r">
                        <div className="flex items-center">
                            <ExclamationCircleIcon className="h-5 w-5 text-blue-400 mr-3" />
                            <p className="text-blue-700">{flash?.message}</p>
                        </div>
                    </div>
                </div>
            )}

            {/* Contact Form Section */}
            <section className="py-16" aria-labelledby="contact-form-title">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 
                            id="contact-form-title"
                            className="text-3xl font-bold text-gray-900 mb-4"
                        >
                            Send Us a Message
                        </h2>
                        <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                            Complete the form below and our team will respond promptly. 
                            All fields marked with <span className="text-red-500">*</span> are required.
                        </p>
                    </div>
                    
                    <form 
                        onSubmit={handleSubmit}
                        className="bg-white rounded-2xl shadow-xl p-8 md:p-10 space-y-8"
                        noValidate
                    >
                        {/* Name Fields */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <InputField
                                label="First Name"
                                id="firstName"
                                name="firstName"
                                placeholder="Enter your first name"
                                autoComplete="given-name"
                            />
                            
                            <InputField
                                label="Last Name"
                                id="lastName"
                                name="lastName"
                                placeholder="Enter your last name"
                                autoComplete="family-name"
                            />
                        </div>

                        {/* Email */}
                        <InputField
                            label="Email Address"
                            id="email"
                            name="email"
                            type="email"
                            placeholder="your.email@example.com"
                            autoComplete="email"
                        />

                        {/* Phone Number */}
                        <div>
                            <label 
                                htmlFor="phone" 
                                className="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Phone Number <span className="text-red-500">*</span>
                            </label>
                            <div className="flex flex-col sm:flex-row gap-3">
                                <div className="sm:w-40">
                                    <select
                                        name="countryCode"
                                        id="countryCode"
                                        value={data.countryCode}
                                        onChange={handleChange}
                                        disabled={processing}
                                        className={`w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ${
                                            processing ? 'opacity-50 cursor-not-allowed' : 'hover:border-gray-400'
                                        }`}
                                        aria-label="Country code"
                                    >
                                        {countryCodes.map((country) => (
                                            <option key={country.code} value={country.code}>
                                                {country.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="flex-1">
                                    <input
                                        type="tel"
                                        name="phone"
                                        id="phone"
                                        value={data.phone}
                                        onChange={handleChange}
                                        disabled={processing}
                                        placeholder="Phone number"
                                        autoComplete="tel"
                                        className={`w-full px-4 py-3 border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ${
                                            errors.phone 
                                                ? 'border-red-300 bg-red-50' 
                                                : 'border-gray-300 hover:border-gray-400'
                                        } ${processing ? 'opacity-50 cursor-not-allowed' : ''}`}
                                        aria-invalid={errors.phone ? 'true' : 'false'}
                                        aria-describedby={errors.phone ? 'phone-error' : undefined}
                                    />
                                    {errors.phone && (
                                        <p 
                                            id="phone-error" 
                                            className="mt-1 text-sm text-red-600 flex items-center gap-1"
                                            role="alert"
                                        >
                                            <ExclamationCircleIcon className="h-4 w-4" />
                                            {errors.phone}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Message */}
                        <div>
                            <label 
                                htmlFor="message" 
                                className="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Your Message <span className="text-red-500">*</span>
                            </label>
                            <textarea
                                name="message"
                                id="message"
                                rows="5"
                                value={data.message}
                                onChange={handleChange}
                                disabled={processing}
                                className={`w-full px-4 py-3 border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none ${
                                    errors.message 
                                        ? 'border-red-300 bg-red-50' 
                                        : 'border-gray-300 hover:border-gray-400'
                                } ${processing ? 'opacity-50 cursor-not-allowed' : ''}`}
                                placeholder="Please provide details about your inquiry..."
                                aria-invalid={errors.message ? 'true' : 'false'}
                                aria-describedby={errors.message ? 'message-error' : undefined}
                            />
                            <div className="flex justify-between items-center mt-1">
                                {errors.message && (
                                    <p 
                                        id="message-error" 
                                        className="text-sm text-red-600 flex items-center gap-1"
                                        role="alert"
                                    >
                                        <ExclamationCircleIcon className="h-4 w-4" />
                                        {errors.message}
                                    </p>
                                )}
                                <span className={`text-sm ${processing ? 'text-gray-400' : 'text-gray-500'} ml-auto`}>
                                    {data.message.length}/1000 characters
                                </span>
                            </div>
                        </div>

                        {/* Privacy Policy Agreement */}
                        <div className="bg-blue-50 rounded-lg p-4">
                            <div className="flex items-start">
                                <div className="flex items-center h-5">
                                    <input
                                        type="checkbox"
                                        name="agree"
                                        id="agree"
                                        checked={data.agree}
                                        onChange={handleChange}
                                        disabled={processing}
                                        className={`h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 ${
                                            processing ? 'opacity-50 cursor-not-allowed' : ''
                                        }`}
                                        required
                                        aria-invalid={errors.agree ? 'true' : 'false'}
                                        aria-describedby={errors.agree ? 'agree-error' : 'agree-description'}
                                    />
                                </div>
                                <div className="ml-3">
                                    <label 
                                        htmlFor="agree" 
                                        className={`text-sm font-medium ${processing ? 'text-gray-400' : 'text-gray-700'}`}
                                    >
                                        I agree to the privacy policy
                                    </label>
                                    <p 
                                        id="agree-description" 
                                        className={`text-sm ${processing ? 'text-gray-400' : 'text-gray-600'} mt-1`}
                                    >
                                        By submitting this form, you acknowledge that you have read and agree to our{' '}
                                        <a 
                                            href="/privacy-policy" 
                                            className={`${processing ? 'text-gray-400' : 'text-indigo-600 hover:text-indigo-500'} font-medium underline`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Privacy Policy
                                        </a>
                                        . We respect your data and will only use it to respond to your inquiry.
                                    </p>
                                    {errors.agree && (
                                        <p 
                                            id="agree-error" 
                                            className="mt-1 text-sm text-red-600 flex items-center gap-1"
                                            role="alert"
                                        >
                                            <ExclamationCircleIcon className="h-4 w-4" />
                                            {errors.agree}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Submit Button */}
                        <div className="pt-4">
                            <button
                                type="submit"
                                disabled={processing}
                                className={`w-full py-3 px-6 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ${
                                    processing
                                        ? 'bg-indigo-400 cursor-not-allowed'
                                        : 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-md hover:shadow-lg'
                                }`}
                                aria-busy={processing}
                            >
                                {processing ? (
                                    <span className="flex items-center justify-center gap-2">
                                        <svg className="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                        </svg>
                                        Sending Message...
                                    </span>
                                ) : (
                                    'Send Message'
                                )}
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {/* Contact Information Footer */}
            <footer className="bg-gradient-to-r from-blue-50 via-white to-indigo-50 py-16 mt-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h3 className="text-2xl font-bold text-gray-900 mb-4">
                            Additional Contact Information
                        </h3>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8 max-w-4xl mx-auto">
                            <div className="p-6 bg-white rounded-xl shadow-sm">
                                <div className="text-indigo-600 mb-3">
                                    <svg className="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                    </svg>
                                </div>
                                <h4 className="font-medium text-gray-900 mb-2">Email Us</h4>
                                <a 
                                    href="mailto:info@igrcfp.org" 
                                    className="text-indigo-600 hover:text-indigo-700 font-medium"
                                >
                                    info@igrcfp.org
                                </a>
                                <p className="text-sm text-gray-600 mt-2">
                                    Typical response time: 24 hours
                                </p>
                            </div>
                            
                            <div className="p-6 bg-white rounded-xl shadow-sm">
                                <div className="text-indigo-600 mb-3">
                                    <svg className="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                                    </svg>
                                </div>
                                <h4 className="font-medium text-gray-900 mb-2">Call Us</h4>
                                <a 
                                    href="tel:+2348000000000" 
                                    className="text-indigo-600 hover:text-indigo-700 font-medium"
                                >
                                    +234 800 000 0000
                                </a>
                                <p className="text-sm text-gray-600 mt-2">
                                    Monday-Friday, 9AM-5PM WAT
                                </p>
                            </div>
                            
                            <div className="p-6 bg-white rounded-xl shadow-sm">
                                <div className="text-indigo-600 mb-3">
                                    <svg className="h-8 w-8 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                    </svg>
                                </div>
                                <h4 className="font-medium text-gray-900 mb-2">Visit Us</h4>
                                <address className="text-gray-600 not-italic">
                                    Tyneside Innovation Centre,<br />
                                    Willington Square,<br />
                                    Wallsend, NE28 6HQ
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </GuestLayout>
    );
}