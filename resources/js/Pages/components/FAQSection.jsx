import React, { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { ChevronDown } from "lucide-react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

const faqs = [
    {
        question: "Who can become a member of IGRCFP?",
        answer:
            "Anyone working or aspiring to work in governance, risk, compliance, fintech, banking, insurance, or regulation can become a member of IGRCFP.",
    },
    {
        question: "Are the Certifications Recognised Globally?",
        answer:
            "Yes, IGRCFP certifications are internationally recognised and aligned with global best practices and standards.",
    },
    {
        question: "How Do I Register for an Event?",
        answer:
            "You can register for events directly through our website under the Events section after logging into your member account.",
    },
    {
        question: "Do I Need Prior Experience to take a Course?",
        answer:
            "Some beginner-level courses do not require prior experience, while advanced certifications may require relevant professional background.",
    },
    {
        question: "What Benefits do Members Receive?",
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
        <section className="bg-gray-50 py-16">
            <div className="max-w-7xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                    {/* LEFT: FAQ Accordion */}
                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="space-y-3"
                    >
                        {faqs.map((faq, index) => (
                            <div
                                key={index}
                                className="bg-white border border-gray-200 rounded-lg overflow-hidden"
                            >
                                <button
                                    onClick={() => toggleFAQ(index)}
                                    className="w-full flex items-center justify-between px-5 py-3.5 text-left text-gray-900 text-sm md:text-base font-medium"
                                >
                                    {faq.question}
                                    <motion.span
                                        animate={{ rotate: activeIndex === index ? 180 : 0 }}
                                        transition={{ duration: 0.25 }}
                                    >
                                        <ChevronDown className="w-4 h-4 text-gray-500" />
                                    </motion.span>
                                </button>

                                <AnimatePresence>
                                    {activeIndex === index && (
                                        <motion.div
                                            initial={{ height: 0, opacity: 0 }}
                                            animate={{ height: "auto", opacity: 1 }}
                                            exit={{ height: 0, opacity: 0 }}
                                            transition={{ duration: 0.3 }}
                                            className="px-5 pb-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100"
                                        >
                                            {faq.answer}
                                        </motion.div>
                                    )}
                                </AnimatePresence>
                            </div>
                        ))}
                    </motion.div>

                    {/* RIGHT: Heading & Description */}
                    <motion.div
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
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
                                FAQ
                            </span>
                        </div>

                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                            GOT <span className="text-[#0A2463]">QUESTIONS?</span><br />
                            WE’VE GOT <span className="text-[#0A2463]">ANSWERS</span>
                        </h2>

                        <p className="text-gray-600 leading-relaxed">
                            Have questions about IGRCFP? We’ve put together answers to some of the most common questions about our membership, certifications, events, and resources. If you need more help, feel free to reach out through our Contact page.
                        </p>
                    </motion.div>

                </div>

                {/* TESTIMONIAL SECTION */}
                <motion.div
                    variants={scaleIn}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    className="mt-16 pt-12 border-t border-gray-200 text-center"
                >
                    <blockquote className="text-xl md:text-2xl italic text-gray-800 max-w-4xl mx-auto leading-relaxed">
                        “IGRCFP has elevated the standard of professional practice in our compliance function. The rigour of their programmes and the quality of their community is simply unmatched in the industry.”
                    </blockquote>
                    <p className="mt-6 text-sm text-gray-600">
                        <span className="font-medium text-[#0A2463]">Chief Compliance Officer</span><br />
                        Pan-African Commercial Banking Group
                    </p>
                </motion.div>

            </div>
        </section>
    );
}