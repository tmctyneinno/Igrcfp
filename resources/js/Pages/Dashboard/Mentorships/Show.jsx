import React, { useEffect, useMemo, useRef, useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function MentorshipShow({ auth, mentorship, updates, messages = [], currentUserId }) {
    const { data, setData, post, processing, reset } = useForm({
        type: 'milestone',
        title: '',
        scheduled_at: '',
        content: '',
        rating: '',
    });

    const [chatMessage, setChatMessage] = useState('');
    const [chatError, setChatError] = useState('');
    const [chatProcessing, setChatProcessing] = useState(false);
    const [liveMessages, setLiveMessages] = useState(messages);
    const chatContainerRef = useRef(null);
    const lastMessageIdRef = useRef(messages.length ? messages[messages.length - 1].id : 0);

    useEffect(() => {
        setLiveMessages(messages);
        lastMessageIdRef.current = messages.length ? messages[messages.length - 1].id : 0;
    }, [messages]);

    useEffect(() => {
        const fetchLatestMessages = async () => {
            try {
                const response = await window.axios.get(route('dashboard.mentorships.messages.index', mentorship.id), {
                    params: {
                        since_id: lastMessageIdRef.current || 0,
                    },
                });

                const incomingMessages = response?.data?.messages || [];
                if (!incomingMessages.length) {
                    return;
                }

                setLiveMessages((previous) => {
                    const existingIds = new Set(previous.map((item) => item.id));
                    const uniqueIncoming = incomingMessages.filter((item) => !existingIds.has(item.id));
                    const merged = [...previous, ...uniqueIncoming];
                    const last = merged[merged.length - 1];
                    lastMessageIdRef.current = last ? last.id : 0;
                    return merged;
                });
            } catch (error) {
                // Keep chat usable even if polling fails temporarily.
            }
        };

        fetchLatestMessages();
        const interval = setInterval(fetchLatestMessages, 2500);

        return () => clearInterval(interval);
    }, [mentorship.id]);

    useEffect(() => {
        if (!chatContainerRef.current) {
            return;
        }

        chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
    }, [liveMessages]);

    const submit = (e) => {
        e.preventDefault();

        post(route('dashboard.mentorships.updates.store', mentorship.id), {
            onSuccess: () => reset(),
        });
    };

    const markComplete = () => {
        post(route('dashboard.mentorships.complete', mentorship.id));
    };

    const sendMessage = async (e) => {
        e.preventDefault();
        setChatError('');

        if (!chatMessage.trim()) {
            setChatError('Please enter a message before sending.');
            return;
        }

        setChatProcessing(true);

        try {
            const response = await window.axios.post(route('dashboard.mentorships.messages.store', mentorship.id), {
                message: chatMessage,
            });

            const newMessage = response?.data?.message;
            if (newMessage) {
                setLiveMessages((previous) => {
                    if (previous.some((item) => item.id === newMessage.id)) {
                        return previous;
                    }

                    const merged = [...previous, newMessage];
                    const last = merged[merged.length - 1];
                    lastMessageIdRef.current = last ? last.id : 0;
                    return merged;
                });
            }

            setChatMessage('');
        } catch (error) {
            const validationError = error?.response?.data?.errors?.message?.[0];
            const fallbackError = error?.response?.data?.message || 'Unable to send your message right now.';
            setChatError(validationError || fallbackError);
        } finally {
            setChatProcessing(false);
        }
    };

    const sections = useMemo(() => ({
        milestone: 'Milestones',
        session: 'Sessions',
        note: 'Notes',
        feedback: 'Feedback',
    }), []);

    return (
        <AuthenticatedLayout auth={auth}>
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div className="bg-white rounded-2xl border p-6">
                    <div className="flex justify-between">
                        <div>
                            <h2 className="text-2xl font-bold">
                                Mentorship with {mentorship.mentor_name}
                            </h2>
                            <p className="text-sm text-gray-600">
                                Mentee: {mentorship.mentee_name}
                            </p>
                            <p className="text-sm text-gray-600">
                                Preferred communication: <b>{mentorship.communication_method || 'Not specified'}</b>
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

                <div className="bg-white rounded-2xl border p-6">
                    <h3 className="text-lg font-semibold mb-2">
                        Mentor/Mentee Chat
                    </h3>
                    <p className="text-sm text-gray-600 mb-4">
                        Use this chat space for ongoing communication and follow-ups.
                    </p>

                    <div ref={chatContainerRef} className="max-h-96 overflow-y-auto border rounded-xl p-4 bg-slate-50 space-y-3">
                        {liveMessages.length > 0 ? (
                            liveMessages.map((msg) => {
                                const isMine = msg.sender_id === currentUserId;

                                return (
                                    <div
                                        key={msg.id}
                                        className={`flex ${isMine ? 'justify-end' : 'justify-start'}`}
                                    >
                                        <div className={`max-w-[85%] rounded-xl px-4 py-2 ${isMine ? 'bg-blue-900 text-white' : 'bg-white border text-gray-900'}`}>
                                            <p className={`text-xs mb-1 ${isMine ? 'text-blue-100' : 'text-gray-500'}`}>
                                                {msg.sender_name} - {msg.created_at}
                                            </p>
                                            <p className="text-sm whitespace-pre-wrap">{msg.message}</p>
                                        </div>
                                    </div>
                                );
                            })
                        ) : (
                            <p className="text-sm text-gray-500">No chat messages yet. Start the conversation below.</p>
                        )}
                    </div>

                    <form onSubmit={sendMessage} className="mt-4">
                        <textarea
                            value={chatMessage}
                            onChange={(e) => setChatMessage(e.target.value)}
                            className="w-full border px-4 py-3 rounded-xl"
                            rows={3}
                            placeholder="Type a message..."
                        />
                        {chatError && (
                            <p className="text-sm text-red-500 mt-1">{chatError}</p>
                        )}
                        <button
                            type="submit"
                            disabled={chatProcessing}
                            className="mt-3 bg-blue-900 text-white px-5 py-2 rounded-lg disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            {chatProcessing ? 'Sending...' : 'Send Message'}
                        </button>
                    </form>
                </div>

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
