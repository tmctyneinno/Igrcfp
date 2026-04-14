import React from "react";
import { Head, Link } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function MembershipDashboard() {
    return (
        <AuthenticatedLayout>
            <Head title="Membership & Mentorship" />

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-gray-900">Membership & Mentorship</h1>
                    <p className="mt-2 text-gray-600">
                        Manage your membership status, discover mentors, and track mentorship applications.
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div className="bg-white rounded-2xl shadow-md p-6 border border-slate-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Membership Plans</h3>
                        <p className="text-sm text-gray-600 mb-4">
                            Explore available tiers and choose the plan that fits your goals.
                        </p>
                        <Link
                            href={route("dashboard.memberships.index")}
                            className="inline-flex items-center px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition"
                        >
                            View Plans
                        </Link>
                    </div>

                    <div className="bg-white rounded-2xl shadow-md p-6 border border-slate-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Membership Status</h3>
                        <p className="text-sm text-gray-600 mb-4">
                            Track your approval status, expiry dates, and next steps.
                        </p>
                        <Link
                            href={route("dashboard.memberships.status")}
                            className="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition"
                        >
                            View Status
                        </Link>
                    </div>

                    <div className="bg-white rounded-2xl shadow-md p-6 border border-slate-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Mentor Discovery</h3>
                        <p className="text-sm text-gray-600 mb-4">
                            Browse active mentors, filter by region and expertise, and apply.
                        </p>
                        <Link
                            href={route("dashboard.mentors.index")}
                            className="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-900 text-white text-sm font-semibold hover:bg-indigo-800 transition"
                        >
                            Find Mentors
                        </Link>
                    </div>

                    <div className="bg-white rounded-2xl shadow-md p-6 border border-slate-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Mentorship Dashboard</h3>
                        <p className="text-sm text-gray-600 mb-4">
                            Review mentorship applications, sessions, notes, and milestones.
                        </p>
                        <Link
                            href={route("dashboard.mentorships.index")}
                            className="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-700 text-white text-sm font-semibold hover:bg-emerald-600 transition"
                        >
                            Open Dashboard
                        </Link>
                    </div>

                    <div className="bg-white rounded-2xl shadow-md p-6 border border-slate-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Become a Mentor</h3>
                        <p className="text-sm text-gray-600 mb-4">
                            Submit your mentor profile for review if you have the mentor membership.
                        </p>
                        <Link
                            href={route("dashboard.mentors.apply-to-become")}
                            className="inline-flex items-center px-4 py-2 rounded-lg bg-orange-600 text-white text-sm font-semibold hover:bg-orange-500 transition"
                        >
                            Apply Now
                        </Link>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
