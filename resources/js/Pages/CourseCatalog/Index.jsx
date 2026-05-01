import React, { useEffect, useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import CourseCard from '@/components/Courses/CourseCard';

export default function CourseCatalog({ auth, courses, categories = [], filters = {}, filterOptions = {} }) {
    const [selectedFilters, setSelectedFilters] = useState(filters || {});

    useEffect(() => {
        setSelectedFilters(filters || {});
    }, [filters]);

    const applyFilters = (updates) => {
        const nextFilters = {
            ...selectedFilters,
            ...updates,
        };

        Object.keys(nextFilters).forEach((key) => {
            if (nextFilters[key] === '' || nextFilters[key] === false || nextFilters[key] === null) {
                delete nextFilters[key];
            }
        });

        setSelectedFilters(nextFilters);

        router.get(route('course.catalog.index'), nextFilters, {
            preserveState: true,
            replace: true,
        });
    };

    const clearFilters = () => {
        setSelectedFilters({});
        router.get(route('course.catalog.index'), {}, {
            preserveState: true,
            replace: true,
        });
    };

    const activeFilterCount = Object.entries(selectedFilters || {}).filter(([key, value]) => {
        return key !== 'sort' && value !== '' && value !== false && value !== null && value !== undefined;
    }).length;

    return (
        <GuestLayout auth={auth}>
            <Head title="IGRCFP | Course Catalog" />

            <div className="min-h-screen bg-gray-50">
                <section className="bg-white border-b border-gray-200">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                        <p className="text-sm font-semibold tracking-[0.2em] text-blue-700 uppercase">
                            Course Catalog
                        </p>
                        <div className="mt-3 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900 md:text-4xl">
                                    Browse IGRCFP courses
                                </h1>
                                <p className="mt-3 max-w-3xl text-gray-600">
                                    Explore published programmes across governance, risk, compliance, financial crime, cybersecurity, digital assets, and emerging technology.
                                </p>
                            </div>
                            <div className="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-900">
                                {courses?.total || 0} courses available
                            </div>
                        </div>
                    </div>
                </section>

                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                    <div className="mb-8 grid gap-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm lg:grid-cols-[1.5fr_1fr_1fr_1fr_auto]">
                        <input
                            type="search"
                            value={selectedFilters.search || ''}
                            onChange={(event) => applyFilters({ search: event.target.value })}
                            placeholder="Search courses"
                            className="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        />

                        <select
                            value={selectedFilters.category || ''}
                            onChange={(event) => applyFilters({ category: event.target.value })}
                            className="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">All categories</option>
                            {categories.map((category) => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                </option>
                            ))}
                        </select>

                        <select
                            value={selectedFilters.level || ''}
                            onChange={(event) => applyFilters({ level: event.target.value })}
                            className="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">All levels</option>
                            {(filterOptions.levels || []).map((level) => (
                                <option key={level} value={level}>
                                    {level}
                                </option>
                            ))}
                        </select>

                        <select
                            value={selectedFilters.sort || 'newest'}
                            onChange={(event) => applyFilters({ sort: event.target.value })}
                            className="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        >
                            {(filterOptions.sortOptions || []).map((option) => (
                                <option key={option.value} value={option.value}>
                                    {option.label}
                                </option>
                            ))}
                        </select>

                        <button
                            type="button"
                            onClick={clearFilters}
                            className="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Clear{activeFilterCount > 0 ? ` (${activeFilterCount})` : ''}
                        </button>
                    </div>

                    <div className="mb-6 flex flex-wrap gap-3">
                        {(filterOptions.priceTypes || []).map((type) => (
                            <button
                                key={type.value}
                                type="button"
                                onClick={() => applyFilters({
                                    price_type: selectedFilters.price_type === type.value ? '' : type.value,
                                })}
                                className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                                    selectedFilters.price_type === type.value
                                        ? 'bg-blue-900 text-white'
                                        : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'
                                }`}
                            >
                                {type.label}
                            </button>
                        ))}
                        <button
                            type="button"
                            onClick={() => applyFilters({ featured: !selectedFilters.featured })}
                            className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                                selectedFilters.featured
                                    ? 'bg-blue-900 text-white'
                                    : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'
                            }`}
                        >
                            Featured
                        </button>
                        <button
                            type="button"
                            onClick={() => applyFilters({ popular: !selectedFilters.popular })}
                            className={`rounded-full px-4 py-2 text-sm font-semibold transition ${
                                selectedFilters.popular
                                    ? 'bg-blue-900 text-white'
                                    : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'
                            }`}
                        >
                            Popular
                        </button>
                    </div>

                    {courses?.data?.length > 0 ? (
                        <>
                            <div className="mb-4 text-sm text-gray-600">
                                Showing {courses.from || 0} - {courses.to || 0} of {courses.total || 0} courses
                            </div>

                            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                {courses.data.map((course) => (
                                    <CourseCard key={course.id} course={course} />
                                ))}
                            </div>
                        </>
                    ) : (
                        <div className="rounded-lg bg-white py-16 text-center shadow-sm">
                            <h2 className="text-xl font-semibold text-gray-900">No courses found</h2>
                            <p className="mt-2 text-gray-600">Try changing your search or filters.</p>
                            <button
                                type="button"
                                onClick={clearFilters}
                                className="mt-6 rounded-lg bg-blue-900 px-6 py-2 font-semibold text-white transition hover:bg-blue-700"
                            >
                                Clear Filters
                            </button>
                        </div>
                    )}

                    {courses?.links?.length > 3 && (
                        <nav className="mt-10 flex flex-wrap justify-center gap-2">
                            {courses.links.map((link, index) => (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url || '#'}
                                    preserveScroll
                                    preserveState
                                    className={`rounded-lg px-4 py-2 text-sm font-semibold transition ${
                                        link.active
                                            ? 'bg-blue-900 text-white'
                                            : link.url
                                                ? 'bg-white text-gray-700 hover:bg-gray-50'
                                                : 'bg-gray-100 text-gray-400'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </nav>
                    )}
                </section>
            </div>
        </GuestLayout>
    );
}
