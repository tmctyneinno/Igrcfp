import React, { useState, useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import HeroSection from '@/Layouts/HeroSection';
import CallToAction from "@/Pages/components/CallToAction";

export default function Index({ documents, categories, filters, auth }) {
    // ✅ Use Inertia's useForm properly
    const { data, setData, get, post, errors, setError, processing } = useForm({
        type: filters.type || '',
        category: filters.category || '',
        full_name: '',
        title: '',
        organisation: '',
        email: '',
        document_id: '',
        document_title: '',
    });
 
    const [openForm, setOpenForm] = useState(false);
    const [selectedDoc, setSelectedDoc] = useState(null);

    // ✅ Auto-fill from localStorage if available
    useEffect(() => {
        const saved = localStorage.getItem('research_contact');
        if (saved) {
            const parsed = JSON.parse(saved);
            setData({
                ...data,
                full_name: parsed.full_name || '',
                title: parsed.title || '',
                organisation: parsed.organisation || '',
                email: parsed.email || '',
            });
        }
    }, []);

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

    const openContactForm = (doc) => {
        setSelectedDoc(doc);
        // ✅ Set document data directly into useForm
        setData({
            ...data,
            document_id: doc.id,
            document_title: doc.title,
        });
        setOpenForm(true);
    };

    const handleInputChange = (e) => {
        setData(e.target.name, e.target.value);
    };

    const validateForm = () => {
        let valid = true;
        // Clear old errors
        setError('full_name', '');
        setError('title', '');
        setError('organisation', '');
        setError('email', '');

        if (!data.full_name) { setError('full_name', 'Full name is required'); valid = false; }
        if (!data.title) { setError('title', 'Title is required'); valid = false; }
        if (!data.organisation) { setError('organisation', 'Organisation is required'); valid = false; }
        if (!data.email) { setError('email', 'Email is required'); valid = false; }
        else if (!/\S+@\S+\.\S+/.test(data.email)) { setError('email', 'Email is invalid'); valid = false; }

        return valid;
    };

    const submitForm = () => {
        if (!validateForm()) return;

        // ✅ Send data DIRECTLY (no wrapping) using Inertia post
        post(route('research.save-contact'), {
            preserveScroll: true,
            onSuccess: () => {
                // Save to localStorage
                localStorage.setItem('research_contact', JSON.stringify({
                    full_name: data.full_name,
                    title: data.title,
                    organisation: data.organisation,
                    email: data.email,
                }));
                // Close modal and redirect
                setOpenForm(false);
                window.location.href = route('research.show', selectedDoc.slug);
            },
        });
    };

    return (
        <GuestLayout auth={auth} forceWhiteNavbar>
            <Head title="Research & White Papers" />

            

            <div className="bg-gray-50 min-h-screen py-0">
                {/* Hero Section */}
                <HeroSection 
                    title ="Research & White Papers"
                    description= "Access expert insights, industry analysis, and comprehensive guides on Governance, Risk, Compliance, and Financial Crime Prevention."
                />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                    {/* Documents Grid */}
                    {documents.data.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                            {documents.data.map((doc) => (
                                <div 
                                    key={doc.id} 
                                    className="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 group"
                                >
                                    <div className="h-40 bg-gradient-to-br from-blue-50 to-gray-50 flex items-center justify-center relative">
                                        <svg className="w-20 h-20 text-blue-900/20 group-hover:text-blue-900/30 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2-2z" />
                                        </svg>
                                        <span className={`absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-medium ${
                                            doc.document_type === 'research' ? 'bg-blue-100 text-blue-800' : 'bg-indigo-100 text-indigo-800'
                                        }`}>
                                            {doc.document_type === 'research' ? 'Research' : 'White Paper'}
                                        </span>
                                    </div>

                                    <div className="p-6">
                                        <button 
                                            type="button"
                                            onClick={() => openContactForm(doc)}
                                            className="text-left w-full cursor-pointer focus:outline-none"
                                        >
                                            <h3 className="text-xl font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-900 transition-colors">
                                                {doc.title}
                                            </h3>
                                        </button>
                                        
                                        {doc.description && (
                                            <p className="text-gray-600 text-sm mb-4 line-clamp-3">
                                                {doc.description}
                                            </p>
                                        )}

                                        <div className="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <div className="flex gap-2">
                                                <button 
                                                    onClick={() => openContactForm(doc)}
                                                    className="text-blue-900 hover:text-blue-700 text-sm font-medium flex items-center gap-1"
                                                >
                                                    View Details
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>
                                                <button 
                                                    onClick={() => openContactForm(doc)}
                                                    className="text-green-700 hover:text-green-900 text-sm font-medium flex items-center gap-1"
                                                >
                                                    Download
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </button>
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

            {/* Contact Form Modal */}
            {openForm && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div className="bg-white rounded-xl shadow-xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
                        <h3 className="text-xl font-bold text-gray-900 mb-4">Request Access</h3>
                        <p className="text-gray-600 mb-6">Please provide your details to view or download this document.</p>

                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input
                                    type="text"
                                    name="full_name"
                                    value={data.full_name}
                                    onChange={handleInputChange}
                                    className={`w-full border rounded-lg px-3 py-2 ${errors.full_name ? 'border-red-500' : 'border-gray-300'}`}
                                />
                                {errors.full_name && <p className="text-red-500 text-xs mt-1">{errors.full_name}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                <input
                                    type="text"
                                    name="title"
                                    value={data.title}
                                    onChange={handleInputChange}
                                    className={`w-full border rounded-lg px-3 py-2 ${errors.title ? 'border-red-500' : 'border-gray-300'}`}
                                />
                                {errors.title && <p className="text-red-500 text-xs mt-1">{errors.title}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Organisation *</label>
                                <input
                                    type="text"
                                    name="organisation"
                                    value={data.organisation}
                                    onChange={handleInputChange}
                                    className={`w-full border rounded-lg px-3 py-2 ${errors.organisation ? 'border-red-500' : 'border-gray-300'}`}
                                />
                                {errors.organisation && <p className="text-red-500 text-xs mt-1">{errors.organisation}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    onChange={handleInputChange}
                                    className={`w-full border rounded-lg px-3 py-2 ${errors.email ? 'border-red-500' : 'border-gray-300'}`}
                                />
                                {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
                            </div>
                        </div>

                        <div className="flex gap-3 mt-6">
                            <button
                                onClick={() => setOpenForm(false)}
                                className="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition"
                                disabled={processing}
                            >
                                Cancel
                            </button>
                            <button
                                onClick={submitForm}
                                className="flex-1 bg-blue-900 text-white py-2 rounded-lg hover:bg-blue-800 transition"
                                disabled={processing}
                            >
                                {processing ? 'Submitting...' : 'Submit & Continue'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
            {/* CTA Section */}
            <CallToAction />
        </GuestLayout>
    );
}