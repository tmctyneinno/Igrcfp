import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

const memberships = [
    {
        title: "Student Member",
        bg: "bg-slate-900",
        text: "text-white",
        icon: "🎓",
        iconColor: "text-white", // Always white for Student Member
    },
    {
        title: "Associate Member",
        bg: "bg-gray-100",
        text: "text-slate-900",
        icon: "🎓",
    },
    {
        title: "Professional Member",
        bg: "bg-emerald-700",
        text: "text-white",
        icon: "🎓",
    },
    {
        title: "Fellow (Senior Executive & Experts)",
        bg: "bg-teal-600",
        text: "text-white",
        icon: "🎓",
    },
    {
        title: "Cooperate Member (for Organizations)",
        bg: "bg-blue-900",
        text: "text-white",
        icon: "🎓",
    },
];

const benefits = [
    {
        title: "Network Opportunities",
        description: "Connect with a global network of compliance professionals.",
        icon: "🎯",
    },
    {
        title: "Get Relevant Updates",
        description: "Access exclusive research, insights, and regulatory updates.",
        icon: "🔔",
    },
    {
        title: "Member Benefits",
        description:
            "Gain access to IGRCFP’s mentor-mentee programme, connecting experienced professionals with emerging talent.",
        icon: "🌐",
    },
    {
        title: "Exclusive Discount",
        description:
            "Receive discounts on certifications, events, and publications.",
        icon: "💸",
    },
    {
        title: "Recognition",
        description: "Gain recognition with post-nominals (e.g., A.IGRCFP, F.IGRCFP).",
        icon: "🏆",
    },
];

export default function MembershipOptions() {
    return (
        <div>
            <div className="max-w-7xl mx-auto px-6 py-10">

                {/* SECTION HEADER */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mb-16"
                >
                    <div className="relative inline-flex items-center mb-4">
                        <span className="absolute left-0 top-1/2 w-14 h-px bg-gray-300"></span>
                        <span className="pl-20 text-sm tracking-widest uppercase text-gray-400">
                            Become Our Membership
                        </span>
                    </div>

                    <h2 className="text-4xl xl:text-5xl font-bold text-blue-900">
                        Our Membership Options
                    </h2>
                </motion.div>

                {/* MEMBERSHIP CARDS */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6"
                >
                    {memberships.map((item, index) => (
                        <motion.div
                            key={index}
                            whileHover={{ y: -6 }}
                            transition={{ duration: 0.3 }}
                            className={`rounded-2xl px-6 py-10 flex flex-col items-start justify-center min-h-[170px] ${item.bg} ${item.text}`}
                        >
                            {/* ICON */}
                            <div className={`text-3xl mb-6 ${item.iconColor || "text-slate-900"}`}>
                                {item.icon}
                            </div>

                            {/* TITLE */}
                            <h4 className="font-semibold text-lg leading-snug">
                                {item.title}
                            </h4>
                        </motion.div>
                    ))}
                </motion.div>
            </div>

            <div className="max-w-7xl mx-auto px-6 py-20">
                {/* SECTION HEADER */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start mb-15">
                    {/* LEFT CONTENT */}
                    <motion.div
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="mb-16"
                    >
                        <div className="relative inline-flex items-center mb-4">
                            <span className="absolute left-0 top-1/2 w-14 h-px bg-gray-300"></span>
                            <span className="pl-20 text-sm tracking-widest uppercase text-gray-400">
                                Why Join?
                            </span>
                        </div>

                        <h2 className="text-4xl xl:text-4xl font-bold text-blue-900">
                            Why Join IGRCFP?
                        </h2> 
                    </motion.div>

                    {/* RIGHT CONTENT */}
                    <motion.div
                        initial={{ opacity: 0, x: 40 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.6, ease: "easeOut" }}
                        viewport={{ once: true }}
                    >
                        <p className="text-gray-600 leading-relaxed mb-8">
                            At IGRCFP, we go beyond certification we build careers and shape leaders. By joining us, 
                            you become part of a global network of governance, risk, compliance, and financial crime professionals
                            who are driving change across industries. Membership gives you access to exclusive insights, research, and training, alongside opportunities for professional recognition, CPD credits, and the use of respected post-nominals. Whether you are in banking, fintech, insurance, or regulation, IGRCFP equips you with the knowledge, tools, and connections to stay ahead, stay compliant, and stand out as a trusted expert.
                        </p>
                    </motion.div>
                </div>

                {/* BENEFIT CARDS */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6"
                >
                    {benefits.map((item, index) => (
                        <motion.div
                            key={index}
                            whileHover={{ y: -6 }}
                            transition={{ duration: 0.3 }}
                            className="rounded-2xl bg-white shadow-lg px-6 py-10 flex flex-col items-center justify-center min-h-[200px] border border-gray-200"
                        >
                            {/* ICON */}
                            <div className="text-4xl mb-6 text-blue-600">
                                {item.icon}
                            </div>

                            {/* TITLE */}
                            <h4 className="font-semibold text-lg leading-snug text-center text-gray-900">
                                {item.title}
                            </h4>

                            {/* DESCRIPTION */}
                            <p className="text-center text-sm text-gray-600 mt-4">
                                {item.description}
                            </p>
                        </motion.div>
                    ))}
                </motion.div>
            </div>
        </div>
    );
}
