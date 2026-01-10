import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function GlobalEvents() {
    return (
        <div className="max-w-7xl mx-auto px-6 py-24">
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
