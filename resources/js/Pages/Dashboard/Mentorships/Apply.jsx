import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Apply({ auth, mentor }) {

    const { data, setData, post, processing, errors } = useForm({
        goals: '',
        preferred_duration: '',
        availability: '',
        communication_method: '',
        notes: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('dashboard.mentors.apply.store', mentor.id));
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

                <div className="bg-white rounded-2xl border shadow-md p-8">

                    <h2 className="text-2xl font-bold text-gray-900 mb-2">
                        Apply to {mentor.name}
                    </h2>

                    <p className="text-sm text-gray-600 mb-6">
                        Share your goals and availability to start the mentorship process.
                    </p>

                    <form onSubmit={submit} className="space-y-4">

                        {/* Goals */}
                        <div>
                            <textarea
                                placeholder="Goals"
                                value={data.goals}
                                onChange={(e) => setData('goals', e.target.value)}
                                className="w-full rounded-lg border px-4 py-2"
                                required
                            />
                            {errors.goals && <p className="text-red-500 text-sm">{errors.goals}</p>}
                        </div>

                        {/* Duration + Availability */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <input
                                type="text"
                                placeholder="Preferred Duration (e.g. 3 months)"
                                value={data.preferred_duration}
                                onChange={(e) => setData('preferred_duration', e.target.value)}
                                className="w-full rounded-lg border px-4 py-2"
                            />

                            <input
                                type="text"
                                placeholder="Availability (e.g. Weekends)"
                                value={data.availability}
                                onChange={(e) => setData('availability', e.target.value)}
                                className="w-full rounded-lg border px-4 py-2"
                            />

                        </div>

                        {/* Communication */}
                        <input
                            type="text"
                            placeholder="Communication Method (e.g. Zoom, Email)"
                            value={data.communication_method}
                            onChange={(e) => setData('communication_method', e.target.value)}
                            className="w-full rounded-lg border px-4 py-2"
                        />

                        {/* Notes */}
                        <textarea
                            placeholder="Notes"
                            value={data.notes}
                            onChange={(e) => setData('notes', e.target.value)}
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
                                href={route('dashboard.mentors.show', mentor.id)}
                                className="px-5 py-2.5 rounded-lg bg-slate-900 text-white"
                            >
                                Back to Profile
                            </Link>

                        </div>

                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}