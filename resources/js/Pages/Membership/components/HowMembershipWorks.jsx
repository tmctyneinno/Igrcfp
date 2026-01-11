import { motion } from 'framer-motion';

const steps = [
    {
        title: 'Choose the membership category that fits you.',
    },
    {
        title: 'Complete the membership application form.',
    },
    {
        title: 'Pay your annual membership fee securely online.',
    },
    {
        title: 'Start accessing your member benefits immediately.',
    },
];

export default function HowMembershipWorks() {
    return (
        <section className="bg-white py-16">
            <div className="max-w-7xl mx-auto px-6">

                {/* Section Header */}
                <div className="mb-12">
                    <p className="text-gray-400 text-sm mb-2">Membership</p>
                    <h2 className="text-3xl md:text-4xl font-semibold text-blue-950">
                        How Membership Works
                    </h2>
                </div>

                {/* Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {steps.map((step, index) => (
                        <motion.div
                            key={index}
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.4, delay: index * 0.1 }}
                            viewport={{ once: true }}
                            className="bg-teal-600 rounded-xl p-6 text-white flex flex-col gap-6 min-h-[200px]"
                        >
                            {/* Icon */}
                            <div className="w-10 h-10 rounded-md bg-white/20 flex items-center justify-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    className="w-6 h-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth={1.5}
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M21 16.5V7.5L12 2.25 3 7.5v9L12 21.75 21 16.5z"
                                    />
                                </svg>
                            </div>

                            {/* Text */}
                            <p className="text-base leading-relaxed">
                                {step.title}
                            </p>
                        </motion.div>
                    ))}
                </div>

            </div>
        </section>
    );
}
