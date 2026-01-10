import { useState, useEffect, useRef } from 'react';
import { Link } from '@inertiajs/react';

export default function WhoAreWe({ auth }) {
    const [activeSlide, setActiveSlide] = useState(0);
    const slideInterval = useRef(null);

   
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
        <section>
          
        </section>
    );
}