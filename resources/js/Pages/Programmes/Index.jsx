import { Head, Link } from "@inertiajs/react";
import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function ProgrammesIndex({auth}) {
    return (
        <>
        <GuestLayout auth={auth}>
            <Head title="Programmes & Courses | IGRCFP" />
            {/* Hero Section - Slimmer */}
            <section className="relative bg-gradient-to-r from-blue-200 via-white to-blue-200 to-indigo-50 py-12 md:py-20">
                <div className="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]" />
                <div className="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium mb-4">
                            Programmes and Courses
                       </div>
                        <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 tracking-tight">
                            Professional Education for Modern Risk, Regulation & Technology
                        </h1> 
                        <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                             Advanced programmes designed for today's complex regulatory, digital, and financial crime landscape.
                       </p>
                    </div>
                </div>
            </section>
            
            {/* Intersection Section */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            At the Intersection of
                        </h2>
                        <p className="text-gray-600 text-lg">
                            Our courses bridge the gap between traditional disciplines and emerging challenges
                        </p>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                        <div className="bg-white p-8 rounded-xl shadow-lg border border-blue-100">
                            <div className="text-blue-900 mb-4">
                                <svg className="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Regulation & Compliance</h3>
                            <p className="text-gray-600">
                                Global regulatory frameworks, supervisory engagement, and compliance management systems
                            </p>
                        </div>
                        
                        <div className="bg-white p-8 rounded-xl shadow-lg border border-blue-100">
                            <div className="text-blue-900 mb-4">
                                <svg className="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Enterprise & Emerging Risk</h3>
                            <p className="text-gray-600">
                                Strategic risk management, horizon scanning, and operational resilience
                            </p>
                        </div>
                        
                        <div className="bg-white p-8 rounded-xl shadow-lg border border-blue-100">
                            <div className="text-blue-900 mb-4">
                                <svg className="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">Technology & Innovation</h3>
                            <p className="text-gray-600">
                                Digital assets, cybersecurity, AI governance, and RegTech solutions
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Core Programme Pathways */}
            <div className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Core Programme Pathways
                        </h2>
                        <p className="text-gray-600 text-lg max-w-3xl mx-auto">
                            Framework-led, practitioner-focused, and globally relevant education
                        </p>
                    </div>

                    <div className="space-y-8">
                        {/* GRC Pathway */}
                        <div className="bg-gradient-to-r from-blue-50 to-white p-8 rounded-2xl shadow-lg border border-blue-100">
                            <div className="flex items-start">
                                <div className="flex-shrink-0">
                                    <div className="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center">
                                        <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                </div>
                                <div className="ml-6 flex-1">
                                    <div className="flex justify-between items-start">
                                        <div>
                                            <h3 className="text-2xl font-bold text-gray-900">Governance, Risk & Compliance (GRC)</h3>
                                            <p className="text-blue-900 font-medium mt-2">For professionals responsible for organisational oversight, risk management, and regulatory compliance</p>
                                        </div>
                                        <a href="/programmes/grc" className="bg-blue-900 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-800 transition duration-300">
                                            Explore Pathway
                                        </a>
                                    </div>
                                    <div className="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div className="bg-white p-4 rounded-lg border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-2">Key Courses Include:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start">
                                                    <svg className="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Advanced Diploma in GRC & Financial Crime Prevention</span>
                                                </li>
                                                <li className="flex items-start">
                                                    <svg className="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Enterprise Risk Management for Senior Leaders</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div className="bg-white p-4 rounded-lg border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-2">Who It's For:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start">
                                                    <svg className="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="3" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                                                    </svg>
                                                    <span>Risk Managers & Compliance Officers</span>
                                                </li>
                                                <li className="flex items-start">
                                                    <svg className="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="3" strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                                                    </svg>
                                                    <span>Board Advisors & Executives</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div className="bg-white p-4 rounded-lg border border-gray-200">
                                            <h4 className="font-semibold text-gray-900 mb-2">Learning Outcomes:</h4>
                                            <ul className="space-y-2">
                                                <li className="flex items-start">
                                                    <svg className="w-5 h-5 text-yellow-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                    <span>Integrated GRC framework implementation</span>
                                                </li>
                                                <li className="flex items-start">
                                                    <svg className="w-5 h-5 text-yellow-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                    <span>Strategic risk decision-making</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Repeat similar structure for other pathways... */}
                        {/* Financial Crime Prevention */}
                        <div className="bg-gradient-to-r from-red-50 to-white p-8 rounded-2xl shadow-lg border border-red-100">
                            <div className="flex items-start">
                                <div className="flex-shrink-0">
                                    <div className="w-16 h-16 bg-red-600 rounded-xl flex items-center justify-center">
                                        <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>
                                <div className="ml-6 flex-1">
                                    <div className="flex justify-between items-start">
                                        <div>
                                            <h3 className="text-2xl font-bold text-gray-900">Financial Crime Prevention & Regulatory Compliance</h3>
                                            <p className="text-red-900 font-medium mt-2">Preventing, detecting, and responding to economic and financial crime across industries</p>
                                        </div>
                                        <a href="/programmes/financial-crime" className="bg-red-900 text-white px-6 py-2 rounded-lg font-medium hover:bg-red-800 transition duration-300">
                                            Explore Pathway
                                        </a>
                                    </div>
                                    <div className="mt-6">
                                        <h4 className="font-semibold text-gray-900 mb-3">Key Courses Include:</h4>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div className="bg-white p-4 rounded-lg border border-gray-200">
                                                <ul className="space-y-2">
                                                    <li className="flex items-start">
                                                        <svg className="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Advanced AML & Financial Intelligence</span>
                                                    </li>
                                                    <li className="flex items-start">
                                                        <svg className="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Fraud, Corruption & Integrity Systems</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div className="bg-white p-4 rounded-lg border border-gray-200">
                                                <ul className="space-y-2">
                                                    <li className="flex items-start">
                                                        <svg className="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Sanctions Risk, Screening & Geopolitical Exposure</span>
                                                    </li>
                                                    <li className="flex items-start">
                                                        <svg className="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Investigations & Cross-Border Cooperation</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Add similar sections for Crypto, Cybersecurity, and AI pathways... */}
                    </div>
                </div>
            </div>

            {/* How Our Courses Are Designed */}
            <div className="bg-gray-50 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            How Our Courses Are Designed
                        </h2>
                        <p className="text-gray-600 text-lg max-w-3xl mx-auto">
                            Built around principles that ensure practical, relevant, and impactful learning
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        {[
                            {
                                title: "Global Best Practices",
                                description: "Based on international regulatory principles and industry standards",
                                icon: "🌍"
                            },
                            {
                                title: "Real-World Application",
                                description: "Case studies and current developments for practical implementation",
                                icon: "🎯"
                            },
                            {
                                title: "Systems Thinking",
                                description: "Understanding interconnections beyond rule memorization",
                                icon: "🔄"
                            },
                            {
                                title: "Ethical Foundation",
                                description: "Focus on accountability and responsible decision-making",
                                icon: "⚖️"
                            }
                        ].map((item, index) => (
                            <div key={index} className="bg-white p-8 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                                <div className="text-4xl mb-4">{item.icon}</div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">{item.title}</h3>
                                <p className="text-gray-600">{item.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* CTA Section */}
            <div className="bg-blue-900 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-6">Ready to Advance Your Career?</h2>
                    <p className="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">
                        Join professionals from leading organizations who trust IGRCFP for their professional development
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href={route('igrcfp.certificates.index')} className="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                            View All Courses
                        </a> 
                        <a href="/contact" className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition duration-300">
                            Contact Admissions
                        </a>
                    </div>
                </div>
            </div>
        </GuestLayout>
        </>
    );
}