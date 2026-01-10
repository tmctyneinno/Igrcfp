import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function AboutIndex({ auth, title, description }) {
    return (
        <GuestLayout auth={auth}>
            <Head title={title} />
            
            {/* Hero Section */}
            <section className="w-full bg-gradient-to-r from-gray-200 via-white to-blue-50 py-28">
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

            {/* Main Content */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
                            <p className="text-gray-600 mb-6">
                                The International Governance, Risk, Compliance & Financial Crime Professionals (IGRCFP) 
                                is a global professional body dedicated to advancing standards, ethics, and best practices 
                                in governance, risk management, compliance, and financial crime prevention.
                            </p>
                            <p className="text-gray-600 mb-6">
                                We provide certification, training, and professional development for individuals and 
                                organizations worldwide, ensuring the highest levels of professionalism and integrity 
                                in these critical fields.
                            </p>
                            
                            <div className="mt-8">
                                <Link
                                    href="/about-us/welcome"
                                    className="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300"
                                >
                                    Welcome Message
                                    <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </Link>
                            </div>
                        </div>
                        
                        <div className="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl p-8">
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">Key Focus Areas</h3>
                            <ul className="space-y-4">
                                {[
                                    "Governance & Ethics",
                                    "Risk Management",
                                    "Regulatory Compliance",
                                    "Financial Crime Prevention",
                                    "Anti-Money Laundering",
                                    "Cybersecurity & Data Protection"
                                ].map((area, index) => (
                                    <li key={index} className="flex items-center">
                                        <svg className="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                        </svg>
                                        <span className="text-gray-700">{area}</span>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}