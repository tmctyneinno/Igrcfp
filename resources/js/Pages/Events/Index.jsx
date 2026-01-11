import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { format, parseISO } from 'date-fns';

export default function Events({ auth, title, description, events }) {
    // Handle paginated events data
    const eventsData = events?.data || events || [];
    
    // Get featured and available counts
    const featuredCount = eventsData.filter(e => e.is_featured).length;
    const availableCount = eventsData.filter(e => e.registration_status === 'available').length;
    const totalCount = eventsData.length;

    // Helper function to get image URL for storage/app/public
    const getImageUrl = (imageUrl) => {
        if (!imageUrl) return '/images/default-event.jpg';
        
        // If image is stored in storage/app/public
        if (imageUrl.startsWith('storage/')) {
            return `/${imageUrl}`;
        }
        // If it's already a full URL, return as is
        if (imageUrl.startsWith('http')) {
            return imageUrl;
        }
        // If it starts with /, return as is (already from public directory)
        if (imageUrl.startsWith('/')) {
            return imageUrl;
        }
        // Default: assume it's in storage/app/public
        return `/storage/${imageUrl}`;
    };

    // Format time display
    const formatTimeDisplay = (event) => {
        if (event.start_time && event.end_time) {
            return `${event.start_time} - ${event.end_time}`;
        } else if (event.event_time) {
            return event.event_time;
        } else if (event.start_time) {
            return `${event.start_time} (Start)`;
        }
        return 'Time TBA';
    };
    const formatEventDate = (dateString) => {
        if (!dateString) return 'Date TBA';
        
        try {
            return format(parseISO(dateString), 'MMMM dd, yyyy');
        } catch (error) {
            console.error('Error formatting date:', error);
            return 'Date TBA';
        }
    };

    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            {/* Hero Section - Slimmer */}
            <section className="relative bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 md:py-20">
                <div className="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]" />
                <div className="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium mb-4">
                            Upcoming Events
                        </div>
                        <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 tracking-tight">
                            {title}
                        </h1>
                        <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                            {description || 'Join us for our upcoming professional development events, workshops, and seminars.'}
                        </p>
                    </div>
                </div>
            </section>

            {/* Events Section - Compact */}
            <section className="py-12 md:py-16 bg-white">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Stats Bar - Smaller */}
                    <div className="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                        <div className="grid grid-cols-3 gap-4">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-blue-700">{featuredCount}</div>
                                <div className="text-sm text-gray-600">Featured</div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-green-700">{availableCount}</div>
                                <div className="text-sm text-gray-600">Available</div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-gray-700">{totalCount}</div>
                                <div className="text-sm text-gray-600">Total</div>
                            </div>
                        </div>
                    </div>

                    {/* Events List - Compact spacing */}
                    <div className="space-y-4">
                        {eventsData.length > 0 ? (
                            eventsData.map((event, index) => (
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
                                            <div className="relative h-38 md:h-full overflow-hidden bg-gray-100">
                                                <img
                                                    src={getImageUrl(event.image)}
                                                    alt={event.title}
                                                    className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                    onError={(e) => {
                                                        e.target.src = '/images/default-event.jpg';
                                                    }}
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
                                        <div className="md:w-2/3 lg:w-3/5 p-2 md:p-4">
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
                                                <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-950 transition-colors line-clamp-2">
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
                                                    <span className="line-clamp-1">{event.venue || event.location}</span>
                                                </div>

                                                {/* Event Time Details */}
                                                <div className="space-y-1 mb-3">
                                                    {/* Combined Time Display */}
                                                    {(event.start_time && event.end_time) ? (
                                                        <div className="flex items-center text-gray-700 text-sm">
                                                            <svg className="w-3.5 h-3.5 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span className="font-medium">Time: {event.start_time} - {event.end_time}</span>
                                                        </div>
                                                    ) : event.event_time ? (
                                                        <div className="flex items-center text-gray-700 text-sm">
                                                            <svg className="w-3.5 h-3.5 mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span className="font-medium">Time: {event.event_time}</span>
                                                        </div>
                                                    ) : null}
                                                </div>

                                                {/* Description Excerpt - Smaller */}
                                                <p className="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">
                                                    {event.excerpt || 'Join us for this professional development opportunity.'}
                                                </p>

                                                {/* Footer with Status and CTA - Compact */}
                                                <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-2 border-t border-gray-100">
                                                    <div className="flex flex-wrap gap-1.5 mb-3 sm:mb-0">
                                                        {/* Registration Status */}
                                                        {event.registration_status === 'sold_out' && (
                                                            <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                Sold Out
                                                            </span>
                                                        )}
                                                        {event.registration_status === 'few_seats' && (
                                                            <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                                Few Seats
                                                            </span>
                                                        )}
                                                        {event.registration_status === 'available' && (
                                                            <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                                Available
                                                            </span>
                                                        )}
                                                    </div>

                                                    {/* Read More Button - Smaller */}
                                                    <Link
                                                        href={route('events.show', event.slug)}
                                                        className="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-900 to-indigo-800 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow hover:shadow-md transform hover:-translate-y-0.5"
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
                            ))
                        ) : (
                            // No Events Message - Compact
                            <div className="text-center py-12">
                                <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <svg className="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-semibold text-gray-700 mb-2">No Events Available</h3>
                                <p className="text-gray-500 max-w-md mx-auto text-sm">
                                    We're currently planning our next events. Please check back soon for updates.
                                </p>
                            </div>
                        )}
                    </div>

                    {/* Pagination - Smaller */}
                    {events && events.meta && events.links && events.links.length > 1 && (
                        <div className="mt-8 flex justify-center">
                            <nav className="inline-flex items-center space-x-1">
                                {events.links.map((link, index) => (
                                    <Link
                                        key={index}
                                        href={link.url || '#'}
                                        className={`
                                            px-3 py-1.5 rounded-md text-sm font-medium transition-all duration-200
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
            <section className="py-12 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-2xl font-bold text-gray-900 mb-3">
                        Want Event Updates?
                    </h2>
                    <p className="text-gray-600 mb-6">
                        Subscribe to our newsletter for the latest event announcements.
                    </p>
                    <Link
                        href={route('contact')}
                        className="inline-flex items-center px-3 py-3 bg-gradient-to-r from-blue-950 to-indigo-950 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow hover:shadow-md transform hover:-translate-y-0.5"
                    >
                        Contact Us
                        <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </Link>
                </div>
            </section>
        </GuestLayout>
    );
}