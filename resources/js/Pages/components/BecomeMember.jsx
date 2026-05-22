import React from "react";
import { motion } from "framer-motion";
import { Link } from "@inertiajs/react";
import { fadeLeft, scaleIn } from "@/utils/motionPresets";

export default function BecomeMember() {
    return (
        <section className="bg-gray py-24 overflow-hidden" data-aos="zoom-in" data-aos-duration="1200">
            <div className="max-w-7xl mx-auto px-6 py-0">
                {/* TOP CONTENT */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start mb-24">

                    {/* LEFT HEADING */}
                    <motion.div
                        variants={fadeLeft}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                    >
                        {/* Section label with line */}
                        <div className="relative inline-flex items-center mb-4">
                            <span className="absolute left-0 top-1/2 w-14 h-px bg-gray-300"></span>
                            <span className="pl-20 text-sm tracking-widest uppercase text-gray-400">
                                Membership
                            </span>
                        </div>

                        <h2 className="text-4xl xl:text-5xl font-bold text-slate-900">
                            Become a Member
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
                            Take your professional journey to the next level by joining
                            IGRCFP. Membership gives you exclusive access to a global
                            network of governance, risk, compliance, and financial crime
                            professionals, cutting-edge research, specialized training,
                            and career-boosting opportunities.
                        </p>

                        <p className="text-gray-600 leading-relaxed mb-10">
                            Enjoy discounts on courses and events, CPD credits, and the
                            use of prestigious post-nominals that highlight your
                            expertise. Whether you’re in banking, fintech, insurance,
                            or regulatory roles, IGRCFP membership equips you with the
                            knowledge, recognition, and connections to excel and make
                            an impact in your field.
                        </p>

                        <Link
                            href="/membership/register"
                            className="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition"
                        >
                            Register as a Member
                            <span aria-hidden>→</span>
                        </Link>
                    </motion.div>
                </div>

                {/* IMAGES */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                    {/* LEFT IMAGE */}
                    <motion.div
                        variants={scaleIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="rounded-3xl overflow-hidden"
                    >
                        <img
                            src="assets/images/home-three/gallery/membership-img1.png"
                            alt="Member working"
                            className="max-w-2xl w-full object-cover"
                        />
                    </motion.div>

                    {/* RIGHT IMAGE */}
                    <motion.div
                        initial={{ opacity: 0, scale: 0.95 }}
                        whileInView={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.6, ease: "easeOut" }}
                        viewport={{ once: true }}
                        className="rounded-3xl overflow-hidden"
                    >
                        <img
                            src="/assets/images/home-three/gallery/membership-img2.png"
                            alt="Members celebrating"
                            className="max-w-2xl w-full pt-24"
                        />
                    </motion.div>
                </div>
            </div>
        </section>
    );
}
