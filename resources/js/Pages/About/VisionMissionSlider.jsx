import { useState, useEffect } from 'react';


export default function VisionMissionSlider() {
   

useEffect(() => {
    const interval = setInterval(() => {
        setActiveIndex((prev) => (prev + 1) % slides.length);
    }, 6000);

    return () => clearInterval(interval);
}, []);

    const slides = [
        {
            title: 'OUR VISION',
            content:
                'To be the foremost international institute advancing governance, compliance, and financial crime prevention through knowledge, innovation, and collaboration.',
        },
        {
            title: 'OUR MISSION',
            content:
                'Elevate GRC & FCC standards globally, empower professionals with skills,
                certifications, and mentorship, empower professionals with skills, certifications,
                and mentorship & champion diversity, equity, and inclusion in compliance leadership',
        },
    ];

    const [activeIndex, setActiveIndex] = useState(0);

    return (
        <section className="py-28 bg-white">
            <div className="max-w-4xl mx-auto px-4 text-center">

                {/* Title with Divider Lines */}
                <div className="flex items-center justify-center gap-6 mb-10">
                    <span className="w-20 h-px bg-gray-300"></span>
                    <h2 className="text-sm tracking-widest font-semibold text-gray-900">
                        {slides[activeIndex].title}
                    </h2>
                    <span className="w-20 h-px bg-gray-300"></span>
                </div>

                {/* Slide Content */}
                <div className="relative min-h-[120px]">
                    {slides.map((slide, index) => (
                        <p
                            key={index}
                            className={`absolute inset-0 text-lg md:text-xl text-gray-700 leading-relaxed max-w-3xl mx-auto transition-opacity duration-500 ${
                                index === activeIndex
                                    ? 'opacity-100'
                                    : 'opacity-0 pointer-events-none'
                            }`}
                        >
                            {slide.content}
                        </p>
                    ))}
                </div>

                {/* Navigation Dots */}
                <div className="flex items-center justify-center gap-3 mt-14">
                    {slides.map((_, index) => (
                        <button
                            key={index}
                            onClick={() => setActiveIndex(index)}
                            className={`w-4 h-4 rounded-full transition ${
                                index === activeIndex
                                    ? 'bg-slate-900'
                                    : 'bg-gray-300 hover:bg-gray-400'
                            }`}
                            aria-label={`Go to slide ${index + 1}`}
                        />
                    ))}
                </div>

            </div>
        </section>
    );
}
