import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { CalendarDays, MapPin } from "lucide-react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

const getStatusStyles = (status) => {
    const styles = {
        available: "bg-emerald-50 text-emerald-700 ring-emerald-200",
        few_seats: "bg-amber-50 text-amber-700 ring-amber-200",
        sold_out: "bg-rose-50 text-rose-700 ring-rose-200",
    };
    return styles[status] || "bg-slate-50 text-slate-700 ring-slate-200";
};

const getStatusLabel = (status) => {
    const labels = {
        available: "Seats available",
        few_seats: "Few seats left",
        sold_out: "Sold out",
        not_set: "Registration TBA",
    };
    return labels[status] || "Registration TBA";
};

// Check if event is past
const isEventPast = (event) => {
    if (!event?.event_date) return false;
    try {
        const eventDate = new Date(event.event_end_date || event.event_date);
        return eventDate < new Date();
    } catch {
        return false;
    }
};

const formatTime = (time) => {
    if (!time) return "";
    if (time.includes("AM") || time.includes("PM")) return time;
    const [hours, minutes = "00"] = time.split(":");
    const hour = Number.parseInt(hours, 10);
    if (Number.isNaN(hour)) return time;
    const suffix = hour >= 12 ? "PM" : "AM";
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes.slice(0, 2)} ${suffix}`;
};

const getEventImage = (event) => {
    if (event?.image_url) return event.image_url;
    if (!event?.image) return "/images/default-event.jpg";
    if (event.image.match(/^(https?:)?\/\//) || event.image.startsWith("/")) return event.image;
    return `/storage/${event.image}`;
};

const getEventTime = (event) => {
    if (event?.event_time && event.event_time !== "Time not set") return event.event_time;
    const startTime = formatTime(event?.start_time);
    const endTime = formatTime(event?.end_time);
    if (startTime && endTime) return `${startTime} - ${endTime}`;
    return startTime || endTime || "Time TBA";
};

export default function GlobalEvents({ events = [] }) {
    const mainEvent = events[0] || null;
    const listEvents = events.slice(1);
    const hasEvents = events.length > 0;

    return (
        <section className="bg-gray-50 py-16">
            <div className="max-w-7xl mx-auto px-6">

                {/* Header */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-10"
                >
                    <div className="relative inline-flex items-center mb-3">
                        <motion.span
                            initial={{ width: 0 }}
                            whileInView={{ width: 48 }}
                            transition={{ duration: 0.5, ease: "easeOut" }}
                            viewport={{ once: true }}
                            className="absolute left-0 top-1/2 h-px bg-[#0A2463]"
                        />
                        <span className="text-sm tracking-widest text-gray-500 pl-14 uppercase">
                            Latest News & Insights
                        </span>
                    </div>
                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900">
                        UPCOMING <span className="text-[#0A2463]">EVENTS</span>
                    </h2>
                </motion.div>

                {hasEvents ? (
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {/* Left: Main Featured Event */}
                        <motion.div
                            variants={scaleIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-white border border-gray-200 rounded-lg p-6 relative overflow-hidden"
                        >
                            <div className="absolute left-0 top-0 bottom-0 w-1 bg-[#0A2463]"></div>

                            <div className="pl-2">
                                {/* Simple text indicator only */}
                                {mainEvent && isEventPast(mainEvent) && (
                                    <p className="text-sm text-gray-500 mb-2 font-normal">
                                        • This event has ended
                                    </p>
                                )}

                                <div className="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    <CalendarDays className="w-4 h-4 text-gray-500" />
                                    <span>{mainEvent?.event_date || "Date TBA"}</span>
                                </div>

                                <h3 className="text-xl md:text-2xl font-bold text-gray-900 mb-3">
                                    <Link href={`/events/${mainEvent?.slug || "#"}`} className="hover:text-[#0A2463] transition-colors">
                                        {mainEvent?.title || "Event Title"}
                                    </Link>
                                </h3>

                                <div className="flex items-center gap-2 text-sm text-gray-600 mb-5">
                                    <MapPin className="w-4 h-4 text-gray-500" />
                                    <span>{mainEvent?.venue || mainEvent?.location || "Location TBA"}</span>
                                </div>

                                <div className="flex flex-wrap gap-2 mb-6">
                                    {["Keynotes", "Workshops", "Awards", "Networking"].map((tag) => (
                                        <span key={tag} className="px-3 py-1 text-xs border border-gray-200 rounded text-gray-600">
                                            {tag}
                                        </span>
                                    ))}
                                </div>

                                <Link
                                    href={mainEvent?.meeting_link || "#"}
                                    className="inline-block bg-[#0A2463] text-white px-5 py-2 text-sm font-medium rounded hover:bg-[#081E52] transition-colors"
                                >
                                    Register Interest {mainEvent?.meeting_link ? "→" : ""}
                                </Link>
                            </div>
                        </motion.div>

                        {/* Right: List of Smaller Events */}
                        <motion.div
                            variants={fadeLeft}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="bg-white border border-gray-200 rounded-lg divide-y divide-gray-200"
                        >
                            {listEvents.length > 0 ? (
                                listEvents.map((event, index) => (
                                    <Link
                                        key={event.id || index}
                                        href={`/events/${event.slug || "#"}`}
                                        className="block p-4 hover:bg-gray-50 transition-colors"
                                    >
                                        <div className="text-xs text-gray-500 mb-1">
                                            {event.event_date || "Date TBA"} • {event.location || "Location TBA"}
                                            {/* Simple text indicator only */}
                                            {isEventPast(event) && <span className="ml-2 text-gray-400">(Ended)</span>}
                                        </div>
                                        <h4 className="text-sm md:text-base font-medium text-gray-900 hover:text-[#0A2463] transition-colors">
                                            {event.title}
                                        </h4>
                                    </Link>
                                ))
                            ) : (
                                <div className="p-6 text-center text-gray-500">
                                    More events coming soon
                                </div>
                            )}
                        </motion.div>

                    </div>
                ) : (
                    <div className="bg-white border border-gray-200 rounded-lg p-10 text-center">
                        <h3 className="text-xl font-semibold text-gray-800 mb-2">No Upcoming Events</h3>
                        <p className="text-gray-600 mb-4">Check back soon for new summits, webinars and workshops.</p>
                        <Link
                            href="/events"
                            className="inline-block bg-[#0A2463] text-white px-5 py-2 rounded hover:bg-[#081E52] transition"
                        >
                            View Event Calendar
                        </Link>
                    </div>
                )}

            </div>
        </section>
    );
}