import React, { useState, useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

export default function SplitHeroSlider({ auth }) {
    const [activeSlide, setActiveSlide] = useState(0);
    const slideInterval = useRef(null);
 
    const slides = [
        {
            id: 1,
            title: "Empower Professionals, Strengthening Governance, Preventing Financial Crime",
            description: "The Institute of GRC & Financial Crime Prevention (IGRCFP) is a global professional body dedicated to advancing excellence in governance, risk management, compliance, and financial crime prevention.",
            label: "PROFESSIONAL BODY . GLOBAL STANDARDS . LONDON, UK",
            stats: [
                { value: "12K+", label: "CERTIFICATE PROGRAMMES" },
                { value: "7", label: "KNOWLEDGE DOMAIN" },
                { value: "3", label: "LEARNING PATHWAYS" },
                { value: "5", label: "DELIVERY METHODS" }
            ],
            ctaPrimary: "Join the Institute",
            ctaSecondary: "Browse Courses",
            primaryRoute: route('register'),
            secondaryRoute: route('igrcfp.certificates.index')
        }
    ];

    // Auto slide functionality
    useEffect(() => {
        startAutoSlide();
        return () => stopAutoSlide();
    }, [activeSlide]);

    const startAutoSlide = () => {
        stopAutoSlide();
        slideInterval.current = setInterval(() => {
            nextSlide();
        }, 8000);
    };

    const stopAutoSlide = () => {
        if (slideInterval.current) {
            clearInterval(slideInterval.current);
        }
    };

    const nextSlide = () => {
        setActiveSlide((prev) => (prev + 1) % slides.length);
    };

    const prevSlide = () => {
        setActiveSlide((prev) => (prev - 1 + slides.length) % slides.length);
    };

    const goToSlide = (index) => {
        setActiveSlide(index);
    };

    return (
        <section 
            className="relative overflow-hidden bg-[#0A1E36]"
            data-aos="fade-up"
            data-aos-duration="1400"
            onMouseEnter={stopAutoSlide}
            onMouseLeave={startAutoSlide}
        >
            {/* Background Grid & Circles Pattern */}
            <div className="absolute inset-0 bg-[#0A1E36]">
                {/* Grid overlay */}
                <div className="absolute inset-0 opacity-10" style={{ backgroundImage: 'linear-gradient(#254066 1px, transparent 1px), linear-gradient(90deg, #254066 1px, transparent 1px)', backgroundSize: '40px 40px' }}></div>
                {/* Concentric circles */}
                <div className="absolute right-0 top-1/2 -translate-y-1/2 w-[600px] h-[600px] opacity-20">
                    <div className="absolute inset-0 rounded-full border border-blue-400/30"></div>
                    <div className="absolute inset-4 rounded-full border border-blue-400/25"></div>
                    <div className="absolute inset-8 rounded-full border border-blue-400/20"></div>
                    <div className="absolute inset-12 rounded-full border border-blue-400/15"></div>
                </div>
            </div>

            <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="min-h-[700px] flex items-center">
                    {/* Left Content */}
                    <div className="w-full lg:w-2/3 py-16">
                        {/* Top Label */}
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-12 h-[1px] bg-gray-300"></div>
                            <span className="text-gray-300 text-sm uppercase tracking-widest font-medium">
                                {slides[activeSlide].label}
                            </span>
                        </div>

                        {/* Main Heading */}
                        <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                            Empower Professionals,<br />
                            <span className="text-gray-300">Strengthening Governance,</span><br />
                            Preventing Financial Crime
                        </h1>

                        {/* Description */}
                        <p className="text-lg md:text-xl text-gray-300 max-w-2xl mb-10 leading-relaxed">
                            {slides[activeSlide].description}
                        </p>

                        {/* CTA Buttons */}
                        <div className="flex flex-wrap gap-4">
                            {!auth?.user ? (
                                <>
                                    <Link
                                        href={slides[activeSlide].primaryRoute}
                                        className="bg-gray-200 hover:bg-white text-gray-900 px-8 py-3 rounded-full font-semibold text-lg transition-all duration-300 shadow-lg hover:shadow-xl"
                                    >
                                        {slides[activeSlide].ctaPrimary}
                                    </Link>
                                    <Link
                                        href={slides[activeSlide].secondaryRoute}
                                        className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full font-semibold text-lg hover:bg-white/10 transition-all duration-300"
                                    >
                                        {slides[activeSlide].ctaSecondary}
                                    </Link>
                                </>
                            ) : (
                                <Link
                                    href={route('dashboard.index')}
                                    className="bg-white text-[#0A1E36] px-8 py-3 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
                                >
                                    Go to Dashboard
                                </Link>
                            )}
                        </div>
                    </div>

                    {/* Right Stats Panel */}
                    <div className="hidden lg:block lg:w-1/3 self-center">
                        <div className="space-y-6 text-right">
                            {slides[activeSlide].stats.map((stat, index) => (
                                <div key={index} className="text-right">
                                    <div className="text-4xl font-bold text-white mb-1">{stat.value}</div>
                                    <div className="text-sm text-gray-300 uppercase tracking-wider">{stat.label}</div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>

            {/* Bottom Tagline Strip */}
            <div className="absolute bottom-0 left-0 right-0 bg-[#08182C] border-t border-[#163052] py-3">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-300 uppercase tracking-wider">
                    <span>TERRORISM FINANCING</span>
                    <span>•</span>
                    <span>KYC & CDD</span>
                    <span>•</span>
                    <span>SANCTIONS COMPLIANCE</span>
                    <span>•</span>
                    <span>ENTERPRISE RISK MANAGEMENT</span>
                    <span>•</span>
                    <span>REGULATORY FRAME WORKS</span>
                    <span>•</span>
                    <span>ESG SUSTAINABLE FINANCE</span>
                    <span>•</span>
                    <span>AI IN COMPLIANCE</span>
                </div>
            </div>
        </section>
    );
}