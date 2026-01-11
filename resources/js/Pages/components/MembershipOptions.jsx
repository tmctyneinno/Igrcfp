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
            <JoinIGRCFP />
            
        </div>
    );
}
