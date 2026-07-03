import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { ArrowRight, CalendarDays, Clock3, MapPin, UsersRound } from "lucide-react";
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

const formatTime = (time) => {
    if (!time) {
        return "";
    }

    if (time.includes("AM") || time.includes("PM")) {
        return time;
    }

    const [hours, minutes = "00"] = time.split(":");
    const hour = Number.parseInt(hours, 10);

    if (Number.isNaN(hour)) {
        return time;
    }

    const suffix = hour >= 12 ? "PM" : "AM";
    const displayHour = hour % 12 || 12;

    return `${displayHour}:${minutes.slice(0, 2)} ${suffix}`;
};

const getEventImage = (event) => {
    if (event?.image_url) {
        return event.image_url;
    }

    if (!event?.image) {
        return "/images/default-event.jpg";
    }

    if (event.image.match(/^(https?:)?\/\//) || event.image.startsWith("/")) {
        return event.image;
    }

    return `/storage/${event.image}`;
};

const getEventTime = (event) => {
    if (event?.event_time && event.event_time !== "Time not set") {
        return event.event_time;
    }

    const startTime = formatTime(event?.start_time);
    const endTime = formatTime(event?.end_time);

    if (startTime && endTime) {
        return `${startTime} - ${endTime}`;
    }

    return startTime || endTime || "Time TBA";
};

export default function GlobalEvents({ events = [] }) {
    const featuredEvent = events[0];
    const supportingEvents = events.slice(1, 3);
    const hasEvents = events.length > 0;

    return (
        <section className="bg-[#f8fafc] py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
            <div className="max-w-7xl mx-auto px-6">
                <div
                    className="flex flex-col lg:flex-row lg:justify-between lg:items-end gap-6 mb-12"
                    data-aos="fade-up"
                >
                    <div className="max-w-3xl">
                        <div className="relative inline-flex items-center mb-3">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 64 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="absolute left-0 top-1/2 h-px bg-emerald-500"
                            />
                            <span className="text-sm tracking-widest text-slate-500 pl-20 uppercase">
                                Events & Summits
                            </span>
                        </div>

                        <h2 className="text-3xl xl:text-5xl font-bold text-slate-950 mb-5">
                            Join the conversations shaping GRC and financial crime prevention
                        </h2>
                        <p className="text-slate-600 leading-relaxed max-w-2xl">
                            Connect with regulators, practitioners, and institutional leaders through
                            IGRCFP conferences, workshops, forums, and specialist sessions.
                        </p>
                    </div>

                    <Link
                        href="/events"
                        className="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-950 text-white rounded-full font-semibold hover:bg-emerald-700 transition w-fit shadow-sm"
                    >
                        View all events
                        <ArrowRight className="h-4 w-4" aria-hidden="true" />
                    </Link>
                </div>

                {hasEvents ? (
                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                        <motion.article
                            variants={scaleIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="lg:col-span-7 group bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300"
                        >
                            <div className="relative min-h-[560px]">
                                <img
                                    src={getEventImage(featuredEvent)}
                                    alt={featuredEvent.title}
                                    className="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    onError={(event) => {
                                        event.currentTarget.src = "/images/default-event.jpg";
                                    }}
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent" />
                                <div className="absolute inset-x-0 bottom-0 p-6 md:p-8 text-white">
                                    <div className="flex flex-wrap items-center gap-2 mb-4">
                                        {featuredEvent.is_featured && (
                                            <span className="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/25 backdrop-blur">
                                                Featured
                                            </span>
                                        )}
                                        <span className={`inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ${getStatusStyles(featuredEvent.registration_status)}`}>
                                            {getStatusLabel(featuredEvent.registration_status)}
                                        </span>
                                    </div>

                                    <h3 className="text-2xl md:text-4xl font-bold leading-tight mb-4 line-clamp-3">
                                        <Link href={`/events/${featuredEvent.slug}`} className="hover:text-emerald-200 transition">
                                            {featuredEvent.title}
                                        </Link>
                                    </h3>

                                    <p className="text-slate-200 leading-relaxed max-w-2xl mb-6 line-clamp-3">
                                        {featuredEvent.short_description}
                                    </p>

                                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm text-slate-100">
                                        <div className="flex items-center gap-2">
                                            <CalendarDays className="h-4 w-4 text-emerald-300" aria-hidden="true" />
                                            <span>{featuredEvent.event_date || "Date TBA"}</span>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <Clock3 className="h-4 w-4 text-emerald-300" aria-hidden="true" />
                                            <span>{getEventTime(featuredEvent)}</span>(UK Time)
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <MapPin className="h-4 w-4 text-emerald-300" aria-hidden="true" />
                                            <span className="truncate">{featuredEvent.venue || featuredEvent.location || "Venue TBA"}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </motion.article>

                        <motion.div
                            variants={fadeLeft}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="lg:col-span-5 flex flex-col gap-4"
                        >
                            {supportingEvents.map((event) => (
                                <article
                                    key={event.id}
                                    className="group bg-white border border-slate-200 rounded-lg p-4 shadow-sm hover:border-emerald-200 hover:shadow-lg transition"
                                >
                                    <div className="flex gap-4">
                                        <img
                                            src={getEventImage(event)}
                                            alt={event.title}
                                            className="h-28 w-28 sm:h-32 sm:w-36 rounded-md object-cover bg-slate-100 flex-shrink-0"
                                            onError={(imageEvent) => {
                                                imageEvent.currentTarget.src = "/images/default-event.jpg";
                                            }}
                                        />
                                        <div className="min-w-0 flex-1">
                                            <div className="flex flex-wrap gap-2 mb-3">
                                                <span className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ${getStatusStyles(event.registration_status)}`}>
                                                    {getStatusLabel(event.registration_status)}
                                                </span>
                                            </div>

                                            <h3 className="text-lg font-bold text-slate-950 leading-snug mb-2 line-clamp-2 group-hover:text-emerald-700 transition">
                                                <Link href={`/events/${event.slug}`}>
                                                    {event.title}
                                                </Link>
                                            </h3>

                                            <div className="space-y-2 text-sm text-slate-600">
                                                <div className="flex items-center gap-2">
                                                    <CalendarDays className="h-4 w-4 text-slate-400" aria-hidden="true" />
                                                    <span>{event.event_date || "Date TBA"}</span>
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <MapPin className="h-4 w-4 text-slate-400" aria-hidden="true" />
                                                    <span className="truncate">{event.venue || event.location || "Venue TBA"}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            ))}

                            {/* <div className="bg-slate-950 rounded-lg p-6 text-white mt-auto">
                                <p className="text-sm uppercase tracking-widest text-emerald-300 mb-2">Event formats</p>
                                <p className="text-lg font-semibold mb-4">
                                    Summits, awards, women in GRC forums, practical workshops, webinars, and specialist speaker sessions.
                                </p>
                                <Link href="/events" className="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-emerald-200 transition">
                                    Browse the full calendar
                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                </Link>
                            </div> */}
                        </motion.div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center bg-white border border-slate-200 rounded-lg p-6 md:p-8 shadow-sm">
                        <motion.div
                            variants={scaleIn}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="overflow-hidden rounded-lg"
                        >
                            <img
                                src="assets/images/home-three/gallery/events-image.png"
                                alt="Global Events & Summits"
                                className="w-full h-full min-h-[320px] object-cover"
                            />
                        </motion.div>

                        <motion.div
                            variants={fadeLeft}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            className="flex flex-col"
                        >
                            <h3 className="text-2xl font-bold text-slate-950 mb-4">
                                New events are being prepared
                            </h3>
                            <p className="text-slate-600 mb-6 leading-relaxed">
                                Our event calendar is updated as programmes are confirmed. Expect global summits,
                                focused workshops, women in GRC and FCC forums, awards, webinars, and speaker sessions.
                            </p>
                            <Link
                                href="/events"
                                className="inline-flex items-center gap-2 px-5 py-3 bg-slate-950 text-white rounded-lg font-semibold hover:bg-emerald-700 transition w-fit"
                            >
                                View events calendar
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        </motion.div>
                    </div>
                )}
            </div>
        </section>
    );
}
