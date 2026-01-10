import { useState, useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

export default function SplitHeroSlider({ auth }) {
    const [activeSlide, setActiveSlide] = useState(0);
    const [isAnimating, setIsAnimating] = useState(false);
    const slideInterval = useRef(null);
    const textSliderRef = useRef(null);
    const imageSliderRef = useRef(null);

    const slides = [
        {
            id: 1,
            title: "Learn From The Best",
            highlighted: "Tutors",
            description: "Join thousands of learners who are advancing their careers with personalized tutoring from industry experts.",
            image: "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            ctaPrimary: "Start Learning Free",
            ctaSecondary: "Browse Courses"
        },
        {
            id: 2,
            title: "Master",
            highlighted: "GRC & Compliance",
            description: "Get certified in Governance, Risk & Compliance with industry-recognized qualifications from leading professionals.",
            image: "https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            ctaPrimary: "Explore Courses",
            ctaSecondary: "View Certifications"
        },
        {
            id: 3,
            title: "Advance Your Career in",
            highlighted: "Financial Crime Prevention",
            description: "Specialize in AML, KYC, and fraud detection with practical training from real-world scenarios.",
            image: "https://images.unsplash.com/photo-1551836026-d5c2c5af78e4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            ctaPrimary: "Learn More",
            ctaSecondary: "Join Now"
        },
        {
            id: 4,
            title: "Become an Expert in",
            highlighted: "Cybersecurity & Data Protection",
            description: "Protect financial institutions from cyber threats with cutting-edge security training and certifications.",
            image: "https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            ctaPrimary: "Start Training",
            ctaSecondary: "Course Details"
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
        }, 6000);
    };

    const stopAutoSlide = () => {
        if (slideInterval.current) {
            clearInterval(slideInterval.current);
        }
    };

    const nextSlide = () => {
        if (isAnimating) return;
        setIsAnimating(true);
        setActiveSlide((prev) => (prev + 1) % slides.length);
        setTimeout(() => setIsAnimating(false), 800);
    };

    const prevSlide = () => {
        if (isAnimating) return;
        setIsAnimating(true);
        setActiveSlide((prev) => (prev - 1 + slides.length) % slides.length);
        setTimeout(() => setIsAnimating(false), 800);
    };

    const goToSlide = (index) => {
        if (isAnimating || index === activeSlide) return;
        setIsAnimating(true);
        setActiveSlide(index);
        setTimeout(() => setIsAnimating(false), 800);
    };

    return (
        <section 
            className="relative bg-gradient-to-r from-blue-50 via-white to-indigo-50 overflow-hidden"
            data-aos="fade-up"
            data-aos-duration="1400"
            onMouseEnter={stopAutoSlide}
            onMouseLeave={startAutoSlide}
        >
            {/* Background Pattern */}
            <div className="absolute inset-0 opacity-5">
                <div className="absolute inset-0 bg-gradient-to-r from-blue-100 to-transparent"></div>
                <div className="absolute right-0 top-0 w-1/3 h-full bg-gradient-to-l from-indigo-100 to-transparent"></div>
            </div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                    
                    {/* Left Column - Text Slider - FIXED */}
                    {/* Left Column - Text Slider */}
<div 
    className="relative h-[400px] lg:h-[500px] flex items-center"
    ref={textSliderRef}
>
    <div className="relative w-full overflow-hidden">
        {slides.map((slide, index) => (
            <div
                key={slide.id}
                className={`absolute inset-0 transition-all duration-700 ease-in-out transform ${
                    index === activeSlide 
                        ? 'translate-x-0 opacity-100 z-10' 
                        : 'translate-x-full opacity-0 z-0'
                }`}
            >
                <div className="space-y-6 lg:space-y-8">
                    {/* Badge */}
                    <div 
                        className="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-600 font-medium text-sm mb-4"
                    >
                        <span className="w-2 h-2 bg-blue-600 rounded-full mr-2 animate-pulse"></span>
                        Slide {index + 1} of {slides.length}
                    </div>

                    {/* Title */}
                    <h1 
                        className="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight"
                    >
                        {slide.title} <span className="text-blue-600 relative">
                            {slide.highlighted}
                            <span className="absolute -bottom-2 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full"></span>
                        </span>
                    </h1>

                    {/* Description */}
                    <p 
                        className="text-lg md:text-xl text-gray-600 max-w-xl"
                    >
                        {slide.description}
                    </p>

                    {/* CTA Buttons */}
                    <div 
                        className="flex flex-col sm:flex-row gap-4 pt-4"
                    >
                        {!auth?.user ? (
                            <>
                                <Link
                                    href={route('register')}
                                    className="group bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02] shadow-lg hover:shadow-blue-500/25 inline-flex items-center justify-center"
                                >
                                    {slide.ctaPrimary}
                                    <svg className="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </Link>
                                <Link
                                    href="/courses"
                                    className="group bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-50 transition-all duration-300 border-2 border-blue-600 hover:border-blue-700 transform hover:-translate-y-1 hover:scale-[1.02] shadow-md inline-flex items-center justify-center"
                                >
                                    {slide.ctaSecondary}
                                    <svg className="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </>
                        ) : (
                            <Link
                                href={route('dashboard')}
                                className="group bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.02] shadow-lg hover:shadow-blue-500/25 inline-flex items-center justify-center"
                            >
                                Go to Dashboard
                                <svg className="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </Link>
                        )}
                    </div>

                    {/* Stats */}
                    <div 
                        className="grid grid-cols-3 gap-4 pt-8 border-t border-gray-200 mt-8"
                    >
                        <div className="text-center">
                            <div className="text-2xl font-bold text-blue-600">10K+</div>
                            <div className="text-sm text-gray-500">Students Enrolled</div>
                        </div>
                        <div className="text-center">
                            <div className="text-2xl font-bold text-blue-600">98%</div>
                            <div className="text-sm text-gray-500">Success Rate</div>
                        </div>
                        <div className="text-center">
                            <div className="text-2xl font-bold text-blue-600">50+</div>
                            <div className="text-sm text-gray-500">Expert Tutors</div>
                        </div>
                    </div>
                </div>
            </div>
        ))}
    </div>

    {/* Slider Controls - Text Side */}
    <div className="absolute -bottom-4 left-0 right-0 flex justify-center lg:justify-start space-x-2">
        {slides.map((_, index) => (
            <button
                key={index}
                onClick={() => goToSlide(index)}
                className={`w-12 h-2 rounded-full transition-all duration-300 ${
                    index === activeSlide 
                        ? 'bg-blue-600 w-16' 
                        : 'bg-gray-300 hover:bg-gray-400'
                }`}
                aria-label={`Go to slide ${index + 1}`}
            />
        ))}
    </div>

    {/* Navigation Arrows - Text Side */}
    <div className="absolute -right-4 top-1/2 transform -translate-y-1/2 hidden lg:flex flex-col space-y-2">
        <button
            onClick={prevSlide}
            className="w-10 h-10 rounded-full bg-white shadow-lg flex items-center justify-center hover:bg-blue-50 hover:shadow-xl transition-all duration-300 group"
            aria-label="Previous slide"
        >
            <svg className="w-5 h-5 text-gray-700 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button
            onClick={nextSlide}
            className="w-10 h-10 rounded-full bg-white shadow-lg flex items-center justify-center hover:bg-blue-50 hover:shadow-xl transition-all duration-300 group"
            aria-label="Next slide"
        >
            <svg className="w-5 h-5 text-gray-700 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>

                    {/* Right Column - Image Slider */}
                    <div 
                        className="relative h-[400px] lg:h-[500px]"
                        ref={imageSliderRef}
                    >
                        <div className="relative w-full h-full overflow-hidden rounded-2xl shadow-2xl">
                            {slides.map((slide, index) => (
                                <div
                                    key={slide.id}
                                    className={`absolute inset-0 transition-all duration-700 ease-in-out ${
                                        index === activeSlide 
                                            ? 'translate-x-0 opacity-100 scale-100 z-10' 
                                            : index < activeSlide
                                            ? '-translate-x-full opacity-0 scale-95 z-0'
                                            : 'translate-x-full opacity-0 scale-95 z-0'
                                    }`}
                                >
                                    {/* Image */}
                                    <div 
                                        className="absolute inset-0 bg-cover bg-center"
                                        style={{ backgroundImage: `url(${slide.image})` }}
                                    >
                                        <div className="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                                    </div>

                                    {/* Image Overlay Content */}
                                    <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                                        <div className="transform transition-all duration-700 delay-500">
                                            <div className="flex items-center space-x-2 mb-2">
                                                <span className="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                                                <span className="text-sm font-medium">Professional Training</span>
                                            </div>
                                            <h3 className="text-xl font-bold mb-2">{slide.highlighted}</h3>
                                            <p className="text-blue-100 text-sm">Slide {index + 1} of {slides.length}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {/* Image Navigation Overlay */}
                            <div className="absolute inset-0 flex items-center justify-between p-4 z-20">
                                <button
                                    onClick={prevSlide}
                                    className="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-all duration-300 transform hover:scale-110 group"
                                    aria-label="Previous image"
                                >
                                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button
                                    onClick={nextSlide}
                                    className="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-all duration-300 transform hover:scale-110 group"
                                    aria-label="Next image"
                                >
                                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>

                            {/* Image Progress Indicator */}
                            <div className="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
                                {slides.map((_, index) => (
                                    <div
                                        key={index}
                                        className={`w-8 h-1 rounded-full transition-all duration-300 ${
                                            index === activeSlide 
                                                ? 'bg-white w-12' 
                                                : 'bg-white/50'
                                        }`}
                                    />
                                ))}
                            </div>
                        </div>

                        {/* Floating Badge */}
                        <div className="absolute -top-4 -right-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl shadow-xl transform rotate-3 z-20">
                            <div className="flex items-center space-x-2">
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span className="font-bold">Certified</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Bottom Controls */}
                <div className="flex justify-center items-center space-x-6 mt-12 lg:mt-0">
                    <button
                        onClick={prevSlide}
                        className="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors duration-300"
                        aria-label="Previous slide"
                    >
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                        </svg>
                        <span className="hidden sm:inline">Previous</span>
                    </button>
                    
                    <div className="flex space-x-2">
                        {slides.map((slide, index) => (
                            <button
                                key={slide.id}
                                onClick={() => goToSlide(index)}
                                className={`flex flex-col items-center space-y-2 group ${
                                    index === activeSlide ? 'text-blue-600' : 'text-gray-400'
                                }`}
                                aria-label={`View slide ${index + 1}: ${slide.title}`}
                            >
                                <div className={`w-3 h-3 rounded-full transition-all duration-300 ${
                                    index === activeSlide 
                                        ? 'bg-blue-600 scale-125' 
                                        : 'bg-gray-300 group-hover:bg-gray-400'
                                }`} />
                                <span className="text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    {index + 1}
                                </span>
                            </button>
                        ))}
                    </div>
                    
                    <button
                        onClick={nextSlide}
                        className="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors duration-300"
                        aria-label="Next slide"
                    >
                        <span className="hidden sm:inline">Next</span>
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                {/* Auto-play toggle */}
                <div className="flex justify-center items-center space-x-2 mt-6">
                    <span className="text-sm text-gray-500">Auto-slide</span>
                    <button
                        onClick={() => slideInterval.current ? stopAutoSlide() : startAutoSlide()}
                        className="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                        aria-label="Toggle auto-slide"
                    >
                        <span className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ${
                            slideInterval.current ? 'translate-x-6' : 'translate-x-1'
                        }`} />
                    </button>
                    <button
                        onClick={startAutoSlide}
                        className="text-sm text-blue-600 hover:text-blue-700 transition-colors duration-300"
                        aria-label="Restart auto-slide"
                    >
                        Restart
                    </button>
                </div>
            </div>
        </section>
    );
}