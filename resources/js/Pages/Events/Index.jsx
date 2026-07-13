import React, { useState, useCallback } from "react";
import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { format, parseISO, isBefore, parseISO as parseDate } from 'date-fns';
import HeroSection from '@/Layouts/HeroSection';
import CallToAction from "@/Pages/components/CallToAction";

export default function Events({ auth, title, description, events }) {
    // Handle paginated events data
    const eventsData = events?.data || events || [];
    
    // Get featured and available counts
    const featuredCount = eventsData.filter(e => e.is_featured).length;
    const availableCount = eventsData.filter(e => e.registration_status === 'available' && !isBefore(new Date(e.end_date || e.start_date), new Date())).length;
    const totalCount = eventsData.length;
 
    // Helper function to get image URL for storage/app/public
    const getImageUrl = useCallback((imageUrl) => {
        if (!imageUrl) return '/images/default-event.jpg';
        
        // Check if it's already a complete URL
        if (imageUrl.match(/^(https?:)?\/\//)) {
            return imageUrl;
        }
        
        // Check if it starts with storage/ (already in public storage)
        if (imageUrl.startsWith('storage/')) {
            return `/${imageUrl}`;
        }
        
        // If it starts with /, return as is (already from public directory)
        if (imageUrl.startsWith('/')) {
            return imageUrl;
        }
        
        // Default: assume it's a filename in storage
        return `/storage/${imageUrl}`;
    }, []);

    // Format time display
    const formatTime = useCallback((time) => {
        if (!time) return '';
        
        // If time is already in 12h format, return as is
        if (time.includes('AM') || time.includes('PM')) {
            return time;
        }
        
        // Convert 24h to 12h format
        if (time.includes(':')) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours, 10);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }
        
        return time;
    }, []);

    const formatEventDate = useCallback((dateString) => {
        if (!dateString) return 'Date TBA';
        
        try {
            return format(parseISO(dateString), 'MMMM dd, yyyy');
        } catch (error) {
            console.error('Error formatting date:', error);
            return 'Date TBA';
        }
    }, []);

    // Check if event is past
    const isEventPast = useCallback((event) => {
        const checkDate = event.end_date || event.start_date;
        if (!checkDate) return false;
        try {
            return isBefore(new Date(checkDate), new Date());
        } catch {
            return false;
        }
    }, []);

    // Share functionality
    const handleShare = async (event) => {
        const shareData = {
            title: event.title,
            text: event.short_description || 'Check out this event!',
            url: window.location.origin + route('events.show', event.slug)
        };
        
        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else {
                // Fallback: copy to clipboard
                await navigator.clipboard.writeText(shareData.url);
                alert('Event link copied to clipboard!');
            }
        } catch (err) {
            console.log('Error sharing:', err);
        }
    };

    return (
        <GuestLayout auth={auth}> 
            <Head title={title} />
             
            {/* Hero Section - Slimmer */}
            <HeroSection 
                title = {title}
                description= " The Institute of Governance, Risk, Compliance & Financial Crime Prevention "
            />

            {/* Events Section - Compact */}
            <section className="py-12 md:py-16 bg-white">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Stats Bar - Smaller */}
                    <div className="mb-8  bg-[#0A1A2F] rounded-xl p-4 border border-blue-100">
                        <div className="grid grid-cols-3 gap-4">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-white">{featuredCount}</div>
                                <div className="text-sm text-white">Featured</div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-white">{availableCount}</div>
                                <div className="text-sm text-white">Available</div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-white">{totalCount}</div>
                                <div className="text-sm text-white">Total</div>
                            </div>
                        </div>
                    </div>

                    {/* Events List - Compact spacing */}
                    <div className="space-y-4">
                        {eventsData.length > 0 ? (
                            eventsData.map((event, index) => {
                                const EventCard = ({ event, index }) => {
                                    const [imageLoaded, setImageLoaded] = useState(false);
                                    const isPast = isEventPast(event);
                                    
                                    return (
                                        <div
                                            key={event.id}
                                            className="group bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200"
                                            data-aos="fade-up"
                                            data-aos-delay={index * 50}
                                            data-aos-duration="600"
                                        >
                                            <div className="flex flex-col md:flex-row">
                                                {/* Event Image - Smaller */}
                                                <div className="md:w-1/3 lg:w-2/5">
                                                    <div className="relative h-48 md:h-full overflow-hidden bg-gray-100">
                                                        {!imageLoaded && (
                                                            <div className="absolute inset-0 bg-gradient-to-r from-gray-200 to-gray-300 animate-pulse" />
                                                        )}
                                                        <img
                                                            src={getImageUrl(event.image)}
                                                            alt={event.title}
                                                            className={`absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ${!imageLoaded ? 'opacity-0' : 'opacity-100'}`}
                                                            onLoad={() => setImageLoaded(true)}
                                                            onError={(e) => {
                                                                e.target.src = '/images/default-event.jpg';
                                                                e.target.onerror = null;
                                                                setImageLoaded(true);
                                                            }}
                                                            loading="lazy"
                                                            width={400}
                                                            height={200}
                                                        />
                                                        {event.is_featured && (
                                                            <div className="absolute top-3 left-3">
                                                                <span className="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-2 py-1 rounded-full text-xs font-semibold shadow">
                                                                    Featured
                                                                </span>
                                                            </div>
                                                        )}
                                                        <div className="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-black/10 to-transparent" />
                                                    </div>
                                                </div>

                                                {/* Event Details - Compact */}
                                                <div className="md:w-2/3 lg:w-3/5 p-4 md:p-6">
                                                    <div className="flex flex-col h-full">
                                                        {/* Date and Time */}
                                                        <div className="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm mb-3">
                                                            <svg className="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <span className="font-medium">
                                                                {event.start_date ? formatEventDate(event.start_date) : 'Date TBA'}
                                                            </span>
                                                        </div>

                                                        {/* Title */}
                                                        <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#0A1A2F] transition-colors line-clamp-2">
                                                            <Link href={route('events.show', event.slug)} className="hover:no-underline">
                                                                {event.title}
                                                            </Link>
                                                        </h3>

                                                        {/* Venue */}
                                                        <div className="flex items-center text-gray-600 text-sm mb-2">
                                                            <svg className="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            <span className="line-clamp-1">{event.venue || event.location || 'Venue TBA'}</span>
                                                        </div>

                                                        {/* Event Time Details */}
                                                        <div className="space-y-1 mb-3">
                                                            {/* Combined Time Display */}
                                                            {(event.start_time && event.end_time) ? (
                                                                <div className="flex items-center text-gray-700 text-sm">
                                                                    <svg className="w-3.5 h-3.5 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <span className="font-medium">Time: {formatTime(event.start_time)} - {formatTime(event.end_time)}</span>
                                                                </div>
                                                            ) : event.event_time ? (
                                                                <div className="flex items-center text-gray-700 text-sm">
                                                                    <svg className="w-3.5 h-3.5 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <span className="font-medium">Time: {event.event_time}</span>
                                                                </div>
                                                            ) : null}

                                                            {/* Price Display */}
                                                            {event.price !== undefined && event.price !== null && (
                                                                <div className="flex items-center text-gray-700 text-sm">
                                                                    <svg className="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    {event.price > 0 ? (
                                                                        <span className="font-medium">${parseFloat(event.price).toFixed(2)}</span>
                                                                    ) : (
                                                                        <span className="font-medium text-green-600">Free</span>
                                                                    )}
                                                                </div>
                                                            )}
                                                        </div>

                                                        {/* Description Excerpt - Smaller */}
                                                        <p className="text-gray-600 text-sm mb-3 line-clamp-2 flex-grow">
                                                            {event.short_description || event.excerpt || 'Join us for this professional development opportunity.'}
                                                        </p>

                                                        {/* Footer with Status and CTA - Compact */}
                                                        <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-3 border-t border-gray-100">
                                                            <div className="flex flex-wrap gap-1.5 mb-3 sm:mb-0">
                                                                {/* Event Status */}
                                                                {event.status === 'cancelled' && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                        Cancelled
                                                                    </span>
                                                                )}
                                                                {event.status === 'draft' && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                        Draft
                                                                    </span>
                                                                )}
                                                                {event.is_upcoming && !isPast && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                        Upcoming
                                                                    </span>
                                                                )}
                                                                {event.is_ongoing && !isPast && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        Live Now
                                                                    </span>
                                                                )}
                                                                {isPast && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                                        Closed
                                                                    </span>
                                                                )}

                                                                {/* Registration Status */}
                                                                {!isPast && event.registration_status === 'sold_out' && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                        Sold Out
                                                                    </span>
                                                                )}
                                                                {!isPast && event.registration_status === 'few_seats' && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                                        Few Seats Left
                                                                    </span>
                                                                )}
                                                                {!isPast && event.registration_status === 'available' && (
                                                                    <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                                        Available
                                                                    </span>
                                                                )}
                                                            </div> 

                                                            <div className="flex items-center gap-2">
                                                                {/* Share Button */}
                                                                <button
                                                                    onClick={() => handleShare(event)}
                                                                    className="inline-flex items-center justify-center p-2 text-gray-500 hover:text-blue-600 transition-colors rounded-lg hover:bg-gray-100"
                                                                    title="Share event"
                                                                >
                                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.368 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                                                    </svg>
                                                                </button>

                                                                {/* Read More Button - Smaller */}
                                                                <Link
                                                                    href={route('events.show', event.slug)}
                                                                    className="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-950 to-indigo-950 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow hover:shadow-md transform hover:-translate-y-0.5"
                                                                >
                                                                    View Details
                                                                    <svg className="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                                    </svg>
                                                                </Link>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                };

                                return <EventCard key={event.id} event={event} index={index} />;
                            })
                        ) : (
                            // No Events Message - Compact
                            <div className="text-center py-16">
                                <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                                    <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-2xl font-semibold text-gray-700 mb-3">No Events Available</h3>
                                <p className="text-gray-500 max-w-md mx-auto mb-6">
                                    We're currently planning our next events. Please check back soon for updates.
                                </p>
                                <Link
                                    href={route('contact')}
                                    className="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Contact Us for Updates
                                    <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Pagination - Smaller */}
                    {events && events.meta && events.links && events.links.length > 1 && (
                        <div className="mt-10 flex justify-center">
                            <nav className="inline-flex items-center space-x-1">
                                {events.links.map((link, index) => (
                                    <Link
                                        key={index}
                                        href={link.url || '#'}
                                        className={`
                                            px-3 py-2 rounded-md text-sm font-medium transition-all duration-200
                                            ${link.active
                                                ? 'bg-blue-600 text-white shadow'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                            }
                                            ${!link.url ? 'opacity-50 cursor-not-allowed' : 'hover:shadow'}
                                        `}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </nav>
                        </div>
                    )}
                </div> 
            </section>

            {/* CTA Section - Compact */}
            <CallToAction />
        </GuestLayout>
    );
}