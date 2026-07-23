import React, { useState, useEffect, useRef } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import ReCAPTCHA from 'react-google-recaptcha';
import HeroSection from '@/Layouts/HeroSection';
import CallToAction from "@/Pages/components/CallToAction";
import { 
    CheckCircleIcon, 
    ExclamationCircleIcon,
    EnvelopeIcon,
    PhoneIcon,
    MapPinIcon,
    XMarkIcon
} from '@heroicons/react/24/outline';

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
 
// Toast Component
const Toast = ({ message, type = 'success', onClose }) => {
    useEffect(() => {
        const timer = setTimeout(() => onClose(), 5000);
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
                <div className="flex-shrink-0"><Icon className={`h-6 w-6 ${iconColor}`} /></div>
                <div className="ml-3 flex-1">
                    <p className="text-sm font-medium text-gray-900">{type === 'success' ? 'Success!' : 'Error!'}</p>
                    <p className="mt-1 text-sm text-gray-700">{message}</p>
                </div>
                <button onClick={onClose} className="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <XMarkIcon className="h-5 w-5" />
                </button>
            </div>
        </div>
    );
};

const InputField = ({ label, id, type = 'text', required = true, value, error, onChange, disabled, ...props }) => (
    <div>
        <label htmlFor={id} className="block text-sm font-medium text-gray-700 mb-2">
            {label} {required && <span className="text-red-500">*</span>}
        </label>
        <input
            id={id}
            type={type}
            value={value || ''}
            onChange={onChange}
            disabled={disabled}
            className={`w-full px-4 py-3.5 border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#0A2463]/30 focus:border-[#0A2463] ${
                error ? 'border-red-300 bg-red-50 focus:ring-red-500/30 focus:border-red-500' : 'border-gray-300 hover:border-gray-400'
            } ${disabled ? 'opacity-60 cursor-not-allowed' : ''}`}
            required={required}
            {...props}
        />
        {error && <p className="mt-2 text-sm text-red-600 flex items-center gap-1.5"><ExclamationCircleIcon className="h-4 w-4" />{error}</p>}
    </div>
);

function ContactForm({ setToast }) {
    const { recaptchaSiteKey } = usePage().props;
    const recaptchaRef = useRef(null);
    const [recaptchaError, setRecaptchaError] = useState('');

    // ✅ Mapped to match backend validation rules
    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        country_code: 'GB',
        message: '',
        agree: true, // ✅ Auto-accept to pass validation (or add checkbox if needed)
        'g-recaptcha-response': '',
    });

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setData(name, type === 'checkbox' ? checked : value);

        // ✅ Auto-split full name into first/last for backend
        if (name === 'name') {
            const parts = value.trim().split(/\s+/);
            setData('first_name', parts[0] || '');
            setData('last_name', parts.slice(1).join(' ') || '');
        }
    };

    const handleRecaptchaChange = (value) => {
        setData('g-recaptcha-response', value || '');
        setRecaptchaError('');
    };

    const handleRecaptchaExpired = () => {
        setData('g-recaptcha-response', '');
        setRecaptchaError('reCAPTCHA expired. Please verify again.');
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!data['g-recaptcha-response']) {
            setRecaptchaError('Please complete the reCAPTCHA verification.');
            return;
        }

        post(route('contact.store'), {
            onSuccess: (page) => {
                reset();
                setRecaptchaError('');
                if (recaptchaRef.current) recaptchaRef.current.reset();
                if (page.props.flash?.success) setToast({ message: page.props.flash.success, type: 'success' });
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            onError: (formErrors) => {
                if (recaptchaRef.current) recaptchaRef.current.reset();
                setData('g-recaptcha-response', '');
                if (formErrors?.general) setToast({ message: formErrors.general, type: 'error' });
            },
        });
    };

    return (
        <div className="bg-gray-100 rounded-2xl p-8 h-full">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">Get In Touch</h2>
            <p className="text-gray-700 mb-8 leading-relaxed">
                We'd love to hear from you. Whether you're exploring membership, training, partnerships, accreditation, or just want to understand what we do a little better.
            </p>

            <form onSubmit={handleSubmit} className="space-y-5">
                {/* Single name field maps to first/last name for backend */}
                <InputField 
                    label="Name" 
                    id="name" 
                    name="name" 
                    value={`${data.first_name} ${data.last_name}`.trim()} 
                    error={errors.first_name || errors.last_name} 
                    onChange={handleChange} 
                    disabled={processing} 
                    autoComplete="name" 
                />
                <InputField label="Email Address" id="email" name="email" type="email" value={data.email} error={errors.email} onChange={handleChange} disabled={processing} placeholder="your@email.com" autoComplete="email" />
                <InputField label="Phone Number" id="phone" name="phone" type="tel" value={data.phone} error={errors.phone} onChange={handleChange} disabled={processing} placeholder="+44 ..." autoComplete="tel" />

                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Message <span className="text-red-500">*</span></label>
                    <textarea
                        name="message"
                        id="message"
                        rows="5"
                        value={data.message}
                        onChange={handleChange}
                        disabled={processing}
                        className={`w-full px-4 py-3.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0A2463]/30 focus:border-[#0A2463] resize-none ${
                            errors.message ? 'border-red-300 bg-red-50' : 'border-gray-300'
                        }`}
                        placeholder="How can we help you?"
                    />
                    {errors.message && <p className="mt-1 text-xs text-red-600 flex items-center gap-1"><ExclamationCircleIcon className="h-3 w-3" />{errors.message}</p>}
                </div>

                <div className="pt-2">
                    <button
                        type="submit"
                        disabled={processing || !data['g-recaptcha-response']}
                        className="w-full py-3.5 px-4 rounded-lg font-semibold bg-[#0A1A2F] hover:bg-[#0A2463] text-white transition-colors focus:outline-none disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        {processing ? 'Sending...' : 'Send Message'}
                    </button>
                </div>

                {recaptchaSiteKey && <ReCAPTCHA ref={recaptchaRef} sitekey={recaptchaSiteKey} onChange={handleRecaptchaChange} onExpired={handleRecaptchaExpired} />}
                {(recaptchaError || errors['g-recaptcha-response']) && <p className="text-sm text-red-600 flex items-center gap-1.5"><ExclamationCircleIcon className="h-4 w-4" />{recaptchaError || errors['g-recaptcha-response']}</p>}
            </form>
        </div>
    );
}

