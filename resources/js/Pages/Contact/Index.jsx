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
    CheckBadgeIcon,
    XMarkIcon
} from '@heroicons/react/24/outline';

// Toast Component
const Toast = ({ message, type = 'success', onClose }) => {
    useEffect(() => {
        const timer = setTimeout(() => {
            onClose();
        }, 5000); // Auto-close after 5 seconds

        return () => clearTimeout(timer);
    }, [onClose]);

    const bgColor = type === 'success' 
        ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500' 
        : 'bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500';
    
    const iconColor = type === 'success' ? 'text-green-500' : 'text-red-500';
    const Icon = type === 'success' ? CheckCircleIcon : ExclamationCircleIcon;

    return (
        <div className={`fixed top-4 right-4 z-50 p-4 rounded-lg shadow-xl max-w-md animate-slide-in ${bgColor}`}>
            <div className="flex items-start">
                <div className="flex-shrink-0">
                    <Icon className={`h-6 w-6 ${iconColor}`} />
                </div>
                <div className="ml-3 flex-1">
                    <p className="text-sm font-medium text-gray-900">
                        {type === 'success' ? 'Success!' : 'Error!'}
                    </p>
                    <p className="mt-1 text-sm text-gray-700">
                        {message}
                    </p>
                </div>
                <button
                    onClick={onClose}
                    className="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600"
                >
                    <XMarkIcon className="h-5 w-5" />
                </button>
            </div>
        </div>
    );
};

