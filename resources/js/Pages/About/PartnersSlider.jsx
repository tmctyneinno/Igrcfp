import React, { useEffect, useRef } from 'react';

export default function PartnersSlider() {
    const sliderRef = useRef(null);
 
    useEffect(() => {
        const slider = sliderRef.current;
        let animationFrame;
        let scrollAmount = 0;

        const scroll = () => {
            scrollAmount += 0.5; // speed control
            slider.scrollLeft = scrollAmount;

            if (scrollAmount >= slider.scrollWidth / 2) {
                scrollAmount = 0;
            }

            animationFrame = requestAnimationFrame(scroll);
        };

        animationFrame = requestAnimationFrame(scroll);

        return () => cancelAnimationFrame(animationFrame);
    }, []);

    const partners = [
        { name: 'Stripe', src: 'assets/images/partners/stripe.png' },
        { name: 'GDPR', src: 'assets/images/partners/gdpr.jpeg' },
        { name: 'ICO', src: 'assets/images/partners/ico.png' },
        { name: 'ISO', src: 'assets/images/partners/iso.png' },
        { name: 'EU', src: 'assets/images/partners/eu.png' },
    ];

    return (
        <section className="py-20 bg-white">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {/* Section Title */}
                <div className="text-center mb-12">
                    <h2 className="text-3xl md:text-3xl font-medium text-gray-900">
                        Our Partners & Affiliates
                    </h2>
                </div>

                {/* Slider */}
                <div
                    ref={sliderRef}
                    className="overflow-hidden whitespace-nowrap"
                >
                    <div className="flex items-center gap-x-16 w-max">
                        {[...partners, ...partners].map((partner, index) => (
                            <img
                                key={index}
                                src={partner.src}
                                alt={partner.name}
                                className="h-10 md:h-16 object-contain opacity-90 hover:opacity-100 transition"
                            />
                        ))}
                    </div>
                </div>

            </div>
        </section>
    );
}
