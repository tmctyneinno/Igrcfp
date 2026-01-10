import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { ChevronDown } from "lucide-react";
import { fadeLeft } from "@/utils/motionPresets";

const faqs = [
    {
        question: "Who can become a member of IGRCFP?",
        answer:
            "Anyone working or aspiring to work in governance, risk, compliance, fintech, banking, insurance, or regulation can become a member of IGRCFP.",
    },
    {
        question: "Are the certifications recognised internationally?",
        answer:
            "Yes, IGRCFP certifications are internationally recognised and aligned with global best practices and standards.",
    },
    {
        question: "How do I register for an event?",
        answer:
            "You can register for events directly through our website under the Events section after logging into your member account.",
    },
    {
        question: "Do I need prior experience to take a course?",
        answer:
            "Some beginner-level courses do not require prior experience, while advanced certifications may require relevant professional background.",
    },
    {
        question: "What benefits do members receive?",
        answer:
            "Members receive access to exclusive research, CPD credits, discounts, mentorship programmes, and professional recognition.",
    },
];

export default function FAQSection() {
    const [activeIndex, setActiveIndex] = useState(null);

    const toggleFAQ = (index) => {
        setActiveIndex(activeIndex === index ? null : index);
    };

    return (
        <div className="max-w-7xl mx-auto px-6 py-24">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                
                {/* LEFT CONTENT */}
                <motion.div
                    variants={fadeLeft}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                >
                    <div className="relative inline-flex items-center mb-6">
                        <span className="absolute left-0 top-1/2 w-12 h-px bg-gray-300"></span>
                        <span className="pl-16 text-sm tracking-widest uppercase text-gray-400">
                            FAQ
                        </span>
                    </div>

                    <h2 className="text-4xl xl:text-4xl font-bold text-slate-900 mb-6 leading-tight">
                        Got Questions? We’ve Got Answers.
                    </h2>

                    <p className="text-gray-600 max-w-lg leading-relaxed">
                        Have questions about IGRCFP? We’ve put together answers
                        to some of the most common questions about our membership,
                        certifications, events, and resources. If you need more
                        help, feel free to reach out through our Contact page.
                    </p>
                </motion.div>

                {/* RIGHT FAQ ACCORDION */}
                <div className="space-y-4">
                    {faqs.map((faq, index) => (
                        <div
                            key={index}
                            className="bg-gray-50 rounded-xl overflow-hidden"
                        >
                            <button
                                onClick={() => toggleFAQ(index)}
                                className="w-full flex items-center justify-between px-6 py-3 text-left text-slate-900 font-medium"
                            >
                                {faq.question}
                                <motion.span
                                    animate={{ rotate: activeIndex === index ? 180 : 0 }}
                                    transition={{ duration: 0.25 }}
                                >
                                    <ChevronDown className="w-5 h-5 text-gray-500" />
                                </motion.span>
                            </button>

                            <AnimatePresence>
                                {activeIndex === index && (
                                    <motion.div
                                        initial={{ height: 0, opacity: 0 }}
                                        animate={{ height: "auto", opacity: 1 }}
                                        exit={{ height: 0, opacity: 0 }}
                                        transition={{ duration: 0.3 }}
                                        className="px-6 pb-5 text-gray-600 text-sm leading-relaxed"
                                    >
                                        {faq.answer}
                                    </motion.div>
                                )}
                            </AnimatePresence>
                        </div>
                    ))}
                </div>

            </div>
        </div>
    );
}
