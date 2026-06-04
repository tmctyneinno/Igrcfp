import { Head } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import React from 'react';

export default function CybersecurityPathway({auth}) {
    const courses = [
        {
            title: "Cyber Risk Governance for Boards & Executives",
            duration: "2 months",
            level: "Executive",
            description: "Strategic cyber risk oversight and governance for senior leadership",
            outcomes: [
                "Develop cyber governance frameworks",
                "Lead cyber risk discussions",
                "Make informed investment decisions"
            ]
        },
        {
            title: "Cybercrime, Fraud & Digital Threats",
            duration: "3 months",
            level: "Professional",
            description: "Understanding and mitigating cyber-enabled financial crime and fraud",
            outcomes: [
                "Identify cybercrime typologies",
                "Implement fraud prevention measures",
                "Coordinate incident response"
            ]
        },
        {
            title: "Data Protection, Privacy & Regulatory Compliance",
            duration: "4 months",
            level: "Professional",
            description: "Global data protection regulations and privacy compliance frameworks",
            outcomes: [
                "Implement data protection programmes",
                "Navigate global privacy regulations",
                "Manage data breach responses"
            ]
        },
        {
            title: "Technology Risk Management & Operational Resilience",
            duration: "3 months",
            level: "Advanced",
            description: "Managing technology risks and building operational resilience",
            outcomes: [
                "Implement risk management frameworks",
                "Design resilient operations",
                "Conduct business continuity planning"
            ]
        },
        {
            title: "Incident Response, Breach Management & Regulatory Reporting",
            duration: "3 months",
            level: "Professional",
            description: "Effective incident response and regulatory compliance during cyber incidents",
            outcomes: [
                "Lead incident response teams",
                "Manage breach notifications",
                "Prepare regulatory reports"
            ]
        },
        {
            title: "Third-Party & Supply Chain Cyber Risk",
            duration: "2 months",
            level: "Professional",
            description: "Managing cyber risks across supply chains and third-party relationships",
            outcomes: [
                "Assess third-party cyber risks",
                "Implement supply chain controls",
                "Manage vendor relationships"
            ]
        }
    ];

    return (
        <>
        <GuestLayout auth={auth} forceWhiteNavbar>
            <Head title="Cybersecurity & Digital Risk | IGRCFP Programmes" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-green-900 to-green-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6">
                            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            Cybersecurity, Technology & Digital Risk
                        </h1>
                        <p className="text-xl text-green-100 max-w-3xl mx-auto">
                            Focused on cyber risk as a governance, regulatory, and financial crime issue — not just an IT problem
                        </p>
                    </div>
                </div>
            </div>

            {/* Core Themes */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Core Programme Themes</h2>
                        <p className="text-gray-600 text-lg">Addressing cyber risk from multiple organizational perspectives</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {[
                            {
                                title: "Enterprise Risk",
                                description: "Cybersecurity as an organizational-wide risk management issue",
                                icon: "🏢"
                            },
                            {
                                title: "Regulatory Compliance",
                                description: "Meeting regulatory expectations around resilience and reporting",
                                icon: "📋"
                            },
                            {
                                title: "Financial Crime",
                                description: "Addressing cyber-enabled fraud and financial crime",
                                icon: "💰"
                            }
                        ].map((theme, index) => (
                            <div key={index} className="bg-green-50 p-8 rounded-xl border border-green-100 hover:shadow-lg transition-shadow duration-300">
                                <div className="text-4xl mb-4">{theme.icon}</div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">{theme.title}</h3>
                                <p className="text-gray-600">{theme.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Programme Overview */}
            <div className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Programme Overview</h2>
                            <p className="text-gray-600 text-lg mb-6">
                                This pathway addresses cybersecurity and digital risk from governance, 
                                compliance, and business perspectives, rather than purely technical viewpoints.
                            </p>
                            <p className="text-gray-600 mb-8">
                                Designed for professionals who need to understand, manage, and oversee 
                                cyber risks within their organizations while ensuring regulatory compliance.
                            </p>
                            
                            <div className="bg-green-50 p-6 rounded-xl">
                                <h3 className="text-xl font-semibold text-green-900 mb-4">Who This Programme Is For</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Executives & Board Members</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>CISOs & Security Leaders</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Risk & Compliance Teams</span>
                                        </li>
                                    </ul>
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Digital Transformation Leaders</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Technology Organizations</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Financial Institution Leaders</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div className="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
                                <h3 className="text-2xl font-bold text-gray-900 mb-6">Programme Focus Areas</h3>
                                <div className="space-y-4">
                                    {[
                                        "Board-level cyber risk governance",
                                        "Regulatory compliance and reporting",
                                        "Incident response and crisis management",
                                        "Third-party and supply chain risk",
                                        "Data protection and privacy",
                                        "Operational resilience planning"
                                    ].map((area, index) => (
                                        <div key={index} className="flex items-start">
                                            <svg className="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span className="text-gray-700">{area}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Courses Grid */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Cybersecurity & Digital Risk Courses</h2>
                        <p className="text-gray-600 text-lg">Comprehensive courses for organizational cyber risk management</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {courses.map((course, index) => (
                            <div key={index} className="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <div className="p-6">
                                    <div className="flex justify-between items-start mb-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                            course.level === 'Executive' ? 'bg-yellow-100 text-yellow-800' :
                                            course.level === 'Advanced' ? 'bg-green-100 text-green-800' :
                                            'bg-blue-100 text-blue-800'
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
                                    <button className="w-full bg-green-900 text-white py-3 rounded-lg font-medium hover:bg-green-800 transition duration-300">
                                        View Course Details
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Certification Section */}
            <div className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-gradient-to-r from-green-900 to-green-800 rounded-2xl p-8 md:p-12">
                        <div className="text-center">
                            <h2 className="text-3xl font-bold text-white mb-6">Professional Recognition</h2>
                            <p className="text-green-100 text-lg mb-8 max-w-3xl mx-auto">
                                Completion of Cybersecurity & Digital Risk pathway courses may lead to IGRCFP professional certifications and eligibility for the Certified Cyber Risk Leader (CCRL) designation.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href={route('igrcfp.certificates.index')} className="bg-white text-green-900 px-8 py-3 rounded-lg font-semibold hover:bg-green-50 transition duration-300">
                                    View all courses
                                </a>
                                <a href={route('contact')} className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-900 transition duration-300">
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