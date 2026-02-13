// resources/js/Components/FilterSidebar.jsx

import React from 'react';
import { motion } from 'framer-motion';

export default function FilterSidebar({ 
    isOpen, 
    onClose, 
    filters, 
    filterOptions, 
    onFilterChange,
    activeFilterCount,
    onReset 
}) {
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
                        <h3 className="text-lg font-semibold">
                            Filters
                            {activeFilterCount > 0 && (
                                <span className="ml-2 text-sm bg-blue-600 text-white px-2 py-0.5 rounded-full">
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
                            value={filters.level}
                            onChange={(e) => onFilterChange('level', e.target.value)}
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        >
                            <option value="">All Levels</option>
                            {filterOptions.levels.map((level) => (
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
                            value={filters.category}
                            onChange={(e) => onFilterChange('category', e.target.value)}
                            className="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        >
                            <option value="">All Categories</option>
                            {filterOptions.categories.map((category) => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Price Type Filter */}
                    <div className="mb-6">
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Price
                        </label>
                        {filterOptions.priceTypes.map((type) => (
                            <label key={type.value} className="flex items-center mb-2">
                                <input
                                    type="radio"
                                    name="price_type"
                                    value={type.value}
                                    checked={filters.price_type === type.value}
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
                        <label className="flex items-center mb-2">
                            <input
                                type="checkbox"
                                checked={filters.featured}
                                onChange={(e) => onFilterChange('featured', e.target.checked)}
                                className="mr-2 rounded"
                            />
                            <span className="text-sm text-gray-700">Featured Only</span>
                        </label>
                        <label className="flex items-center">
                            <input
                                type="checkbox"
                                checked={filters.popular}
                                onChange={(e) => onFilterChange('popular', e.target.checked)}
                                className="mr-2 rounded"
                            />
                            <span className="text-sm text-gray-700">Popular Only</span>
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
                            className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Apply
                        </button>
                    </div>
                </div>
            </motion.div>
        </motion.div>
    );
}