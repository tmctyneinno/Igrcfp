import React, { useState, useEffect } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { 
    CheckCircleIcon, 
    ExclamationCircleIcon,
    EnvelopeIcon,
    PhoneIcon,
    MapPinIcon,
    ClockIcon,
    ChatBubbleLeftRightIcon,
    UserGroupIcon,
    CheckBadgeIcon
} from '@heroicons/react/24/outline';

export default function Index({ auth, title }) {
    const pageProps = usePage().props;
    const flash = pageProps?.flash || {};

    // Use snake_case for ALL form fields to match Laravel validation
    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        country_code: 'NG',
        message: '',
        agree: false,
    });

    const [showSuccess, setShowSuccess] = useState(false);
    const [successMessage, setSuccessMessage] = useState('');

    // Check for flash message on component mount and when page props change
    useEffect(() => {
        console.log('Flash data:', flash);
        console.log('Has success message?', !!flash?.success);
        
        if (flash?.success) {
            setShowSuccess(true);
            setSuccessMessage(flash.success);
            
            // Auto-hide after 8 seconds
            const timer = setTimeout(() => {
                setShowSuccess(false);
            }, 8000);
            
            return () => clearTimeout(timer);
        }
    }, [flash]);

    const countryCodes = [
        { code: 'NG', name: 'Nigeria (+234)' },
        { code: 'US', name: 'USA (+1)' },
        { code: 'GB', name: 'UK (+44)' },
        { code: 'GH', name: 'Ghana (+233)' },
        { code: 'KE', name: 'Kenya (+254)' },
        { code: 'ZA', name: 'South Africa (+27)' },
        { code: 'CA', name: 'Canada (+1)' },
        { code: 'AU', name: 'Australia (+61)' },
        { code: 'FR', name: 'France (+33)' },
        { code: 'DE', name: 'Germany (+49)' },
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        
        console.log('Form data being sent:', data);
        
        // Submit with a different approach
        post(route('contact.store'), {
            ...data,
            preserveState: false, // This forces a full page reload
        }, {
            onSuccess: () => {
                console.log('Form submitted successfully!');
                reset();
                // Force scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            onError: (errors) => {
                console.log('Form errors:', errors);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
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
                className="block text-sm font-medium text-gray-700 mb-2"
            >
                {label} {required && <span className="text-red-500">*</span>}
            </label>
            <input
                id={id}
                type={type}
                value={data[props.name] || ''}
                onChange={handleChange}
                disabled={processing}
                className={`w-full px-4 py-3.5 border rounded-xl transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-indigo-500/30 focus:border-indigo-500 ${
                    errors[props.name] 
                        ? 'border-red-300 bg-red-50 focus:ring-red-500/30 focus:border-red-500' 
                        : 'border-gray-300 hover:border-gray-400 focus:shadow-lg'
                } ${processing ? 'opacity-60 cursor-not-allowed' : ''}`}
                required={required}
                {...props}
            />
            {errors[props.name] && (
                <p className="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                    <ExclamationCircleIcon className="h-4 w-4 flex-shrink-0" />
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
            
            {/* Hero Banner */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                   <div className="text-center">
                        <div className="inline-flex items-center bg-blue-100 px-3 py-1 justify-center space-x-2 mb-6 rounded-full">
                            <div className="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                            <span className="font-medium text-sm tracking-wider">{title}</span>
                        </div>
                        <h1 className="text-3xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                            Let's Start a Conversation
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Your success is our priority. Connect with our expert team for personalized support, partnership opportunities, or any questions about our services.
                        </p>
                    </div>
                </div>
            </section>

           

            {/* SUCCESS MESSAGE - Always shows if there's a flash.success */}
            {flash?.success && (
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-4">
                    <div className="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-6 rounded-lg shadow-lg animate-fade-in">
                        <div className="flex items-start">
                            <div className="flex-shrink-0">
                                <CheckCircleIcon className="h-8 w-8 text-green-500 animate-bounce" />
                            </div>
                            <div className="ml-4">
                                <h3 className="text-lg font-semibold text-green-800 mb-1">
                                    Message Sent Successfully!
                                </h3>
                                <p className="text-green-700 mb-2">
                                    {flash.success}
                                </p>
                                <p className="text-sm text-green-600">
                                    ✅ We've received your message and will respond within 24-48 hours.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* ERROR MESSAGE */}
            {flash?.error && (
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-4">
                    <div className="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-6 rounded-lg shadow-lg animate-fade-in">
                        <div className="flex items-center">
                            <ExclamationCircleIcon className="h-8 w-8 text-red-500 mr-3 flex-shrink-0" />
                            <div>
                                <p className="text-red-800 font-semibold">{flash.error}</p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Main Content Section */}
            <section className="py-16 lg:py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                       
                        {/* Left Column - Contact Information */}
                        <div className="space-y-8">
                            <div className="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl p-8 border border-gray-100">
                                <div className="text-center mb-10">
                                    <div className="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl mb-6">
                                        <UserGroupIcon className="h-8 w-8 text-white" />
                                    </div>
                                    <h2 className="text-3xl font-bold text-gray-900 mb-3">
                                        Get in Touch
                                    </h2>
                                    <p className="text-gray-600">
                                        Multiple ways to connect with our team.
                                    </p>
                                </div>

                                <div className="space-y-6">
                                    <div className="flex items-start p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:shadow-md transition-shadow duration-200">
                                        <div className="flex-shrink-0">
                                            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                                <EnvelopeIcon className="h-6 w-6 text-white" />
                                            </div>
                                        </div>
                                        <div className="ml-5">
                                            <h3 className="font-semibold text-gray-900 mb-1">Email Us</h3>
                                            <a 
                                                href="mailto:enquiries@igrfcp.org" 
                                                className="text-blue-600 hover:text-blue-700 font-medium text-lg block mb-1"
                                            >
                                               enquiries@igrfcp.org
                                            </a>
                                            <p className="text-sm text-gray-600">
                                                We typically respond within 24 hours
                                            </p>
                                        </div>
                                    </div>

                                    <div className="flex items-start p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:shadow-md transition-shadow duration-200">
                                        <div className="flex-shrink-0">
                                            <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                                <MapPinIcon className="h-6 w-6 text-white" />
                                            </div>
                                        </div>
                                        <div className="ml-5">
                                            <h3 className="font-semibold text-gray-900 mb-1">Visit Our Office</h3>
                                            <address className="text-gray-800 not-italic mb-2">
                                                85 Great Portland Street<br />
                                                First Floor, W1W 7LT<br />
                                                London, United Kingdom
                                            </address>
                                            <a 
                                                href="https://maps.google.com/?q=85+Great+Portland+Street+London+W1W+7LT"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-purple-600 hover:text-purple-700 font-medium text-sm inline-flex items-center"
                                            >
                                                Get Directions
                                                <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div className="mt-8 pt-8 border-t border-gray-200">
                                    <div className="flex items-center justify-center space-x-3">
                                        <CheckBadgeIcon className="h-6 w-6 text-green-500" />
                                        <span className="text-gray-700 font-medium">
                                            Average Response Time: <span className="text-green-600">24-48 hours</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                         {/* Right Column - Contact Form */}
                        <div>
                            <div className="bg-white rounded-2xl shadow-2xl p-8 md:p-8 border border-gray-100">
                                <div className="text-center mb-10">
                                    <div className="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-6">
                                        <EnvelopeIcon className="h-8 w-8 text-white" />
                                    </div>
                                    <h2 className="text-3xl font-bold text-gray-900 mb-3">
                                        Send Your Message
                                    </h2>
                                    <p className="text-gray-600">
                                        Fill out the form below and we'll get back to you as soon as possible.
                                    </p>
                                </div>
                                
                                <form onSubmit={handleSubmit} className="space-y-8">
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <InputField
                                            label="First Name"
                                            id="first_name"
                                            name="first_name"
                                            placeholder=""
                                            autoComplete="given-name"
                                        />
                                        
                                        <InputField
                                            label="Last Name"
                                            id="last_name"
                                            name="last_name"
                                            placeholder=""
                                            autoComplete="family-name"
                                        />
                                    </div>

                                    <InputField
                                        label="Email Address"
                                        id="email"
                                        name="email"
                                        type="email"
                                        placeholder=""
                                        autoComplete="email"
                                    />

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Phone Number <span className="text-red-500">*</span>
                                        </label>
                                        <div className="flex flex-col sm:flex-row gap-4">
                                            <div className="sm:w-48">
                                                <select
                                                    name="country_code"
                                                    id="country_code"
                                                    value={data.country_code}
                                                    onChange={handleChange}
                                                    disabled={processing}
                                                    className="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-3 focus:ring-indigo-500/30 focus:border-indigo-500 hover:border-gray-400 focus:shadow-lg"
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
                                                    className={`w-full px-4 py-3.5 border rounded-xl transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-indigo-500/30 focus:border-indigo-500 ${
                                                        errors.phone 
                                                            ? 'border-red-300 bg-red-50 focus:ring-red-500/30 focus:border-red-500' 
                                                            : 'border-gray-300 hover:border-gray-400 focus:shadow-lg'
                                                    }`}
                                                />
                                                {errors.phone && (
                                                    <p className="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                                        <ExclamationCircleIcon className="h-4 w-4 flex-shrink-0" />
                                                        {errors.phone}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Your Message <span className="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            name="message"
                                            id="message"
                                            rows="6"
                                            value={data.message}
                                            onChange={handleChange}
                                            disabled={processing}
                                            className={`w-full px-4 py-3.5 border rounded-xl transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-indigo-500/30 focus:border-indigo-500 resize-none ${
                                                errors.message 
                                                    ? 'border-red-300 bg-red-50 focus:ring-red-500/30 focus:border-red-500' 
                                                    : 'border-gray-300 hover:border-gray-400 focus:shadow-lg'
                                            }`}
                                            placeholder="Tell us how we can help you..."
                                        />
                                        {errors.message && (
                                            <p className="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                                <ExclamationCircleIcon className="h-4 w-4 flex-shrink-0" />
                                                {errors.message}
                                            </p>
                                        )}
                                    </div>

                                    <div className="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                        <div className="flex items-start">
                                            <div className="flex items-center h-5 mt-1">
                                                <input
                                                    type="checkbox"
                                                    name="agree"
                                                    id="agree"
                                                    checked={data.agree}
                                                    onChange={handleChange}
                                                    disabled={processing}
                                                    className="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-3 focus:ring-indigo-500/30"
                                                    required
                                                />
                                            </div>
                                            <div className="ml-4">
                                                <label htmlFor="agree" className="text-sm font-medium text-gray-900">
                                                    I agree to the privacy policy
                                                </label>
                                                <p className="text-sm text-gray-600 mt-1.5">
                                                    By submitting this form, you acknowledge that you have read and agree to our{' '}
                                                    <a 
                                                        href="/privacy-policy" 
                                                        className="text-indigo-600 hover:text-indigo-500 font-medium underline"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                    >
                                                        Privacy Policy
                                                    </a>
                                                    . Your information is secure with us.
                                                </p>
                                                {errors.agree && (
                                                    <p className="mt-2 text-sm text-red-600 flex items-center gap-1.5">
                                                        <ExclamationCircleIcon className="h-4 w-4 flex-shrink-0" />
                                                        {errors.agree}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="pt-2">
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className="w-full py-4 px-6 rounded-xl font-semibold bg-gradient-to-r from-blue-900 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-900/30 active:scale-[0.98] shadow-lg hover:shadow-xl disabled:opacity-70 disabled:cursor-not-allowed"
                                        >
                                            {processing ? (
                                                <span className="flex items-center justify-center gap-3">
                                                    <svg className="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                    </svg>
                                                    <span>Sending Your Message...</span>
                                                </span>
                                            ) : (
                                                <span className="flex items-center justify-center gap-2">
                                                    <EnvelopeIcon className="h-5 w-5" />
                                                    Send Message
                                                </span>
                                            )}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-20 bg-gradient-to-r from-blue-950 to-blue-900">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-6">Ready to Start Your Learning Journey?</h2>
                    <p className="text-blue-100 mb-8 text-lg">
                        Join thousands of successful learners who have transformed their careers with our platform.
                    </p>
                    <Link
                        href={auth.user ? route('dashboard') : route('register')}
                        className="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition duration-300 shadow-lg transform hover:scale-105"
                    >
                        {auth.user ? 'Continue Learning' : 'Get Started for Free'}
                    </Link>
                </div>
            </section>

            {/* Add CSS animations */}
            <style jsx>{`
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(-20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .animate-fade-in {
                    animation: fadeIn 0.5s ease-out;
                }
                
                .animate-bounce {
                    animation: bounce 1s infinite;
                }
                
                @keyframes bounce {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-5px);
                    }
                }
            `}</style>
        </GuestLayout>
    );
}