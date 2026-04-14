import React from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MentorShow({ auth, mentor }) {

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

                <div className="bg-white rounded-2xl border shadow-md p-8">

                    {/* Header */}
                    <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                        <div className="flex items-center gap-4">
                            <div className="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl font-semibold text-blue-900">
                                {mentor.name.charAt(0).toUpperCase()}
                            </div>

                            <div>
                                <h2 className="text-2xl font-bold text-gray-900">
                                    {mentor.name}
                                </h2>

                                <p className="text-sm text-gray-500">
                                    {mentor.title || 'Mentor'}
                                </p>

                                <p className="text-sm text-gray-600">
                                    {mentor.domain || 'General'} • {mentor.region || 'Global'}
                                </p>
                            </div>
                        </div>

                        <div className="text-sm text-gray-600">
                            <p><b>Rating:</b> {Number(mentor.rating).toFixed(1)}</p>
                            <p><b>Completed:</b> {mentor.completed}</p>
                        </div>

                    </div>

                    {/* Bio + Expertise */}
                    <div className="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <h3 className="text-lg font-semibold mb-2">Bio</h3>
                            <p className="text-sm text-gray-600">
                                {mentor.bio || 'No bio provided.'}
                            </p>
                        </div>

                        <div>
                            <h3 className="text-lg font-semibold mb-2">Expertise Summary</h3>
                            <p className="text-sm text-gray-600">
                                {mentor.expertise_summary || 'No expertise summary provided.'}
                            </p>
                        </div>

                    </div>

                    {/* Tags */}
                    <div className="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <h4 className="text-sm font-semibold mb-2">Languages</h4>
                            <div className="flex flex-wrap gap-2">
                                {mentor.languages.map((lang, i) => (
                                    <span key={i} className="text-xs bg-slate-100 px-3 py-1 rounded-full">
                                        {lang}
                                    </span>
                                ))}
                            </div>
                        </div>

                        <div>
                            <h4 className="text-sm font-semibold mb-2">Skills</h4>
                            <div className="flex flex-wrap gap-2">
                                {mentor.skills.map((skill, i) => (
                                    <span key={i} className="text-xs bg-blue-50 text-blue-800 px-3 py-1 rounded-full">
                                        {skill}
                                    </span>
                                ))}
                            </div>
                        </div>

                        <div>
                            <h4 className="text-sm font-semibold mb-2">Certifications</h4>
                            <div className="flex flex-wrap gap-2">
                                {mentor.certifications.map((cert, i) => (
                                    <span key={i} className="text-xs bg-emerald-50 text-emerald-800 px-3 py-1 rounded-full">
                                        {cert}
                                    </span>
                                ))}
                            </div>
                        </div>

                    </div>

                    {/* Actions */}
                    <div className="mt-8 flex flex-wrap gap-3">

                        {mentor.availability_status === 'not_taking' ? (
                            <span className="bg-amber-50 text-amber-800 px-4 py-2 rounded-lg text-sm font-semibold">
                                Not accepting applications
                            </span>
                        ) : mentor.slots_left <= 0 ? (
                            <span className="bg-rose-50 text-rose-800 px-4 py-2 rounded-lg text-sm font-semibold">
                                No capacity available
                            </span>
                        ) : (
                            <Link
                                href={route('dashboard.mentors.apply', mentor.id)}
                                className="px-5 py-2.5 rounded-lg bg-blue-900 text-white text-sm font-semibold"
                            >
                                Apply for Mentorship
                            </Link>
                        )}

                        <Link
                            href={route('dashboard.mentors.index')}
                            className="px-5 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold"
                        >
                            Back to Mentors
                        </Link>

                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}