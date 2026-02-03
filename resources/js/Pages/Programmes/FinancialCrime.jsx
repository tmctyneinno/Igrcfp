 import { Head } from '@inertiajs/react';
import React from 'react';

export default function FinancialCrimePathway() {
    const courses = [
        {
            title: "Advanced AML & Financial Intelligence",
            duration: "4 months",
            level: "Advanced",
            description: "Comprehensive anti-money laundering frameworks, suspicious activity reporting, and financial intelligence gathering",
            outcomes: [
                "Implement AML compliance programmes",
                "Conduct financial intelligence analysis",
                "Manage suspicious activity reporting"
            ]
        },
        {
            title: "Fraud, Corruption & Integrity Systems",
            duration: "3 months",
            level: "Professional",
            description: "Detection, prevention, and investigation of fraud, bribery, and corruption across organizations",
            outcomes: [
                "Design fraud prevention frameworks",
                "Implement integrity systems",
                "Conduct corruption risk assessments"
            ]
        },
        {
            title: "Sanctions Risk, Screening & Geopolitical Exposure",
            duration: "2 months",
            level: "Professional",
            description: "Global sanctions compliance, screening methodologies, and geopolitical risk management",
            outcomes: [
                "Develop sanctions compliance programmes",
                "Implement screening solutions",
                "Manage geopolitical exposure"
            ]
        },
        {
            title: "Investigations, Enforcement & Cross-Border Cooperation",
            duration: "3 months",
            level: "Advanced",
            description: "Financial crime investigation techniques, enforcement actions, and international cooperation",
            outcomes: [
                "Conduct financial investigations",
                "Navigate enforcement processes",
                "Coordinate cross-border cooperation"
            ]
        },
        {
            title: "Financial Crime Risk Assessment & Controls",
            duration: "3 months",
            level: "Professional",
            description: "Risk assessment methodologies and control frameworks for financial crime prevention",
            outcomes: [
                "Conduct financial crime risk assessments",
                "Design control frameworks",
                "Monitor control effectiveness"
            ]
        },
        {
            title: "Emerging Financial Crime Threats",
            duration: "2 months",
            level: "Advanced",
            description: "New and evolving financial crime typologies including digital assets and cyber-enabled crime",
            outcomes: [
                "Identify emerging threats",
                "Develop mitigation strategies",
                "Adapt to evolving typologies"
            ]
        }
    ];

    return (
        <>
            <Head title="Financial Crime Prevention | IGRCFP Programmes" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-red-900 to-red-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6">
                            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            Financial Crime Prevention & Regulatory Compliance
                        </h1>
                        <p className="text-xl text-red-100 max-w-3xl mx-auto">
                            Focused on preventing, detecting, and responding to economic and financial crime across industries
                        </p>
                    </div>
                </div>
            </div>

            {/* Overview Section */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Programme Overview</h2>
                            <p className="text-gray-600 text-lg mb-6">
                                The Financial Crime Prevention pathway equips professionals with the knowledge and 
                                skills needed to combat money laundering, terrorist financing, fraud, corruption, 
                                and other financial crimes in an increasingly complex global landscape.
                            </p>
                            <p className="text-gray-600 mb-8">
                                Our courses integrate regulatory requirements with practical implementation, 
                                ensuring professionals can design, implement, and maintain effective financial 
                                crime prevention programmes.
                            </p>
                            
                            <div className="bg-red-50 p-6 rounded-xl">
                                <h3 className="text-xl font-semibold text-red-900 mb-4">Who This Programme Is For</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>AML Compliance Officers</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Fraud Specialists</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Financial Investigators</span>
                                        </li>
                                    </ul>
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Sanctions Compliance Teams</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Financial Institution Compliance</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Law Enforcement & Regulators</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div className="bg-gray-50 p-8 rounded-2xl border border-gray-200">
                                <h3 className="text-2xl font-bold text-gray-900 mb-6">Key Programme Features</h3>
                                <div className="space-y-6">
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Comprehensive Coverage</h4>
                                            <p className="text-gray-600">AML, fraud, corruption, sanctions, and emerging threats</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Practical Implementation</h4>
                                            <p className="text-gray-600">Real-world case studies and regulatory scenarios</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Global Perspective</h4>
                                            <p className="text-gray-600">International standards and cross-border considerations</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Courses Grid */}
            <div className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Financial Crime Prevention Courses</h2>
                        <p className="text-gray-600 text-lg">Specialized courses for comprehensive financial crime prevention</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {courses.map((course, index) => (
                            <div key={index} className="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <div className="p-6">
                                    <div className="flex justify-between items-start mb-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                            course.level === 'Advanced' ? 'bg-red-100 text-red-800' :
                                            'bg-green-100 text-green-800'
                                        }`}>
                                            {course.level}
                                        </span>
                                        <span className="text-sm text-gray-500">{course.duration}</span>
                                    </div>
                                    <h3 className="text-xl font-bold text-gray-900 mb-3">{course.title}</h3>
                                    <p className="text-gray-600 mb-4">{course.description}</p>
                                    <div className="space-y-2 mb-6">
                                        <h4 className="font-semibold text-gray-900">Learning Outcomes:</h4>
                                        <ul className="space-y-1">
                                            {course.outcomes.map((outcome, idx) => (
                                                <li key={idx} className="flex items-start text-sm text-gray-600">
                                                    <svg className="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    {outcome}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                    <button className="w-full bg-red-900 text-white py-3 rounded-lg font-medium hover:bg-red-800 transition duration-300">
                                        View Course Details
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Certification Section */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-gradient-to-r from-red-900 to-red-800 rounded-2xl p-8 md:p-12">
                        <div className="text-center">
                            <h2 className="text-3xl font-bold text-white mb-6">Professional Recognition</h2>
                            <p className="text-red-100 text-lg mb-8 max-w-3xl mx-auto">
                                Completion of Financial Crime Prevention pathway courses may lead to IGRCFP professional certifications and eligibility for the Certified Financial Crime Specialist (CFCS) designation.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="/certifications/cfc" className="bg-white text-red-900 px-8 py-3 rounded-lg font-semibold hover:bg-red-50 transition duration-300">
                                    Explore CFCS Certification
                                </a>
                                <a href="/contact" className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-900 transition duration-300">
                                    Contact Admissions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}