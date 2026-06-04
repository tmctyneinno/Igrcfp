import GuestLayout from '@/Layouts/GuestLayout';
import { Head } from '@inertiajs/react';
import React from 'react';

export default function AIPathway({ auth}) {
    const courses = [
        {
            title: "AI Governance, Ethics & Accountability",
            duration: "3 months",
            level: "Executive",
            description: "Governance frameworks and ethical considerations for AI deployment",
            outcomes: [
                "Design AI governance frameworks",
                "Implement ethical AI practices",
                "Establish accountability mechanisms"
            ]
        },
        {
            title: "Algorithmic Risk, Bias & Explainability",
            duration: "3 months",
            level: "Advanced",
            description: "Identifying and mitigating risks in algorithmic decision-making",
            outcomes: [
                "Assess algorithmic bias",
                "Implement explainability frameworks",
                "Manage model risk"
            ]
        },
        {
            title: "RegTech & SupTech Applications",
            duration: "3 months",
            level: "Professional",
            description: "Regulatory technology applications and supervisory technology implementation",
            outcomes: [
                "Evaluate RegTech solutions",
                "Implement SupTech tools",
                "Optimize compliance processes"
            ]
        },
        {
            title: "Data Governance, Ownership & Protection",
            duration: "4 months",
            level: "Professional",
            description: "Comprehensive data governance frameworks for AI and advanced analytics",
            outcomes: [
                "Design data governance frameworks",
                "Manage data ownership issues",
                "Implement data protection controls"
            ]
        },
        {
            title: "Technology Ethics & Responsible Innovation",
            duration: "2 months",
            level: "Executive",
            description: "Ethical frameworks for technology development and deployment",
            outcomes: [
                "Develop ethical technology policies",
                "Implement responsible innovation practices",
                "Navigate ethical dilemmas"
            ]
        },
        {
            title: "AI Regulatory Compliance & Risk Management",
            duration: "3 months",
            level: "Professional",
            description: "Regulatory requirements and risk management for AI systems",
            outcomes: [
                "Navigate AI regulations",
                "Implement AI risk management",
                "Conduct AI impact assessments"
            ]
        }
    ];

    return (
        <>
       <GuestLayout auth={auth} forceWhiteNavbar>
            <Head title="AI & Emerging Technology | IGRCFP Programmes" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-r from-yellow-900 to-yellow-800 py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6">
                            <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            AI, Data & Emerging Technology Governance
                        </h1>
                        <p className="text-xl text-yellow-100 max-w-3xl mx-auto">
                            Courses addressing the governance and compliance challenges of advanced technologies
                        </p>
                    </div>
                </div>
            </div>

            {/* Programme Overview */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Programme Overview</h2>
                            <p className="text-gray-600 text-lg mb-6">
                                This pathway addresses the governance, compliance, and ethical challenges 
                                presented by artificial intelligence, data analytics, and other emerging technologies.
                            </p>
                            <p className="text-gray-600 mb-8">
                                Designed for professionals who need to understand, govern, and responsibly 
                                deploy advanced technologies while ensuring regulatory compliance and ethical standards.
                            </p>
                            
                            <div className="bg-yellow-50 p-6 rounded-xl">
                                <h3 className="text-xl font-semibold text-yellow-900 mb-4">Who This Programme Is For</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Technology Leaders</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Compliance & Risk Professionals</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Regulators & Policy Makers</span>
                                        </li>
                                    </ul>
                                    <ul className="space-y-2">
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Data Governance Specialists</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Ethics & Governance Committees</span>
                                        </li>
                                        <li className="flex items-center">
                                            <svg className="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4" />
                                            </svg>
                                            <span>Innovation Teams</span>
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
                                        <div className="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Holistic Governance</h4>
                                            <p className="text-gray-600">Comprehensive frameworks for AI and emerging tech governance</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Practical Implementation</h4>
                                            <p className="text-gray-600">Actionable frameworks for technology governance and compliance</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg className="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 className="text-lg font-semibold text-gray-900 mb-2">Ethical Foundation</h4>
                                            <p className="text-gray-600">Strong focus on ethics, fairness, and responsible innovation</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Focus Areas */}
            <div className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Programme Focus Areas</h2>
                        <p className="text-gray-600 text-lg">Addressing critical challenges in advanced technology deployment</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {[
                            {
                                title: "AI Governance",
                                description: "Frameworks for responsible AI development and deployment",
                                icon: "🤖"
                            },
                            {
                                title: "Algorithmic Ethics",
                                description: "Addressing bias, fairness, and transparency in algorithms",
                                icon: "⚖️"
                            },
                            {
                                title: "Data Stewardship",
                                description: "Responsible data governance and management practices",
                                icon: "📊"
                            },
                            {
                                title: "Regulatory Compliance",
                                description: "Navigating evolving regulatory landscapes for technology",
                                icon: "📋"
                            },
                            {
                                title: "Risk Management",
                                description: "Identifying and mitigating technology-related risks",
                                icon: "🛡️"
                            },
                            {
                                title: "Innovation Ethics",
                                description: "Balancing innovation with ethical considerations",
                                icon: "💡"
                            }
                        ].map((area, index) => (
                            <div key={index} className="bg-white p-8 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                                <div className="text-4xl mb-4">{area.icon}</div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">{area.title}</h3>
                                <p className="text-gray-600">{area.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Courses Grid */}
            <div className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">AI & Emerging Technology Courses</h2>
                        <p className="text-gray-600 text-lg">Specialized courses for technology governance and compliance</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {courses.map((course, index) => (
                            <div key={index} className="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <div className="p-6">
                                    <div className="flex justify-between items-start mb-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                            course.level === 'Executive' ? 'bg-yellow-100 text-yellow-800' :
                                            course.level === 'Advanced' ? 'bg-orange-100 text-orange-800' :
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
                                    <button className="w-full bg-yellow-900 text-white py-3 rounded-lg font-medium hover:bg-yellow-800 transition duration-300">
                                        View Course Details
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* CTA Section */}
            <div className="py-16 bg-gradient-to-r from-yellow-900 to-yellow-800">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-white mb-6">Lead the Future of Technology</h2>
                    <p className="text-yellow-100 text-lg mb-8 max-w-2xl mx-auto">
                        Equip yourself with the knowledge and skills to govern advanced technologies responsibly and effectively
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href={route('igrcfp.certificates.index')} className="bg-white text-yellow-900 px-8 py-3 rounded-lg font-semibold hover:bg-yellow-50 transition duration-300">
                            View All Courses
                        </a>
                        <a href="/contact" className="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-yellow-900 transition duration-300">
                            Contact Admissions
                        </a>
                    </div>
                </div>
            </div>
            </GuestLayout>
        </>
    );
}