export default function Index({ auth, title }) {
    const pageProps = usePage().props;
    const flash = pageProps?.flash || {};
    const [toast, setToast] = useState(null);

    useEffect(() => {
        if (flash?.success) setToast({ message: flash.success, type: 'success' });
        if (flash?.error) setToast({ message: flash.error, type: 'error' });
    }, [flash]);

    return (
        <GuestLayout auth={auth}>
            <Head title={title}>
                <meta name="description" content="Get in touch with IGRCFP. Our team is ready to assist you with any inquiries or support you may need." />
            </Head>
            
            {toast && <Toast message={toast.message} type={toast.type} onClose={() => setToast(null)} />}
            
            <HeroSection title={title} description="Let's Start a Conversation" />
            
            <section className="py-12 lg:py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        {/* Left Column - Contact Information */}
                        <div className="bg-[#0A1A2F] text-white rounded-2xl p-8">
                            <h2 className="text-3xl font-bold mb-3">Contact Information</h2>
                            <p className="text-gray-300 mb-8">Get in touch with the appropriate department for efficient assistance.</p>

                            <div className="space-y-4 mb-10">
                                {/* General Enquiries */}
                                <div className="flex items-center gap-4 bg-white rounded-lg p-4">
                                    <div className="flex-shrink-0 w-10 h-10 bg-[#061E34] rounded-md flex items-center justify-center">
                                        <EnvelopeIcon className="h-5 w-5 text-[#ffffff]" />
                                    </div>
                                    <div>
                                        <h4 className="font-semibold text-black">General Enquiries <span className="text-xs text-black"> (Partnership, Publications)</span></h4>
                                      
                                        <a href="mailto:enquiries@igrcfp.org" className="text-sm text-blue-600 font-medium">enquiries@igrcfp.org</a>
                                    </div>
                                </div>

                                {/* Training & Certification */}
                                <div className="flex items-center gap-4 bg-white rounded-lg p-4">
                                    <div className="flex-shrink-0 w-10 h-10 bg-[#061E34] rounded-md flex items-center justify-center">
                                        <svg className="h-5 w-5 text-[#FFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 14l9-5-9-5-9 5 9 5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 className="font-semibold text-black">Training & Certification</h4>
                                        <a href="mailto:training@igrcfp.org" className="text-sm text-blue-600 font-medium">training@igrcfp.org</a>
                                    </div>
                                </div>

                                {/* Phone Number */}
                                <div className="flex items-center gap-4 bg-white rounded-lg p-4">
                                    <div className="flex-shrink-0 w-10 h-10 bg-[#061E34] rounded-md flex items-center justify-center">
                                        <PhoneIcon className="h-5 w-5 text-[#FFF]" />
                                    </div>
                                    <div>
                                        <h4 className="font-semibold text-black">Phone Number</h4>
                                        <a href="tel:+442078560149" className="text-sm text-blue-600 font-medium">+44 2078560149</a>
                                    </div>
                                </div>
                            </div>

                            {/* Our Offices */}
                            <div className="mb-8">
                                <h3 className="text-xl font-bold mb-5">Our Offices</h3>
                                
                                <div className="space-y-4">
                                    {/* London Office */}
                                    <div className="flex items-center gap-4 bg-[#061E34] rounded-lg p-4">
                                        <div className="flex-shrink-0 w-10 h-10 bg-white/20 rounded-md flex items-center justify-center">
                                            <MapPinIcon className="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <h4 className="font-semibold">London Office (UK)</h4>
                                            <p className="text-xs text-gray-300">Headquarters</p>
                                            <a 
                                                href="https://maps.google.com/?q=85+Great+Portland+Street+London+W1W+7LT"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-sm text-blue-300 hover:text-blue-200"
                                            >
                                                85 Great Portland Street London W1W 7LT United Kingdom
                                            </a>
                                        </div>
                                    </div>

                                    {/* US Office */}
                                    <div className="flex items-center gap-4 bg-white/10 rounded-lg p-4">
                                        <div className="flex-shrink-0 w-10 h-10 bg-white/20 rounded-md flex items-center justify-center">
                                            <MapPinIcon className="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <h4 className="font-semibold">US Office</h4>
                                            <p className="text-xs text-gray-300">Regional Office</p>
                                            <a 
                                                href="https://maps.google.com/?q=1111B+S+Governors+Ave+Suite+57613+Dover+DE+19904"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-sm text-blue-300 hover:text-blue-200"
                                            >
                                                1111B S Governors Ave Suite 57613, Dover, DE 19904
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Response Time */}
                            <div className="bg-white/10 rounded-lg p-4 text-xs text-gray-300">
                                <p className="font-medium mb-1">Response Time</p>
                                <p>We strive to respond to all inquiries within 24 hours during business days. For urgent matters, please include “URGENT” in your subject line.</p>
                            </div>
                        </div>

                        {/* Right Column - Contact Form */}
                        <ContactForm setToast={setToast} />
                    </div>
                </div>
            </section>

            <CallToAction />

            <style jsx>{`
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                .animate-slide-in { animation: slideIn 0.3s ease-out forwards; }
            `}</style>
        </GuestLayout>
    );
}