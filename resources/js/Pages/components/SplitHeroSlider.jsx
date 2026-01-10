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