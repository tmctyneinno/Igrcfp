import React from "react";
import { Head, Link } from '@inertiajs/react';
import { motion } from "framer-motion";
import { fadeIn } from "@/utils/motionPresets";
import GuestLayout from '@/Layouts/GuestLayout';
import CallToAction from "@/Pages/components/CallToAction";

const values = [
    {
        title: "Integrity",
        copy: "We uphold the highest ethical standards.",
        icon: (
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.75} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        ),
    },
    {
        title: "Excellence",
        copy: "We pursue quality in everything we do.",
        icon: (
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.75} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        ),
    },
    {
        title: "Collaboration",
        copy: "We believe in the power of partnership and community.",
        icon: (
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.75} d="M7 11l3 3L18 5m-9 6l3 3m0 0l6-6M3 21h18" />
        ),
    },
    {
        title: "Impact",
        copy: "We create meaningful impact in professions and communities.",
        icon: (
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.75} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h1a2.5 2.5 0 012.5 2.5v0a2.5 2.5 0 002.5 2.5h1.024M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        ),
    },
    {
        title: "Innovation",
        copy: "We embrace change and drive forward thinking.",
        icon: (
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.75} d="M9 18h6M10 21h4M12 3a6 6 0 00-3.6 10.8c.4.3.6.8.6 1.2v.5h6v-.5c0-.4.2-.9.6-1.2A6 6 0 0012 3z" />
        ),
    },
];

const pathwaySteps = [
    { label: "Professional Development & CPD", tint: "bg-teal-600" },
    { label: "Professional Qualification Certificate", tint: "bg-green-600" },
    { label: "Diploma", tint: "bg-amber-500" },
    { label: "Advanced Diploma", tint: "bg-indigo-700" },
    { label: "Professional Postgraduate Diploma", tint: "bg-blue-900", note: "Highest level" },
];

const focusAreas = [
    "Governance, Risk & Compliance",
    "Financial Crime Prevention",
    "Anti-Money Laundering & CTF",
    "Operational Risk",
    "Enterprise Risk Management",
    "Cyber Risk Governance",
    "Cybersecurity & Digital Risk",
    "Artificial Intelligence Governance",
    "Emerging Technology Risk",
    "Crypto & Digital Assets",
    "RegTech",
    "ESG Risk",
    "Project Management",
    "Business Analysis",
    "Certified MLRO Development",
    "Chief Risk Officer Development",
    "Compliance Leadership",
    "Islamic Financial Governance",
    "Internal Controls & Assurance",
    "Ethics & Professional Conduct",
];

const intakes = [
    { month: "February", copy: "Begin your journey at the start of the year." },
    { month: "June", copy: "The perfect mid-year entry point for your professional goals." },
    { month: "October", copy: "Your final intake of the year for the next milestone." },
];

const membershipGrades = [
    {
        code: "AIGRCFP",
        name: "Associate Member",
        copy: "For emerging and developing professionals building their foundation in the profession.",
        tint: "border-green-200 bg-green-50 text-green-800",
    },
    {
        code: "MIGRCFP",
        name: "Member",
        copy: "For experienced professionals demonstrating expertise and good standing.",
        tint: "border-blue-200 bg-blue-50 text-blue-900",
    },
    {
        code: "FIGRCFP",
        name: "Fellow",
        copy: "For senior professionals with significant experience, leadership and contribution to the profession.",
        tint: "border-amber-200 bg-amber-50 text-amber-800",
    },
];

const impactStats = [
    "Qualifications that build capability",
    "Professionals developed globally",
    "Chapters across regions",
    "Industries strengthened",
    "Standards elevated",
    "A safer, more resilient world",
];

