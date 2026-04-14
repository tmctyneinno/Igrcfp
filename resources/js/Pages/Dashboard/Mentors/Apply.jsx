import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Apply({ auth, existingApplication }) {

    const { data, setData, post, processing, errors } = useForm({
        title: '',
        domain: '',
        region: '',
        country: '',
        bio: '',
        expertise_summary: '',
        availability_status: 'taking',
        max_mentees: 3,
        languages: '',
        skills: '',
        certifications: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('dashboard.mentors.apply-to-become.store'));
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="bg-white rounded-2xl border border-slate-200 shadow-md p-8">

                    <h2 className="text-2xl font-bold text-gray-900 mb-2">
                        Apply to Become a Mentor
                    </h2>
                    <p className="text-sm text-gray-600 mb-6">
                        Share your expertise so we can match you with mentees.
                    </p>

                    {/* Existing Application */}
                    {existingApplication && (
                        <div className="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-gray-700">
                            Latest application status:
                            <span className="font-semibold ml-1">
                                {existingApplication.status}
                            </span>

                            {existingApplication.admin_feedback && (
                                <p className="mt-2 text-gray-600">
                                    Admin feedback: {existingApplication.admin_feedback}
                                </p>
                            )}
                        </div>
                    )}

                    {/* Form */}
                    <form onSubmit={submit} className="space-y-4">

                        {/* Title + Domain */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input
                                    type="text"
                                    placeholder="Title"
                                    value={data.title}
                                    onChange={e => setData('title', e.target.value)}
                                    className="w-full rounded-lg border px-4 py-2"
                                />
                                {errors.title && <p className="text-red-500 text-sm">{errors.title}</p>}
                            </div>

                            <div>
                                <input
                                    type="text"
                                    placeholder="Domain"
                                    value={data.domain}
                                    onChange={e => setData('domain', e.target.value)}
                                    className="w-full rounded-lg border px-4 py-2"
                                />
                                {errors.domain && <p className="text-red-500 text-sm">{errors.domain}</p>}
                            </div>
                        </div>

                        {/* Region + Country */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input
                                type="text"
                                placeholder="Region"
                                value={data.region}
                                onChange={e => setData('region', e.target.value)}
                                className="w-full rounded-lg border px-4 py-2"
                            />

                            <input
                                type="text"
                                placeholder="Country"
                                value={data.country}
                                onChange={e => setData('country', e.target.value)}
                                className="w-full rounded-lg border px-4 py-2"
                            />
                        </div>

                        {/* Bio */}
                        <textarea
                            placeholder="Bio"
                            value={data.bio}
                            onChange={e => setData('bio', e.target.value)}
                            className="w-full rounded-lg border px-4 py-2"
                        />
                        {errors.bio && <p className="text-red-500 text-sm">{errors.bio}</p>}

                        {/* Expertise */}
                        <textarea
                            placeholder="Expertise Summary"
                            value={data.expertise_summary}
                            onChange={e => setData('expertise_summary', e.target.value)}
                            className="w-full rounded-lg border px-4 py-2"
                        />

                        {/* Availability + Max */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select
                                value={data.availability_status}
                                onChange={e => setData('availability_status', e.target.value)}
                                className="w-full rounded-lg border px-4 py-2"
                            >
                                <option value="taking">Taking mentees</option>
                                <option value="not_taking">Not taking</option>
                            </select>

                            <input
                                type="number"
                                value={data.max_mentees}
                                onChange={e => setData('max_mentees', e.target.value)}
                                className="w-full rounded-lg border px-4 py-2"
                            />
                        </div>

                        {/* Optional Fields */}
                        <input
                            type="text"
                            placeholder="Languages (comma separated)"
                            value={data.languages}
                            onChange={e => setData('languages', e.target.value)}
                            className="w-full rounded-lg border px-4 py-2"
                        />

                        <input
                            type="text"
                            placeholder="Skills (comma separated)"
                            value={data.skills}
                            onChange={e => setData('skills', e.target.value)}
                            className="w-full rounded-lg border px-4 py-2"
                        />

                        <input
                            type="text"
                            placeholder="Certifications (comma separated)"
                            value={data.certifications}
                            onChange={e => setData('certifications', e.target.value)}
                            className="w-full rounded-lg border px-4 py-2"
                        />

                        {/* Buttons */}
                        <div className="flex gap-3 pt-2">
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-5 py-2.5 rounded-lg bg-blue-900 text-white"
                            >
                                Submit Application
                            </button>

                            <Link
                                href={route('dashboard.mentors.index')}
                                className="px-5 py-2.5 rounded-lg bg-slate-900 text-white"
                            >
                                Back to Mentors
                            </Link>
                        </div>

                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}