export default function Index({ auth, title }) {
    const pageProps = usePage().props;
    const flash = pageProps?.flash || {};

    // Use snake_case for ALL form fields to match Laravel validation
    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        country_code: 'GB',
        message: '',
        agree: false,
    });

    // State for toast notifications
    const [toast, setToast] = useState(null);

    // Show toast when flash message comes from server
    useEffect(() => {
        if (flash?.success) {
            setToast({
                message: flash.success,
                type: 'success'
            });
        }
        
        if (flash?.error) {
            setToast({
                message: flash.error,
                type: 'error'
            });
        }
        
        if (flash?.message) {
            setToast({
                message: flash.message,
                type: 'info'
            });
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
        
        post(route('contact.store'), data, {
            onSuccess: (page) => {
                console.log('Form submitted successfully!');
                reset();
                
                // Show toast notification for success
                if (page.props.flash?.success) {
                    setToast({
                        message: page.props.flash.success,
                        type: 'success'
                    });
                }
                
                
                // Scroll to top to show form is cleared
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            onError: (errors) => {
                console.log('Form errors:', errors);
                
                // Show error toast if there's a general error
                if (errors?.general) {
                    setToast({
                        message: errors.general,
                        type: 'error'
                    });
                }
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
            
            {/* Toast Notification */}
            {toast && (
                <Toast 
                    message={toast.message} 
                    type={toast.type} 
                    onClose={() => setToast(null)} 
                />
            )}
            
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

            {/* Main Content Section */}
            {/* Main Content */}
<section className="py-12 lg:py-16">
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
           
            {/* Left Column - Contact Info & Media */}
            <div className="space-y-6">
                {/* Contact Info Card */}
                <div className="bg-white rounded-xl shadow-lg p-6">
                    <div className="text-center mb-6">
                        <div className="inline-flex items-center justify-center w-12 h-12 bg-purple-600 rounded-lg mb-4">
                            <UserGroupIcon className="h-6 w-6 text-white" />
                        </div>
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">
                            Get in Touch
                        </h2>
                        <p className="text-gray-600 text-sm">
                            Multiple ways to connect with our team
                        </p>
                    </div>

                    <div className="space-y-4">
                        <div className="flex items-center p-4 bg-blue-50 rounded-lg">
                            <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <EnvelopeIcon className="h-5 w-5 text-white" />
                            </div>
                            <div className="ml-4">
                                <h3 className="font-medium text-gray-900">Email Us</h3>
                                <a 
                                    href="mailto:enquiries@igrfcp.org" 
                                    className="text-blue-600 hover:text-blue-700 font-medium block"
                                >
                                   enquiries@igrfcp.org
                                </a>
                                <p className="text-xs text-gray-600 mt-1">
                                    Response within 24 hours
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center p-4 bg-purple-50 rounded-lg">
                            <div className="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <MapPinIcon className="h-5 w-5 text-white" />
                            </div>
                            <div className="ml-4">
                                <h3 className="font-medium text-gray-900">Our Office</h3>
                                <address className="text-sm text-gray-800 not-italic">
                                    85 Great Portland Street<br />
                                    First Floor, W1W 7LT<br />
                                    London, UK
                                </address>
                                <a 
                                    href="https://maps.google.com/?q=85+Great+Portland+Street+London"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="text-purple-600 hover:text-purple-700 text-xs font-medium inline-flex items-center mt-1"
                                >
                                    Get Directions
                                    <svg className="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div className="mt-6 pt-6 border-t border-gray-200">
                        <div className="flex items-center justify-center space-x-2">
                            <CheckBadgeIcon className="h-5 w-5 text-green-500" />
                            <span className="text-sm text-gray-700">
                                Response Time: <span className="text-green-600 font-medium">24-48 hours</span>
                            </span>
                        </div>
                    </div>
                </div>

                {/* Google Map Embed */}
                <div className="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div className="p-4 border-b border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900 flex items-center">
                            <MapPinIcon className="h-5 w-5 text-blue-500 mr-2" />
                            Our Location
                        </h3>
                    </div>
                    <div className="relative h-56">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2482.778581834643!2d-0.14409758422943673!3d51.51890797963733!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48761ad554c4a7c5%3A0xc78e84c8b982c8a6!2s85%20Great%20Portland%20St%2C%20London%20W1W%207LT%2C%20UK!5e0!3m2!1sen!2suk!4v1638446789056!5m2!1sen!2suk"
                            width="100%"
                            height="100%"
                            style={{ border: 0 }}
                            allowFullScreen=""
                            loading="lazy"
                            referrerPolicy="no-referrer-when-downgrade"
                            title="IGRCFP Office Location"
                            className="absolute inset-0"
                        ></iframe>
                    </div>
                </div>

                {/* Office Image */}
                <div className="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div className="p-4 border-b border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900 flex items-center">
                            <svg className="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Our Office Environment
                        </h3>
                    </div>
                    <div className="relative h-48">
                        <img
                            src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="IGRCFP Office Interior"
                            className="w-full h-full object-cover"
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent flex items-end">
                            <div className="p-4 text-white">
                                <p className="text-sm">Modern workspace for innovation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             {/* Right Column - Contact Form */}
            <div>
                <div className="bg-white rounded-xl shadow-lg p-6">
                    <div className="text-center mb-6">
                        <div className="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-lg mb-4">
                            <EnvelopeIcon className="h-6 w-6 text-white" />
                        </div>
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">
                            Send Your Message
                        </h2>
                        <p className="text-gray-600 text-sm">
                            We'll get back to you as soon as possible
                        </p>
                    </div>
                    
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            placeholder="john.doe@example.com"
                            autoComplete="email"
                        />

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span className="text-red-500">*</span>
                            </label>
                            <div className="flex gap-3">
                                <div className="w-40">
                                    <select
                                        name="country_code"
                                        id="country_code"
                                        value={data.country_code}
                                        onChange={handleChange}
                                        disabled={processing}
                                        className="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500"
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
                                        className={`w-full px-3 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 ${
                                            errors.phone 
                                                ? 'border-red-300 bg-red-50' 
                                                : 'border-gray-300'
                                        }`}
                                    />
                                    {errors.phone && (
                                        <p className="mt-1 text-xs text-red-600 flex items-center gap-1">
                                            <ExclamationCircleIcon className="h-3 w-3" />
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
                                rows="4"
                                value={data.message}
                                onChange={handleChange}
                                disabled={processing}
                                className={`w-full px-3 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 resize-none ${
                                    errors.message 
                                        ? 'border-red-300 bg-red-50' 
                                        : 'border-gray-300'
                                }`}
                                placeholder="How can we help you?"
                            />
                            {errors.message && (
                                <p className="mt-1 text-xs text-red-600 flex items-center gap-1">
                                    <ExclamationCircleIcon className="h-3 w-3" />
                                    {errors.message}
                                </p>
                            )}
                        </div>

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
                                        className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500/30"
                                        required
                                    />
                                </div>
                                <div className="ml-3">
                                    <label htmlFor="agree" className="text-sm font-medium text-gray-900">
                                        I agree to the privacy policy
                                    </label>
                                    <p className="text-xs text-gray-600 mt-1">
                                        By submitting, you agree to our{' '}
                                        <a 
                                            href="/privacy-policy" 
                                            className="text-blue-600 hover:text-blue-500 font-medium"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Privacy Policy
                                        </a>
                                        .
                                    </p>
                                    {errors.agree && (
                                        <p className="mt-1 text-xs text-red-600 flex items-center gap-1">
                                            <ExclamationCircleIcon className="h-3 w-3" />
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
                                className="w-full py-3 px-4 rounded-lg font-medium bg-blue-700 hover:bg-blue-800 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/30 disabled:opacity-60 disabled:cursor-not-allowed"
                            >
                                {processing ? (
                                    <span className="flex items-center justify-center gap-2">
                                        <svg className="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                        </svg>
                                        <span>Sending...</span>
                                    </span>
                                ) : (
                                    <span className="flex items-center justify-center gap-2">
                                        <EnvelopeIcon className="h-4 w-4" />
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
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes fadeOut {
                    from {
                        opacity: 1;
                    }
                    to {
                        opacity: 0;
                    }
                }
                
                .animate-slide-in {
                    animation: slideIn 0.3s ease-out forwards;
                }
                
                .animate-fade-out {
                    animation: fadeOut 0.3s ease-out forwards;
                }
            `}</style>
        </GuestLayout>
    );
}