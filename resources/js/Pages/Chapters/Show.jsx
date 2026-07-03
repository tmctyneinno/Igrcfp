import { Head, Link } from '@inertiajs/react';
import React from 'react';
import GuestLayout from "@/Layouts/GuestLayout";
import { Calendar, MapPin, Users, ArrowLeft, Award, Star, Shield, Clock, ChevronRight, Building2, Globe, Mail } from 'lucide-react';

export default function Show({ chapter, upcomingEvents, leadership, memberBenefits }) {
    return (
        <GuestLayout>
            <Head title={`${chapter.region} Chapter | IGRCFP`} />

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Breadcrumb */}
                <div className="mb-6">
                    <nav className="flex items-center text-sm text-gray-500 space-x-2">
                        <Link href="/" className="hover:text-blue-900 transition-colors">Home</Link>
                        <span>/</span>
                        <Link href={route('chapters.index')} className="hover:text-blue-900 transition-colors">Chapters</Link>
                        <span>/</span>
                        <span className="text-gray-900 font-medium">{chapter.region} Chapter</span>
                    </nav>
                </div>

                {/* Back Link */}
                <div className="mb-6">
                    <Link 
                        href={route('chapters.index')} 
                        className="inline-flex items-center text-blue-900 hover:text-blue-700 font-medium transition-colors"
                    >
                        <ArrowLeft className="w-4 h-4 mr-2" />
                        Back to All Chapters
                    </Link>
                </div>

                {/* Chapter Header Card */}
                <div className="bg-gradient-to-br from-blue-50 to-white rounded-2xl shadow-sm border border-blue-100 p-6 md:p-8 mb-10">
                    <div className="md:flex md:items-start md:justify-between gap-6">
                        <div className="flex-1">
                            <div className="flex items-center gap-3 mb-2">
                                <Globe className="w-6 h-6 text-blue-900" />
                                <span className="text-sm font-medium text-blue-600 uppercase tracking-wider">{chapter.region}</span>
                            </div>
                            <h1 className="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                                {chapter.name || `${chapter.region} Chapter`}
                            </h1>
                            <p className="text-lg text-gray-600 mb-4 max-w-2xl">
                                {chapter.country_focus 
                                    ? `Serving governance, risk, and compliance professionals in: ${chapter.country_focus}` 
                                    : 'Regional chapter for IGRCFP members and professionals in governance, risk, compliance, and financial crime prevention.'}
                            </p>
                            
                            {/* Chapter Stats */}
                            <div className="flex flex-wrap gap-4 text-sm">
                                <span className="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-blue-200 text-blue-800 font-medium shadow-sm">
                                    <Users className="w-4 h-4 mr-1.5" />
                                    {chapter.members_count || 0} Members
                                </span>
                                <span className="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-blue-200 text-blue-800 font-medium shadow-sm">
                                    <Calendar className="w-4 h-4 mr-1.5" />
                                    {chapter.events_count || 0} Events
                                </span>
                                <span className={`inline-flex items-center px-3 py-1.5 rounded-full font-medium shadow-sm ${
                                    chapter.is_active 
                                        ? 'bg-green-50 text-green-800 border border-green-200' 
                                        : 'bg-gray-100 text-gray-600 border border-gray-200'
                                }`}>
                                    <span className={`w-2 h-2 rounded-full mr-1.5 ${chapter.is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-400'}`}></span>
                                    {chapter.is_active ? 'Active Chapter' : 'Inactive'}
                                </span>
                            </div>
                        </div>

                        {/* Chapter Badge/Icon */}
                        <div className="hidden md:flex flex-shrink-0">
                            <div className="w-24 h-24 bg-blue-900 rounded-2xl flex items-center justify-center shadow-lg">
                                <Building2 className="w-12 h-12 text-white" />
                            </div>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Main Content - Events */}
                    <div className="lg:col-span-2">
                        {/* Events Section */}
                        <div className="mb-10">
                            <div className="flex items-center justify-between mb-6">
                                <h2 className="text-2xl font-bold text-gray-900 flex items-center">
                                    <Calendar className="w-6 h-6 mr-2 text-blue-900" />
                                    Upcoming Events
                                </h2>
                                {upcomingEvents && upcomingEvents.length > 0 && (
                                    <span className="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                        {upcomingEvents.length} {upcomingEvents.length === 1 ? 'Event' : 'Events'}
                                    </span>
                                )}
                            </div>

                            {upcomingEvents && upcomingEvents.length > 0 ? (
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {upcomingEvents.map((event) => (
                                        <div 
                                            key={event.id} 
                                            className="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-blue-200 transition-all duration-300 overflow-hidden"
                                        >
                                            {/* Event Image */}
                                            <div className="h-44 w-full bg-gradient-to-br from-blue-100 to-indigo-100 relative overflow-hidden">
                                                {event.image ? (
                                                    <img 
                                                        src={`/storage/${event.image}`} 
                                                        alt={event.title}
                                                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                    />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center">
                                                        <Calendar className="w-16 h-16 text-blue-300" />
                                                    </div>
                                                )}
                                                {/* Date Badge */}
                                                <div className="absolute top-3 left-3 bg-white/95 backdrop-blur-sm rounded-lg px-3 py-1.5 text-center shadow-sm">
                                                    <div className="text-xs font-semibold text-blue-900 uppercase">
                                                        {new Date(event.start_date).toLocaleDateString('en-US', { month: 'short' })}
                                                    </div>
                                                    <div className="text-lg font-bold text-blue-900 leading-tight">
                                                        {new Date(event.start_date).getDate()}
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Event Content */}
                                            <div className="p-5">
                                                <h3 className="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-900 transition-colors">
                                                    {event.title}
                                                </h3>
                                                
                                                {event.short_description && (
                                                    <p className="text-gray-600 text-sm mb-4 line-clamp-2">
                                                        {event.short_description}
                                                    </p>
                                                )}

                                                <div className="space-y-2.5 text-sm text-gray-600 mb-4">
                                                    <div className="flex items-center">
                                                        <Calendar className="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" />
                                                        <span>
                                                            {new Date(event.start_date).toLocaleDateString('en-US', { 
                                                                weekday: 'long',
                                                                month: 'long', 
                                                                day: 'numeric',
                                                                year: 'numeric'
                                                            })}
                                                            {event.start_time && ` • ${event.start_time}`}
                                                        </span>
                                                    </div>
                                                    {event.location && (
                                                        <div className="flex items-center">
                                                            <MapPin className="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" />
                                                            <span className="line-clamp-1">{event.location}</span>
                                                        </div>
                                                    )}
                                                    {event.venue && (
                                                        <div className="flex items-center">
                                                            <Building2 className="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" />
                                                            <span className="line-clamp-1">{event.venue}</span>
                                                        </div>
                                                    )}
                                                </div>

                                                {/* Available Seats */}
                                                <div className="flex items-center justify-between mb-4 pt-3 border-t border-gray-100">
                                                    <span className="text-xs font-medium text-gray-500">
                                                        {event.available_seats > 0 ? (
                                                            <span className="text-green-600">
                                                                {event.available_seats} spots left
                                                            </span>
                                                        ) : (
                                                            <span className="text-red-600">Sold Out</span>
                                                        )}
                                                    </span>
                                                    <span className="text-xs text-gray-400">
                                                        {event.capacity} total capacity
                                                    </span>
                                                </div>

                                                <Link 
                                                    href={route('events.show', event.slug ?? event.id)} 
                                                    className="flex items-center justify-center w-full px-4 py-2.5 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-medium transition-colors group/btn"
                                                >
                                                    View Event Details
                                                    <ChevronRight className="w-4 h-4 ml-1.5 transition-transform group-hover/btn:translate-x-0.5" />
                                                </Link>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="bg-gray-50 rounded-xl border border-dashed border-gray-200 p-10 text-center">
                                    <Calendar className="w-12 h-12 text-gray-400 mx-auto mb-4" />
                                    <h3 className="text-xl font-medium text-gray-700 mb-2">No Upcoming Events</h3>
                                    <p className="text-gray-500 max-w-md mx-auto mb-6">
                                        There are currently no upcoming events scheduled for this chapter. Check back soon or browse all events.
                                    </p>
                                    <Link 
                                        href={route('events.index')} 
                                        className="inline-flex items-center px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-medium transition-colors"
                                    >
                                        <Calendar className="w-4 h-4 mr-2" />
                                        View All Events
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Chapter Description */}
                        {chapter.description && (
                            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 mb-10">
                                <h2 className="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <Building2 className="w-5 h-5 mr-2 text-blue-900" />
                                    About This Chapter
                                </h2>
                                <div className="prose prose-gray max-w-none text-gray-600 leading-relaxed">
                                    {chapter.description}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Sidebar */}
                    <div className="lg:col-span-1 space-y-6">
                        {/* Member Benefits */}
                        {memberBenefits && memberBenefits.length > 0 && (
                            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <Award className="w-5 h-5 mr-2 text-amber-500" />
                                    Member Benefits
                                </h3>
                                <ul className="space-y-3">
                                    {memberBenefits.map((benefit, index) => (
                                        <li key={index} className="flex items-start text-sm text-gray-600">
                                            <Star className="w-4 h-4 mr-2 text-amber-500 flex-shrink-0 mt-0.5" />
                                            <span>{benefit}</span>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        )}

                        {/* Leadership Team */}
                        {leadership && leadership.length > 0 && (
                            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <Users className="w-5 h-5 mr-2 text-blue-900" />
                                    Chapter Leadership
                                </h3>
                                <div className="space-y-4">
                                    {leadership.map((leader, index) => (
                                        <div key={index} className="flex items-center gap-3">
                                            <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span className="text-blue-900 font-bold text-sm">
                                                    {leader.name.charAt(0).toUpperCase()}
                                                </span>
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold text-gray-900">{leader.name}</p>
                                                <p className="text-xs text-gray-500">{leader.role}</p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Chapter Info Card */}
                        <div className="bg-gradient-to-br from-blue-900 to-indigo-900 rounded-xl shadow-lg p-6 text-white">
                            <h3 className="text-lg font-bold mb-4 flex items-center">
                                <Shield className="w-5 h-5 mr-2 text-blue-300" />
                                Chapter Information
                            </h3>
                            <div className="space-y-3 text-sm">
                                <div className="flex items-center">
                                    <Globe className="w-4 h-4 mr-2 text-blue-300 flex-shrink-0" />
                                    <span>Region: <strong>{chapter.region}</strong></span>
                                </div>
                                {chapter.country_focus && (
                                    <div className="flex items-center">
                                        <MapPin className="w-4 h-4 mr-2 text-blue-300 flex-shrink-0" />
                                        <span>Focus: <strong>{chapter.country_focus}</strong></span>
                                    </div>
                                )}
                                <div className="flex items-center">
                                    <Users className="w-4 h-4 mr-2 text-blue-300 flex-shrink-0" />
                                    <span>Members: <strong>{chapter.members_count || 0}</strong></span>
                                </div>
                                <div className="flex items-center">
                                    <Calendar className="w-4 h-4 mr-2 text-blue-300 flex-shrink-0" />
                                    <span>Events: <strong>{chapter.events_count || 0}</strong></span>
                                </div>
                            </div>

                            {/* Contact CTA */}
                            <div className="mt-6 pt-4 border-t border-white/20">
                                <Link
                                    href={route('contact')}
                                    className="flex items-center justify-center w-full px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-colors border border-white/20"
                                >
                                    <Mail className="w-4 h-4 mr-2" />
                                    Contact Chapter
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}