import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Index({ documents, categories, filters, auth }) {
    const { data, setData, get } = useForm({
        type: filters.type || '',
        category: filters.category || '',
    });

    const handleFilter = (e) => {
        e.preventDefault();
        get(route('research.index'), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const resetFilters = () => {
        setData({ type: '', category: '' });
        get(route('research.index'), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Choose layout based on auth status
    
    return (
         <GuestLayout auth={auth} forceWhiteNavbar>
            <Head title="Research & White Papers" />

            <div className="bg-gray-50 min-h-screen py-0">
                {/* Hero Section */}
                <section className="w-full bg-gradient-to-r from-blue-50 via-white to-blue-50 pb-5 md:py-28 border-b">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-4xl mx-auto">
                        
                        <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                            Research & White Papers
                        </h1>
                        
                        <p className="text-lg text-gray-600 max-w-3xl mx-auto">
                            Access expert insights, industry analysis, and comprehensive guides on Governance, Risk, Compliance, and Financial Crime prevention.
                        </p>
                        </div>
                    </div>
                </section>
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                   
                    {/* Filter Form */}
                    {/* <div className="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
                        <form onSubmit={handleFilter} className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label htmlFor="type" className="block text-sm font-medium text-gray-700 mb-2">
                                    Document Type
                                </label>
                                <select
                                    id="type"
                                    value={data.type}
                                    onChange={(e) => setData('type', e.target.value)}
                                    className="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">All Types</option>
                                    <option value="research">Research Content</option>
                                    <option value="whitepaper">White Paper</option>
                                </select>
                            </div>

                            <div>
                                <label htmlFor="category" className="block text-sm font-medium text-gray-700 mb-2">
                                    Category
                                </label>
                                <select
                                    id="category"
                                    value={data.category}
                                    onChange={(e) => setData('category', e.target.value)}
                                    className="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">All Categories</option>
                                    {categories.map((cat) => (
                                        <option key={cat} value={cat}>{cat}</option>
                                    ))}
                                </select>
                            </div>

                            <div className="flex items-end gap-2">
                                <button
                                    type="submit"
                                    className="w-full bg-blue-900 text-white px-4 py-2.5 rounded-lg hover:bg-blue-800 transition duration-200 flex items-center justify-center gap-2"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Filter
                                </button>
                                <button
                                    type="button"
                                    onClick={resetFilters}
                                    className="w-full bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition duration-200 flex items-center justify-center gap-2"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div> */}

                    {/* Documents Grid */}
                    {documents.data.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                            {documents.data.map((doc) => (
                                <div 
                                    key={doc.id} 
                                    className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 group"
                                >
                                    {/* Document Icon / Preview */}
                                    <div className="h-40 bg-gradient-to-br from-blue-50 to-gray-50 flex items-center justify-center relative">
                                        <svg className="w-20 h-20 text-blue-900/20 group-hover:text-blue-900/30 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span className={`absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-medium ${
                                            doc.document_type === 'research' 
                                                ? 'bg-blue-100 text-blue-800' 
                                                : 'bg-indigo-100 text-indigo-800'
                                        }`}>
                                            {doc.document_type === 'research' ? 'Research' : 'White Paper'}
                                        </span>
                                    </div>

                                    {/* Content */}
                                    <div className="p-6">
                                        <h3 className="text-xl font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-900 transition-colors">
                                            {doc.title}
                                        </h3>
                                        
                                        {doc.description && (
                                            <p className="text-gray-600 text-sm mb-4 line-clamp-3">
                                                {doc.description}
                                            </p>
                                        )}

                                       
                                        <div className="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <span className="text-xs text-gray-500">
                                                {doc.file_size ? `${(doc.file_size / 1024 / 1024).toFixed(2)} MB` : 'PDF Document'}
                                            </span>
                                            <div className="flex gap-2">
                                                <Link 
                                                    href={route('research.show', doc.slug)}
                                                    className="text-blue-900 hover:text-blue-700 text-sm font-medium flex items-center gap-1"
                                                >
                                                    View Details
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </Link>
                                                <a 
                                                    href={doc.file_url} 
                                                    target="_blank" 
                                                    rel="noopener noreferrer"
                                                    className="text-green-700 hover:text-green-900 text-sm font-medium flex items-center gap-1"
                                                >
                                                    Download
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                            <svg className="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 className="text-xl font-medium text-gray-900 mb-2">No documents found</h3>
                            <p className="text-gray-600 mb-4">
                                {filters.type || filters.category 
                                    ? 'Try adjusting your filters to see more results.' 
                                    : 'Research papers and white papers will be published here soon.'}
                            </p>
                            {(filters.type || filters.category) && (
                                <button
                                    onClick={resetFilters}
                                    className="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition duration-200"
                                >
                                    Reset Filters
                                </button>
                            )}
                        </div>
                    )}

                    {/* Pagination */}
                    {documents.links && documents.data.length > 0 && (
                        <div className="mt-8">
                            <nav className="flex justify-center">
                                <div className="flex gap-2">
                                    {documents.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url || '#'}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            className={`px-4 py-2 rounded-lg text-sm font-medium transition ${
                                                link.active 
                                                    ? 'bg-blue-900 text-white' 
                                                    : link.url 
                                                        ? 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' 
                                                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                            }`}
                                            preserveState
                                            preserveScroll
                                        />
                                    ))}
                                </div>
                            </nav>
                        </div>
                    )}
                </div>
            </div>
        </GuestLayout> 
    );
}