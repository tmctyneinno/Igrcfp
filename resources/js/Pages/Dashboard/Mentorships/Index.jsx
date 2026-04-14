import React from 'react';
import { Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MentorshipDashboard({
    auth,
    mentorProfile,
    menteeApplications,
    mentorApplications,
    mentorMentorships,
    menteeMentorships,
}) {

    const { post, setData } = useForm({
        decision: '',
        mentor_feedback: '',
    });

    const decide = (id, decision) => {
        post(route('dashboard.mentorships.decide', id), {
            data: { decision },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

                {/* Header */}
                <div>
                    <h2 className="text-3xl font-bold text-gray-900">
                        Mentorship Dashboard
                    </h2>
                    <p className="mt-2 text-gray-600">
                        Track your applications and active mentorships.
                    </p>
                </div>

                {/* Mentee Section */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {/* Applications */}
                    <div className="bg-white rounded-2xl border p-6">
                        <h3 className="text-lg font-semibold mb-4">
                            Your Applications (Mentee)
                        </h3>

                        {menteeApplications.length > 0 ? (
                            menteeApplications.map(app => (
                                <div key={app.id} className="border p-4 rounded-xl mb-3">
                                    <p>Mentor: <b>{app.mentor_name}</b></p>
                                    <p>Status: {app.status}</p>
                                    <p className="text-xs">{app.created_at}</p>
                                </div>
                            ))
                        ) : (
                            <p className="text-sm text-gray-500">
                                No applications submitted yet.
                            </p>
                        )}
                    </div>

                    {/* Mentorships */}
                    <div className="bg-white rounded-2xl border p-6">
                        <h3 className="text-lg font-semibold mb-4">
                            Your Mentorships (Mentee)
                        </h3>

                        {menteeMentorships.length > 0 ? (
                            menteeMentorships.map(m => (
                                <div key={m.id} className="border p-4 rounded-xl flex justify-between mb-3">
                                    <div>
                                        <p>Mentor: <b>{m.mentor_name}</b></p>
                                        <p>Status: {m.status}</p>
                                    </div>

                                    <Link
                                        href={route('dashboard.mentorships.show', m.id)}
                                        className="text-blue-900"
                                    >
                                        View
                                    </Link>
                                </div>
                            ))
                        ) : (
                            <p className="text-sm text-gray-500">
                                No active mentorships yet.
                            </p>
                        )}
                    </div>
                </div>

                {/* Mentor Section */}
                {mentorProfile && (
                    <>
                        {/* Applications to You */}
                        <div className="bg-white rounded-2xl border p-6">
                            <h3 className="text-lg font-semibold mb-4">
                                Applications to You (Mentor)
                            </h3>

                            {mentorApplications.length > 0 ? (
                                mentorApplications.map(app => (
                                    <div key={app.id} className="border p-4 rounded-xl mb-3">

                                        <p>Mentee: <b>{app.mentee_name}</b></p>
                                        <p className="text-sm">{app.goals}</p>
                                        <p>Status: {app.status}</p>

                                        {app.status === 'pending' && (
                                            <div className="mt-3 flex gap-2">
                                                <button
                                                    onClick={() => decide(app.id, 'accepted')}
                                                    className="bg-emerald-600 text-white px-3 py-1 rounded"
                                                >
                                                    Accept
                                                </button>

                                                <button
                                                    onClick={() => decide(app.id, 'declined')}
                                                    className="bg-rose-600 text-white px-3 py-1 rounded"
                                                >
                                                    Decline
                                                </button>
                                            </div>
                                        )}
                                    </div>
                                ))
                            ) : (
                                <p className="text-sm text-gray-500">
                                    No applications yet.
                                </p>
                            )}
                        </div>

                        {/* Active Mentorships */}
                        <div className="bg-white rounded-2xl border p-6">
                            <h3 className="text-lg font-semibold mb-4">
                                Active Mentorships (Mentor)
                            </h3>

                            {mentorMentorships.length > 0 ? (
                                mentorMentorships.map(m => (
                                    <div key={m.id} className="border p-4 rounded-xl flex justify-between mb-3">
                                        <div>
                                            <p>Mentee: <b>{m.mentee_name}</b></p>
                                            <p>Status: {m.status}</p>
                                        </div>

                                        <Link
                                            href={route('dashboard.mentorships.show', m.id)}
                                            className="text-blue-900"
                                        >
                                            View
                                        </Link>
                                    </div>
                                ))
                            ) : (
                                <p className="text-sm text-gray-500">
                                    No active mentorships yet.
                                </p>
                            )}
                        </div>
                    </>
                )}

            </div>
        </AuthenticatedLayout>
    );
}