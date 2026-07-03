import { motion, AnimatePresence } from "framer-motion";
import { Star } from "lucide-react";
import React, { useEffect, useState } from "react";

const testimonials = [
    {
        name: "Dr. Samuel Akinwale",
        role: "Compliance & Risk Management Professional",
        image: "https://i.pravatar.cc/100?img=13",
        rating: 5,
        message:
            "The courses provide practical insight into governance, risk management, and financial crime prevention. The learning structure and discussions reflect real-world regulatory challenges faced by professionals today.",
    },
    {
        name: "Fatima Bello",
        role: "AML & Financial Crime Analyst",
        image: "https://i.pravatar.cc/100?img=22",
        rating: 5,
        message:
            "What stands out is the depth of content and clarity of instruction. The platform bridges theory and practice, helping professionals understand compliance obligations and emerging financial crime risks.",
    },
    {
        name: "Michael O. Thompson",
        role: "Internal Auditor",
        image: "https://i.pravatar.cc/100?img=36",
        rating: 5,
        message:
            "The Institute’s course management system makes learning structured and accessible. The modules on governance and fraud risk assessment are well-designed and immediately applicable in professional settings.",
    },
    {
        name: "Aisha Mohammed",
        role: "Public Sector Risk Officer",
        image: "https://i.pravatar.cc/100?img=48",
        rating: 5,
        message:
            "These courses strengthened my understanding of governance frameworks and financial crime prevention. The platform encourages critical thinking and informed discourse among practitioners.",
    },
];


export default function Testimonials() {
    const [index, setIndex] = useState(0);

    const visibleTestimonials = testimonials.slice(index, index + 2);

    useEffect(() => {
        const timer = setInterval(() => {
            setIndex((prev) =>
                prev + 2 >= testimonials.length ? 0 : prev + 2
            );
        }, 5000);

        return () => clearInterval(timer);
    }, []);

    return (
        <section className="max-w-7xl mx-auto px-6 py-24 overflow-hidden">
            {/* TITLE */}
            <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.6 }}
                className="text-center text-4xl font-semibold text-slate-900 mb-16"
            >
                What Our Clients Say
            </motion.h2>

            {/* SLIDER */}
            <div className="relative">
                <AnimatePresence mode="wait">
                    <motion.div
                        key={index}
                        initial={{ opacity: 0, x: 50 }}
                        animate={{ opacity: 1, x: 0 }}
                        exit={{ opacity: 0, x: -50 }}
                        transition={{ duration: 0.5 }}
                        className="grid grid-cols-1 md:grid-cols-2 gap-8"
                    >
                        {visibleTestimonials.map((item, i) => (
                            <div
                                key={i}
                                className="bg-slate-100 rounded-2xl p-8"
                            >
                                {/* HEADER */}
                                <div className="flex items-start justify-between mb-6">
                                    <div className="flex items-center gap-4">
                                        <img
                                            src={item.image}
                                            alt={item.name}
                                            className="w-12 h-12 rounded-full object-cover"
                                        />
                                        <div>
                                            <h4 className="font-semibold text-slate-900">
                                                {item.name}
                                            </h4>
                                            <p className="text-sm text-slate-500">
                                                {item.role}
                                            </p>
                                        </div>
                                    </div>

                                    {/* STARS */}
                                    <div className="flex gap-1">
                                        {[...Array(item.rating)].map((_, j) => (
                                            <Star
                                                key={j}
                                                className="w-4 h-4 fill-slate-900 text-slate-900"
                                            />
                                        ))}
                                    </div>
                                </div>

                                {/* MESSAGE */}
                                <p className="text-slate-700 leading-relaxed">
                                    {item.message}
                                </p>
                            </div>
                        ))}
                    </motion.div>
                </AnimatePresence>
            </div>

            {/* DOTS */}
            <div className="flex justify-center gap-2 mt-10">
                {Array.from({
                    length: Math.ceil(testimonials.length / 2),
                }).map((_, i) => (
                    <button
                        key={i}
                        onClick={() => setIndex(i * 2)}
                        className={`w-3 h-3 rounded-full transition ${
                            index === i * 2
                                ? "bg-slate-900"
                                : "bg-slate-300"
                        }`}
                    />
                ))}
            </div>
        </section>
    );
}
