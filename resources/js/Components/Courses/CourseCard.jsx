// resources/js/Pages/Courses/Index.jsx

import React, { useState, useEffect } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import CourseCard from '@/Components/Courses/CourseCard';
import SearchBar from '@/Components/Courses/SearchBar';

export default function Courses({ courses = null, filters = null, filterOptions = null }) {
    const { url } = usePage();
    const [showFilters, setShowFilters] = useState(false);
    const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);
    
    // Provide default values if props are undefined
    const safeFilters = filters || {
        search: '',
        level: '',
        price_type: '',
        featured: false,
        popular: false,
        format: '',
        sort_field: 'created_at',
        sort_direction: 'desc'
    };
    
    const safeFilterOptions = filterOptions || {
        levels: [],
        formats: [],
        priceTypes: [
            { value: '', label: 'All Prices' },
            { value: 'free', label: 'Free' },
            { value: 'paid', label: 'Paid' },
            { value: 'discounted', label: 'Discounted' },
        ],
        sortOptions: [
            { value: 'created_at_desc', label: 'Newest First' },
            { value: 'created_at_asc', label: 'Oldest First' },
            { value: 'price_asc', label: 'Price: Low to High' },
            { value: 'price_desc', label: 'Price: High to Low' },
            { value: 'title_asc', label: 'Title: A to Z' },
            { value: 'title_desc', label: 'Title: Z to A' },
        ]
    };

    const [selectedFilters, setSelectedFilters] = useState(safeFilters);

    // Update filters when props change
    useEffect(() => {
        if (filters) {
            setSelectedFilters(filters);
        }
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
        const [sort_field, sort_direction] = e.target.value.split('_');
        handleFilterChange('sort_field', sort_field);
        handleFilterChange('sort_direction', sort_direction);
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
        return Object.entries(selectedFilters).filter(([key, value]) => 
            value && value !== '' && value !== false && key !== 'sort_field' && key !== 'sort_direction'
        ).length;
    };

    // Safe courses data
    const coursesData = courses?.data || [];
    const pagination = courses || {
        from: 0,
        to: 0,
        total: 0,
        links: []
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Header Section - Matching your certification component style */}
            <section className="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5 }}
                    >
                        <div className="relative inline-flex items-center mb-3">
                            <motion.span
                                initial={{ width: 0 }}
                                animate={{ width: 64 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                className="absolute left-0 top-1/2 h-px bg-blue-300"
                            />
                            <span className="text-sm tracking-widest text-blue-200 pl-20 uppercase">
                                Explore Our Programs
                            </span>
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold mb-4">
                            All Courses & Certifications
                        </h1>
                        <p className="text-xl text-blue-100 max-w-3xl">
                            Explore our comprehensive range of professional certifications 
                            and training programs designed to advance your career.
                        </p>
                    </motion.div>
                </div>
            </section>

            {/* Main Content */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                {/* Search and Filter Bar */}
                <div className="mb-8">
                    <div className="flex flex-col lg:flex-row gap-4 items-center justify-between">
                        {/* Search - Matching your design */}
                        <div className="w-full lg:w-96">
                            <SearchBar
                                value={selectedFilters?.search || ''}
                                onChange={(value) => handleFilterChange('search', value)}
                                placeholder="Search courses by title or description..."
                            />
                        </div>

                        {/* Sort and Filter Controls */}
                        <div className="flex items-center gap-4 w-full lg:w-auto">
                            {/* Sort Dropdown */}
                            <select
                                value={`${selectedFilters?.sort_field || 'created_at'}_${selectedFilters?.sort_direction || 'desc'}`}
                                onChange={handleSortChange}
                                className="flex-1 lg:flex-none px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                            >
                                {safeFilterOptions.sortOptions.map((option) => (
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
                        <FilterSidebarMobile
                            isOpen={mobileFiltersOpen}
                            onClose={() => setMobileFiltersOpen(false)}
                            filters={selectedFilters}
                            filterOptions={safeFilterOptions}
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
                            <div className="bg-white rounded-xl shadow-md p-6 sticky top-24">
                                <h3 className="font-semibold text-lg mb-4 text-gray-900">Filters</h3>
                                
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
                                        {safeFilterOptions.levels?.map((level) => (
                                            <option key={level} value={level}>
                                                {level}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                {/* Format Filter */}
                                {safeFilterOptions.formats && safeFilterOptions.formats.length > 0 && (
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Format
                                        </label>
                                        <select
                                            value={selectedFilters?.format || ''}
                                            onChange={(e) => handleFilterChange('format', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                            <option value="">All Formats</option>
                                            {safeFilterOptions.formats.map((format) => (
                                                <option key={format} value={format}>
                                                    {format}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                )}

                                {/* Price Type Filter */}
                                <div className="mb-6">
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Price
                                    </label>
                                    {safeFilterOptions.priceTypes.map((type) => (
                                        <label key={type.value} className="flex items-center mb-2 cursor-pointer">
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
                                    <label className="flex items-center mb-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            checked={selectedFilters?.featured || false}
                                            onChange={(e) => handleFilterChange('featured', e.target.checked)}
                                            className="mr-2 rounded"
                                        />
                                        <span className="text-sm text-gray-700">⭐ Featured Only</span>
                                    </label>
                                    <label className="flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            checked={selectedFilters?.popular || false}
                                            onChange={(e) => handleFilterChange('popular', e.target.checked)}
                                            className="mr-2 rounded"
                                        />
                                        <span className="text-sm text-gray-700">🔥 Popular Only</span>
                                    </label>
                                </div>

                                {/* Apply Button for Mobile Filters */}
                                <button
                                    onClick={() => setShowFilters(false)}
                                    className="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-700 transition"
                                >
                                    Apply Filters
                                </button>
                            </div>
                        </motion.div>
                    )}

                    {/* Courses Grid */}
                    <div className="flex-1">
                        {/* Results Count */}
                        <div className="mb-4 text-sm text-gray-600">
                            Showing {pagination.from || 0} - {pagination.to || 0} of {pagination.total || 0} courses
                        </div>

                        {/* Courses */}
                        {coursesData.length > 0 ? (
                            <div className={`grid gap-6 ${
                                showFilters 
                                    ? 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3' 
                                    : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'
                            }`}>
                                {coursesData.map((course, index) => (
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
                            <div className="text-center py-16 bg-white rounded-xl shadow-md">
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
                                    className="inline-flex items-center px-6 py-3 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-700 transition transform hover:-translate-y-1"
                                >
                                    Clear All Filters
                                </button>
                            </div>
                        )}

                        {/* Pagination */}
                        {pagination.links && pagination.links.length > 3 && (
                            <div className="mt-12 flex justify-center">
                                <nav className="flex items-center gap-2">
                                    {pagination.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url || '#'}
                                            className={`px-4 py-2 rounded-lg transition ${
                                                link.active
                                                    ? 'bg-blue-900 text-white'
                                                    : link.url
                                                    ? 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
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
    );
}

// Mobile Filter Sidebar Component
function FilterSidebarMobile({ isOpen, onClose, filters, filterOptions, onFilterChange, activeFilterCount, onReset }) {
    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 lg:hidden"
        >
            {/* Backdrop */}
            <div className="absolute inset-0 bg-black bg-opacity-50" onClick={onClose} />

            {/* Sidebar */}
            <motion.div
                initial={{ x: '-100%' }}
                animate={{ x: 0 }}
                exit={{ x: '-100%' }}
                transition={{ type: 'tween' }}
                className="absolute left-0 top-0 bottom-0 w-80 bg-white shadow-xl overflow-y-auto"
            >
                <div className="p-6">
                    {/* Header */}
                    <div className="flex items-center justify-between mb-6">
                        <h3 className="text-lg font-semibold text-gray-900">
                            Filters
                            {activeFilterCount > 0 && (
                                <span className="ml-2 text-sm bg-blue-900 text-white px-2 py-0.5 rounded-full">
                                    {activeFilterCount}
                                </span>
                            )}
                        </h3>
                        <button
                            onClick={onClose}
                            className="p-2 hover:bg-gray-100 rounded-lg"
                        >
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {/* Level Filter */}
                    <div className="mb-6">
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Level
                        </label>
                        <select
                            value={filters?.level || ''}
                            onChange={(e) => onFilterChange('level', e.target.value)}
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        >
                            <option value="">All Levels</option>
                            {filterOptions?.levels?.map((level) => (
                                <option key={level} value={level}>
                                    {level}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Format Filter */}
                    {filterOptions?.formats && filterOptions.formats.length > 0 && (
                        <div className="mb-6">
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Format
                            </label>
                            <select
                                value={filters?.format || ''}
                                onChange={(e) => onFilterChange('format', e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg"
                            >
                                <option value="">All Formats</option>
                                {filterOptions.formats.map((format) => (
                                    <option key={format} value={format}>
                                        {format}
                                    </option>
                                ))}
                            </select>
                        </div>
                    )}

                    {/* Price Type Filter */}
                    <div className="mb-6">
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Price
                        </label>
                        {filterOptions?.priceTypes?.map((type) => (
                            <label key={type.value} className="flex items-center mb-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="price_type_mobile"
                                    value={type.value}
                                    checked={filters?.price_type === type.value}
                                    onChange={(e) => onFilterChange('price_type', e.target.value)}
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
                        <label className="flex items-center mb-2 cursor-pointer">
                            <input
                                type="checkbox"
                                checked={filters?.featured || false}
                                onChange={(e) => onFilterChange('featured', e.target.checked)}
                                className="mr-2 rounded"
                            />
                            <span className="text-sm text-gray-700">⭐ Featured Only</span>
                        </label>
                        <label className="flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                checked={filters?.popular || false}
                                onChange={(e) => onFilterChange('popular', e.target.checked)}
                                className="mr-2 rounded"
                            />
                            <span className="text-sm text-gray-700">🔥 Popular Only</span>
                        </label>
                    </div>

                    {/* Actions */}
                    <div className="flex gap-3 pt-4 border-t">
                        <button
                            onClick={onReset}
                            className="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                        >
                            Reset
                        </button>
                        <button
                            onClick={onClose}
                            className="flex-1 px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-700"
                        >
                            Apply
                        </button>
                    </div>
                </div>
            </motion.div>
        </motion.div>
    );
}