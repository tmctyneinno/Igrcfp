import { Head } from '@inertiajs/react';
import React from 'react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Index({ auth, courses, filters, filterOptions }) {
    const courses = [
        {
            title: "Advanced Diploma in Governance, Risk, Compliance & Financial Crime Prevention",
            duration: "12 months",
            level: "Advanced",
            description: "Comprehensive programme covering integrated GRC frameworks and financial crime prevention",
            outcomes: [
                "Master integrated GRC implementation",
                "Develop financial crime prevention strategies",
                "Lead organisational compliance transformation"
            ]
        }, 
        { 
            title: "Enterprise Risk Management for Senior Leaders",
            duration: "3 months",
            level: "Executive",
            description: "Strategic risk management for board members and C-suite executives",
            outcomes: [
                "Strategic risk decision-making",
                "Board-level risk reporting",
                "Risk culture development"
            ]
        },
        {
            title: "Board Governance, Accountability & Ethical Leadership",
            duration: "2 months",
            level: "Board Level",
            description: "Governance frameworks and ethical leadership for board directors",
            outcomes: [
                "Board governance best practices",
                "Ethical decision-making frameworks",
                "Stakeholder accountability"
            ]
        },
        {
            title: "Compliance Management Systems (CMS) Design & Implementation",
            duration: "4 months",
            level: "Professional",
            description: "Practical course on designing and implementing effective compliance systems",
            outcomes: [
                "CMS design and implementation",
                "Compliance monitoring and testing",
                "Regulatory reporting"
            ]
        },
        {
            title: "Regulatory Change & Horizon Scanning",
            duration: "2 months",
            level: "Professional",
            description: "Proactive approach to regulatory changes and emerging risks",
            outcomes: [
                "Regulatory change management",
                "Horizon scanning techniques",
                "Impact assessment methodologies"
            ]
        },
        {
            title: "ESG, Sustainability & Conduct Risk",
            duration: "3 months",
            level: "Advanced",
            description: "Integrating ESG factors and conduct risk into GRC frameworks",
            outcomes: [
                "ESG integration in risk management",
                "Conduct risk frameworks",
                "Sustainability reporting"
            ]
        }
    ];

    return (
        <>
        <GuestLayout auth={auth}>
            <Head title="GRC Pathway | IGRCFP Programmes" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-blue-900 to-blue-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6">
                            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            Governance, Risk & Compliance (GRC)
                        </h1>
                        <p className="text-xl text-blue-100 max-w-3xl mx-auto">
                            Designed for professionals responsible for organisational oversight, risk management, and regulatory compliance
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
                                The GRC pathway provides comprehensive education for professionals managing 
                                governance structures, risk frameworks, and compliance obligations in today's 
                                complex regulatory environment.
                            </p>
                            <p className="text-gray-600 mb-8">
                                Our courses bridge the gap between theoretical frameworks and practical 
                                implementation, ensuring professionals can effectively manage organizational 
                                risks while maintaining regulatory compliance and ethical standards.
                            </p>
                            
                            <div className="bg-blue-50 p-6 rounded-xl">
                                <h3 className="text-xl font-semibold text-blue-900 mb-4">Who This Programme Is For</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Risk Managers</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Compliance Officers</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Board Advisors</span>
                                        </li>
                                    </ul>
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Internal Auditors</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Executives</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Public Sector Leaders</span>
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
                                        <div className="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Integrated Approach</h4>
                                            <p className="text-gray-600">Holistic view of governance, risk, and compliance as interconnected disciplines</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Practical Implementation</h4>
                                            <p className="text-gray-600">Real-world case studies and implementation frameworks</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Global Standards</h4>
                                            <p className="text-gray-600">Based on international best practices and regulatory frameworks</p>
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
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">GRC Pathway Courses</h2>
                        <p className="text-gray-600 text-lg">Select from our comprehensive range of GRC courses</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {courses.map((course, index) => (
                            <div key={index} className="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <div className="p-6">
                                    <div className="flex justify-between items-start mb-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                            course.level === 'Advanced' ? 'bg-blue-100 text-blue-800' :
                                            course.level === 'Executive' ? 'bg-purple-100 text-purple-800' :
                                            course.level === 'Board Level' ? 'bg-yellow-100 text-yellow-800' :
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
                                    <button className="w-full bg-blue-900 text-white py-3 rounded-lg font-medium hover:bg-blue-800 transition duration-300">
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
                    <div className="bg-gradient-to-r from-blue-900 to-blue-800 rounded-2xl p-8 md:p-12">
                        <div className="text-center">
                            <h2 className="text-3xl font-bold text-white mb-6">Professional Recognition</h2>
                            <p className="text-blue-100 text-lg mb-8 max-w-3xl mx-auto">
                                Completion of GRC pathway courses may lead to IGRCFP professional certifications and eligibility for the Certified GRC & Financial Crime Specialist (CGFCS) designation.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="/certifications" className="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                                    Explore Certifications
                                </a>
                                <a href="/contact" className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-900 transition duration-300">
                                    Contact Admissions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
        </>
    );
}