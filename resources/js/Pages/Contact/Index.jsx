import React, { useState } from 'react';
import { Head, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { CheckCircleIcon, ExclamationCircleIcon } from '@heroicons/react/24/outline';

export default function Index({ auth, title }) {
    const pageProps = usePage().props;
    const flash = pageProps?.flash || {};
    
    const [formData, setFormData] = useState({
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        countryCode: 'NG',
        message: '',
        agree: false,
    });
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [errors, setErrors] = useState({});
    const [submitStatus, setSubmitStatus] = useState(null);

    const countryCodes = [
        { code: 'NG', name: 'Nigeria (+234)' },
        { code: 'US', name: 'USA (+1)' },
        { code: 'GB', name: 'UK (+44)' },
        { code: 'GH', name: 'Ghana (+233)' },
        { code: 'KE', name: 'Kenya (+254)' },
        { code: 'ZA', name: 'South Africa (+27)' },
    ];

    const validateForm = () => {
        const newErrors = {};
        
        if (!formData.firstName.trim()) {
            newErrors.firstName = 'First name is required';
        }
        
        if (!formData.lastName.trim()) {
            newErrors.lastName = 'Last name is required';
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!formData.email.trim()) {
            newErrors.email = 'Email is required';
        } else if (!emailRegex.test(formData.email)) {
            newErrors.email = 'Please enter a valid email address';
        }
        
        const phoneRegex = /^[0-9+\-\s()]{10,}$/;
        if (!formData.phone.trim()) {
            newErrors.phone = 'Phone number is required';
        } else if (!phoneRegex.test(formData.phone.replace(/\s/g, ''))) {
            newErrors.phone = 'Please enter a valid phone number';
        }
        
        if (!formData.message.trim()) {
            newErrors.message = 'Message is required';
        } else if (formData.message.trim().length < 10) {
            newErrors.message = 'Message must be at least 10 characters';
        }
        
        if (!formData.agree) {
            newErrors.agree = 'You must agree to the privacy policy';
        }
        
        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleChange = (e) => {
        const { name, value, type } = e.target;
        
        if (type === 'checkbox') {
            setFormData(prev => ({
                ...prev,
                [name]: e.target.checked,
            }));
        } else {
            setFormData(prev => ({
                ...prev,
                [name]: value,
            }));
            
            // Clear error for this field when user starts typing
            if (errors[name]) {
                setErrors(prev => ({
                    ...prev,
                    [name]: '',
                }));
            }
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        setIsSubmitting(true);
        setSubmitStatus(null);
        
        try {
            // In a real application, this would be an Inertia POST request
            // await Inertia.post('/contact', formData);
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            setSubmitStatus('success');
            setFormData({
                firstName: '',
                lastName: '',
                email: '',
                phone: '',
                countryCode: 'NG',
                message: '',
                agree: false,
            });
            
            // Show success message for 5 seconds
            setTimeout(() => setSubmitStatus(null), 5000);
        } catch (error) {
            setSubmitStatus('error');
            console.error('Form submission error:', error);
        } finally {
            setIsSubmitting(false);
        }
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
                className={`w-full px-4 py-3 border rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ${
                    errors[props.name] 
                        ? 'border-red-300 bg-red-50' 
                        : 'border-gray-300 hover:border-gray-400'
                }`}
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

            {submitStatus === 'success' && (
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                    <div 
                        className="bg-green-50 border-l-4 border-green-400 p-4 rounded-r"
                        role="alert"
                        aria-live="polite"
                    >
                        <div className="flex items-center">
                            <CheckCircleIcon className="h-5 w-5 text-green-400 mr-3" />
                            <div>
                                <p className="text-green-800 font-medium">
                                    Message Sent Successfully!
                                </p>
                                <p className="text-green-700 mt-1">
                                    Thank you for contacting us. We'll respond within 24-48 hours.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {submitStatus === 'error' && (
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                    <div 
                        className="bg-red-50 border-l-4 border-red-400 p-4 rounded-r"
                        role="alert"
                        aria-live="assertive"
                    >
                        <div className="flex items-center">
                            <ExclamationCircleIcon className="h-5 w-5 text-red-400 mr-3" />
                            <div>
                                <p className="text-red-800 font-medium">
                                    Submission Failed
                                </p>
                                <p className="text-red-700 mt-1">
                                    Please try again or contact us directly at info@igrcfp.org
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Rest of your component remains the same */}
            {/* ... */}
        </GuestLayout>
    );
}