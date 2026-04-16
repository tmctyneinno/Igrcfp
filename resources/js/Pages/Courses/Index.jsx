// resources/js/Pages/Courses/Index.jsx

import React, { useState, useEffect } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import CourseCard from '@/components/Courses/CourseCard';
import FilterSidebar from '@/components/Courses/FilterSidebar';
import SearchBar from '@/components/Courses/SearchBar';
import GuestLayout from '@/Layouts/GuestLayout';
 
export default function Courses({ auth, courses, filters, filterOptions, title, description, igrcfpCategory }) {
    const { url } = usePage(); 
    const [showFilters, setShowFilters] = useState(true);
    const [selectedFilters, setSelectedFilters] = useState(filters || {});
    const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);

    const pathwayGuidance = {
        'IGRCFP Certificates': {
            heading: 'IGRCFP CERTIFICATES',
            intro: 'Specialist programmes such as:',
            items: [
                'Trade Based Money Laundering (TBML)',
                'Crypto & Digital Asset Risk',
                'Cybersecurity & Digital Risk',
                'Blockchain Governance',
                'AML & Financial Crime Foundations',
            ],
            note: 'Learners are expected to complete these specialist certificate pathways before progressing to IGRCFP Diploma courses.',
        },
        'IGRCFP Diploma': {
            heading: 'IGRCFP DIPLOMA',
            intro: 'Before progressing to the IGRCFP Advanced Diploma, learners should complete the operational and practitioner-level pillars in:',
            items: [
                'Governance',
                'Risk',
                'Compliance',
                'FinCrime',
            ],
            note: 'These diploma studies build the practitioner-level foundation required for advancement into the next stage.',
        },
        'IGRCFP Advanced Diploma': {
            heading: 'IGRCFP ADVANCED DIPLOMA',
            intro: 'Learners in this category are expected to attain Advanced Professional Status before accessing:',
            items: [
                'IGRCFP Fellowship',
                'F-IGRCFP - Senior Leaders',
            ],
            note: 'Fellowship is a recognition stage based on professional experience, leadership, and contribution to the field.',
        },
    };

    const currentGuidance = igrcfpCategory ? pathwayGuidance[igrcfpCategory] : null;

    // Provide default values for filterOptions if undefined
    const defaultFilterOptions = {
        sortOptions: [
            { value: 'title_asc', label: 'Title (A-Z)' },
            { value: 'title_desc', label: 'Title (Z-A)' },
            { value: 'created_at_desc', label: 'Newest First' },
            { value: 'created_at_asc', label: 'Oldest First' },
            { value: 'price_asc', label: 'Price: Low to High' },
            { value: 'price_desc', label: 'Price: High to Low' },
        ],
        levels: ['Beginner', 'Intermediate', 'Advanced', 'All Levels'],
        categories: [],
        priceTypes: [
            { value: 'all', label: 'All' },
            { value: 'free', label: 'Free' },
            { value: 'paid', label: 'Paid' }
        ]
    };

    // Merge provided filterOptions with defaults
    const mergedFilterOptions = {
        sortOptions: filterOptions?.sortOptions || defaultFilterOptions.sortOptions,
        levels: filterOptions?.levels || defaultFilterOptions.levels,
        categories: filterOptions?.categories || defaultFilterOptions.categories,
        priceTypes: filterOptions?.priceTypes || defaultFilterOptions.priceTypes
    };

    // Update filters when props change
    useEffect(() => {
        setSelectedFilters(filters || {});
    }, [filters]);

    // Handle filter changes
    const handleFilterChange = (key, value) => {
        const newFilters = { ...selectedFilters, [key]: value, page: 1 };
        setSelectedFilters(newFilters);
        
        // Debounce search input
        if (key === 'search') {
            const timeout = setTimeout(() => {
                router.get(url.split('?')[0], newFilters, {
                    preserveState: true,
                    replace: true
                });
            }, 500);
            return () => clearTimeout(timeout);
        }
        
        // Instant update for other filters
        router.get(url.split('?')[0], newFilters, {
            preserveState: true,
            replace: true
        });
    };

    // Handle sort change
    const handleSortChange = (e) => {
        handleFilterChange('sort_field', e.target.value);
    };

    // Reset all filters
    const resetFilters = () => {
        router.get(url.split('?')[0], {}, {
            preserveState: true,
            replace: true
        });
    };

    // Get active filter count
    const getActiveFilterCount = () => {
        if (!selectedFilters) return 0;
        return Object.entries(selectedFilters).filter(([key, value]) => 
            value && value !== '' && value !== false && key !== 'sort_field' && key !== 'sort_direction'
        ).length;
    };

    return (
        <GuestLayout auth={auth}> 
            <Head title={title ? `IGRCFP | ${title}` : 'IGRCFP | Courses'} />
            <div className="min-h-screen bg-gray-50">
                <section className="bg-white border-b border-gray-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                        <p className="text-sm font-semibold tracking-[0.2em] text-blue-700 uppercase">
                            {igrcfpCategory || 'Courses'}
                        </p>
                        <h1 className="mt-3 text-3xl md:text-4xl font-bold text-gray-900">
                            {title || 'Courses'}
                        </h1>
                        <p className="mt-3 max-w-3xl text-gray-600">
                            {description || 'Browse our latest professional learning programmes.'}
                        </p>
                    </div>
                </section>
               
                {/* Main Content */}
                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    {currentGuidance && (
                        <div className="mb-8 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-slate-50 p-6 shadow-sm">
                            <p className="text-xs font-semibold uppercase tracking-[0.25em] text-blue-700">
                                {currentGuidance.heading}
                            </p>
                            <p className="mt-3 text-lg font-semibold text-gray-900">
                                {currentGuidance.intro}
                            </p>
                            <ul className="mt-4 space-y-2 text-gray-700">
                                {currentGuidance.items.map((item) => (
                                    <li key={item} className="flex items-start gap-3">
                                        <span className="mt-1 h-2 w-2 rounded-full bg-blue-600"></span>
                                        <span>{item}</span>
                                    </li>
                                ))}
                            </ul>
                            <p className="mt-4 text-sm leading-6 text-gray-600">
                                {currentGuidance.note}
                            </p>
                        </div>
                    )}

                    {/* Search and Filter Bar */}
                    <div className="mb-8">
                        <div className="flex flex-col lg:flex-row gap-4 items-center justify-between">
                            {/* Search */}
                            <div className="w-full lg:w-96">
                                <SearchBar
                                    value={selectedFilters?.search || ''}
                                    onChange={(value) => handleFilterChange('search', value)}
                                    placeholder="Search courses by title, description, or tags..."
                                />
                            </div>

                            {/* Sort and Filter Controls */}
                            <div className="flex items-center gap-4 w-full lg:w-auto">
                                {/* Sort Dropdown */}
                                <select
                                    value={selectedFilters?.sort_field || 'created_at_desc'}
                                    onChange={handleSortChange}
                                    className="flex-1 lg:flex-none px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                >
                                    {mergedFilterOptions.sortOptions.map((option) => (
                                        <option key={option.value} value={option.value}>
                                            {option.label}
                                        </option>
                                    ))}
                                </select>

                                {/* Filter Toggle Button (Mobile) */}
                                <button
                                    onClick={() => setMobileFiltersOpen(true)}
                                    className="lg:hidden flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    <span>Filters</span>
                                    {getActiveFilterCount() > 0 && (
                                        <span className="bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                            {getActiveFilterCount()}
                                        </span>
                                    )}
                                </button>

                                {/* Desktop Filter Toggle */}
                                <button
                                    onClick={() => setShowFilters(!showFilters)}
                                    className="hidden lg:flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    <span>{showFilters ? 'Hide Filters' : 'Show Filters'}</span>
                                </button>

                                {/* Reset Filters */}
                                {getActiveFilterCount() > 0 && (
                                    <button
                                        onClick={resetFilters}
                                        className="text-sm text-gray-600 hover:text-blue-600 transition"
                                    >
                                        Clear All
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Mobile Filter Sidebar */}
                    <AnimatePresence>
                        {mobileFiltersOpen && (
                            <FilterSidebar
                                isOpen={mobileFiltersOpen}
                                onClose={() => setMobileFiltersOpen(false)}
                                filters={selectedFilters}
                                filterOptions={mergedFilterOptions}
                                onFilterChange={handleFilterChange}
                                activeFilterCount={getActiveFilterCount()}
                                onReset={resetFilters}
                            />
                        )}
                    </AnimatePresence>

                    {/* Desktop Filters and Courses Grid */}
                    <div className="flex gap-8">
                        {/* Desktop Filters */}
                        {showFilters && (
                            <motion.div
                                initial={{ opacity: 0, x: -20 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0, x: -20 }}
                                className="hidden lg:block w-64 flex-shrink-0"
                            >
                                <div className="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                                    <h3 className="font-semibold text-lg mb-4">Filters</h3>
                                    
                                    {/* Level Filter */}
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Level
                                        </label>
                                        <select
                                            value={selectedFilters?.level || ''}
                                            onChange={(e) => handleFilterChange('level', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                            <option value="">All Levels</option>
                                            {mergedFilterOptions.levels.map((level) => (
                                                <option key={level} value={level}>
                                                    {level}
                                                </option>
                                            ))}
                                        </select>
                                    </div>

                                    {/* Category Filter */}
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Category
                                        </label>
                                        <select
                                            value={selectedFilters?.category || ''}
                                            onChange={(e) => handleFilterChange('category', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                            <option value="">All Categories</option>
                                            {mergedFilterOptions.categories.map((category) => (
                                                <option key={category.id || category} value={category.id || category}>
                                                    {category.name || category}
                                                </option>
                                            ))}
                                        </select>
                                    </div>

                                    {/* Price Type Filter */}
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Price
                                        </label>
                                        {mergedFilterOptions.priceTypes.map((type) => (
                                            <label key={type.value} className="flex items-center mb-2">
                                                <input
                                                    type="radio"
                                                    name="price_type"
                                                    value={type.value}
                                                    checked={selectedFilters?.price_type === type.value}
                                                    onChange={(e) => handleFilterChange('price_type', e.target.value)}
                                                    className="mr-2"
                                                />
                                                <span className="text-sm text-gray-700">{type.label}</span>
                                            </label>
                                        ))}
                                    </div>

                                    {/* Special Filters */}
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Special
                                        </label>
                                        <label className="flex items-center mb-2">
                                            <input
                                                type="checkbox"
                                                checked={selectedFilters?.featured || false}
                                                onChange={(e) => handleFilterChange('featured', e.target.checked)}
                                                className="mr-2 rounded"
                                            />
                                            <span className="text-sm text-gray-700">Featured Only</span>
                                        </label>
                                        <label className="flex items-center">
                                            <input
                                                type="checkbox"
                                                checked={selectedFilters?.popular || false}
                                                onChange={(e) => handleFilterChange('popular', e.target.checked)}
                                                className="mr-2 rounded"
                                            />
                                            <span className="text-sm text-gray-700">Popular Only</span>
                                        </label>
                                    </div>
                                </div>
                            </motion.div>
                        )}

                        {/* Courses Grid */}
                        <div className="flex-1">
                            {/* Results Count */}
                            {courses && (
                                <div className="mb-4 text-sm text-gray-600">
                                    Showing {courses.from || 0} - {courses.to || 0} of {courses.total || 0} courses
                                </div>
                            )}

                            {/* Courses */}
                            {courses?.data && courses.data.length > 0 ? (
                                <div className={`grid gap-6 ${
                                    showFilters 
                                        ? 'grid-cols-1 md:grid-cols-3 xl:grid-cols-3' 
                                        : 'grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4'
                                }`}> 
                                    {courses.data.map((course, index) => (
                                        <motion.div
                                            key={course.id}
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            transition={{ duration: 0.3, delay: index * 0.05 }}
                                        >
                                            <CourseCard course={course} />
                                        </motion.div>
                                    ))} 
                                </div>
                            ) : (
                                <div className="text-center py-16 bg-white rounded-xl">
                                    <svg className="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 className="text-xl font-semibold text-gray-900 mb-2">
                                        No courses found
                                    </h3>
                                    <p className="text-gray-600 mb-6">
                                        Try adjusting your search or filter criteria
                                    </p>
                                    <button
                                        onClick={resetFilters}
                                        className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                    >
                                        Clear All Filters
                                    </button>
                                </div>
                            )}

                            {/* Pagination */}
                            {courses?.links && courses.links.length > 3 && (
                                <div className="mt-8 flex justify-center">
                                    <nav className="flex items-center gap-2">
                                        {courses.links.map((link, index) => (
                                            <Link
                                                key={index}
                                                href={link.url || '#'}
                                                className={`px-4 py-2 rounded-lg transition ${
                                                    link.active
                                                        ? 'bg-blue-600 text-white'
                                                        : link.url
                                                        ? 'bg-white text-gray-700 hover:bg-gray-50'
                                                        : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                }`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                                preserveState
                                                preserveScroll
                                            />
                                        ))}
                                    </nav>
                                </div>
                            )}
                        </div>
                    </div>
                </section>
            </div>
        </GuestLayout>
    );
}
