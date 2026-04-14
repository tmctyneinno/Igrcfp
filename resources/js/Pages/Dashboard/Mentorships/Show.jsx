import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MentorshipShow({ auth, mentorship, updates }) {

    const { data, setData, post, processing, errors, reset } = useForm({
        type: 'milestone',
        title: '',
        scheduled_at: '',
        content: '',
        rating: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('dashboard.mentorships.updates.store', mentorship.id), {
            onSuccess: () => reset(),
        });
    };

    const markComplete = () => {
        post(route('dashboard.mentorships.complete', mentorship.id));
    };

    const sections = {
        milestone: 'Milestones',
        session: 'Sessions',
        note: 'Notes',
        feedback: 'Feedback',
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

                {/* Header */}
                <div className="bg-white rounded-2xl border p-6">
                    <div className="flex justify-between">

                        <div>
                            <h2 className="text-2xl font-bold">
                                Mentorship with {mentorship.mentor_name}
                            </h2>
                            <p className="text-sm text-gray-600">
                                Mentee: {mentorship.mentee_name}
                            </p>
                        </div>

                        <div className="text-sm">
                            <p>Status: <b>{mentorship.status}</b></p>
                            <p>Started: {mentorship.started_at || 'N/A'}</p>
                        </div>

                    </div>

                    <div className="mt-4 flex gap-3">
                        <button
                            onClick={markComplete}
                            className="bg-emerald-600 text-white px-5 py-2 rounded-lg"
                        >
                            Mark as Completed
                        </button>

                        <Link
                            href={route('dashboard.mentorships.index')}
                            className="bg-slate-900 text-white px-5 py-2 rounded-lg"
                        >
                            Back
                        </Link>
                    </div>
                </div>

                {/* Add Update */}
                <div className="bg-white rounded-2xl border p-6">
                    <h3 className="text-lg font-semibold mb-4">Add Update</h3>

                    <form onSubmit={submit} className="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <select
                            value={data.type}
                            onChange={(e) => setData('type', e.target.value)}
                            className="md:col-span-2 border px-4 py-2 rounded-lg"
                        >
                            <option value="milestone">Milestone</option>
                            <option value="session">Session</option>
                            <option value="note">Note</option>
                            <option value="feedback">Feedback</option>
                        </select>

                        <input
                            type="text"
                            placeholder="Title"
                            value={data.title}
                            onChange={(e) => setData('title', e.target.value)}
                            className="border px-4 py-2 rounded-lg"
                        />

                        <input
                            type="datetime-local"
                            value={data.scheduled_at}
                            onChange={(e) => setData('scheduled_at', e.target.value)}
                            className="border px-4 py-2 rounded-lg"
                        />

                        <textarea
                            placeholder="Details"
                            value={data.content}
                            onChange={(e) => setData('content', e.target.value)}
                            className="md:col-span-2 border px-4 py-2 rounded-lg"
                        />

                        <input
                            type="number"
                            min="1"
                            max="5"
                            placeholder="Rating"
                            value={data.rating}
                            onChange={(e) => setData('rating', e.target.value)}
                            className="border px-4 py-2 rounded-lg"
                        />

                        <button
                            type="submit"
                            disabled={processing}
                            className="md:col-span-2 bg-blue-900 text-white px-5 py-2 rounded-lg"
                        >
                            Save Update
                        </button>

                    </form>
                </div>

                {/* Updates Sections */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {Object.entries(sections).map(([type, label]) => (
                        <div key={type} className="bg-white rounded-2xl border p-6">

                            <h3 className="text-lg font-semibold mb-4">{label}</h3>

                            {updates[type]?.length > 0 ? (
                                updates[type].map((u) => (
                                    <div key={u.id} className="border p-4 rounded-xl mb-3">
                                        <p className="font-semibold">
                                            {u.title || label}
                                        </p>

                                        <p className="text-xs text-gray-500">
                                            {u.created_at}
                                        </p>

                                        {u.scheduled_at && (
                                            <p className="text-xs text-gray-500">
                                                Scheduled: {u.scheduled_at}
                                            </p>
                                        )}

                                        {u.rating && (
                                            <p className="text-xs text-gray-500">
                                                Rating: {u.rating}/5
                                            </p>
                                        )}

                                        <p className="text-sm mt-2">
                                            {u.content}
                                        </p>
                                    </div>
                                ))
                            ) : (
                                <p className="text-sm text-gray-500">
                                    No {label.toLowerCase()} yet.
                                </p>
                            )}

                        </div>
                    ))}

                </div>

            </div>
        </AuthenticatedLayout>
    );
}