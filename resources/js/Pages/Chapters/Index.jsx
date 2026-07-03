import { Head, Link } from '@inertiajs/react';
import React, { useState } from 'react';
import GuestLayout from "@/Layouts/GuestLayout";
import { 
    MapPin, 
    Users, 
    Calendar, 
    ChevronRight, 
    Globe, 
    ArrowUpRight,
    Search,
    Filter,
    Mail,
    Building2,
    TrendingUp
} from 'lucide-react';

const regionStyles = {
    'Africa': { 
        gradient: 'from-amber-400 to-orange-500',
        lightBg: 'bg-amber-50',
        border: 'border-amber-200',
        badge: 'bg-amber-100 text-amber-800',
        icon: 'text-amber-500',
        strip: 'from-amber-400 via-orange-400 to-amber-500'
    },
    'Europe': { 
        gradient: 'from-blue-400 to-indigo-500',
        lightBg: 'bg-blue-50',
        border: 'border-blue-200',
        badge: 'bg-blue-100 text-blue-800',
        icon: 'text-blue-500',
        strip: 'from-blue-400 via-indigo-400 to-blue-500'
    },
    'Asia': { 
        gradient: 'from-red-400 to-rose-500',
        lightBg: 'bg-red-50',
        border: 'border-red-200',
        badge: 'bg-red-100 text-red-800',
        icon: 'text-red-500',
        strip: 'from-red-400 via-rose-400 to-red-500'
    },
    'North America': { 
        gradient: 'from-emerald-400 to-green-500',
        lightBg: 'bg-emerald-50',
        border: 'border-emerald-200',
        badge: 'bg-emerald-100 text-emerald-800',
        icon: 'text-emerald-500',
        strip: 'from-emerald-400 via-green-400 to-emerald-500'
    },
    'South America': { 
        gradient: 'from-violet-400 to-purple-500',
        lightBg: 'bg-violet-50',
        border: 'border-violet-200',
        badge: 'bg-violet-100 text-violet-800',
        icon: 'text-violet-500',
        strip: 'from-violet-400 via-purple-400 to-violet-500'
    },
    'Oceania': { 
        gradient: 'from-teal-400 to-cyan-500',
        lightBg: 'bg-teal-50',
        border: 'border-teal-200',
        badge: 'bg-teal-100 text-teal-800',
        icon: 'text-teal-500',
        strip: 'from-teal-400 via-cyan-400 to-teal-500'
    },
    'Middle East': { 
        gradient: 'from-rose-400 to-pink-500',
        lightBg: 'bg-rose-50',
        border: 'border-rose-200',
        badge: 'bg-rose-100 text-rose-800',
        icon: 'text-rose-500',
        strip: 'from-rose-400 via-pink-400 to-rose-500'
    },
};

const defaultStyle = { 
    gradient: 'from-gray-400 to-slate-500',
    lightBg: 'bg-gray-50',
    border: 'border-gray-200',
    badge: 'bg-gray-100 text-gray-800',
    icon: 'text-gray-500',
    strip: 'from-gray-400 via-slate-400 to-gray-500'
};

