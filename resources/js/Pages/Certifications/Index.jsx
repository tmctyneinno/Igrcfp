import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Certifications({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-blue-200 via-white to-blue-200 py-28">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                            {title}
                        </h1> 
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            The Institute of Governance, Risk, Compliance & Financial Crime Prevention 
                        </p>
                    </div>
                </div>
            </section>

            {/* Main Content Section */}
            <section className="w-full bg-white py-16 md:py-24">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                            Advance Your Career. Build Institutional Resilience.
                        </h2>
                        <p className="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto">
                            Our professional certifications are designed to equip individuals and institutions with globally relevant skills to tackle financial crime and compliance risks.
                        </p>
                    </div>

                    {/* Trusted By Section */}
                    <div className="mb-16">
                        <h3 className="text-center text-lg font-semibold text-gray-500 mb-8 uppercase tracking-wider">
                            TRUSTED BY
                        </h3>
                        
                        {/* Stats Grid */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl mx-auto">
                            {/* Learners Stat */}
                            <div className="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                                <div className="text-4xl md:text-5xl font-bold text-blue-600 mb-2">
                                    40k+
                                </div>
                                <div className="text-lg font-medium text-gray-700">
                                    learners
                                </div>
                                <p className="text-gray-500 mt-2 text-sm">
                                    Professionals trained worldwide
                                </p>
                            </div>
                            
                            {/* Experts Stat */}
                            <div className="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                                <div className="text-4xl md:text-5xl font-bold text-green-600 mb-2">
                                    100+
                                </div>
                                <div className="text-lg font-medium text-gray-700">
                                    experts
                                </div>
                                <p className="text-gray-500 mt-2 text-sm">
                                    Industry-leading instructors
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Logo Grid - Placeholder for trusted companies */}
                    <div className="mt-16 pt-8 border-t border-gray-200">
                        <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center justify-center">
                            {/* Replace these with actual company logos */}
                            {[1, 2, 3, 4, 5, 6].map((i) => (
                                <div 
                                    key={i}
                                    className="h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 font-medium"
                                >
                                    LOGO {i}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* Call to Action Section */}
            <section className="w-full bg-gradient-to-r from-blue-500 to-blue-600 py-16">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl md:text-4xl font-bold text-white mb-6">
                        Ready to Get Certified?
                    </h2>
                    <p className="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                        Join thousands of professionals who have advanced their careers with our globally recognized certifications.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link 
                            href="/certifications#programs"
                            className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition duration-300"
                        >
                            View Programs
                        </Link>
                        <Link 
                            href="/contact"
                            className="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-blue-600 md:py-4 md:text-lg md:px-10 transition duration-300"
                        >
                            Contact Admissions
                        </Link>
                    </div>
                </div>
            </section>

            {/* Certification Programs Preview */}
            <section className="w-full bg-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
                        Featured Certification Programs
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {/* Program 1 */}
                        <div className="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                            <div className="p-8">
                                <div className="inline-block px-4 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
                                    GRC
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900 mb-4">
                                    Governance, Risk & Compliance Professional
                                </h3>
                                <p className="text-gray-600 mb-6">
                                    Master the frameworks and practices for effective GRC implementation in modern organizations.
                                </p>
                                <div className="flex items-center justify-between">
                                    <span className="text-gray-500">12 weeks</span>
                                    <Link 
                                        href="/certifications/grc"
                                        className="text-blue-600 font-medium hover:text-blue-800"
                                    >
                                        Learn more →
                                    </Link>
                                </div>
                            </div>
                        </div>

                        {/* Program 2 */}
                        <div className="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                            <div className="p-8">
                                <div className="inline-block px-4 py-1 bg-green-100 text-green-600 rounded-full text-sm font-semibold mb-4">
                                    Financial Crime
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900 mb-4">
                                    Financial Crime Prevention Specialist
                                </h3>
                                <p className="text-gray-600 mb-6">
                                    Develop expertise in AML, fraud detection, and financial crime investigation techniques.
                                </p>
                                <div className="flex items-center justify-between">
                                    <span className="text-gray-500">16 weeks</span>
                                    <Link 
                                        href="/certifications/financial-crime"
                                        className="text-blue-600 font-medium hover:text-blue-800"
                                    >
                                        Learn more →
                                    </Link>
                                </div>
                            </div>
                        </div>

                        {/* Program 3 */}
                        <div className="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                            <div className="p-8">
                                <div className="inline-block px-4 py-1 bg-purple-100 text-purple-600 rounded-full text-sm font-semibold mb-4">
                                    Risk Management
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900 mb-4">
                                    Enterprise Risk Management Expert
                                </h3>
                                <p className="text-gray-600 mb-6">
                                    Learn to identify, assess, and mitigate risks across all levels of your organization.
                                </p>
                                <div className="flex items-center justify-between">
                                    <span className="text-gray-500">10 weeks</span>
                                    <Link 
                                        href="/certifications/risk-management"
                                        className="text-blue-600 font-medium hover:text-blue-800"
                                    >
                                        Learn more →
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}