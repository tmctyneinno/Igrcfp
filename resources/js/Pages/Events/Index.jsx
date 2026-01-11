import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Events({ auth, title, description, events }) {
    // Handle paginated events data
    const eventsData = events?.data || events || [];
    
    // Get featured and available counts
    const featuredCount = eventsData.filter(e => e.is_featured).length;
    const availableCount = eventsData.filter(e => e.registration_status === 'available').length;
    const totalCount = eventsData.length;

    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            {/* Hero Section */}
            <section className="relative bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-20 md:py-28">
                <div className="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]" />
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium mb-6">
                            Upcoming Events
                        </div>
                        <h1 className="text-3xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 tracking-tight">
                            {title}
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                            {description || 'Join us for our upcoming professional development events, workshops, and seminars.'}
                        </p>
                    </div>
                </div>
            </section>

            {/* Events Section */}
            <section className="py-16 md:py-24 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Stats Bar */}
                    <div className="mb-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div className="text-center">
                                <div className="text-3xl font-bold text-blue-700">
                                    {featuredCount}
                                </div>
                                <div className="text-gray-600">Featured Events</div>
                            </div>
                            <div className="text-center">
                                <div className="text-3xl font-bold text-green-700">
                                    {availableCount}
                                </div>
                                <div className="text-gray-600">Available Now</div>
                            </div>
                            <div className="text-center">
                                <div className="text-3xl font-bold text-gray-700">{totalCount}</div>
                                <div className="text-gray-600">Total Events</div>
                            </div>
                        </div>
                    </div>

                    {/* Events List */}
                    <div className="space-y-8">
                        {eventsData.length > 0 ? (
                            eventsData.map((event, index) => (
                                <div
                                    key={event.id}
                                    className="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200"
                                    data-aos="fade-up"
                                    data-aos-delay={index * 100}
                                    data-aos-duration="800"
                                >
                                    <div className="flex flex-col lg:flex-row">
                                        {/* Event Image */}
                                        <div className="lg:w-2/5">
                                            <div className="relative h-64 lg:h-full overflow-hidden">
                                                <img
                                                    src={event.image_url}
                                                    alt={event.title}
                                                    className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                />
                                                {event.is_featured && (
                                                    <div className="absolute top-4 left-4">
                                                        <span className="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                                            Featured
                                                        </span>
                                                    </div>
                                                )}
                                                <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                            </div>
                                        </div>

                                        {/* Event Details */}
                                        <div className="lg:w-3/5 p-6 lg:p-8">
                                            <div className="flex flex-col h-full justify-between">
                                                <div>
                                                    {/* Date Badge */}
                                                    <div className="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg mb-4">
                                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span className="font-medium">{event.event_date}</span>
                                                        <span className="mx-2">•</span>
                                                        <span className="font-medium">{event.event_time}</span>
                                                    </div>

                                                    {/* Title */}
                                                    <h3 className="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-700 transition-colors">
                                                        <Link href={route('events.show', event.slug)} className="hover:no-underline">
                                                            {event.title}
                                                        </Link>
                                                    </h3>

                                                    {/* Venue */}
                                                    <div className="flex items-center text-gray-600 mb-4">
                                                        <svg className="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        <span>{event.venue || event.location}</span>
                                                    </div>

                                                    {/* Description Excerpt */}
                                                    <p className="text-gray-600 mb-6 line-clamp-2">
                                                        {event.excerpt || 'Join us for this professional development opportunity.'}
                                                    </p>
                                                </div>

                                                {/* Footer with Status and CTA */}
                                                <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-4 border-t border-gray-100">
                                                    <div className="flex flex-wrap gap-2 mb-4 sm:mb-0">
                                                        {/* Registration Status */}
                                                        {event.registration_status === 'sold_out' && (
                                                            <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                                Sold Out
                                                            </span>
                                                        )}
                                                        {event.registration_status === 'few_seats' && (
                                                            <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                                                Few Seats Left
                                                            </span>
                                                        )}
                                                        {event.registration_status === 'available' && (
                                                            <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                                                Available Now
                                                            </span>
                                                        )}
                                                    </div>

                                                    {/* Read More Button */}
                                                    <Link
                                                        href={route('events.show', event.slug)}
                                                        className="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                                    >
                                                        View Details
                                                        <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            // No Events Message
                            <div className="text-center py-16">
                                <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                                    <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-2xl font-semibold text-gray-700 mb-3">No Events Available</h3>
                                <p className="text-gray-500 max-w-md mx-auto">
                                    We're currently planning our next events. Please check back soon for updates or subscribe to our newsletter.
                                </p>
                            </div>
                        )}
                    </div>

                    {/* Pagination */}
                    {events && events.meta && events.links && events.links.length > 1 && (
                        <div className="mt-12 flex justify-center">
                            <nav className="inline-flex items-center space-x-2">
                                {events.links.map((link, index) => (
                                    <Link
                                        key={index}
                                        href={link.url || '#'}
                                        className={`
                                            px-4 py-2 rounded-lg font-medium transition-all duration-200
                                            ${link.active
                                                ? 'bg-blue-600 text-white shadow-md'
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

            {/* CTA Section */}
            <section className="py-16 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">
                        Can't Find What You're Looking For?
                    </h2>
                    <p className="text-xl text-gray-600 mb-8">
                        Subscribe to our newsletter to receive updates about future events and professional development opportunities.
                    </p>
                    <Link
                        href={route('contact')}
                        className="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-950 to-indigo-900 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Contact Us
                        <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </Link>
                </div>
            </section>
        </GuestLayout>
    );
}