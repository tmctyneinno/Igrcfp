import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function GlobalEvents() {
    return (
        <div className="max-w-7xl mx-auto px-6 py-24">
            {/* HEADER */}
                        <div
                            className="flex flex-col md:flex-row md:justify-between md:items-center mb-12"
                            data-aos="fade-up"
                        >
                            <div>
                                <span className="text-sm uppercase tracking-widest text-gray-400">
                                    Certifications & Trainings
                                </span>
                                <h2 className="text-3xl font-bold text-gray-900 mt-2">
                                    Our Programmes
                                </h2>
                                <p className="text-gray-600 mt-3 max-w-2xl">
                                    Our professional certifications are designed to equip individuals
                                    and institutions with globally relevant skills to tackle financial
                                    crime and compliance risks.
                                </p>
                            </div>
            
                            <Link
                                href="/courses"
                                className="mt-6 md:mt-0 text-blue-950 font-semibold hover:text-blue-700 transition"
                            >
                                View All Courses →
                            </Link>
                        </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                {/* LEFT – IMAGE */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="relative"
                >
                    <div className="rounded-2xl overflow-hidden border-4 border-blue-500">
                        <img
                            src="assets/images/home-three/gallery/events-image.png"
                            alt="Global Events & Summits"
                            className="w-full h-full object-cover"
                        />
                    </div>
                </motion.div>

                {/* RIGHT – CONTENT */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="flex flex-col"
                >
                    

                    <p className="text-gray-600 mb-6 leading-relaxed">
                        At IGRCFP, our events connect professionals, regulators,
                        and industry leaders worldwide. From global summits to
                        focused workshops, we provide platforms for learning,
                        networking, and shaping the future of governance,
                        compliance, and financial crime prevention.
                    </p>

                    <div className="mb-8">
                        <h4 className="font-semibold text-slate-900 mb-4">
                            Our Key Events:
                        </h4>

                        <ul className="space-y-3 text-gray-600 list-disc list-inside">
                            <li>
                                <strong>Global Summits</strong> – Annual gatherings in
                                Africa and Europe with keynotes, panels, and networking.
                            </li>
                            <li>
                                <strong>Annual Awards</strong> – Recognising excellence
                                in compliance and governance.
                            </li>
                            <li>
                                <strong>Women in GRC & FCC Forums</strong> – Empowering
                                women leaders in the industry.
                            </li>
                            <li>
                                <strong>Workshops & Webinars</strong> – Practical,
                                hands-on learning across regions.
                            </li>
                            <li>
                                <strong>Speaker Series</strong> – Fireside chats with
                                regulators and global experts.
                            </li>
                        </ul>
                    </div>

                    <Link
                        href="/events"
                        className="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition w-fit"
                    >
                        View Upcoming Events
                        <span aria-hidden>→</span>
                    </Link>
                </motion.div>
            </div>
        </div>
    );
}
