import { motion, AnimatePresence } from "framer-motion";
import { Link } from "@inertiajs/react";
import React, { useState, useEffect } from "react";

// Carousel navigation icons
const ChevronLeft = (props) => (
    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" {...props}>
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
    </svg>
);

const ChevronRight = (props) => (
    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" {...props}>
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
    </svg>
);

export default function WhatWeOffer() {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [direction, setDirection] = useState(0);
    const [isAutoPlaying, setIsAutoPlaying] = useState(true);

    const allCourses = [
        // GRC & Risk Management Courses
        {
            title: "GRC & Risk Management",
            description: "Comprehensive programmes for governance, risk, and compliance professionals",
            link: "/programmes/grc",
            theme: "blue",
            bgColor: "blue-50",
            borderColor: "blue-100",
            iconBg: "blue-600",
            textColor: "blue-900",
            hoverColor: "blue-700",
            category: "GRC & Risk Management",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            )
        },
        {
            title: "Enterprise Risk Management (ERM)",
            description: "Strategic risk management frameworks for modern organizations",
            link: "/programmes/grc",
            theme: "cyan",
            bgColor: "cyan-50",
            borderColor: "cyan-100",
            iconBg: "cyan-600",
            textColor: "cyan-900",
            hoverColor: "cyan-700",
            category: "GRC & Risk Management",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        // Financial Crime Prevention Courses
        {
            title: "Financial Crime Prevention",
            description: "Specialised courses in AML, fraud prevention, sanctions, and investigations",
            link: "/programmes/financial-crime",
            theme: "red",
            bgColor: "red-50",
            borderColor: "red-100",
            iconBg: "red-600",
            textColor: "red-900",
            hoverColor: "red-700",
            category: "Financial Crime Prevention",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        {
            title: "AML & Counter-Terrorist Financing",
            description: "Advanced anti-money laundering frameworks and CTF compliance strategies",
            link: "/programmes/financial-crime",
            theme: "rose",
            bgColor: "rose-50",
            borderColor: "rose-100",
            iconBg: "rose-600",
            textColor: "rose-900",
            hoverColor: "rose-700",
            category: "Financial Crime Prevention",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            )
        },
        // AI Courses
        {
            title: "AI Governance, Ethics & Accountability",
            description: "Governance frameworks and ethical considerations for AI deployment",
            link: "/programmes/ai",
            theme: "purple",
            bgColor: "purple-50",
            borderColor: "purple-100",
            iconBg: "purple-600",
            textColor: "purple-900",
            hoverColor: "purple-700",
            category: "AI & Emerging Tech",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            )
        },
        {
            title: "Algorithmic Risk, Bias & Explainability",
            description: "Identifying and mitigating risks in algorithmic decision-making",
            link: "/programmes/ai",
            theme: "orange",
            bgColor: "orange-50",
            borderColor: "orange-100",
            iconBg: "orange-600",
            textColor: "orange-900",
            hoverColor: "orange-700",
            category: "AI & Emerging Tech",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            )
        },
        {
            title: "RegTech & SupTech Applications",
            description: "Regulatory technology applications and supervisory technology implementation",
            link: "/programmes/ai",
            theme: "green",
            bgColor: "green-50",
            borderColor: "green-100",
            iconBg: "green-600",
            textColor: "green-900",
            hoverColor: "green-700",
            category: "AI & Emerging Tech",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
            )
        },
        {
            title: "Data Governance, Ownership & Protection",
            description: "Comprehensive data governance frameworks for AI and advanced analytics",
            link: "/programmes/ai",
            theme: "blue",
            bgColor: "blue-50",
            borderColor: "blue-100",
            iconBg: "blue-600",
            textColor: "blue-900",
            hoverColor: "blue-700",
            category: "AI & Emerging Tech",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        {
            title: "Technology Ethics & Responsible Innovation",
            description: "Ethical frameworks for technology development and deployment",
            link: "/programmes/ai",
            theme: "teal",
            bgColor: "teal-50",
            borderColor: "teal-100",
            iconBg: "teal-600",
            textColor: "teal-900",
            hoverColor: "teal-700",
            category: "AI & Emerging Tech",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            )
        },
        {
            title: "AI Regulatory Compliance & Risk Management",
            description: "Regulatory requirements and risk management for AI systems",
            link: "/programmes/ai",
            theme: "red",
            bgColor: "red-50",
            borderColor: "red-100",
            iconBg: "red-600",
            textColor: "red-900",
            hoverColor: "red-700",
            category: "AI & Emerging Tech",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        // Crypto Courses
        {
            title: "Crypto-Asset Regulation & Compliance",
            description: "Regulatory frameworks for crypto-assets across jurisdictions and compliance obligations",
            link: "/programmes/crypto",
            theme: "indigo",
            bgColor: "indigo-50",
            borderColor: "indigo-100",
            iconBg: "indigo-600",
            textColor: "indigo-900",
            hoverColor: "indigo-700",
            category: "Crypto & Digital Assets",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            )
        },
        {
            title: "AML, Sanctions & Financial Crime Risks in Crypto",
            description: "Financial crime risks specific to crypto-assets and preventive measures",
            link: "/programmes/crypto",
            theme: "pink",
            bgColor: "pink-50",
            borderColor: "pink-100",
            iconBg: "pink-600",
            textColor: "pink-900",
            hoverColor: "pink-700",
            category: "Crypto & Digital Assets",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        {
            title: "Blockchain Technology: Governance, Risk & Controls",
            description: "Understanding blockchain technology and implementing governance frameworks",
            link: "/programmes/crypto",
            theme: "cyan",
            bgColor: "cyan-50",
            borderColor: "cyan-100",
            iconBg: "cyan-600",
            textColor: "cyan-900",
            hoverColor: "cyan-700",
            category: "Crypto & Digital Assets",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            )
        },
        {
            title: "Decentralized Finance (DeFi) Risk & Oversight",
            description: "Risk management and oversight frameworks for DeFi protocols and platforms",
            link: "/programmes/crypto",
            theme: "amber",
            bgColor: "amber-50",
            borderColor: "amber-100",
            iconBg: "amber-600",
            textColor: "amber-900",
            hoverColor: "amber-700",
            category: "Crypto & Digital Assets",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            )
        },
        {
            title: "Virtual Asset Service Provider (VASP) Compliance",
            description: "Compliance requirements and frameworks for VASPs under FATF standards",
            link: "/programmes/crypto",
            theme: "lime",
            bgColor: "lime-50",
            borderColor: "lime-100",
            iconBg: "lime-600",
            textColor: "lime-900",
            hoverColor: "lime-700",
            category: "Crypto & Digital Assets",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        {
            title: "Crypto Custody & Security Solutions",
            description: "Custody solutions, security frameworks, and asset protection for crypto",
            link: "/programmes/crypto",
            theme: "violet",
            bgColor: "violet-50",
            borderColor: "violet-100",
            iconBg: "violet-600",
            textColor: "violet-900",
            hoverColor: "violet-700",
            category: "Crypto & Digital Assets",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            )
        },
        // Cybersecurity Courses
        {
            title: "Cyber Risk Governance for Boards & Executives",
            description: "Strategic cyber risk oversight and governance for senior leadership",
            link: "/programmes/cybersecurity",
            theme: "emerald",
            bgColor: "emerald-50",
            borderColor: "emerald-100",
            iconBg: "emerald-600",
            textColor: "emerald-900",
            hoverColor: "emerald-700",
            category: "Cybersecurity",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            )
        },
        {
            title: "Cybercrime, Fraud & Digital Threats",
            description: "Understanding and mitigating cyber-enabled financial crime and fraud",
            link: "/programmes/cybersecurity",
            theme: "rose",
            bgColor: "rose-50",
            borderColor: "rose-100",
            iconBg: "rose-600",
            textColor: "rose-900",
            hoverColor: "rose-700",
            category: "Cybersecurity",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        {
            title: "Data Protection, Privacy & Regulatory Compliance",
            description: "Global data protection regulations and privacy compliance frameworks",
            link: "/programmes/cybersecurity",
            theme: "sky",
            bgColor: "sky-50",
            borderColor: "sky-100",
            iconBg: "sky-600",
            textColor: "sky-900",
            hoverColor: "sky-700",
            category: "Cybersecurity",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            )
        },
        {
            title: "Technology Risk Management & Operational Resilience",
            description: "Managing technology risks and building operational resilience",
            link: "/programmes/cybersecurity",
            theme: "amber",
            bgColor: "amber-50",
            borderColor: "amber-100",
            iconBg: "amber-600",
            textColor: "amber-900",
            hoverColor: "amber-700",
            category: "Cybersecurity",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            )
        },
        {
            title: "Incident Response, Breach Management & Regulatory Reporting",
            description: "Effective incident response and regulatory compliance during cyber incidents",
            link: "/programmes/cybersecurity",
            theme: "indigo",
            bgColor: "indigo-50",
            borderColor: "indigo-100",
            iconBg: "indigo-600",
            textColor: "indigo-900",
            hoverColor: "indigo-700",
            category: "Cybersecurity",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            )
        },
        {
            title: "Third-Party & Supply Chain Cyber Risk",
            description: "Managing cyber risks across supply chains and third-party relationships",
            link: "/programmes/cybersecurity",
            theme: "slate",
            bgColor: "slate-50",
            borderColor: "slate-100",
            iconBg: "slate-600",
            textColor: "slate-900",
            hoverColor: "slate-700",
            category: "Cybersecurity",
            svg: (
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            )
        }
    ];

    const goToPrevious = () => {
        setDirection(-1);
        setIsAutoPlaying(false);
        setCurrentIndex((prevIndex) => 
            prevIndex === 0 ? allCourses.length - 1 : prevIndex - 1
        );
    };

    const goToNext = () => {
        setDirection(1);
        setIsAutoPlaying(false);
        setCurrentIndex((prevIndex) => 
            prevIndex === allCourses.length - 1 ? 0 : prevIndex + 1
        );
    };

    const goToSlide = (index) => {
        setDirection(index > currentIndex ? 1 : -1);
        setIsAutoPlaying(false);
        setCurrentIndex(index);
    };

    useEffect(() => {
        if (!isAutoPlaying) return;
        const interval = setInterval(() => {
            setDirection(1);
            setCurrentIndex((prevIndex) => 
                prevIndex === allCourses.length - 1 ? 0 : prevIndex + 1
            );
        }, 5000);
        return () => clearInterval(interval);
    }, [isAutoPlaying, allCourses.length]);

    const handleMouseEnter = () => setIsAutoPlaying(false);
    const handleMouseLeave = () => setIsAutoPlaying(true);

    const slideVariants = {
        enter: (direction) => ({
            x: direction > 0 ? 400 : -400,
            opacity: 0,
            scale: 0.9
        }),
        center: {
            x: 0,
            opacity: 1,
            scale: 1,
            transition: {
                x: { type: "spring", stiffness: 300, damping: 30 },
                opacity: { duration: 0.3 },
                scale: { duration: 0.3 }
            }
        },
        exit: (direction) => ({
            x: direction > 0 ? -400 : 400,
            opacity: 0,
            scale: 0.9,
            transition: {
                x: { type: "spring", stiffness: 300, damping: 30 },
                opacity: { duration: 0.3 },
                scale: { duration: 0.3 }
            }
        })
    };

    const currentCourse = allCourses[currentIndex];

    return (
        <section className="py-20 bg-white">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center mb-16">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">
                        What We Offer
                    </h2>
                    <p className="text-gray-600 text-lg max-w-3xl mx-auto">
                        Advanced professional programmes at the intersection of regulation, risk, and technology
                    </p>
                </div>

                {/* Carousel Section */}
                <div 
                    className="relative"
                    onMouseEnter={handleMouseEnter}
                    onMouseLeave={handleMouseLeave}
                >
                    <div className="relative overflow-hidden rounded-2xl">
                        <AnimatePresence initial={false} custom={direction} mode="wait">
                            <motion.div
                                key={currentIndex}
                                custom={direction}
                                variants={slideVariants}
                                initial="enter"
                                animate="center"
                                exit="exit"
                                className={`bg-${currentCourse.bgColor} p-8 rounded-2xl border border-${currentCourse.borderColor} hover:shadow-xl transition-shadow duration-300`}
                            >
                                <div className="flex flex-col items-center text-center">
                                    <div className={`w-20 h-20 ${currentCourse.iconBg} rounded-xl flex items-center justify-center mb-6`}>
                                        <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {currentCourse.svg}
                                        </svg>
                                    </div>
                                    <span className="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/50 text-gray-700 mb-3">
                                        {currentCourse.category}
                                    </span>
                                    <h3 className="text-2xl font-bold text-gray-900 mb-3">{currentCourse.title}</h3>
                                    <p className="text-gray-600 mb-6 max-w-2xl">
                                        {currentCourse.description}
                                    </p>
                                    <a 
                                        href={currentCourse.link} 
                                        className={`text-${currentCourse.textColor} font-medium hover:text-${currentCourse.hoverColor} inline-flex items-center text-lg`}
                                    >
                                        Learn More
                                        <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </motion.div>
                        </AnimatePresence>
                    </div>

                    {/* Navigation Buttons */}
                    <button
                        onClick={goToPrevious}
                        className="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 hover:scale-110"
                        aria-label="Previous course"
                    >
                        <ChevronLeft />
                    </button>
                    <button
                        onClick={goToNext}
                        className="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 hover:scale-110"
                        aria-label="Next course"
                    >
                        <ChevronRight />
                    </button>

                    {/* Dots Indicator */}
                    <div className="flex justify-center mt-8 space-x-2 flex-wrap gap-2">
                        {allCourses.map((_, index) => (
                            <button
                                key={index}
                                onClick={() => goToSlide(index)}
                                className={`transition-all duration-300 rounded-full ${
                                    index === currentIndex
                                        ? "w-8 h-2 bg-blue-600"
                                        : "w-2 h-2 bg-gray-300 hover:bg-blue-400"
                                }`}
                                aria-label={`Go to slide ${index + 1}`}
                            />
                        ))}
                    </div>

                    {/* Auto-play indicator */}
                    <div className="absolute bottom-16 left-1/2 transform -translate-x-1/2">
                        <div className="flex items-center space-x-2 bg-white/80 backdrop-blur-sm px-3 py-1 rounded-full text-xs text-gray-500">
                            <div className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span>{isAutoPlaying ? "Auto-playing" : "Paused"}</span>
                        </div>
                    </div>
                </div>

                {/* Category Quick Links */}
                <div className="grid grid-cols-1 md:grid-cols-5 gap-4 mt-16">
                    <a href="/programmes/grc" className="bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-4 rounded-xl text-center hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <h3 className="text-sm font-bold mb-1">GRC & Risk</h3>
                        <p className="text-blue-100 text-xs">2 courses →</p>
                    </a>
                    <a href="/programmes/financial-crime" className="bg-gradient-to-r from-red-600 to-rose-600 text-white p-4 rounded-xl text-center hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <h3 className="text-sm font-bold mb-1">Financial Crime</h3>
                        <p className="text-red-100 text-xs">2 courses →</p>
                    </a>
                    <a href="/programmes/ai" className="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-4 rounded-xl text-center hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <h3 className="text-sm font-bold mb-1">AI & Emerging Tech</h3>
                        <p className="text-purple-100 text-xs">6 courses →</p>
                    </a>
                    <a href="/programmes/crypto" className="bg-gradient-to-r from-indigo-600 to-violet-600 text-white p-4 rounded-xl text-center hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <h3 className="text-sm font-bold mb-1">Crypto & Digital Assets</h3>
                        <p className="text-indigo-100 text-xs">6 courses →</p>
                    </a>
                    <a href="/programmes/cybersecurity" className="bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-4 rounded-xl text-center hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <h3 className="text-sm font-bold mb-1">Cybersecurity</h3>
                        <p className="text-emerald-100 text-xs">6 courses →</p>
                    </a>
                </div>

                <div className="text-center mt-12">
                    <a href="/programmes" className="inline-flex items-center justify-center bg-blue-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 transition duration-300">
                        Explore All Programmes
                        <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    );
}