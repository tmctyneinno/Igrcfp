import React from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
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
        { code: 'CA', name: 'Canada (+1)' },
        { code: 'AU', name: 'Australia (+61)' },
        { code: 'FR', name: 'France (+33)' },
        { code: 'DE', name: 'Germany (+49)' },
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
                aria-invalid={errors[props.name] ? 'true' : 'false'}
                aria-describedby={errors[props.name] ? `${id}-error` : undefined}
                {...props}
            />
            {errors[props.name] && (
                <p 
                    id={`${id}-error`} 
                    className="mt-2 text-sm text-red-600 flex items-center gap-1.5"
                    role="alert"
                >
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
            
            {/* Hero Banner with Background Image */}
             <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                   <div className="text-center">
                        
                        <div className="inline-flex items-center bg-blue-100 px-3 py-1 justify-center space-x-2 mb-6 rounded-full">
                            <div className="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                            <span className=" font-medium text-sm  tracking-wider"> {title}</span>
                        </div>
                        <h1 
                            id="page-title"
                            className="text-3xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight"
                        >
                            Let's Start a Conversation
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Your success is our priority. Connect with our expert team for personalized support, partnership opportunities, or any questions about our services.
                        </p>
                    </div>
                </div>
            </section>
          

            {/* Status Messages */}
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-4">
                {flash?.success && (
                    <div className="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                        <div className="flex items-center">
                            <CheckCircleIcon className="h-5 w-5 text-green-500 mr-3 flex-shrink-0" />
                            <p className="text-green-800">{flash?.success}</p>
                        </div>
                    </div>
                )}

                {flash?.error && (
                    <div className="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                        <div className="flex items-center">
                            <ExclamationCircleIcon className="h-5 w-5 text-red-500 mr-3 flex-shrink-0" />
                            <p className="text-red-800">{flash?.error}</p>
                        </div>
                    </div>
                )}

                {flash?.message && (
                    <div className="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                        <div className="flex items-center">
                            <ChatBubbleLeftRightIcon className="h-5 w-5 text-blue-500 mr-3 flex-shrink-0" />
                            <p className="text-blue-800">{flash?.message}</p>
                        </div>
                    </div>
                )}
            </div>

            {/* Main Content Section */}
            <section className="py-16 lg:py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                       
                        {/* Left  Column - Contact Information & Map */}
                        <div className="space-y-1">
                            {/* Contact Information Cards */}
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
                                    {/* Email Card */}
                                    <div className="flex items-start p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:shadow-md transition-shadow duration-200">
                                        <div className="flex-shrink-0">
                                            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                                <EnvelopeIcon className="h-6 w-6 text-white" />
                                            </div>
                                        </div>
                                        <div className="ml-5">
                                            <h3 className="font-semibold text-gray-900 mb-1">Email Us</h3>
                                            <a 
                                                href="mailto:info@igrcfp.org" 
                                                className="text-blue-600 hover:text-blue-700 font-medium text-lg block mb-1"
                                            >
                                               enquiries@igrfcp.org
                                            </a>
                                            <p className="text-sm text-gray-600">
                                                We typically respond within 24 hours
                                            </p>
                                        </div>
                                    </div>

                                    {/* Phone Card */}
                                    <div className="flex items-start p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:shadow-md transition-shadow duration-200">
                                        <div className="flex-shrink-0">
                                            <div className="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                                <PhoneIcon className="h-6 w-6 text-white" />
                                            </div>
                                        </div>
                                        <div className="ml-5">
                                            <h3 className="font-semibold text-gray-900 mb-1">Call Us</h3>
                                            {/* <a 
                                                href="tel:+2348000000000" 
                                                className="text-emerald-600 hover:text-emerald-700 font-medium text-lg block mb-1"
                                            >
                                                +234 800 000 0000
                                            </a> */}
                                            <div className="flex items-center text-sm text-gray-600">
                                                <ClockIcon className="h-4 w-4 mr-1.5" />
                                                Mon-Fri: 9AM-6PM WAT
                                            </div>
                                        </div>
                                    </div>

                                    {/* Location Card */}
                                    <div className="flex items-start p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:shadow-md transition-shadow duration-200">
                                        <div className="flex-shrink-0">
                                            <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                                <MapPinIcon className="h-6 w-6 text-white" />
                                            </div>
                                        </div>
                                        <div className="ml-5">
                                            <h3 className="font-semibold text-gray-900 mb-1">Visit Our Office</h3>
                                            <address className="text-gray-800 not-italic mb-2">
                                                85 Great Portland Street
                                                First Floor, W1W 7LT
                                                London, United Kingdom
                                            </address>
                                            <a 
                                                href="https://maps.google.com/?q=Tyneside+Innovation+Centre+Wallington+Square+Wallsend+NE28+6HQ"
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

                                {/* Response Time Info */}
                                <div className="mt-8 pt-8 border-t border-gray-200">
                                    <div className="flex items-center justify-center space-x-3">
                                        <CheckBadgeIcon className="h-6 w-6 text-green-500" />
                                        <span className="text-gray-700 font-medium">
                                            Average Response Time: <span className="text-green-600">24-48 hours</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Google Map Embed */}
                            <div className="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                                <div className="p-6 border-b border-gray-200">
                                    <h3 className="text-xl font-bold text-gray-900 flex items-center">
                                        <MapPinIcon className="h-6 w-6 text-blue-500 mr-2" />
                                        Our Location
                                    </h3>
                                </div>
                                <div className="relative h-80">
                                    {/* Google Maps Iframe */}
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2290.089024865421!2d-1.5337692836437086!3d54.99101198035857!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487e70e3c24a8a2f%3A0x8b4b4b4b4b4b4b4b!2sTyneside%20Innovation%20Centre!5e0!3m2!1sen!2suk!4v1638446789056!5m2!1sen!2suk"
                                        width="100%"
                                        height="100%"
                                        style={{ border: 0 }}
                                        allowFullScreen=""
                                        loading="lazy"
                                        referrerPolicy="no-referrer-when-downgrade"
                                        title="IGRCFP Office Location"
                                        className="absolute inset-0"
                                    ></iframe>
                                    
                                    {/* Map Overlay with Logo */}
                                    <div className="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm rounded-xl p-4 shadow-lg max-w-xs">
                                        <div className="flex items-center space-x-3">
                                            <div className="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                                                <MapPinIcon className="h-5 w-5 text-white" />
                                            </div>
                                            <div>
                                                <h4 className="font-semibold text-gray-900 text-sm">IGRCFP Headquarters</h4>
                                                <p className="text-xs text-gray-600">Tyneside Innovation Centre</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Office Image */}
                            <div className="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                                <div className="p-6 border-b border-gray-200">
                                    <h3 className="text-xl font-bold text-gray-900 flex items-center">
                                        <svg className="h-6 w-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Our Office Environment
                                    </h3>
                                </div>
                                <div className="relative h-64">
                                    <img
                                        src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                                        alt="IGRCFP Office Interior"
                                        className="w-full h-full object-cover"
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent flex items-end">
                                        <div className="p-6 text-white">
                                            <p className="text-sm opacity-90">Modern workspace designed for innovation and collaboration</p>
                                        </div>
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
                                    {/* Name Fields */}
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <InputField
                                            label="First Name"
                                            id="firstName"
                                            name="firstName"
                                            placeholder="John"
                                            autoComplete="given-name"
                                        />
                                        
                                        <InputField
                                            label="Last Name"
                                            id="lastName"
                                            name="lastName"
                                            placeholder="Doe"
                                            autoComplete="family-name"
                                        />
                                    </div>

                                    {/* Email */}
                                    <InputField
                                        label="Email Address"
                                        id="email"
                                        name="email"
                                        type="email"
                                        placeholder="john.doe@example.com"
                                        autoComplete="email"
                                    />

                                    {/* Phone Number */}
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Phone Number <span className="text-red-500">*</span>
                                        </label>
                                        <div className="flex flex-col sm:flex-row gap-4">
                                            <div className="sm:w-48">
                                                <select
                                                    name="countryCode"
                                                    id="countryCode"
                                                    value={data.countryCode}
                                                    onChange={handleChange}
                                                    disabled={processing}
                                                    className={`w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-3 focus:ring-indigo-500/30 focus:border-indigo-500 ${
                                                        processing ? 'opacity-60 cursor-not-allowed' : 'hover:border-gray-400 focus:shadow-lg'
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
                                                    className={`w-full px-4 py-3.5 border rounded-xl transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-indigo-500/30 focus:border-indigo-500 ${
                                                        errors.phone 
                                                            ? 'border-red-300 bg-red-50 focus:ring-red-500/30 focus:border-red-500' 
                                                            : 'border-gray-300 hover:border-gray-400 focus:shadow-lg'
                                                    } ${processing ? 'opacity-60 cursor-not-allowed' : ''}`}
                                                    aria-invalid={errors.phone ? 'true' : 'false'}
                                                    aria-describedby={errors.phone ? 'phone-error' : undefined}
                                                />
                                                {errors.phone && (
                                                    <p 
                                                        id="phone-error" 
                                                        className="mt-2 text-sm text-red-600 flex items-center gap-1.5"
                                                        role="alert"
                                                    >
                                                        <ExclamationCircleIcon className="h-4 w-4 flex-shrink-0" />
                                                        {errors.phone}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Message */}
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
                                            } ${processing ? 'opacity-60 cursor-not-allowed' : ''}`}
                                            placeholder="Tell us how we can help you..."
                                            aria-invalid={errors.message ? 'true' : 'false'}
                                            aria-describedby={errors.message ? 'message-error' : undefined}
                                        />
                                        <div className="flex justify-between items-center mt-3">
                                            {errors.message && (
                                                <p 
                                                    id="message-error" 
                                                    className="text-sm text-red-600 flex items-center gap-1.5"
                                                    role="alert"
                                                >
                                                    <ExclamationCircleIcon className="h-4 w-4 flex-shrink-0" />
                                                    {errors.message}
                                                </p>
                                            )}
                                            <span className={`text-sm ${processing ? 'text-gray-400' : 'text-gray-500'} ml-auto`}>
                                                {data.message.length}/2000 characters
                                            </span>
                                        </div>
                                    </div>

                                    {/* Privacy Policy Agreement */}
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
                                                    className={`h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-3 focus:ring-indigo-500/30 ${
                                                        processing ? 'opacity-60 cursor-not-allowed' : ''
                                                    }`}
                                                    required
                                                    aria-invalid={errors.agree ? 'true' : 'false'}
                                                    aria-describedby={errors.agree ? 'agree-error' : 'agree-description'}
                                                />
                                            </div>
                                            <div className="ml-4">
                                                <label 
                                                    htmlFor="agree" 
                                                    className={`text-sm font-medium ${processing ? 'text-gray-400' : 'text-gray-900'}`}
                                                >
                                                    I agree to the privacy policy
                                                </label>
                                                <p 
                                                    id="agree-description" 
                                                    className={`text-sm ${processing ? 'text-gray-400' : 'text-gray-600'} mt-1.5`}
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
                                                    . Your information is secure with us.
                                                </p>
                                                {errors.agree && (
                                                    <p 
                                                        id="agree-error" 
                                                        className="mt-2 text-sm text-red-600 flex items-center gap-1.5"
                                                        role="alert"
                                                    >
                                                        <ExclamationCircleIcon className="h-4 w-4 flex-shrink-0" />
                                                        {errors.agree}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Submit Button */}
                                    <div className="pt-2">
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className={`w-full py-4 px-6 rounded-xl font-semibold transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 ${
                                                processing
                                                    ? 'bg-gradient-to-r from-gray-400 to-gray-500 cursor-not-allowed'
                                                    : 'bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 active:scale-[0.98] shadow-lg hover:shadow-xl'
                                            }`}
                                            aria-busy={processing}
                                        >
                                            {processing ? (
                                                <span className="flex items-center justify-center gap-3 text-white">
                                                    <svg className="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                    </svg>
                                                    <span>Sending Your Message...</span>
                                                </span>
                                            ) : (
                                                <span className="text-white flex items-center justify-center gap-2">
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

            {/* Statistics Section */}
            <section className="py-16 bg-gradient-to-r from-gray-50 to-blue-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Why Choose Us
                        </h2>
                        <p className="text-gray-600 max-w-2xl mx-auto">
                            We're committed to providing exceptional service and support to all our clients.
                        </p>
                    </div>
                    
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div className="text-center p-6 bg-white rounded-2xl shadow-lg">
                            <div className="text-4xl font-bold text-blue-600 mb-2">24h</div>
                            <div className="text-sm text-gray-600">Average Response Time</div>
                        </div>
                        <div className="text-center p-6 bg-white rounded-2xl shadow-lg">
                            <div className="text-4xl font-bold text-blue-600 mb-2">99%</div>
                            <div className="text-sm text-gray-600">Satisfaction Rate</div>
                        </div>
                        <div className="text-center p-6 bg-white rounded-2xl shadow-lg">
                            <div className="text-4xl font-bold text-blue-600 mb-2">5+</div>
                            <div className="text-sm text-gray-600">Years of Experience</div>
                        </div>
                        <div className="text-center p-6 bg-white rounded-2xl shadow-lg">
                            <div className="text-4xl font-bold text-blue-600 mb-2">1000+</div>
                            <div className="text-sm text-gray-600">Happy Clients</div>
                        </div>
                    </div>
                </div>
            </section>

            {/* CSS for Blob Animation */}
            <style jsx>{`
                @keyframes blob {
                    0% {
                        transform: translate(0px, 0px) scale(1);
                    }
                    33% {
                        transform: translate(30px, -50px) scale(1.1);
                    }
                    66% {
                        transform: translate(-20px, 20px) scale(0.9);
                    }
                    100% {
                        transform: translate(0px, 0px) scale(1);
                    }
                }
                .animate-blob {
                    animation: blob 7s infinite;
                }
                .animation-delay-2000 {
                    animation-delay: 2s;
                }
                .animation-delay-4000 {
                    animation-delay: 4s;
                }
            `}</style>
        </GuestLayout>
    );
}