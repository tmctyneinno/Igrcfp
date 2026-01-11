import { useState } from 'react';
import { Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { format, parseISO } from 'date-fns';

export default function EventRegister({ auth, event }) {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        company: '',
        position: '',
        additional_attendees: 0,
        dietary_requirements: '',
        special_requirements: '',
        hear_about_event: '',
        agree_to_terms: false,
    });

    const [isSubmitting, setIsSubmitting] = useState(false);
    const [errors, setErrors] = useState({});

    // Helper function to get image URL
    const getImageUrl = (imageUrl) => {
        if (!imageUrl) return '/images/default-event.jpg';
        
        if (imageUrl.startsWith('storage/')) {
            return `/${imageUrl}`;
        }
        if (imageUrl.startsWith('http')) {
            return imageUrl;
        }
        if (imageUrl.startsWith('/')) {
            return imageUrl;
        }
        return `/storage/${imageUrl}`;
    };

    // Format date
    const formatEventDate = (dateString) => {
        if (!dateString) return 'Date TBA';
        
        try {
            return format(parseISO(dateString), 'MMMM dd, yyyy');
        } catch (error) {
            console.error('Error formatting date:', error);
            return 'Date TBA';
        }
    };

    // Format time
    const formatTime = (time) => {
        if (!time) return '';
        if (time.includes(':')) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }
        return time;
    };

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value
        }));
        
        // Clear error when user starts typing
        if (errors[name]) {
            setErrors(prev => ({ ...prev, [name]: '' }));
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        // Basic validation
        const newErrors = {};
        if (!formData.name.trim()) newErrors.name = 'Name is required';
        if (!formData.email.trim()) newErrors.email = 'Email is required';
        if (!formData.phone.trim()) newErrors.phone = 'Phone number is required';
        if (!formData.agree_to_terms) newErrors.agree_to_terms = 'You must agree to the terms and conditions';
        
        if (Object.keys(newErrors).length > 0) {
            setErrors(newErrors);
            return;
        }

        setIsSubmitting(true);

        try {
            // Submit registration
            await axios.post(`/events/${event.slug}/register`, {
                ...formData,
                event_id: event.id,
            });

            // Show success message (you could redirect to a thank you page instead)
            alert('Registration successful! You will receive a confirmation email shortly.');
            window.history.back(); // Go back to event page
        } catch (error) {
            if (error.response?.data?.errors) {
                setErrors(error.response.data.errors);
            } else {
                alert('An error occurred. Please try again.');
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleCancel = () => {
        window.history.back();
    };

    const hearAboutOptions = [
        'Email Newsletter',
        'Social Media',
        'Website',
        'Colleague/Friend',
        'Search Engine',
        'Previous Event',
        'Other'
    ];

    return (
        <GuestLayout auth={auth}>
            <Head title={`Register - ${event.title}`} />

            {/* Modal Overlay */}
            <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div className="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
                    {/* Modal Header */}
                    <div className="sticky top-0 bg-white border-b border-gray-200 p-6 z-10">
                        <div className="flex justify-between items-center">
                            <div>
                                <h2 className="text-2xl font-bold text-gray-900">Register for Event</h2>
                                <p className="text-gray-600 mt-1">{event.title}</p>
                            </div>
                            <button
                                onClick={handleCancel}
                                className="text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {/* Modal Content - Scrollable */}
                    <div className="overflow-y-auto max-h-[calc(90vh-140px)]">
                        <div className="p-6">
                            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                {/* Left Column - Event Info */}
                                <div className="lg:col-span-1">
                                    <div className="bg-gray-50 rounded-xl p-6 sticky top-6">
                                        {/* Event Image */}
                                        <div className="rounded-lg overflow-hidden mb-6">
                                            <img
                                                src={getImageUrl(event.image)}
                                                alt={event.title}
                                                className="w-full h-48 object-cover"
                                                onError={(e) => {
                                                    e.target.src = '/images/default-event.jpg';
                                                }}
                                            />
                                        </div>

                                        {/* Event Details */}
                                        <div className="space-y-4">
                                            <div>
                                                <p className="text-sm text-gray-500 mb-1">Date</p>
                                                <p className="font-medium text-gray-900">
                                                    {formatEventDate(event.start_date)}
                                                </p>
                                            </div>

                                            {event.start_time && event.end_time && (
                                                <div>
                                                    <p className="text-sm text-gray-500 mb-1">Time</p>
                                                    <p className="font-medium text-gray-900">
                                                        {formatTime(event.start_time)} - {formatTime(event.end_time)}
                                                    </p>
                                                </div>
                                            )}

                                            <div>
                                                <p className="text-sm text-gray-500 mb-1">Venue</p>
                                                <p className="font-medium text-gray-900">
                                                    {event.venue || event.location}
                                                </p>
                                                {event.address && (
                                                    <p className="text-sm text-gray-600 mt-1">{event.address}</p>
                                                )}
                                            </div>

                                            {event.capacity && event.available_seats !== undefined && (
                                                <div className="pt-4 border-t border-gray-200">
                                                    <div className="flex justify-between items-center mb-2">
                                                        <p className="text-sm text-gray-500">Available Seats</p>
                                                        <p className="font-medium text-gray-900">
                                                            {event.available_seats} / {event.capacity}
                                                        </p>
                                                    </div>
                                                    <div className="w-full bg-gray-200 rounded-full h-2">
                                                        <div 
                                                            className="bg-blue-600 h-2 rounded-full" 
                                                            style={{ 
                                                                width: `${(event.available_seats / event.capacity) * 100}%` 
                                                            }}
                                                        ></div>
                                                    </div>
                                                </div>
                                            )}

                                            {/* Status Badge */}
                                            <div className={`inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold ${
                                                event.registration_status === 'sold_out' ? 'bg-red-100 text-red-800' :
                                                event.registration_status === 'few_seats' ? 'bg-amber-100 text-amber-800' :
                                                'bg-emerald-100 text-emerald-800'
                                            }`}>
                                                {event.registration_status === 'sold_out' ? 'Sold Out' : 
                                                 event.registration_status === 'few_seats' ? 'Few Seats Left' : 
                                                 'Registration Open'}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Right Column - Registration Form */}
                                <div className="lg:col-span-2">
                                    {event.registration_status === 'sold_out' ? (
                                        <div className="text-center py-12">
                                            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                                                <svg className="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                            <h3 className="text-xl font-semibold text-gray-700 mb-2">Event Sold Out</h3>
                                            <p className="text-gray-500 mb-6">
                                                This event has reached full capacity. Please check back for future events.
                                            </p>
                                            <button
                                                onClick={handleCancel}
                                                className="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-all duration-300"
                                            >
                                                Back to Event
                                            </button>
                                        </div>
                                    ) : (
                                        <form onSubmit={handleSubmit} className="space-y-6">
                                            {/* Personal Information */}
                                            <div className="space-y-6">
                                                <h3 className="text-lg font-semibold text-gray-900 border-b pb-2">
                                                    Personal Information
                                                </h3>
                                                
                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                                            Full Name *
                                                        </label>
                                                        <input
                                                            type="text"
                                                            name="name"
                                                            value={formData.name}
                                                            onChange={handleChange}
                                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                                                errors.name ? 'border-red-500' : 'border-gray-300'
                                                            }`}
                                                            placeholder="John Doe"
                                                        />
                                                        {errors.name && (
                                                            <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                                                        )}
                                                    </div>

                                                    <div>
                                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                                            Email Address *
                                                        </label>
                                                        <input
                                                            type="email"
                                                            name="email"
                                                            value={formData.email}
                                                            onChange={handleChange}
                                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                                                errors.email ? 'border-red-500' : 'border-gray-300'
                                                            }`}
                                                            placeholder="john@example.com"
                                                        />
                                                        {errors.email && (
                                                            <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                                        )}
                                                    </div>
                                                </div>

                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                                            Phone Number *
                                                        </label>
                                                        <input
                                                            type="tel"
                                                            name="phone"
                                                            value={formData.phone}
                                                            onChange={handleChange}
                                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                                                errors.phone ? 'border-red-500' : 'border-gray-300'
                                                            }`}
                                                            placeholder="+1 (555) 123-4567"
                                                        />
                                                        {errors.phone && (
                                                            <p className="mt-1 text-sm text-red-600">{errors.phone}</p>
                                                        )}
                                                    </div>

                                                    <div>
                                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                                            Company/Organization
                                                        </label>
                                                        <input
                                                            type="text"
                                                            name="company"
                                                            value={formData.company}
                                                            onChange={handleChange}
                                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                            placeholder="Your Company"
                                                        />
                                                    </div>
                                                </div>

                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                                        Position/Title
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="position"
                                                        value={formData.position}
                                                        onChange={handleChange}
                                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        placeholder="Your Position"
                                                    />
                                                </div>
                                            </div>

                                            {/* Additional Information */}
                                            <div className="space-y-6">
                                                <h3 className="text-lg font-semibold text-gray-900 border-b pb-2">
                                                    Additional Information
                                                </h3>

                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                                        How did you hear about this event?
                                                    </label>
                                                    <select
                                                        name="hear_about_event"
                                                        value={formData.hear_about_event}
                                                        onChange={handleChange}
                                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                    >
                                                        <option value="">Select an option</option>
                                                        {hearAboutOptions.map(option => (
                                                            <option key={option} value={option}>{option}</option>
                                                        ))}
                                                    </select>
                                                </div>

                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                                            Additional Attendees
                                                        </label>
                                                        <div className="flex items-center">
                                                            <button
                                                                type="button"
                                                                onClick={() => setFormData(prev => ({
                                                                    ...prev,
                                                                    additional_attendees: Math.max(0, prev.additional_attendees - 1)
                                                                }))}
                                                                className="px-4 py-2 border border-gray-300 rounded-l-lg hover:bg-gray-50"
                                                            >
                                                                -
                                                            </button>
                                                            <input
                                                                type="number"
                                                                name="additional_attendees"
                                                                value={formData.additional_attendees}
                                                                onChange={handleChange}
                                                                min="0"
                                                                max="5"
                                                                className="w-20 px-4 py-2 border-t border-b border-gray-300 text-center"
                                                            />
                                                            <button
                                                                type="button"
                                                                onClick={() => setFormData(prev => ({
                                                                    ...prev,
                                                                    additional_attendees: Math.min(5, prev.additional_attendees + 1)
                                                                }))}
                                                                className="px-4 py-2 border border-gray-300 rounded-r-lg hover:bg-gray-50"
                                                            >
                                                                +
                                                            </button>
                                                        </div>
                                                        <p className="text-xs text-gray-500 mt-2">
                                            You can bring up to 5 additional attendees
                                        </p>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                                        Dietary Requirements
                                                    </label>
                                                    <textarea
                                                        name="dietary_requirements"
                                                        value={formData.dietary_requirements}
                                                        onChange={handleChange}
                                                        rows="2"
                                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        placeholder="Any dietary restrictions or preferences..."
                                                    />
                                                </div>

                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                                        Special Requirements
                                                    </label>
                                                    <textarea
                                                        name="special_requirements"
                                                        value={formData.special_requirements}
                                                        onChange={handleChange}
                                                        rows="2"
                                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        placeholder="Any accessibility needs or other requirements..."
                                                    />
                                                </div>
                                            </div>

                                            {/* Terms and Conditions */}
                                            <div className="space-y-4">
                                                <div className="flex items-start">
                                                    <input
                                                        type="checkbox"
                                                        id="agree_to_terms"
                                                        name="agree_to_terms"
                                                        checked={formData.agree_to_terms}
                                                        onChange={handleChange}
                                                        className="mt-1 mr-3"
                                                    />
                                                    <div>
                                                        <label htmlFor="agree_to_terms" className="text-sm text-gray-700">
                                                            I agree to the Terms and Conditions and Privacy Policy *
                                                        </label>
                                                        {errors.agree_to_terms && (
                                                            <p className="mt-1 text-sm text-red-600">{errors.agree_to_terms}</p>
                                                        )}
                                                        <p className="text-xs text-gray-500 mt-1">
                                                            By registering, you agree to receive event-related communications.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Form Actions */}
                                            <div className="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                                                <button
                                                    type="button"
                                                    onClick={handleCancel}
                                                    className="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-300"
                                                    disabled={isSubmitting}
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    type="submit"
                                                    disabled={isSubmitting || event.registration_status === 'sold_out'}
                                                    className={`flex-1 px-6 py-3 font-medium rounded-lg transition-all duration-300 ${
                                                        isSubmitting || event.registration_status === 'sold_out'
                                                            ? 'bg-gray-400 cursor-not-allowed'
                                                            : 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl'
                                                    }`}
                                                >
                                                    {isSubmitting ? (
                                                        <span className="flex items-center justify-center">
                                                            <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            Processing...
                                                        </span>
                                                    ) : (
                                                        'Complete Registration'
                                                    )}
                                                </button>
                                            </div>
                                        </form>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}