export default function Index({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />

            {/* Hero Section */}
            <section className="w-full bg-[#0A1A2F] text-white pt-28 pb-10 relative overflow-hidden">
                <div className="max-w-7xl mx-auto px-6 lg:px-8 ">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="text-left"
                    >
                        {/* Top line text */}
                        <div className="flex items-center gap-4 mb-4">
                            <motion.span
                                initial={{ width: 0 }}
                                whileInView={{ width: 48 }}
                                transition={{ duration: 0.5, ease: "easeOut" }}
                                viewport={{ once: true }}
                                className="h-px bg-gray-300"
                            />
                            <span className="text-sm tracking-widest text-gray-300 uppercase">
                                Professional Body . Global Standards . London, UK
                            </span>
                        </div>

                        {/* Main Heading */}
                        <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                            {title}
                        </h1>

                        {/* Subtitle */}
                        <p className="text-lg md:text-xl text-gray-300 max-w-3xl">
                            The Institute of Governance, Risk, Compliance & Financial Crime Prevention
                        </p>
                    </motion.div>
                    {/* Bottom Tagline Bar */}
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        transition={{ delay: 0.2 }}
                        className="mt-12 pt-4 border-t border-gray-700 flex flex-wrap gap-x-2 gap-y-2 text-xs uppercase tracking-wider text-gray-300"
                    >
                        <span>Terrorism Financing</span>
                        <span>•</span>
                        <span>KYC & CDD</span>
                        <span>•</span>
                        <span>Sanctions Compliance</span>
                        <span>•</span>
                        <span>Enterprise Risk Management</span>
                        <span>•</span>
                        <span>Regulatory Frameworks</span>
                        <span>•</span>
                        <span>ESG Sustainable Finance</span>
                        <span>•</span>
                        <span>AI in Compliance</span>
                    </motion.div>
                </div>
            </section>

            {/* Values strip */}
            <section className="bg-blue-900 text-white">
                <div className="max-w-7xl mx-auto px-6 lg:px-8 py-8 grid grid-cols-2 md:grid-cols-5 gap-8">
                    {values.map((v) => (
                        <div key={v.title} className="flex flex-col gap-2">
                            <svg className="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {v.icon}
                            </svg>
                            <p className="font-semibold text-sm">{v.title}</p>
                            <p className="text-xs text-blue-100 leading-relaxed">{v.copy}</p>
                        </div>
                    ))}
                </div>
            </section>

            {/* Our Story + Our Vision */}
            <section className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                    >
                        <div
                            className="h-48 bg-cover bg-center relative"
                            style={{ backgroundImage: "url('/assets/images/our-story-path.png')" }}
                        >
                            <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
                        </div>
                        <div className="p-8">
                            <h2 className="text-2xl font-bold text-blue-900 mb-4">Our Story</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                The Institute of Governance, Risk, Compliance & Financial Crime Prevention was created around a simple but important idea: professionals working in governance, risk, compliance and financial crime prevention need more than isolated training. They need a structured professional home.
                            </p>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                As regulation becomes more complex, technology reshapes risk, financial crime becomes more sophisticated, and organisations face increasing pressure to demonstrate resilience, accountability and ethical leadership, the role of GRC and financial crime professionals has never been more important.
                            </p>
                            <p className="text-gray-700 leading-relaxed">
                                What began as a commitment to strengthening professional knowledge is now developing into a much broader institution focused on building capability, recognising experience, connecting practitioners and contributing to higher professional standards across industries and borders.
                            </p>
                        </div>
                    </motion.div>

                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        transition={{ delay: 0.1 }}
                        className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                    >
                        <div
                            className="h-48 bg-cover bg-center relative"
                            style={{ backgroundImage: "url('/assets/images/our-vision-globe.jpg')" }}
                        >
                            <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
                        </div>
                        <div className="p-8">
                            <h2 className="text-2xl font-bold text-blue-900 mb-4">Our Vision</h2>
                            <p className="text-gray-700 leading-relaxed mb-6">
                                To become a globally respected professional institute for Governance, Risk, Compliance and Financial Crime Prevention, recognised for developing capable professionals, strengthening institutions and advancing responsible professional practice.
                            </p>
                            <p className="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">
                                A place where professionals can
                            </p>
                            <div className="grid grid-cols-5 gap-3 text-center">
                                {["Learn", "Qualify", "Connect", "Progress", "Contribute"].map((w) => (
                                    <div key={w} className="flex flex-col items-center gap-2">
                                        <div className="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center text-blue-900">
                                            <span className="text-xs font-bold">{w.charAt(0)}</span>
                                        </div>
                                        <span className="text-xs text-gray-600">{w}</span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </motion.div>
                </div>
            </section>

            {/* Professional Pathway */}
            <section className="bg-white py-16 border-t border-gray-100">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    <motion.h2
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="text-2xl font-bold text-blue-900 mb-2"
                    >
                        A Professional Pathway, Not Just a Course Catalogue
                    </motion.h2>
                    <p className="text-gray-600 max-w-2xl mb-10">
                        Education as a journey rather than a one-off course — a learner completing a qualification today should already understand what their next professional milestone could be.
                    </p>

                    <div className="flex flex-col md:flex-row items-stretch gap-3">
                        {pathwaySteps.map((step, i) => (
                            <React.Fragment key={step.label}>
                                <div className="flex-1 flex flex-col items-center text-center gap-3 bg-gray-50 rounded-xl p-5 border border-gray-100">
                                    <div className={`w-10 h-10 rounded-full ${step.tint} text-white flex items-center justify-center text-sm font-bold`}>
                                        {i + 1}
                                    </div>
                                    <p className="text-sm font-medium text-gray-800">{step.label}</p>
                                    {step.note && (
                                        <span className="text-[11px] font-semibold text-amber-600 uppercase tracking-wide">{step.note}</span>
                                    )}
                                </div>
                                {i < pathwaySteps.length - 1 && (
                                    <div className="hidden md:flex items-center text-gray-300">
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                )}
                            </React.Fragment>
                        ))}
                    </div>
                </div>
            </section>

            {/* Global Professional Cohorts */}
            <section className="relative py-16 overflow-hidden">
                {/* Background image */}
                <div
                    className="absolute inset-0 bg-cover bg-center"
                    style={{ backgroundImage: "url('/assets/images/professionals-global-team.jpg')" }}
                />
                {/* Navy overlay for readability */}
                <div className="absolute inset-0 bg-blue-900/90" />

                <div className="relative max-w-7xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="text-center mb-10"
                    >
                        <h2 className="text-2xl font-bold text-white mb-2">Global Professional Cohorts</h2>
                        <p className="text-blue-100 max-w-2xl mx-auto">
                            Structured learning, a global community and real professional growth — three main cohort intakes annually, with selected CPD programmes available year-round.
                        </p>
                    </motion.div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {intakes.map((intake) => (
                            <div key={intake.month} className="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 text-center">
                                <span className="inline-block text-xs font-bold tracking-wide text-amber-400 uppercase mb-3">
                                    {intake.month} Intake
                                </span>
                                <p className="text-blue-100 text-sm leading-relaxed">{intake.copy}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Areas of Professional Excellence */}
            <section className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="mb-10"
                    >
                        <h2 className="text-2xl font-bold text-blue-900 mb-2">A Wider Professional Scope</h2>
                        <p className="text-gray-600 max-w-3xl">
                            Modern governance and risk problems increasingly intersect. Cyber risk is no longer just an IT issue, financial crime is no longer simply an AML issue, and AI governance cannot be separated from risk, ethics and regulation — this convergence is where IGRCFP contributes.
                        </p>
                    </motion.div>
                    <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        {focusAreas.map((area) => (
                            <div key={area} className="bg-white border border-gray-100 rounded-lg px-4 py-3 text-sm text-gray-700 shadow-sm">
                                {area}
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Membership */}
            <section className="bg-white py-16 border-t border-gray-100">
                <div className="max-w-7xl mx-auto px-6 lg:px-8">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="mb-10"
                    >
                        <h2 className="text-2xl font-bold text-blue-900 mb-2">Professional Membership</h2>
                        <p className="text-gray-600 max-w-2xl">
                            A pathway for professionals to formalise their experience, demonstrate professional standing and contribute to the wider professional community. Applications and credentials are subject to assessment and verification.
                        </p>
                    </motion.div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {membershipGrades.map((grade) => (
                            <div key={grade.code} className={`rounded-2xl border p-6 ${grade.tint}`}>
                                <p className="text-xs font-bold uppercase tracking-wide mb-1">{grade.code}</p>
                                <p className="text-lg font-semibold mb-3">{grade.name}</p>
                                <p className="text-sm leading-relaxed opacity-90">{grade.copy}</p>
                            </div>
                        ))}
                    </div>
                    <div className="mt-6">
                        <Link href={route('membership')} className="inline-flex items-center text-sm font-medium text-blue-900 hover:text-blue-700">
                            Explore membership grades
                            <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
                        </Link>
                    </div>
                </div>
            </section>

            {/* Chapters / Emerging Professionals / Corporate */}
            <section className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                    >
                        <h3 className="text-lg font-bold text-blue-900 mb-2">Regional Chapters</h3>
                        <p className="text-sm text-gray-500 mb-4">Global standards. Local relevance.</p>
                        <p className="text-sm text-gray-700 leading-relaxed mb-4">
                            Professional standards may be international, but risk, regulation and financial crime are experienced locally. The Chapter becomes the local professional community, while the Institute provides the wider global framework.
                        </p>
                        <Link href={route('chapters.index')} className="text-sm font-medium text-blue-900 hover:text-blue-700">
                            View Regional Chapters →
                        </Link>
                    </motion.div>

                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        transition={{ delay: 0.1 }}
                        className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                    >
                        <h3 className="text-lg font-bold text-blue-900 mb-2">Emerging Professionals</h3>
                        <p className="text-sm text-gray-500 mb-4">Investing in the next generation.</p>
                        <p className="text-sm text-gray-700 leading-relaxed mb-4">
                            Initiatives such as the Emerging Professionals Scholarship Programme improve access to professional education for individuals who demonstrate ambition, commitment and potential.
                        </p>
                        <Link href={route('contact')} className="text-sm font-medium text-blue-900 hover:text-blue-700">
                            Learn about scholarships →
                        </Link>
                    </motion.div>

                    <motion.div
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        transition={{ delay: 0.2 }}
                        className="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                    >
                        <h3 className="text-lg font-bold text-blue-900 mb-2">Corporate & Institutional</h3>
                        <p className="text-sm text-gray-500 mb-4">Develop capability. Strengthen resilience.</p>
                        <p className="text-sm text-gray-700 leading-relaxed mb-4">
                            Organisations can sponsor employees into professional qualifications, establish dedicated corporate cohorts and build structured internal academies with IGRCFP.
                        </p>
                        <Link href={route('contact')} className="text-sm font-medium text-blue-900 hover:text-blue-700">
                            Talk to our team →
                        </Link>
                    </motion.div>
                </div>
            </section>

            {/* Impact band */}
            <section className="relative py-16 overflow-hidden">
                {/* Background image */}
                <div
                    className="absolute inset-0 bg-cover bg-center"
                    style={{ backgroundImage: "url('/assets/images/professionals-silhouette.avif')" }}
                />
                {/* Dark navy overlay for contrast */}
                <div className="absolute inset-0 bg-[#0A1A2F]/95" />

                <div className="relative max-w-7xl mx-auto px-6 lg:px-8">
                    <motion.h2
                        variants={fadeIn}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        className="text-2xl font-bold text-white mb-10 text-center"
                    >
                        Our Impact
                    </motion.h2>
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-12">
                        {impactStats.map((stat) => (
                            <div key={stat} className="text-center">
                                <div className="w-12 h-12 mx-auto mb-3 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                    <svg className="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.75} d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p className="text-xs text-blue-100 leading-relaxed">{stat}</p>
                            </div>
                        ))}
                    </div>
                    <div className="border-t border-white/10 pt-8 text-center">
                        <p className="text-xl md:text-2xl font-semibold text-white italic">
                            "Alone we go fast. Together we go far."
                        </p>
                        <p className="text-amber-400 mt-3 font-medium">
                            Stronger Professionals. Stronger Institutions. Stronger World.
                        </p>
                    </div>
                </div>
            </section>

            {/* CTA */}
            <CallToAction />
        </GuestLayout>
    );
}