import React, { useState, useRef } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { 
    CheckCircleIcon, 
    ExclamationCircleIcon, 
    PhotoIcon,
    XMarkIcon,
    MapPinIcon,
    EnvelopeIcon,
    PhoneIcon,
    GlobeAltIcon
} from '@heroicons/react/24/outline';

export default function ContactUs({ auth, title }) {
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
        attachments: [],
    });

    const fileInputRef = useRef(null);
    const [previewImages, setPreviewImages] = useState([]);

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
        const formData = new FormData();
        
        // Append all form data
        Object.keys(data).forEach(key => {
            if (key === 'attachments') {
                data.attachments.forEach(file => {
                    formData.append('attachments[]', file);
                });
            } else if (key === 'agree') {
                formData.append(key, data[key] ? '1' : '0');
            } else {
                formData.append(key, data[key]);
            }
        });

        post(route('contact.store'), {
            preserveScroll: true,
            data: formData,
            onSuccess: () => {
                reset();
                setPreviewImages([]);
            },
        });
    };

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setData(name, type === 'checkbox' ? checked : value);
    };

    const handleFileChange = (e) => {
        const files = Array.from(e.target.files);
        
        // Validate file types and size
        const validFiles = files.filter(file => {
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!validTypes.includes(file.type)) {
                alert(`File ${file.name} is not a supported format. Please upload JPEG, PNG, GIF, or PDF files.`);
                return false;
            }
            
            if (file.size > maxSize) {
                alert(`File ${file.name} is too large. Maximum size is 5MB.`);
                return false;
            }
            
            return true;
        });

        // Create previews for images
        const imagePreviews = validFiles
            .filter(file => file.type.startsWith('image/'))
            .map(file => ({
                url: URL.createObjectURL(file),
                name: file.name,
                type: file.type
            }));

        setPreviewImages(prev => [...prev, ...imagePreviews]);
        setData('attachments', [...data.attachments, ...validFiles]);
    };

    const removeAttachment = (index) => {
        const updatedAttachments = [...data.attachments];
        const updatedPreviews = [...previewImages];
        
        // Revoke object URL to prevent memory leaks
        if (updatedPreviews[index]) {
            URL.revokeObjectURL(updatedPreviews[index].url);
        }
        
        updatedAttachments.splice(index, 1);
        updatedPreviews.splice(index, 1);
        
        setData('attachments', updatedAttachments);
        setPreviewImages(updatedPreviews);
    };

    const openFilePicker = () => {
        fileInputRef.current.click();
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
            
            {/* Hero Section with Background Image */}
            <section 
                className="relative py-20 md:py-28 bg-gradient-to-r from-blue-900/90 to-indigo-900/90"
                aria-labelledby="page-title"
            >
                {/* Background Image */}
                <div 
                    className="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20"
                    style={{
                        backgroundImage: 'url("https://images.unsplash.com/photo-1556761175-b413da4baf72?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80")'
                    }}
                />
                
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto">
                        <h1 
                            id="page-title"
                            className="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight"
                        >
                            {title}
                        </h1>
                        <p className="text-xl text-blue-100 leading-relaxed">
                            Our dedicated team is committed to providing exceptional support. 
                            Reach out with your questions, feedback, or partnership inquiries.
                        </p>
                    </div>
                </div>
            </section>

            {/* Status Messages */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-4">
                {flash?.success && (
                    <div className="bg-green-50 border-l-4 border-green-400 p-4 rounded-r">
                        <div className="flex items-center">
                            <CheckCircleIcon className="h-5 w-5 text-green-400 mr-3" />
                            <p className="text-green-700">{flash?.success}</p>
                        </div>
                    </div>
                )}

                {flash?.error && (
                    <div className="bg-red-50 border-l-4 border-red-400 p-4 rounded-r">
                        <div className="flex items-center">
                            <ExclamationCircleIcon className="h-5 w-5 text-red-400 mr-3" />
                            <p className="text-red-700">{flash?.error}</p>
                        </div>
                    </div>
                )}
            </div>

            {/* Main Content Grid */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Contact Information & Map Column */}
                    <div className="lg:col-span-1 space-y-8">
                        {/* Contact Info Cards */}
                        <div className="bg-white rounded-2xl shadow-xl p-6 space-y-6">
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">Get in Touch</h3>
                            
                            <div className="space-y-4">
                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-50 p-3 rounded-lg">
                                        <MapPinIcon className="h-6 w-6 text-blue-600" />
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900">Visit Our Office</h4>
                                        <p className="text-gray-600 mt-1">
                                            Tyneside Innovation Centre<br />
                                            Willington Square<br />
                                            Wallsend, NE28 6HQ
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-50 p-3 rounded-lg">
                                        <EnvelopeIcon className="h-6 w-6 text-blue-600" />
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900">Email Us</h4>
                                        <a 
                                            href="mailto:info@igrcfp.org" 
                                            className="text-blue-600 hover:text-blue-700 font-medium"
                                        >
                                            info@igrcfp.org
                                        </a>
                                        <p className="text-sm text-gray-500 mt-1">
                                            Response time: 24 hours
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-50 p-3 rounded-lg">
                                        <PhoneIcon className="h-6 w-6 text-blue-600" />
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900">Call Us</h4>
                                        <a 
                                            href="tel:+2348000000000" 
                                            className="text-blue-600 hover:text-blue-700 font-medium"
                                        >
                                            +234 800 000 0000
                                        </a>
                                        <p className="text-sm text-gray-500 mt-1">
                                            Mon-Fri, 9AM-5PM WAT
                                        </p>
                                    </div>
                                </div>

                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-50 p-3 rounded-lg">
                                        <GlobeAltIcon className="h-6 w-6 text-blue-600" />
                                    </div>
                                    <div>
                                        <h4 className="font-medium text-gray-900">Website</h4>
                                        <a 
                                            href="https://www.igrcfp.org" 
                                            className="text-blue-600 hover:text-blue-700 font-medium"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            www.igrcfp.org
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Google Map */}
                        <div className="bg-white rounded-2xl shadow-xl p-6">
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">Find Us on Map</h3>
                            <div className="relative h-64 md:h-80 rounded-lg overflow-hidden">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2290.964897232018!2d-1.5345391232329413!3d54.99158397186481!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487e70b1c4a97a59%3A0xf9c31faf6bcd4012!2sTyneside%20Innovation%20Centre%2C%20Willington%20Square%2C%20Wallsend%20NE28%206HQ%2C%20UK!5e0!3m2!1sen!2sng!4v1700000000000!5m2!1sen!2sng"
                                    width="100%"
                                    height="100%"
                                    style={{ border: 0 }}
                                    allowFullScreen=""
                                    loading="lazy"
                                    referrerPolicy="no-referrer-when-downgrade"
                                    title="IGRCFP Location Map"
                                    className="absolute inset-0"
                                />
                            </div>
                            <div className="mt-4 text-center">
                                <a
                                    href="https://goo.gl/maps/example"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium"
                                >
                                    <MapPinIcon className="h-5 w-5 mr-2" />
                                    Open in Google Maps
                                </a>
                            </div>
                        </div>
                    </div>

                    {/* Contact Form Column */}
                    <div className="lg:col-span-2">
                        <div className="bg-white rounded-2xl shadow-xl p-8">
                            <div className="text-center mb-8">
                                <h2 className="text-3xl font-bold text-gray-900 mb-2">
                                    Send Us a Message
                                </h2>
                                <p className="text-gray-600">
                                    Fill out the form below and we'll get back to you as soon as possible.
                                </p>
                            </div>
                            
                            <form onSubmit={handleSubmit} className="space-y-6" noValidate>
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

                                {/* Contact Info */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <InputField
                                        label="Email Address"
                                        id="email"
                                        name="email"
                                        type="email"
                                        placeholder="your.email@example.com"
                                        autoComplete="email"
                                    />

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Phone Number <span className="text-red-500">*</span>
                                        </label>
                                        <div className="flex gap-3">
                                            <div className="w-32">
                                                <select
                                                    name="countryCode"
                                                    value={data.countryCode}
                                                    onChange={handleChange}
                                                    disabled={processing}
                                                    className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                >
                                                    {countryCodes.map(country => (
                                                        <option key={country.code} value={country.code}>
                                                            {country.code}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div className="flex-1">
                                                <input
                                                    type="tel"
                                                    name="phone"
                                                    value={data.phone}
                                                    onChange={handleChange}
                                                    disabled={processing}
                                                    placeholder="Phone number"
                                                    className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 ${
                                                        errors.phone ? 'border-red-300' : 'border-gray-300'
                                                    }`}
                                                />
                                                {errors.phone && (
                                                    <p className="mt-1 text-sm text-red-600">{errors.phone}</p>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Message */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Your Message <span className="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        name="message"
                                        value={data.message}
                                        onChange={handleChange}
                                        disabled={processing}
                                        rows="4"
                                        placeholder="Tell us how we can help you..."
                                        className={`w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none ${
                                            errors.message ? 'border-red-300' : 'border-gray-300'
                                        }`}
                                    />
                                    {errors.message && (
                                        <p className="mt-1 text-sm text-red-600">{errors.message}</p>
                                    )}
                                    <div className="flex justify-between items-center mt-1">
                                        <span className="text-sm text-gray-500">
                                            {data.message.length}/2000 characters
                                        </span>
                                    </div>
                                </div>

                                {/* File Upload */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Attachments (Optional)
                                    </label>
                                    <div className="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                        <div className="space-y-1 text-center">
                                            <PhotoIcon className="mx-auto h-12 w-12 text-gray-400" />
                                            <div className="flex text-sm text-gray-600">
                                                <label className="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                    <span>Upload files</span>
                                                    <input
                                                        ref={fileInputRef}
                                                        type="file"
                                                        multiple
                                                        accept=".jpg,.jpeg,.png,.gif,.pdf"
                                                        onChange={handleFileChange}
                                                        className="sr-only"
                                                    />
                                                </label>
                                                <p className="pl-1">or drag and drop</p>
                                            </div>
                                            <p className="text-xs text-gray-500">
                                                PNG, JPG, GIF, PDF up to 5MB each
                                            </p>
                                            <p className="text-xs text-gray-500">
                                                Maximum 5 files
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {/* File Previews */}
                                    {previewImages.length > 0 && (
                                        <div className="mt-4">
                                            <h4 className="text-sm font-medium text-gray-700 mb-2">
                                                Selected Files ({data.attachments.length})
                                            </h4>
                                            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                                {previewImages.map((preview, index) => (
                                                    <div key={index} className="relative group">
                                                        <div className="relative h-24 w-full rounded-lg overflow-hidden bg-gray-100">
                                                            {preview.type.startsWith('image/') ? (
                                                                <img
                                                                    src={preview.url}
                                                                    alt={preview.name}
                                                                    className="h-full w-full object-cover"
                                                                />
                                                            ) : (
                                                                <div className="flex items-center justify-center h-full">
                                                                    <div className="text-center">
                                                                        <div className="text-xs text-gray-500 mb-1">PDF</div>
                                                                        <div className="text-xs text-gray-700 truncate px-2">
                                                                            {preview.name}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            )}
                                                            <button
                                                                type="button"
                                                                onClick={() => removeAttachment(index)}
                                                                className="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                            >
                                                                <XMarkIcon className="h-4 w-4" />
                                                            </button>
                                                        </div>
                                                        <p className="text-xs text-gray-500 truncate mt-1">
                                                            {preview.name}
                                                        </p>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    )}
                                    
                                    {errors.attachments && (
                                        <p className="mt-2 text-sm text-red-600">{errors.attachments}</p>
                                    )}
                                </div>

                                {/* Privacy Policy */}
                                <div className="flex items-start">
                                    <div className="flex items-center h-5">
                                        <input
                                            id="agree"
                                            name="agree"
                                            type="checkbox"
                                            checked={data.agree}
                                            onChange={handleChange}
                                            disabled={processing}
                                            className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        />
                                    </div>
                                    <div className="ml-3 text-sm">
                                        <label htmlFor="agree" className="font-medium text-gray-700">
                                            I agree to the{' '}
                                            <a href="/privacy-policy" className="text-blue-600 hover:text-blue-500">
                                                privacy policy
                                            </a>
                                        </label>
                                        <p className="text-gray-500">
                                            By submitting this form, you consent to our privacy policy.
                                        </p>
                                        {errors.agree && (
                                            <p className="mt-1 text-sm text-red-600">{errors.agree}</p>
                                        )}
                                    </div>
                                </div>

                                {/* Submit Button */}
                                <div>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className={`w-full py-3 px-4 rounded-lg font-medium transition-colors ${
                                            processing
                                                ? 'bg-gray-400 cursor-not-allowed'
                                                : 'bg-blue-600 hover:bg-blue-700 text-white'
                                        }`}
                                    >
                                        {processing ? (
                                            <span className="flex items-center justify-center">
                                                <svg className="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                                </svg>
                                                Sending...
                                            </span>
                                        ) : (
                                            'Send Message'
                                        )}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {/* Footer Section */}
            <footer className="bg-gray-900 text-white py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h3 className="text-xl font-bold mb-4">About IGRCFP</h3>
                            <p className="text-gray-300">
                                We are dedicated to fostering innovation and providing exceptional support 
                                to our community. Your success is our priority.
                            </p>
                        </div>
                        <div>
                            <h3 className="text-xl font-bold mb-4">Quick Links</h3>
                            <ul className="space-y-2">
                                <li><a href="/about" className="text-gray-300 hover:text-white">About Us</a></li>
                                <li><a href="/services" className="text-gray-300 hover:text-white">Services</a></li>
                                <li><a href="/contact" className="text-gray-300 hover:text-white">Contact</a></li>
                                <li><a href="/privacy-policy" className="text-gray-300 hover:text-white">Privacy Policy</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 className="text-xl font-bold mb-4">Connect With Us</h3>
                            <div className="flex space-x-4">
                                <a href="#" className="text-gray-300 hover:text-white">
                                    <span className="sr-only">Facebook</span>
                                    <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="#" className="text-gray-300 hover:text-white">
                                    <span className="sr-only">Twitter</span>
                                    <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="#" className="text-gray-300 hover:text-white">
                                    <span className="sr-only">LinkedIn</span>
                                    <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div className="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                        <p>&copy; {new Date().getFullYear()} IGRCFP. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </GuestLayout>
    );
}