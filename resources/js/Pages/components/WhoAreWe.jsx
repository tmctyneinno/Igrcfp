import { useState, useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

export default function WhoAreWe({ auth }) {
    const [activeSlide, setActiveSlide] = useState(0);
    const slideInterval = useRef(null);

    // (Optional future use)
    const slides = [0];

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

    return (
        <section className="who-we-are">
            <div className="container">
                <div className="content-grid">
                    
                    {/* LEFT TEXT */}
                    <div className="text-content">
                        <span className="subtitle">Who We Are</span>
                        <h2>Know More About Us</h2>

                        <p>
                            The Institute of Governance, Risk & Compliance & Financial Crime
                            Prevention (IGRCFP) is a global professional body dedicated to
                            raising standards in governance, risk management, compliance,
                            and financial crime prevention.
                        </p>

                        <p>
                            We equip professionals and organizations with world-class
                            training, certifications, and resources to stay ahead in a
                            fast-changing regulatory environment.
                        </p>

                        <p>
                            With a presence across Africa, Europe, Asia, the Middle East,
                            and the Americas, IGRCFP connects experts, regulators, and
                            industry leaders to share knowledge, drive innovation, and
                            build stronger institutions worldwide.
                        </p>

                        <Link href="/about-us" className="cta-btn">
                            Learn More About Us →
                        </Link>
                    </div>

                    {/* RIGHT IMAGE */}
                    <div className="image-content">
                        <img
                            src="/assets/images/about/network.png"
                            alt="IGRCFP global network"
                        />
                    </div>

                </div>
            </div>
        </section>
    );
}
