import React, { useState, useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';
import img1 from '../../assets/slider1.png';
import img2 from '../../assets/slider1.png';
import img3 from '../../assets/slider1.png';
 
export default function SplitHeroSlider({ auth }) {
    const [activeSlide, setActiveSlide] = useState(0);
    const slideInterval = useRef(null);
   
    const slides = [ 
        {
            id: 1,
            title: "Empower Professionals, Strengthening Governance, Preventing Financial Crime",
            highlighted: "",
            description: "The Institute of GRC & Financial Crime Prevention (IGRCFP) is a global professional body dedicated to advancing excellence in governance, risk management, compliance, and financial crime prevention.",
            image: img1,
            ctaPrimary: "Join the Institute",
            ctaSecondary: "Browse Courses",
            secondaryRoute: route('igrcfp.certificates.index')
        },
        {
            id: 2,
            title: "Master",
            description: "Get certified in Governance, Risk & Compliance with industry-recognized qualifications from leading professionals.",
            image: img2,
            ctaPrimary: "Explore Courses",
            ctaSecondary: "View Certifications",
            secondaryRoute: route('igrcfp.certificates.index')
        },
        {
            id: 3,
            title: "Advance Your Career in",
            description: "Specialize in AML, KYC, and fraud detection with practical training from real-world scenarios.",
            image: img3,
            ctaPrimary: "Learn More",
            ctaSecondary: "Join Now",
            secondaryRoute: route('igrcfp.certificates.index')
        },
        // {
        //     id: 4,
        //     title: "Become an Expert in",
        //     highlighted: "Cybersecurity & Data Protection",
        //     description: "Protect financial institutions from cyber threats with cutting-edge security training and certifications.",
        //     image: img3,
        //     ctaPrimary: "Start Training",
        //     ctaSecondary: "Course Details",
        //     secondaryRoute: route('igrcfp.certificates.index')
        // }
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
        }, 6000);
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
            className="relative overflow-hidden bg-white"
            data-aos="fade-up"
            data-aos-duration="1400"
            onMouseEnter={stopAutoSlide}
            onMouseLeave={startAutoSlide}
        >
            {/* Background Image that covers the entire section */}
            <div 
                className="absolute inset-0 bg-cover bg-center transition-all duration-700"
                style={{ 
                    backgroundImage: `url(${slides[activeSlide].image})`,
                }}
            >
                {/* Dark overlay for better text visibility */}
                {/* <div className="absolute inset-0 bg-black/40"></div> */}
            </div>

            <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="min-h-[600px] lg:min-h-[700px] flex items-center">
                    
                    {/* Left Column - Text Content */}
                    <div className="w-full lg:w-3/5 py-12 lg:py-20">
                        <div className="relative w-full max-w-xl mx-auto lg:mx-0">
                            {/* Title */}
                            <h3 className="text-3xl md:text-4xl lg:text-5xl font-bold text-black leading-tight">
                                {slides[activeSlide].title} 
                                {slides[activeSlide].highlighted && (
                                    <span className="text-blue-300"> {slides[activeSlide].highlighted}</span>
                                )}
                            </h3>
                            
                            {/* Description */}
                            <p className="text-lg md:text-xl text-black-100 max-w-xl mt-4">
                                {slides[activeSlide].description}
                            </p>
                            
                            {/* CTA Buttons */}
                            <div className="flex flex-col sm:flex-row gap-4 pt-8">
                                {!auth?.user ? (
                                    <>
                                        <Link
                                            href={route('register')}
                                            className="group bg-gradient-to-r from-blue-800 to-blue-800 text-white px-7 py-2.5 rounded-full  font-semibold text-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02] shadow-lg hover:shadow-blue-500/25 inline-flex items-center justify-center"
                                        >
                                            {slides[activeSlide].ctaPrimary}
                                            <svg className="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </Link>
                                        <Link
                                            href={slides[activeSlide].secondaryRoute}
                                            className="group bg-white/10 backdrop-blur-sm text-blue-800 px-8 py-2.5 rounded-full font-semibold text-lg hover:bg-white/20 transition-all duration-300 border-2 border-blue-800 d hover:border-blue-800/80 transform hover:-translate-y-1 hover:scale-[1.02] shadow-md inline-flex items-center justify-center"
                                        >
                                            {slides[activeSlide].ctaSecondary}
                                            <svg className="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                            </svg>
                                        </Link>
                                    </>
                                ) : (
                                    <Link
                                        href={route('dashboard.index')}
                                        className="group bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02] shadow-lg hover:shadow-blue-500/25 inline-flex items-center justify-center"
                                    >
                                        Go to Dashboard
                                        <svg className="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Right Column - Empty (image is full background) */}
                    <div className="hidden lg:block lg:w-2/5"></div>
                </div>
            </div>

            {/* Navigation Controls - Positioned on the Right */}
            <div className="absolute bottom-8 right-4 lg:right-8 z-20 flex items-center gap-4">
                <button
                    onClick={prevSlide}
                    className="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-all duration-300 transform hover:scale-110"
                    aria-label="Previous slide"
                >
                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <div className="flex space-x-2">
                    {slides.map((_, index) => (
                        <button
                            key={index}
                            onClick={() => goToSlide(index)}
                            className={`h-1 rounded-full transition-all duration-300 ${
                                index === activeSlide 
                                    ? 'bg-white w-12' 
                                    : 'bg-white/50 w-8 hover:bg-white/70'
                            }`}
                        />
                    ))}
                </div>
                
                <button
                    onClick={nextSlide}
                    className="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-all duration-300 transform hover:scale-110"
                    aria-label="Next slide"
                >
                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </section>
    );
}