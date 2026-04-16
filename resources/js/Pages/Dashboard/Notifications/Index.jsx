import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function NotificationsIndex({ auth, settings, unreadCount, recentNotifications = [] }) {
    const { data, setData, post, processing } = useForm({
        email_notifications: Boolean(settings?.email_notifications),
        sms_notifications: Boolean(settings?.sms_notifications),
        newsletter_subscription: Boolean(settings?.newsletter_subscription),
        marketing_emails: Boolean(settings?.marketing_emails),
    });

    const submit = (event) => {
        event.preventDefault();
        post(route('notifications.settings.update'));
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Notifications" />

            <div className="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h1 className="text-2xl font-bold text-slate-900">Notification Settings</h1>
                    <p className="mt-2 text-sm text-slate-600">
                        Unread mentorship messages: <span className="font-semibold">{unreadCount}</span>
                    </p>
                </div>

                <form onSubmit={submit} className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div className="space-y-4">
                        <label className="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <span className="text-sm font-medium text-slate-900">Email notifications</span>
                            <input
                                type="checkbox"
                                checked={data.email_notifications}
                                onChange={(e) => setData('email_notifications', e.target.checked)}
                                className="h-4 w-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500"
                            />
                        </label>

                        <label className="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <span className="text-sm font-medium text-slate-900">SMS notifications</span>
                            <input
                                type="checkbox"
                                checked={data.sms_notifications}
                                onChange={(e) => setData('sms_notifications', e.target.checked)}
                                className="h-4 w-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500"
                            />
                        </label>

                        <label className="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <span className="text-sm font-medium text-slate-900">Newsletter subscription</span>
                            <input
                                type="checkbox"
                                checked={data.newsletter_subscription}
                                onChange={(e) => setData('newsletter_subscription', e.target.checked)}
                                className="h-4 w-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500"
                            />
                        </label>

                        <label className="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <span className="text-sm font-medium text-slate-900">Marketing emails</span>
                            <input
                                type="checkbox"
                                checked={data.marketing_emails}
                                onChange={(e) => setData('marketing_emails', e.target.checked)}
                                className="h-4 w-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500"
                            />
                        </label>
                    </div>

                    <button
                        type="submit"
                        disabled={processing}
                        className="mt-6 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        Save Settings
                    </button>
                </form>

                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 className="text-lg font-semibold text-slate-900">Recent Mentorship Notifications</h2>
                    <div className="mt-4 space-y-3">
                        {recentNotifications.length > 0 ? (
                            recentNotifications.map((item) => (
                                <div key={item.id} className="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <p className="text-sm font-semibold text-slate-900">
                                        {item.sender_name} messaged you
                                        {item.counterparty_name ? ` in mentorship with ${item.counterparty_name}` : ''}
                                    </p>
                                    <p className="mt-1 text-sm text-slate-600">{item.preview}</p>
                                    <div className="mt-2 flex items-center justify-between">
                                        <p className="text-xs text-slate-500">{item.created_at}</p>
                                        <Link
                                            href={route('dashboard.mentorships.show', item.mentorship_id)}
                                            className="text-xs font-semibold text-sky-700 hover:text-sky-800"
                                        >
                                            Open chat
                                        </Link>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <p className="text-sm text-slate-600">No mentorship notifications yet.</p>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
