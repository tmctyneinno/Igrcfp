import { useEffect, useRef, useState } from "react";
import { Link } from "@inertiajs/react";
import { motion } from "framer-motion";

export default function WhoAreWe() {
    const [activeSlide, setActiveSlide] = useState(0);
    const slideInterval = useRef(null);

    const slides = [0]; // reserved for future expansion

    useEffect(() => {
        startAutoSlide();
        return () => stopAutoSlide();
    }, [activeSlide]);

    const startAutoSlide = () => {
        stopAutoSlide();
        slideInterval.current = setInterval(() => {
            setActiveSlide((prev) => (prev + 1) % slides.length);
        }, 6000);
    };

    const stopAutoSlide = () => {
        if (slideInterval.current) clearInterval(slideInterval.current);
    };

    return (
        <section className="bg-white py-24 overflow-hidden">
            <div className="max-w-7xl mx-auto px-6">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    {/* TEXT CONTENT */}
                    <motion.div
                        initial={{ opacity: 0, x: -60 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.8, ease: "easeOut" }}
                        viewport={{ once: true }}
                    >
                        <span className="text-sm uppercase tracking-widest text-gray-400">
                            Who We Are
                        </span>

                        <h2 className="text-4xl xl:text-5xl font-bold text-slate-900 mt-3 mb-6">
                            Know More About Us
                        </h2>

                        <p className="text-gray-600 leading-relaxed mb-4">
                            The Institute of Governance, Risk & Compliance & Financial Crime
                            Prevention (IGRCFP) is a global professional body dedicated to
                            raising standards in governance, risk management, compliance,
                            and financial crime prevention.
                        </p>

                        <p className="text-gray-600 leading-relaxed mb-4">
                            We equip professionals and organizations with world‑class
                            training, certifications, and resources to stay ahead in a
                            fast‑changing regulatory environment.
                        </p>

                        <p className="text-gray-600 leading-relaxed">
                            With a presence across Africa, Europe, Asia, the Middle East,
                            and the Americas, IGRCFP connects experts, regulators, and
                            industry leaders to share knowledge, drive innovation, and
                            build stronger institutions worldwide.
                        </p>

                        <Link
                            href="/about-us"
                            className="inline-flex items-center gap-3 mt-8 px-7 py-4 rounded-xl bg-slate-900 text-white font-semibold shadow-lg hover:bg-slate-800 transition"
                        >
                            Learn More About Us
                            <span className="text-lg">→</span>
                        </Link>
                    </motion.div>

                    {/* IMAGE CONTENT */}
                    <motion.div
                        initial={{ opacity: 0, scale: 0.9 }}
                        whileInView={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.9, ease: "easeOut" }}
                        viewport={{ once: true }}
                        className="flex justify-center"
                    >
                        <img
                            src="/assets/images/about/network.png"
                            alt="IGRCFP global professional network"
                            className="max-w-xl w-full"
                        />
                    </motion.div>

                </div>
            </div>
        </section>
    );
}