export default function Index({ chapters, auth }) {
    const [searchTerm, setSearchTerm] = useState('');
    const [filterStatus, setFilterStatus] = useState('all');
    const [selectedRegion, setSelectedRegion] = useState('all');

    const regions = [...new Set(chapters.map(c => c.region))].sort();

    const filteredChapters = chapters.filter(chapter => {
        const matchesSearch = 
            (chapter.region || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
            (chapter.country_focus || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
            (chapter.name || '').toLowerCase().includes(searchTerm.toLowerCase());
        const matchesStatus = filterStatus === 'all' || 
            (filterStatus === 'active' && chapter.is_active) ||
            (filterStatus === 'inactive' && !chapter.is_active);
        const matchesRegion = selectedRegion === 'all' || chapter.region === selectedRegion;
        
        return matchesSearch && matchesStatus && matchesRegion;
    });

    const totalChapters = chapters.length;
    const activeChapters = chapters.filter(c => c.is_active).length;
    const totalMembers = chapters.reduce((sum, c) => sum + (c.members_count || 0), 0);
    const totalEvents = chapters.reduce((sum, c) => sum + (c.events_count || 0), 0);

    const getRegionStyle = (region) => regionStyles[region] || defaultStyle;

    return (
        <GuestLayout auth={auth}>
            <Head title="Regional Chapters | IGRCFP">
                <meta name="description" content="Connect with IGRCFP regional chapters worldwide. Join local events, network with professionals, and grow your career." />
            </Head>

            {/* Hero Banner */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center bg-blue-100 px-4 py-1.5 justify-center space-x-2 mb-6 rounded-full">
                            <div className="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                            <span className="font-semibold text-sm tracking-wider text-blue-800 uppercase">Regional Chapters</span>
                        </div>
                        <h1 className="text-3xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                            Regional Chapters of{' '}
                            <span className="text-blue-900">IGRCFP</span>
                        </h1>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                            Connect with local professionals, attend events, and grow your network 
                            through IGRCFP chapters across regions and countries.
                        </p>
                        
                        {/* Stats */}
                        <div className="mt-10 flex flex-wrap justify-center gap-8 md:gap-16">
                            <div className="text-center">
                                <div className="text-3xl md:text-4xl font-bold text-blue-900">{totalChapters}</div>
                                <div className="text-sm text-gray-500 font-medium mt-1">Total Chapters</div>
                            </div>
                            <div className="text-center">
                                <div className="text-3xl md:text-4xl font-bold text-green-600">{activeChapters}</div>
                                <div className="text-sm text-gray-500 font-medium mt-1">Active</div>
                            </div>
                            <div className="text-center">
                                <div className="text-3xl md:text-4xl font-bold text-blue-900">{totalMembers.toLocaleString()}</div>
                                <div className="text-sm text-gray-500 font-medium mt-1">Members</div>
                            </div>
                            <div className="text-center">
                                <div className="text-3xl md:text-4xl font-bold text-green-600">{totalEvents}</div>
                                <div className="text-sm text-gray-500 font-medium mt-1">Upcoming Events</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Filters Bar */}
            <section className="bg-white border-b border-gray-200 sticky top-16 z-30 shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div className="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                        <div className="flex items-center gap-3 flex-wrap">
                            <div className="flex items-center text-sm text-gray-500">
                                <Filter className="w-4 h-4 mr-1.5" />
                                Filters:
                            </div>
                            <select
                                value={selectedRegion}
                                onChange={(e) => setSelectedRegion(e.target.value)}
                                className="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white"
                            >
                                <option value="all">All Regions</option>
                                {regions.map(region => (
                                    <option key={region} value={region}>{region}</option>
                                ))}
                            </select>
                            <select
                                value={filterStatus}
                                onChange={(e) => setFilterStatus(e.target.value)}
                                className="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white"
                            >
                                <option value="all">All Status</option>
                                <option value="active">Active Only</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            {(selectedRegion !== 'all' || filterStatus !== 'all' || searchTerm) && (
                                <button
                                    onClick={() => {
                                        setSearchTerm('');
                                        setSelectedRegion('all');
                                        setFilterStatus('all');
                                    }}
                                    className="text-sm text-blue-600 hover:text-blue-800 font-medium"
                                >
                                    Clear All
                                </button>
                            )}
                        </div>
                        <div className="relative w-full sm:w-72">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <input
                                type="text"
                                placeholder="Search chapters, countries..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="w-full pl-10 pr-4 py-2.5 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/* Chapters Grid */}
            <section className="bg-gray-50/50 py-12 lg:py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {filteredChapters.length > 0 ? (
                        <>
                            <div className="flex items-center justify-between mb-8">
                                <div>
                                    <h2 className="text-2xl font-bold text-gray-900">
                                        {selectedRegion !== 'all' ? `${selectedRegion} Chapters` : 'Explore Chapters'}
                                    </h2>
                                    <p className="text-gray-500 text-sm mt-1">
                                        Showing {filteredChapters.length} of {chapters.length} chapters
                                    </p>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {filteredChapters.map((chapter) => {
                                    const style = getRegionStyle(chapter.region);
                                    
                                    return (
                                        <div
                                            key={chapter.id}
                                            className="group bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden flex flex-col"
                                        >
                                            {/* Region Color Strip */}
                                            <div className={`h-1.5 bg-gradient-to-r ${style.strip}`}></div>

                                            <div className="p-6 flex flex-col flex-1">
                                                {/* Header */}
                                                <div className="flex items-start justify-between mb-4">
                                                    <div className="flex-1 min-w-0">
                                                        <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${style.badge}`}>
                                                            <Globe className="w-3 h-3 mr-1" />
                                                            {chapter.region}
                                                        </span>
                                                        <h3 className="text-lg font-bold text-gray-900 mt-2 group-hover:text-blue-900 transition-colors truncate">
                                                            {chapter.name || `${chapter.region} Chapter`}
                                                        </h3>
                                                    </div>
                                                    <span className={`inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full ml-3 flex-shrink-0 ${
                                                        chapter.is_active 
                                                            ? 'bg-green-50 text-green-700 border border-green-200' 
                                                            : 'bg-gray-100 text-gray-500 border border-gray-200'
                                                    }`}>
                                                        <span className={`w-1.5 h-1.5 rounded-full ${chapter.is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-400'}`}></span>
                                                        {chapter.is_active ? 'Active' : 'Inactive'}
                                                    </span>
                                                </div>

                                                {/* Info */}
                                                <div className="space-y-3 flex-1">
                                                    <div className="flex items-start text-sm text-gray-600">
                                                        <MapPin className={`w-4 h-4 mr-2.5 mt-0.5 ${style.icon} flex-shrink-0`} />
                                                        <span className="line-clamp-2">{chapter.country_focus || 'Multiple countries in region'}</span>
                                                    </div>
                                                    <div className="flex items-center text-sm text-gray-600">
                                                        <Users className={`w-4 h-4 mr-2.5 ${style.icon} flex-shrink-0`} />
                                                        <span>
                                                            <strong className="text-gray-900">{chapter.members_count || 0}</strong>{' '}
                                                            <span className="text-gray-500">Members</span>
                                                        </span>
                                                    </div>
                                                    <div className="flex items-center text-sm text-gray-600">
                                                        <Calendar className={`w-4 h-4 mr-2.5 ${style.icon} flex-shrink-0`} />
                                                        <span>
                                                            <strong className="text-gray-900">{chapter.events_count || 0}</strong>{' '}
                                                            <span className="text-gray-500">Upcoming Events</span>
                                                        </span>
                                                    </div>
                                                </div>

                                                {/* Action */}
                                                <div className="mt-6 pt-4 border-t border-gray-100">
                                                    <Link
                                                        href={route('chapters.show', chapter.slug ?? chapter.id)}
                                                        className="inline-flex items-center justify-between w-full px-5 py-3 bg-gradient-to-r from-blue-900 to-blue-800 hover:from-blue-800 hover:to-blue-700 text-white rounded-xl font-medium transition-all duration-200 group/btn shadow-sm hover:shadow-md"
                                                    >
                                                        <span>View Chapter & Events</span>
                                                        <ArrowUpRight className="w-4 h-4 ml-2 transition-transform duration-200 group-hover/btn:translate-x-0.5 group-hover/btn:-translate-y-0.5" />
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </>
                    ) : (
                        <div className="text-center py-16">
                            <div className="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <MapPin className="w-10 h-10 text-blue-400" />
                            </div>
                            <h3 className="text-2xl font-bold text-gray-900 mb-2">No Chapters Found</h3>
                            <p className="text-gray-500 max-w-md mx-auto mb-8">
                                {searchTerm || selectedRegion !== 'all' || filterStatus !== 'all'
                                    ? 'No chapters match your current filters. Try adjusting your search criteria or clearing filters.'
                                    : 'We are currently expanding our chapter network. Check back soon or contact us to start a chapter in your region.'
                                }
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link
                                    href={route('contact')}
                                    className="inline-flex items-center px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-xl font-medium transition-colors shadow-sm"
                                >
                                    <Mail className="w-4 h-4 mr-2" />
                                    Contact Us
                                </Link>
                                {(searchTerm || selectedRegion !== 'all' || filterStatus !== 'all') && (
                                    <button
                                        onClick={() => {
                                            setSearchTerm('');
                                            setSelectedRegion('all');
                                            setFilterStatus('all');
                                        }}
                                        className="inline-flex items-center px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-colors"
                                    >
                                        <Filter className="w-4 h-4 mr-2" />
                                        Clear Filters
                                    </button>
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </section>

            {/* CTA Section */}
            <section className="bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div className="inline-flex items-center bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full mb-6 border border-white/20">
                        <Building2 className="w-4 h-4 text-blue-300 mr-2" />
                        <span className="text-blue-200 font-medium text-sm">Get Involved</span>
                    </div>
                    <h2 className="text-3xl font-bold text-white mb-4">
                        Want to Start a Chapter in Your Region?
                    </h2>
                    <p className="text-blue-200 max-w-2xl mx-auto mb-8 text-lg leading-relaxed">
                        We're always looking for passionate governance, risk, and compliance professionals 
                        to lead and grow our community. Join us in expanding the IGRCFP network worldwide.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            href={route('contact')}
                            className="inline-flex items-center px-8 py-3.5 bg-white text-blue-900 hover:bg-blue-50 rounded-xl font-semibold transition-colors shadow-lg"
                        >
                            <Mail className="w-5 h-5 mr-2" />
                            Express Interest
                            <ChevronRight className="w-5 h-5 ml-2" />
                        </Link>
                        <Link
                            href={route('welcome-to-igrcfp')}
                            className="inline-flex items-center px-8 py-3.5 border-2 border-white/30 text-white hover:bg-white/10 rounded-xl font-semibold transition-colors"
                        >
                            <Building2 className="w-5 h-5 mr-2" />
                            Learn More About IGRCFP
                        </Link>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}