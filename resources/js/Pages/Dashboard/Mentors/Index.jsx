import React from 'react';
import { router, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MentorsIndex({ auth, mentors, filters }) {

    const handleFilter = (e) => {
        e.preventDefault();

        router.get(route('dashboard.mentors.index'), filters, {
            preserveState: true,
            replace: true,
        });
    };

    const updateFilter = (key, value) => {
        router.get(route('dashboard.mentors.index'), {
            ...filters,
            [key]: value,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {/* Header */}
                <div className="mb-8">
                    <h2 className="text-3xl font-bold text-gray-900">Find a Mentor</h2>
                    <p className="mt-2 text-gray-600">
                        Browse active mentors and apply for guidance tailored to your goals.
                    </p>
                </div>

                {/* Filters */}
                <form onSubmit={handleFilter} className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

                    <input
                        type="text"
                        value={filters.search || ''}
                        onChange={(e) => updateFilter('search', e.target.value)}
                        placeholder="Search by name or domain"
                        className="w-full rounded-lg border px-4 py-2 text-sm"
                    />

                    <input
                        type="text"
                        value={filters.region || ''}
                        onChange={(e) => updateFilter('region', e.target.value)}
                        placeholder="Region"
                        className="w-full rounded-lg border px-4 py-2 text-sm"
                    />

                    <input
                        type="text"
                        value={filters.country || ''}
                        onChange={(e) => updateFilter('country', e.target.value)}
                        placeholder="Country"
                        className="w-full rounded-lg border px-4 py-2 text-sm"
                    />

                    <select
                        value={filters.availability || ''}
                        onChange={(e) => updateFilter('availability', e.target.value)}
                        className="w-full rounded-lg border px-4 py-2 text-sm"
                    >
                        <option value="">Availability</option>
                        <option value="taking">Taking mentees</option>
                        <option value="not_taking">Not taking</option>
                    </select>

                </form>

                {/* Mentors Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {mentors.data.length > 0 ? (
                        mentors.data.map((mentor) => (
                            <div key={mentor.id} className="bg-white rounded-2xl shadow-md border p-6 flex flex-col justify-between">

                                <div>
                                    <div className="flex items-center gap-3 mb-4">
                                        <div className="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-lg font-semibold text-blue-900">
                                            {mentor.name.charAt(0).toUpperCase()}
                                        </div>

                                        <div>
                                            <h3 className="text-lg font-semibold text-gray-900">
                                                {mentor.name}
                                            </h3>
                                            <p className="text-sm text-gray-500">
                                                {mentor.title || 'Mentor'}
                                            </p>
                                        </div>
                                    </div>

                                    <div className="space-y-2 text-sm text-gray-600">
                                        <p><b>Domain:</b> {mentor.domain || 'General'}</p>
                                        <p><b>Region:</b> {mentor.region || 'Global'}</p>
                                        <p><b>Country:</b> {mentor.country || 'N/A'}</p>
                                        <p><b>Availability:</b> {mentor.availability_status === 'taking' ? 'Taking mentees' : 'Not taking'}</p>
                                        <p><b>Rating:</b> {Number(mentor.rating).toFixed(1)}</p>
                                        <p><b>Completed:</b> {mentor.completed}</p>
                                    </div>
                                </div>

                                <div className="mt-6 flex items-center justify-between">
                                    <span className="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
                                        {mentor.slots_left} slots left
                                    </span>

                                    <Link
                                        href={route('dashboard.mentors.show', mentor.id)}
                                        className="text-sm font-semibold text-blue-900"
                                    >
                                        View Profile
                                    </Link>
                                </div>

                            </div>
                        ))
                    ) : (
                        <div className="col-span-full text-center text-gray-500">
                            No mentors match your filters yet.
                        </div>
                    )}
                </div>

                {/* Pagination */}
                <div className="mt-8 flex gap-2 flex-wrap">
                    {mentors.links.map((link, index) => (
                        <button
                            key={index}
                            disabled={!link.url}
                            onClick={() => router.visit(link.url)}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                            className={`px-3 py-1 border rounded ${
                                link.active ? 'bg-blue-900 text-white' : ''
                            }`}
                        />
                    ))}
                </div>

            </div>
        </AuthenticatedLayout>
    );
}