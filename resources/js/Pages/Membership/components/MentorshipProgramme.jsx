import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

const steps = [
    { label: "Join IGRCFP", icon: "🤝" },
    { label: "Apply as Mentor/Mentee", icon: "📝" },
    { label: "Get Matched", icon: "👥" },
    { label: "Start Mentorship", icon: "😊" },
];

export default function MentorshipProgramme() {
    return (
        <div className="bg-gray max-w-7xl mx-auto px-6 py-24">
            {/* SECTION HEADER */}
            <motion.div
                variants={fadeLeft}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true }}
                className="max-w-3xl mb-16"
            >
                <div className="relative inline-flex items-center mb-4">
                    <span className="absolute left-0 top-1/2 w-14 h-px bg-gray-300"></span>
                    <span className="pl-20 text-sm tracking-widest uppercase text-gray-400">
                        Mentorship programme
                    </span>
                </div>

                <h2 className="text-4xl font-bold text-gray-900 mb-6">
                    Know More About Us
                </h2>

                <p className="text-gray-600 leading-relaxed">
                    At IGRCFP, we believe learning goes beyond the classroom. Our
                    mentorship programme pairs senior professionals with aspiring
                    practitioners to provide guidance, career support, and industry
                    insights. Whether you are a mentor sharing your expertise or a
                    mentee growing your skills, this programme helps you build
                    meaningful professional relationships.
                </p>
            </motion.div>

            {/* STEPS / TIMELINE */}
            <motion.div
                variants={scaleIn}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true }}
                className="mb-20"
            >
                <div className="relative flex items-center justify-between max-w-4xl mx-auto">
                    {/* LINE */}
                    <div className="absolute top-1/4 left-0 w-full h-1 bg-blue-900 rounded-full z-10"></div>

                    {steps.map((step, index) => (
                        <div
                            key={index}
                            className="relative z-10 pt-3 flex flex-col items-center text-center w-1/4"
                        >
                            <div className="w-10 h-10 flex items-center justify-center rounded-full bg-blue-900 text-white text-sm font-semibold mb-4">
                                ✓
                            </div>
                            <div className="text-2xl mb-2">{step.icon}</div>
                            <p className="text-sm text-blue-900 font-medium">
                                {step.label}
                            </p>
                        </div>
                    ))}
                </div>
            </motion.div>

            {/* CARDS */}
            <motion.div
                variants={scaleIn}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true }}
                className="grid grid-cols-1 md:grid-cols-2 gap-10"
            >
                {/* MENTEES */}
                <div className="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                    <img
                        src="/assets/images/innerpage/gallery/mentee.png"
                        alt="For Mentees"
                        className="mb-6 mx-auto"
                    />

                    <h3 className="text-xl font-semibold text-center mb-4">
                        For Mentees
                    </h3>

                    <h4 className="font-semibold mb-2">Benefits</h4>
                    <ul className="text-gray-600 text-sm space-y-2 mb-4 list-disc list-inside">
                        <li>Learn from experienced professionals.</li>
                        <li>Gain career guidance and real-world insights.</li>
                        <li>Build your network and confidence.</li>
                    </ul>

                    <p className="text-xs text-gray-500 mb-6">
                        <strong>Eligibility:</strong> Available to Student
                        Affiliates & Associate Members
                    </p>

                    <Link
                        href="/mentorship/mentee"
                        className="text-blue-600 font-medium hover:underline"
                    >
                        Join as a Mentee →
                    </Link>
                </div>

                {/* MENTORS */}
                <div className="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                    <img
                        src="/assets/images/innerpage/gallery/mentor.png"
                        alt="For Mentors"
                        className="mb-6 mx-auto"
                    />

                    <h3 className="text-xl font-semibold text-center mb-4">
                        For Mentors
                    </h3>

                    <h4 className="font-semibold mb-2">Benefits</h4>
                    <ul className="text-gray-600 text-sm space-y-2 mb-4 list-disc list-inside">
                        <li>Share expertise and give back.</li>
                        <li>Strengthen leadership & coaching skills.</li>
                        <li>Gain recognition as an industry leader.</li>
                    </ul>

                    <p className="text-xs text-gray-500 mb-6">
                        <strong>Eligibility:</strong> Available to Fellows &
                        Senior Associate Members
                    </p>

                    <Link
                        href="/mentorship/mentor"
                        className="text-blue-600 font-medium hover:underline"
                    >
                        Join as a Mentor →
                    </Link>
                </div>
            </motion.div>
        </div>
    );
}
