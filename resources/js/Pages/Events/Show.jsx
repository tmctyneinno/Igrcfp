import React from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { format, parseISO } from 'date-fns';

export default function EventShow({ auth, event, relatedEvents }) {
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

    // Format event date range
    const formatEventDateRange = () => {
        if (!event.start_date) return 'Date TBA';
        
        try {
            const startDate = parseISO(event.start_date);
            const endDate = event.end_date ? parseISO(event.end_date) : null;
            
            if (!endDate || format(startDate, 'yyyy-MM-dd') === format(endDate, 'yyyy-MM-dd')) {
                return format(startDate, 'MMMM dd, yyyy');
            }
            
            if (startDate.getFullYear() === endDate.getFullYear()) {
                if (startDate.getMonth() === endDate.getMonth()) {
                    return `${format(startDate, 'MMMM dd')} - ${format(endDate, 'dd, yyyy')}`;
                }
                return `${format(startDate, 'MMMM dd')} - ${format(endDate, 'MMMM dd, yyyy')}`;
            }
            
            return `${format(startDate, 'MMMM dd, yyyy')} - ${format(endDate, 'MMMM dd, yyyy')}`;
        } catch (error) {
            console.error('Error formatting date range:', error);
            return 'Date TBA';
        }
    };

    // Check if event is upcoming/ongoing/past
    const getEventStatus = () => {
        if (!event.start_date) return 'unknown';
        
        try {
            const startDate = parseISO(event.start_date);
            const endDate = event.end_date ? parseISO(event.end_date) : startDate;
            const now = new Date();
            
            if (now < startDate) return 'upcoming';
            if (now >= startDate && now <= endDate) return 'ongoing';
            return 'past';
        } catch (error) {
            return 'unknown';
        }
    };

    // Format time
    const formatTime = (time) => {
        if (!time) return '';
        // Convert 24h to 12h format if needed
        if (time.includes(':')) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }
        return time;
    };

    return (
        <GuestLayout auth={auth}>
            <Head title={event.title} />
            <Head>
                <meta name="description" content={event.meta_description || event.short_description} />
                <meta name="keywords" content={event.meta_keywords} />
            </Head>

            {/* Hero Section */}
            <section className="relative bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-16 md:py-24">
                <div className="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]" />
                <div className="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium mb-6">
                            Event Details
                        </div>
                        <h1 className="text-3xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 tracking-tight">
                            {event.title}
                        </h1>
                        
                        {/* Event Status Badge */}
                        <div className="flex flex-wrap justify-center gap-3 mb-8">
                            {event.is_featured && (
                                <span className="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full text-sm font-semibold">
                                    Featured Event
                                </span>
                            )}
                            <span className={`inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold ${
                                getEventStatus() === 'upcoming' ? 'bg-green-100 text-green-800' :
                                getEventStatus() === 'ongoing' ? 'bg-blue-100 text-blue-800' :
                                'bg-gray-100 text-gray-800'
                            }`}>
                                {getEventStatus() === 'upcoming' ? 'Upcoming' : 
                                 getEventStatus() === 'ongoing' ? 'Ongoing' : 'Past Event'}
                            </span>
                            <span className={`inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold ${
                                event.registration_status === 'sold_out' ? 'bg-red-100 text-red-800' :
                                event.registration_status === 'few_seats' ? 'bg-amber-100 text-amber-800' :
                                'bg-emerald-100 text-emerald-800'
                            }`}>
                                {event.registration_status === 'sold_out' ? 'Sold Out' : 
                                 event.registration_status === 'few_seats' ? 'Few Seats Left' : 
                                 'Registration Open'}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            {/* Main Content */}
            <section className="py-16 bg-white">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        {/* Left Column - Event Details */}
                        <div className="lg:col-span-2">
                            {/* Event Image */}
                            <div className="relative rounded-2xl overflow-hidden shadow-xl mb-8">
                                <img
                                    src={getImageUrl(event.image)}
                                    alt={event.title}
                                    className="w-full h-auto max-h-[500px] object-cover"
                                    onError={(e) => {
                                        e.target.src = '/images/default-event.jpg';
                                    }}
                                />
                                {event.is_featured && (
                                    <div className="absolute top-4 left-4">
                                        <span className="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                            Featured Event
                                        </span>
                                    </div>
                                )}
                            </div>

                            {/* Event Description */}
                            {/* <div className="prose prose-lg max-w-none mb-12">
                                <div dangerouslySetInnerHTML={{ __html: event.description }} />
                            </div> */} 
                            <div className="prose prose-lg max-w-none mb-12 prose-headings:font-bold prose-strong:font-bold prose-em:italic">
                                <div dangerouslySetInnerHTML={{ __html: event.description }} />
                            </div>
                            {/* Event Highlights */}
                            <div className="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 mb-12">
                                <h3 className="text-2xl font-bold text-gray-900 mb-6">Event Highlights</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="flex items-start">
                                        <svg className="w-6 h-6 text-blue-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <h4 className="font-semibold text-gray-900 mb-1">Date & Time</h4>
                                            <p className="text-gray-600">{formatEventDateRange()}</p>
                                            {event.start_time && event.end_time && (
                                                <p className="text-gray-600 mt-1">
                                                    {formatTime(event.start_time)} - {formatTime(event.end_time)}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                    
                                    <div className="flex items-start">
                                        <svg className="w-6 h-6 text-blue-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <div>
                                            <h4 className="font-semibold text-gray-900 mb-1">Venue</h4>
                                            <p className="text-gray-600">{event.venue || event.location}</p>
                                            {event.address && (
                                                <p className="text-gray-600 text-sm mt-1">{event.address}</p>
                                            )}
                                        </div>
                                    </div>
                                    
                                    {event.capacity && (
                                        <div className="flex items-start">
                                            <svg className="w-6 h-6 text-blue-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <div>
                                                <h4 className="font-semibold text-gray-900 mb-1">Capacity</h4>
                                                <p className="text-gray-600">
                                                    {event.available_seats !== undefined ? (
                                                        <span>{event.available_seats} of {event.capacity} seats available</span>
                                                    ) : (
                                                        <span>{event.capacity} seats</span>
                                                    )}
                                                </p>
                                            </div>
                                        </div>
                                    )}
                                    
                                    {event.user && (
                                        <div className="flex items-start">
                                            <svg className="w-6 h-6 text-blue-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <div>
                                                <h4 className="font-semibold text-gray-900 mb-1">Organizer</h4>
                                                {/* <p className="text-gray-600">{event.user.name}</p> */}
                                                <p className="text-gray-600">Morgans</p>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Right Column - Registration & Info */}
                        <div className="lg:col-span-1">
                            <div className="sticky top-24">
                                {/* Registration Card */}
                                <div className="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-8">
                                    <h3 className="text-2xl font-bold text-gray-900 mb-6">Register Now</h3>
                                    
                                    {/* Status */}
                                    <div className="mb-6">
                                        <div className={`inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold mb-4 ${
                                            event.registration_status === 'sold_out' ? 'bg-red-100 text-red-800' :
                                            event.registration_status === 'few_seats' ? 'bg-amber-100 text-amber-800' :
                                            'bg-emerald-100 text-emerald-800'
                                        }`}>
                                            {event.registration_status === 'sold_out' ? 'Sold Out' : 
                                             event.registration_status === 'few_seats' ? 'Few Seats Left' : 
                                             'Available Seats'}
                                        </div>
                                        
                                        {event.capacity && event.available_seats !== undefined && (
                                            <div className="mb-4">
                                                <div className="flex justify-between text-sm text-gray-600 mb-2">
                                                    <span>Available Seats</span>
                                                    <span>{event.available_seats} / {event.capacity}</span>
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
                                    </div>
                                    
                                    {/* Action Button */}
                                    {/* Action Button */}
{event.registration_status === 'sold_out' ? (
    <button
        disabled
        className="w-full py-3 px-6 bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed"
    >
        Event Sold Out
    </button>
) : event.meeting_link ? (
    // Show Meeting Link if available
    <a
        href={event.meeting_link}
        target="_blank"
        rel="noopener noreferrer"
        className="block w-full text-center py-3 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all duration-300 shadow-lg hover:shadow-xl"
    >
        <span className="flex items-center justify-center">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            Join Meeting
        </span>
    </a>
) : (
    // Show Registration Link
    <Link
        href={route('events.register', event.link)}
        className="block w-full text-center py-3 px-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl"
        preserveState
        preserveScroll
    >
        Register Now
    </Link>
)}
                                    
                                    <p className="text-center text-sm text-gray-500 mt-4">
                                        Limited seats available. Register early to secure your spot.
                                    </p>
                                </div>

                                {/* Quick Info Card */}
                                <div className="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                                    <h4 className="font-semibold text-gray-900 mb-4">Quick Information</h4>
                                    <div className="space-y-4">
                                        <div>
                                            <p className="text-sm text-gray-500 mb-1">Date</p>
                                            <p className="font-medium text-gray-900">{formatEventDateRange()}</p>
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
                                            <p className="font-medium text-gray-900">{event.venue || event.location}</p>
                                        </div>
                                        
                                        {event.user && (
                                            <div>
                                                <p className="text-sm text-gray-500 mb-1">Organizer</p>
                                                {/* <p className="font-medium text-gray-900">{event.user.name}</p> */}
                                                  <p className="font-medium text-gray-900">Morgans</p>
                                            </div>
                                        )}
                                        
                                        <div className="pt-4 border-t border-gray-200">
                                            <p className="text-sm text-gray-500 mb-2">Share this event</p>
                                            <div className="flex space-x-3">
                                                <button className="text-gray-400 hover:text-blue-600 transition-colors">
                                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                                    </svg>
                                                </button>
                                                <button className="text-gray-400 hover:text-blue-800 transition-colors">
                                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                                                    </svg>
                                                </button>
                                                <button className="text-gray-400 hover:text-red-600 transition-colors">
                                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                    </svg>
                                                </button>
                                                <button className="text-gray-400 hover:text-blue-700 transition-colors">
                                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Related Events */}
            {relatedEvents && relatedEvents.length > 0 && (
                <section className="py-16 bg-gray-50">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-12">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">Related Events</h2>
                            <p className="text-gray-600">You might also be interested in these events</p>
                        </div>
                        
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {relatedEvents.map((relatedEvent) => (
                                <div key={relatedEvent.id} className="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300">
                                    <div className="relative h-40">
                                        <img
                                            src={getImageUrl(relatedEvent.image)}
                                            alt={relatedEvent.title}
                                            className="w-full h-full object-cover"
                                            onError={(e) => {
                                                e.target.src = '/images/default-event.jpg';
                                            }}
                                        />
                                        {relatedEvent.is_featured && (
                                            <div className="absolute top-3 left-3">
                                                <span className="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                                    Featured
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                    <div className="p-6">
                                        <div className="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs mb-3">
                                            <span>{formatEventDate(relatedEvent.start_date)}</span>
                                        </div>
                                        <h3 className="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                            <Link href={route('events.show', relatedEvent.slug)} className="hover:text-blue-700">
                                                {relatedEvent.title}
                                            </Link>
                                        </h3>
                                        <div className="flex items-center text-gray-600 text-sm mb-4">
                                            <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span className="line-clamp-1">{relatedEvent.venue || relatedEvent.location}</span>
                                        </div>
                                        <Link
                                            href={route('events.show', relatedEvent.slug)}
                                            className="inline-flex items-center justify-center w-full py-2 bg-gradient-to-r from-blue-900 to-indigo-900 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300"
                                        >
                                            View Details
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* CTA Section */}
            <section className="py-16 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Need More Information?</h2>
                    <p className="text-xl text-gray-600 mb-8">
                        Have questions about this event? Contact our events team for assistance.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            href={route('events.index')}
                            className="inline-flex items-center justify-center px-8 py-3 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-all duration-300"
                        >
                            View All Events
                        </Link>
                        <Link
                            href={route('contact')}
                            className="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl"
                        >
                            Contact Us
                        </Link>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}