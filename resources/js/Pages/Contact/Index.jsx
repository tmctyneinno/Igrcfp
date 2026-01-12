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
        country_code: 'NG',
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
                
                // Show toast notification for success
                if (page.props.flash?.success) {
                    setToast({
                        message: page.props.flash.success,
                        type: 'success'
                    });
                }
                
                reset();
                
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